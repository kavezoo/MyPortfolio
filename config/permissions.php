<?php
/**
 * Copyright 2010 - 2026, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2026, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/*
 * Rules are evaluated top-down, first matching rule will apply.
 * Unauthenticated users only match rules with bypassAuth => true.
 */

return [
    'CakeDC/Auth.permissions' => [
        // CakeDC Users: login / register / password flow (no auth required)
        [
            'prefix' => false,
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => [
                'socialLogin',
                'login',
                'logout',
                'socialEmail',
                'verify',
                'register',
                'validateEmail',
                'changePassword',
                'resetPassword',
                'requestResetPassword',
                'resendTokenValidation',
                'linkSocial',
                'webauthn2fa',
                'webauthn2faRegister',
                'webauthn2faRegisterOptions',
                'webauthn2faAuthenticate',
                'webauthn2faAuthenticateOptions',
                'requestLoginLink',
                'sendLoginLink',
                'singleTokenLogin',
            ],
            'bypassAuth' => true,
        ],
        [
            'prefix' => false,
            'plugin' => 'CakeDC/Users',
            'controller' => 'SocialAccounts',
            'action' => [
                'validateAccount',
                'resendValidation',
            ],
            'bypassAuth' => true,
        ],

        // Public portfolio (no login)
        [
            'prefix' => false,
            'plugin' => false,
            'controller' => ['Pages', 'Photos', 'BlogPosts', 'ContactMessages'],
            'action' => '*',
            'bypassAuth' => true,
        ],
        [
            'prefix' => false,
            'plugin' => false,
            'controller' => 'Error',
            'action' => '*',
            'bypassAuth' => true,
        ],

        // Admin prefix: only logged-in admin role
        [
            'role' => \CakeDC\Users\Model\Table\UsersTable::ROLE_ADMIN,
            'prefix' => 'Admin',
            'extension' => '*',
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',
        ],

        // Logged-in users: profile / logout in Users plugin
        [
            'role' => '*',
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => ['profile', 'logout', 'linkSocial', 'callbackLinkSocial'],
        ],
        [
            'role' => '*',
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => 'resetOneTimePasswordAuthenticator',
            'allowed' => function (array $user, $role, \Cake\Http\ServerRequest $request) {
                $userId = \Cake\Utility\Hash::get($request->getAttribute('params'), 'pass.0');
                if (!empty($userId) && !empty($user)) {
                    return $userId === $user['id'];
                }

                return false;
            },
        ],

        [
            'role' => '*',
            'plugin' => 'DebugKit',
            'controller' => '*',
            'action' => '*',
            'bypassAuth' => true,
        ],
    ],
];
