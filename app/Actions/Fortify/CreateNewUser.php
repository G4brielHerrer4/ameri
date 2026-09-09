<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role_id' => ['nullable', 'exists:roles,id'], // 👈 AGREGADO: role_id opcional
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Si se envió un role_id, usar ese, sino asignar 'cliente' por defecto
        if (isset($input['role_id']) && $input['role_id']) {
            $roleId = $input['role_id'];
        } else {
            // Obtener el rol de Cliente (slug = 'cliente')
            $clienteRole = Role::where('slug', 'cliente')->first();
            $roleId = $clienteRole ? $clienteRole->id : null;
        }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role_id' => $roleId,
        ]);
    }
}