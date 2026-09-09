<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * BlogPosts Controller
 *
 * @property \App\Model\Table\BlogPostsTable $BlogPosts
 */
class BlogPostsController extends AppController
{
    /**
     * Index method
     *
     * @return void
     */
    public function index(): void
    {
        $page = $this->fetchTable('Pages')->getBySlug('blog');
        $blogPosts = $this->BlogPosts->find('published')->all();

        $this->set(compact('page', 'blogPosts'));
        $this->set('title', $page->title);
        $this->set('basePagePath', '/blog');
    }
}
