<?php

return [
    'Users.Social.login' => false,

    // Bejelentkezés után az admin felületre irányít
    'Auth.AuthenticationComponent.loginRedirect' => '/admin',

    // Form mező: email (POST), azonosítás: users.email oszlop
    // Fontos: az "Authentication.Password" kulcsot NEM szabad pontozott Configure
    // stringgel felülírni, mert szétválik Authentication + Password ágra.
    'Auth.Authenticators.Form.fields' => [
        'username' => 'email',
        'password' => 'password',
    ],
    'Auth.Authenticators.Form.identifier' => [
        'Authentication.Password' => [
            'fields' => [
                'username' => 'email',
                'password' => 'password',
            ],
            'resolver' => [
                'className' => 'Authentication.Orm',
                'finder' => 'active',
            ],
        ],
    ],
];
