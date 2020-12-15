<?php

return [

    'errors' => [

        'auth' => 'Invalid email or password.',

        'validation' => [
            'required' => 'The field is required.',
            'email' => 'The email format is incorrect.',
            'email.unique' => 'The email is already taken.',
            'file' => 'The field must be a file.',
            'image' => 'The image format is incorrect.',
            'string' => 'The field must be a string.',
            'mimetypes' => 'The supported files types: :values.',
            'password.confirmed' => 'The password is not confirmed.',

            'min' => [
                'string' => 'The field cannot contain less than :min characters.',
            ],

            'max' => [
                'file' => 'The file size cannot be greater than :max kilobytes.',
                'string' => 'The field cannot contain more than :max characters.',
            ],
        ],

    ],

];
