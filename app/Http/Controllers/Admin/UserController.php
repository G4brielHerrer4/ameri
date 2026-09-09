<?php
// app/Http/Controllers/Admin/UserController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        // El middleware 'role:admin' ya está en la ruta, no es necesario aquí
    }

    /**
     * Mostrar lista de usuarios
     */
    public function index()
    {
        $users = User::with('role')->orderBy('id', 'desc')->get();
        $roles = Role::all();
        return view('admin.usuarios.administracion.index', compact('users', 'roles'));
    }

    /**
     * Mostrar formulario de creación (si usas modales no es necesario)
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.usuarios.administracion.create', compact('roles'));
    }

    /**
     * Validaciones para crear usuario
     */
    private function validateCreate(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-ZáéíóúñÑüÜ\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/'
            ],
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id'
            ]
        ], [
            // Mensajes personalizados
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.regex' => 'Ingrese un formato de correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.',
            'role_id.required' => 'Debe seleccionar un rol.',
            'role_id.exists' => 'El rol seleccionado no es válido.'
        ]);
    }

    /**
     * Validaciones para actualizar usuario
     */
    private function validateUpdate(Request $request, User $user)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:3',
                'regex:/^[a-zA-ZáéíóúñÑüÜ\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id'
            ]
        ];

        // Si se proporciona contraseña, validarla
        if ($request->filled('password')) {
            $rules['password'] = [
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ];
        }

        $messages = [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'email.regex' => 'Ingrese un formato de correo válido.',
            'role_id.required' => 'Debe seleccionar un rol.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número y un carácter especial.'
        ];

        return Validator::make($request->all(), $rules, $messages);
    }

    /**
     * Almacenar un nuevo usuario
     */
    public function store(Request $request)
    {
        // Validar los datos
        $validator = $this->validateCreate($request);
        
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado correctamente',
                    'user' => $user->load('role')
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario creado correctamente');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el usuario: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al crear el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Mostrar formulario de edición (si usas modales no es necesario)
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.usuarios.administracion.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar un usuario existente
     */
    public function update(Request $request, User $user)
    {
        // Validar los datos
        $validator = $this->validateUpdate($request, $user);
        
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
            ];

            // Solo actualizar contraseña si se proporcionó
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado correctamente',
                    'user' => $user->fresh('role')
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario actualizado correctamente');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al actualizar el usuario: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Eliminar un usuario
     */
    public function destroy(User $user)
    {
        try {
            // No permitir eliminar el propio usuario
            if ($user->id === Auth::id()) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No puedes eliminar tu propio usuario.'
                    ], 403);
                }
                return redirect()->route('admin.users.index')
                    ->with('error', 'No puedes eliminar tu propio usuario.');
            }

            // No permitir eliminar el último administrador
            if ($user->role && $user->role->slug === 'admin') {
                $adminCount = User::where('role_id', $user->role_id)->count();
                if ($adminCount <= 1) {
                    if (request()->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No puedes eliminar el último administrador del sistema.'
                        ], 403);
                    }
                    return redirect()->route('admin.users.index')
                        ->with('error', 'No puedes eliminar el último administrador del sistema.');
                }
            }

            $user->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado correctamente'
                ]);
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'Usuario eliminado correctamente');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.users.index')
                ->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Buscar usuarios (para autocompletado o API)
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'term' => 'required|string|min:2|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $users = User::with('role')
            ->where('name', 'LIKE', "%{$request->term}%")
            ->orWhere('email', 'LIKE', "%{$request->term}%")
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

}