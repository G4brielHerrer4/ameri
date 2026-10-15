<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Throwable;

class PasswordResetCodeController extends Controller
{
    /**
     * Mostrar formulario para solicitar el código.
     */
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Generar y enviar el código de seis dígitos.
     */
    public function sendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $email = strtolower(trim($request->input('email')));

        $request->session()->forget([
            'password_reset_verified_email',
            'password_reset_verified_at',
            'password_reset_code_id',
        ]);

        $limiterKey = 'password-reset-email:' . hash('sha256', $email);

        if (RateLimiter::tooManyAttempts($limiterKey, 3)) {
            return back()->withErrors([
                'email' => 'Has solicitado varios códigos. Espera unos minutos antes de intentarlo nuevamente.',
            ])->withInput();
        }

        RateLimiter::hit($limiterKey, 600);

        $user = User::where('email', $email)->first();

        // Mensaje genérico para no revelar si existe una cuenta.
        if (!$user || !$user->estado) {
            $request->session()->put('password_reset_email', $email);

            return redirect()->route('password.code.form')
                ->with('status', 'Si existe una cuenta activa con ese correo, recibirás un código de recuperación.');
        }

        // Invalidar cualquier código anterior para este correo.
        PasswordResetCode::where('email', $email)->delete();

        $code = (string) random_int(100000, 999999);

        $resetCode = PasswordResetCode::create([
            'email' => $email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(2),
            'attempts' => 0,
        ]);

        try {
            Mail::raw(
                "Hola,\n\n"
                . "Tu código para recuperar la contraseña de AMERI es: {$code}\n\n"
                . "Este código vence en 2 minutos y solo puede utilizarse una vez.\n"
                . "Si no solicitaste esta recuperación, ignora este mensaje.\n\n"
                . "Por seguridad, no compartas este código con nadie.",
                function ($message) use ($email) {
                    $message->to($email)
                        ->subject('Código de recuperación de contraseña - AMERI');
                }
            );
        } catch (Throwable $e) {
            $resetCode->delete();

            report($e);

            return back()->withErrors([
                'email' => 'No pudimos enviar el correo en este momento. Inténtalo más tarde.',
            ])->withInput();
        }

        $request->session()->put('password_reset_email', $email);

        return redirect()->route('password.code.form')
            ->with('status', 'Si existe una cuenta activa con ese correo, recibirás un código de recuperación.');
    }

    /**
     * Mostrar formulario para introducir el código.
     */
    public function showVerifyForm(Request $request)
    {
        if (!$request->session()->has('password_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.passwords.verify-code');
    }

    /**
     * Comprobar el código, su vencimiento y los intentos.
     */
    public function verifyCode(Request $request)
    {
        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $validator = Validator::make($request->all(), [
            'code' => ['required', 'digits:6'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $resetCode = PasswordResetCode::where('email', $email)
            ->whereNull('used_at')
            ->latest('id')
            ->first();

        if (!$resetCode || $resetCode->expires_at->isPast()) {
            return back()->withErrors([
                'code' => 'El código ha vencido o no existe. Solicita uno nuevo.',
            ]);
        }

        if ($resetCode->attempts >= 5) {
            return back()->withErrors([
                'code' => 'Has superado el número de intentos. Solicita un código nuevo.',
            ]);
        }

        $resetCode->increment('attempts');
        $resetCode->refresh();

        if (!Hash::check($request->input('code'), $resetCode->code_hash)) {
            return back()->withErrors([
                'code' => 'El código ingresado es incorrecto.',
            ]);
        }

        $resetCode->verified_at = now();
        $resetCode->save();

        $request->session()->put([
            'password_reset_verified_email' => $email,
            'password_reset_verified_at' => now()->timestamp,
            'password_reset_code_id' => $resetCode->id,
        ]);

        return redirect()->route('password.code.reset');
    }

    /**
     * Mostrar formulario para establecer la nueva contraseña.
     */
    public function showResetForm(Request $request)
    {
        if (!$this->hasVerifiedCode($request)) {
            return redirect()->route('password.request')
                ->withErrors([
                    'email' => 'Debes verificar un código válido antes de cambiar la contraseña.',
                ]);
        }

        return view('auth.passwords.reset-code');
    }

    /**
     * Guardar la nueva contraseña y consumir el código.
     */
    public function resetPassword(Request $request)
    {
        if (!$this->hasVerifiedCode($request)) {
            return redirect()->route('password.request')
                ->withErrors([
                    'email' => 'La verificación ha vencido. Solicita un nuevo código.',
                ]);
        }

        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $email = $request->session()->get('password_reset_verified_email');
        $codeId = $request->session()->get('password_reset_code_id');

        $updated = DB::transaction(function () use ($email, $codeId, $request) {
            $resetCode = PasswordResetCode::where('id', $codeId)
                ->where('email', $email)
                ->lockForUpdate()
                ->first();

            if (
                !$resetCode ||
                $resetCode->used_at !== null ||
                $resetCode->verified_at === null ||
                $resetCode->verified_at->lt(now()->subMinutes(5))
            ) {
                return false;
            }

            $user = User::where('email', $email)
                ->where('estado', true)
                ->lockForUpdate()
                ->first();

            if (!$user) {
                return false;
            }

            $user->password = Hash::make($request->input('password'));
            $user->save();

            $resetCode->used_at = now();
            $resetCode->save();

            return true;
        });

        if (!$updated) {
            $request->session()->forget([
                'password_reset_email',
                'password_reset_verified_email',
                'password_reset_verified_at',
                'password_reset_code_id',
            ]);

            return redirect()->route('password.request')
                ->withErrors([
                    'email' => 'No se pudo completar la recuperación. Solicita un código nuevo.',
                ]);
        }

        $request->session()->forget([
            'password_reset_email',
            'password_reset_verified_email',
            'password_reset_verified_at',
            'password_reset_code_id',
        ]);

        return redirect()->route('login')
            ->with('status', 'Tu contraseña se cambió correctamente. Ya puedes iniciar sesión.');
    }

    /**
     * Comprobar que el usuario verificó el código recientemente.
     */
    private function hasVerifiedCode(Request $request): bool
    {
        $email = $request->session()->get('password_reset_verified_email');
        $verifiedAt = $request->session()->get('password_reset_verified_at');
        $codeId = $request->session()->get('password_reset_code_id');

        if (!$email || !$verifiedAt || !$codeId) {
            return false;
        }

        if ((int) $verifiedAt < now()->subMinutes(5)->timestamp) {
            return false;
        }

        return PasswordResetCode::where('id', $codeId)
            ->where('email', $email)
            ->whereNotNull('verified_at')
            ->whereNull('used_at')
            ->exists();
    }
}
