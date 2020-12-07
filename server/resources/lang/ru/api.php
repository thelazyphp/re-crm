<?php

return [

    'v1' => [

        'errors' => [

            'auth' => 'Неверный email или пароль.',

            'validation' => [

                'required' => 'Поле является обязательным.',
                'string' => 'Поле не является строкой.',
                'email' => 'Некорректный email.',
                'email.unique' => 'Email уже занят.',
                'password_conf.same' => 'Пароль не подтвержден.',

                'min' => [
                    'string' => 'Поле не может содержать менее than :min символов.',
                ],

                'max' => [
                    'string' => 'Поле не может содержать более than :max символов.',
                ],

            ],

        ],

    ],

];
