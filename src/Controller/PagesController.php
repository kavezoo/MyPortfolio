<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

/**
 * Pages Controller
 *
 * @property \App\Model\Table\PagesTable $Pages
 */
class PagesController extends AppController
{
    /**
     * Home page
     *
     * @return void
     */
    public function home(): void
    {
        $page = $this->Pages->getBySlug('home');
        $this->set(compact('page'));
        $this->set('title', $page->title);
        $this->set('basePagePath', '/');
    }

    /**
     * About page
     *
     * @return void
     */
    public function about(): void
    {
        $page = $this->Pages->getBySlug('rolam');
        $this->set(compact('page'));
        $this->set('title', $page->title);
        $this->set('basePagePath', '/rolam');
    }
}
