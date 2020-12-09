<?php

return [

    'layout' => [
        //
    ],

    'errors' => [
        'credentials' => 'Invalid email or password.',

        'validation' => [
            'required' => 'The field is required.',
            'string' => 'The field must be a string.',
            'email' => 'The email is incorrect.',
            'email.unique' => 'The email is already taken.',
            'image' => 'The image is incorrect.',
            'password' => 'The password is invalid.',
            'password.confirmed' => 'The password is not confirmed.',

            'min' => [
                'string' => 'The field cannot contain less than :min characters.',
            ],

            'max' => [
                'string' => 'The field cannot contain more than :max characters.',
                'file' => 'The file size cannot be greater than :max kilobytes.',
            ],
        ],
    ],

];
