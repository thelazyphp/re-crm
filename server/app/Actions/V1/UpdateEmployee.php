<?php

namespace App\Actions\V1;

use App\Models\Employee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UpdateEmployee
{
    /**
     * @param  \App\Models\Employee  $employee
     * @param  array  $input
     * @return \App\Models\Employee
     */
    public function update(Employee $employee, $input)
    {
        Validator::make($input, [
            'profile_photo' => ['nullable', 'image', 'max:10240'],
            'name' => ['string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($employee)],
            'password' => ['string', 'min:8'],
        ], trans('api.v1.errors.validation'))->validate();

        return tap($employee->fill(tap($input, function (&$input) {
            if (isset($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            }
        })), function ($employee) use ($input) {
            if (! empty($input['profile_photo'])) {
                $employee->updateProfilePhoto($input['profile_photo']);
            }
        });
    }
}
