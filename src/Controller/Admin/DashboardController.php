<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Dashboard Controller
 *
 * Admin landing page with quick links to content sections.
 */
class DashboardController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index(): void
    {
        $counts = [
            'pages' => $this->fetchTable('Pages')->find()->count(),
            'photos' => $this->fetchTable('Photos')->find()->count(),
            'blogPosts' => $this->fetchTable('BlogPosts')->find()->count(),
            'contactMessages' => $this->fetchTable('ContactMessages')->find()->count(),
            'tags' => $this->fetchTable('Tags')->find()->count(),
            'settings' => $this->fetchTable('Settings')->find()->count(),
        ];

        $this->set(compact('counts'));
        $this->set('title', 'Admin');
    }
}
