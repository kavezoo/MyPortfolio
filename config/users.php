<?php

return [
    // Social login később (Facebook + Google). A gombok a login sablonban kikommentelve vannak.
    'Users.Social.login' => false,

    // Regisztráció sablon kész, de jelenleg nem elérhető
    'Users.Registration.active' => false,

    // Magic link / one-time login most nem kell
    'OneTimeLogin.enabled' => false,

    // Bejelentkezés után az admin felületre irányít
    'Auth.AuthenticationComponent.loginRedirect' => '/admin',

    // Jelszó reset keresés email alapján
    'Users.PasswordReset.findWith' => ['email'],

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

    /*
    // Social login bekapcsolása később — csak Facebook és Google:
    'Users.Social.login' => true,
    'OAuth.providers.facebook.options.clientId' => 'YOUR_FACEBOOK_APP_ID',
    'OAuth.providers.facebook.options.clientSecret' => 'YOUR_FACEBOOK_APP_SECRET',
    'OAuth.providers.google.options.clientId' => 'YOUR_GOOGLE_CLIENT_ID',
    'OAuth.providers.google.options.clientSecret' => 'YOUR_GOOGLE_CLIENT_SECRET',
    */
];
