<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Event\EventInterface;
use KvAdmin\Controller\AppController as KvAdminAppController;

/**
 * Admin Application Controller (KvAdmin / Tabler).
 */
class AppController extends KvAdminAppController
{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Skip public-site chrome (menu/settings) on admin pages.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function beforeRender(EventInterface $event): void
    {
        // Do not call App\Controller\AppController::beforeRender().
    }
}
