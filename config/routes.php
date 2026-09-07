<?php
/**
 * Routes configuration.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Configure;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

/*
 * This file is loaded in the context of the `Application` class.
 * So you can use `$this` to reference the application class instance
 * if required.
 */
return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->prefix('Admin', function (RouteBuilder $builder): void {
        $builder->setRouteClass(DashedRoute::class);
        $builder->connect('/', ['controller' => 'Dashboard', 'action' => 'index']);
        $builder->fallbacks(DashedRoute::class);
    });

    $languages = array_keys(Configure::read('App.languages') ?: [
        'hu' => [],
        'en' => [],
        'de' => [],
        'it' => [],
        'fr' => [],
    ]);
    $langPattern = implode('|', $languages);

    $routes->scope('/', function (RouteBuilder $builder) use ($langPattern): void {
        $builder->redirect('/', '/hu', ['status' => 302]);

        $builder->connect(
            '/protect/{slug}.png',
            ['controller' => 'Photos', 'action' => 'shield']
        )
            ->setPass(['slug'])
            ->setPatterns(['slug' => '[a-z0-9-]+']);

        $withLang = function (string $template, array $defaults = []) use ($builder, $langPattern) {
            return $builder
                ->connect($template, $defaults)
                ->setPatterns(['lang' => $langPattern])
                ->setPersist(['lang']);
        };

        $withLang('/{lang}', ['controller' => 'Pages', 'action' => 'home']);
        $withLang('/{lang}/rolam', ['controller' => 'Pages', 'action' => 'about']);
        $withLang('/{lang}/galeria', ['controller' => 'Photos', 'action' => 'index']);
        $withLang('/{lang}/panoramak', ['controller' => 'Photos', 'action' => 'panoramas']);
        $withLang('/{lang}/blog', ['controller' => 'BlogPosts', 'action' => 'index']);
        $withLang('/{lang}/kapcsolat', ['controller' => 'ContactMessages', 'action' => 'add']);
        $builder
            ->connect('/{lang}/foto/{uuid}', ['controller' => 'Photos', 'action' => 'view'])
            ->setPass(['uuid'])
            ->setPatterns([
                'lang' => $langPattern,
                'uuid' => '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}',
            ])
            ->setPersist(['lang']);

        $builder
            ->connect('/{lang}/{controller}', ['action' => 'index'])
            ->setPatterns(['lang' => $langPattern])
            ->setPersist(['lang']);
        $builder
            ->connect('/{lang}/{controller}/{action}/*', [])
            ->setPatterns(['lang' => $langPattern])
            ->setPersist(['lang']);
    });
};
