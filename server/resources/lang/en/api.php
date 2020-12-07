<?php

return [

    'v1' => [

        'errors' => [

            'auth' => 'Invalid email or password.',

            'validation' => [

                'required' => 'The field is required.',
                'string' => 'The field must be a string.',
                'email' => 'The email is incorrect.',
                'email.unique' => 'The email is already taken.',
                'password_conf.same' => 'The password is not confirmed.',

                'min' => [
                    'string' => 'The field cannot contain less than :min characters.',
                ],

                'max' => [
                    'string' => 'The field cannot contain more than :max characters.',
                ],

            ],

        ],

    ],

];
