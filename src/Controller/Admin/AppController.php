<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\I18n\I18n;
use KvAdmin\Controller\AppController as KvAdminAppController;

/**
 * Admin Application Controller (KvAdmin / Tabler).
 */
class AppController extends KvAdminAppController
{
    use TranslatableFormTrait;

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
     * Keep Translate + admin UI on the default (Hungarian) locale.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $defaultLocale = (string)Configure::read('App.defaultLocale', 'hu_HU');
        I18n::setLocale($defaultLocale);

        foreach (['Pages', 'PageBlocks', 'PageBlockItems', 'Photos', 'BlogPosts', 'Tags', 'PhotoCategories', 'Settings'] as $alias) {
            try {
                $table = $this->fetchTable($alias);
            } catch (\Throwable $e) {
                continue;
            }
            if ($table->hasBehavior('Translate')) {
                $table->getBehavior('Translate')->setLocale($defaultLocale);
            }
        }

        $this->set('contentLocales', $this->contentLocales());
        $this->set('defaultLocale', $defaultLocale);
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
