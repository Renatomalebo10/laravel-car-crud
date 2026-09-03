<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Valida e cria um novo utilizador no registo.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $userCount = User::count();

        // 1. Bloqueia o registo se já existirem 2 utilizadores
        if ($userCount >= 2) {
            throw ValidationException::withMessages([
                'email' => ['O limite máximo de 2 utilizadores para este sistema já foi atingido.'],
            ]);
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // 2. O 1º registo é 'admin', o 2º registo é 'user'
        $role = ($userCount === 0) ? 'admin' : 'user';

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'role' => $role,
            'password' => Hash::make($input['password']),
        ]);
    }
}