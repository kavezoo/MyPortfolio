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

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\I18n\I18n;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/5/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }

    /**
     * Apply the active language from the URL.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $languages = Configure::read('App.languages') ?: [];
        $default = (string)Configure::read('App.defaultLanguage', 'hu');
        $lang = (string)$this->request->getParam('lang', $default);
        if (!isset($languages[$lang])) {
            $lang = $default;
        }

        $locale = (string)($languages[$lang]['locale'] ?? Configure::read('App.defaultLocale'));
        I18n::setLocale($locale);
        Configure::write('App.language', $lang);

        foreach (['Pages', 'PageBlocks', 'PageBlockItems', 'Photos', 'BlogPosts', 'Tags', 'PhotoCategories', 'Settings'] as $alias) {
            try {
                $table = $this->fetchTable($alias);
            } catch (\Throwable $e) {
                continue;
            }
            if ($table->hasBehavior('Translate')) {
                $table->getBehavior('Translate')->setLocale($locale);
            }
        }

        $this->set('currentLang', $lang);
        $this->set('languages', $languages);
    }

    /**
     * Load site-wide navigation and settings for every view.
     *
     * @param \Cake\Event\EventInterface $event Event.
     * @return void
     */
    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);

        $lang = (string)Configure::read('App.language', 'hu');
        $path = $this->pathWithoutLang((string)$this->request->getPath());

        $this->set('siteSettings', $this->fetchTable('Settings')->asMap());
        $this->set('menuPages', $this->fetchTable('Pages')->find('menu')->all());
        $this->set('currentUrl', $path);
        $this->set('currentLang', $lang);
        $this->set('languages', Configure::read('App.languages') ?: []);
        $this->set('langPath', $path);
    }

    /**
     * Build a localized path (e.g. /hu/galeria).
     *
     * @param string $path Path without language prefix.
     * @param string|null $lang Language code.
     * @return string
     */
    protected function localizedPath(string $path, ?string $lang = null): string
    {
        $lang = $lang ?: (string)Configure::read('App.language', 'hu');
        $path = $this->normalizePath($path);
        if ($path === '/') {
            return '/' . $lang;
        }

        return '/' . $lang . $path;
    }

    /**
     * Strip the language prefix from a request path.
     *
     * @param string $path Request path.
     * @return string
     */
    protected function pathWithoutLang(string $path): string
    {
        $path = $this->normalizePath($path);
        $languages = array_keys(Configure::read('App.languages') ?: []);
        if (!$languages) {
            return $path;
        }

        $pattern = '#^/(' . implode('|', array_map('preg_quote', $languages)) . ')(/.*)?$#';
        if (preg_match($pattern, $path, $matches)) {
            return isset($matches[2]) && $matches[2] !== ''
                ? $this->normalizePath($matches[2])
                : '/';
        }

        return $path;
    }

    /**
     * Normalize a path for comparisons and menu highlighting.
     *
     * @param string $path Request path.
     * @return string
     */
    protected function normalizePath(string $path): string
    {
        if ($path === '' || $path === '/') {
            return '/';
        }

        return '/' . trim($path, '/');
    }
}
