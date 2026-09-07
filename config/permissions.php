<?php
/**
 * Rules are evaluated top-down, first matching rule will apply.
 * Unauthenticated users only match rules with bypassAuth => true.
 */

return [
    'CakeDC/Auth.permissions' => [
        // Elérhető auth flow: login + jelszó visszaállítás
        [
            'prefix' => false,
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => [
                'login',
                'logout',
                'requestResetPassword',
                'resetPassword',
                'changePassword',
                // 'register', // sablon kész, de most nem elérhető
                // 'validateEmail',
                // 'socialLogin',
                // 'socialEmail',
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

        // Logged-in users: profile / logout
        [
            'role' => '*',
            'plugin' => 'CakeDC/Users',
            'controller' => 'Users',
            'action' => ['profile', 'logout', 'changePassword'],
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
