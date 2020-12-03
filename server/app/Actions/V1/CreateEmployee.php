<?php

namespace App\Actions\V1;

use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class CreateEmployee
{
    /**
     * @param  array  $input
     * @return \App\Models\Employee
     */
    public function create($input)
    {
        Validator::make($input, [
            'profile_photo' => ['nullable', 'image', 'max:10240'],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ], trans('api.v1.errors.validation'))->validate();

        return tap(Employee::create([
            'name' => $input['name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]), function ($employee) use ($input) {
            if (! empty($input['profile_photo'])) {
                $employee->updateProfilePhoto($input['profile_photo']);
            }
        });
    }
}
