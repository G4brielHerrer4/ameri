<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Crea un usuario registrado desde el formulario público.
     *
     * Reglas:
     *  - role_id = null (pendiente de asignación por un administrador)
     *  - estado  = false (pendiente de aprobación)
     *
     * El admin puede asignar rol y activar después desde el CRUD.
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telefono'  => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'password'  => $this->passwordRules(),
            'terms'     => Jetstream::hasTermsAndPrivacyPolicyFeature()
                ? ['accepted', 'required']
                : '',
        ], [
            'name.required'  => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'El correo electrónico no es válido.',
            'email.unique'   => 'Este correo ya está registrado.',
        ])->validate();

        return User::create([
            'name'      => $input['name'],
            'email'     => $input['email'],
            'telefono'  => $input['telefono']  ?? null,
            'direccion' => $input['direccion'] ?? null,
            'password'  => Hash::make($input['password']),
            'role_id'   => null,   // Pendiente de asignación
            'estado'    => false,  // Pendiente de aprobación
        ]);
    }
}