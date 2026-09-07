<?php
declare(strict_types=1);

use Migrations\BaseMigration;
use Migrations\Db\Table;

class InitialPortfolio extends BaseMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/migrations/5/guides/writing-migrations/migration-methods.html#the-change-method
     *
     * @return void
     */
    public function change(): void
    {
        $settings = $this->table('settings');
        $settings
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('label', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('value', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(['name'], ['unique' => true]);
        $this->addCommonColumns($settings)->create();

        $photoCategories = $this->table('photo_categories');
        $photoCategories
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('photos_count', 'integer', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(['slug'], ['unique' => true]);
        $this->addCommonColumns($photoCategories)->create();

        $tags = $this->table('tags');
        $tags
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('photos_count', 'integer', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(['name'], ['unique' => true])
            ->addIndex(['slug'], ['unique' => true]);
        $this->addCommonColumns($tags)->create();

        $photos = $this->table('photos');
        $photos
            ->addColumn('photo_category_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('code', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('filename', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('location', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('city', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('camera', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => true,
            ])
            ->addColumn('lens', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => true,
            ])
            ->addColumn('exposure', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('aperture', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('iso', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('focal', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('shot_date', 'date', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('shot_time', 'time', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('dimensions', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('in_gallery', 'boolean', [
                'default' => true,
                'null' => false,
            ])
            ->addColumn('tags_count', 'integer', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(['slug'], ['unique' => true])
            ->addIndex(['photo_category_id'])
            ->addIndex(['city'])
            ->addForeignKey('photo_category_id', 'photo_categories', 'id', [
                'delete' => 'RESTRICT',
                'update' => 'CASCADE',
            ]);
        $this->addCommonColumns($photos)->create();

        $photosTags = $this->table('photos_tags');
        $photosTags
            ->addColumn('photo_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('tag_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addIndex(['photo_id', 'tag_id'], ['unique' => true])
            ->addIndex(['tag_id'])
            ->addForeignKey('photo_id', 'photos', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey('tag_id', 'tags', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ]);
        $this->addCommonColumns($photosTags)->create();

        $pages = $this->table('pages');
        $pages
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => false,
            ])
            ->addColumn('menu_label', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('meta_description', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('hero_title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('hero_lead', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('hero_photo_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('body', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('template', 'string', [
                'default' => 'default',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('page_blocks_count', 'integer', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(['slug'], ['unique' => true])
            ->addIndex(['hero_photo_id'])
            ->addForeignKey('hero_photo_id', 'photos', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE',
            ]);
        $this->addCommonColumns($pages)->create();

        $pageBlocks = $this->table('page_blocks');
        $pageBlocks
            ->addColumn('page_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('block_type', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('layout', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('lead', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('body', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('quote', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('button_label', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('button_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('photo_id', 'integer', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('page_block_items_count', 'integer', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('photos_count', 'integer', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(['page_id'])
            ->addIndex(['photo_id'])
            ->addForeignKey('page_id', 'pages', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey('photo_id', 'photos', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE',
            ]);
        $this->addCommonColumns($pageBlocks)->create();

        $pageBlockItems = $this->table('page_block_items');
        $pageBlockItems
            ->addColumn('page_block_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('item_type', 'string', [
                'default' => 'list',
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('body', 'text', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addIndex(['page_block_id'])
            ->addForeignKey('page_block_id', 'page_blocks', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ]);
        $this->addCommonColumns($pageBlockItems)->create();

        $pageBlocksPhotos = $this->table('page_blocks_photos');
        $pageBlocksPhotos
            ->addColumn('page_block_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('photo_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('css_class', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addIndex(['page_block_id', 'photo_id'], ['unique' => true])
            ->addIndex(['photo_id'])
            ->addForeignKey('page_block_id', 'page_blocks', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey('photo_id', 'photos', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ]);
        $this->addCommonColumns($pageBlocksPhotos)->create();

        $blogPosts = $this->table('blog_posts');
        $blogPosts
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('body', 'text', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('published', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('photos_count', 'integer', [
                'default' => 0,
                'null' => false,
                'signed' => false,
            ])
            ->addIndex(['slug'], ['unique' => true]);
        $this->addCommonColumns($blogPosts)->create();

        $blogPostsPhotos = $this->table('blog_posts_photos');
        $blogPostsPhotos
            ->addColumn('blog_post_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addColumn('photo_id', 'integer', [
                'default' => null,
                'null' => false,
            ])
            ->addIndex(['blog_post_id', 'photo_id'], ['unique' => true])
            ->addIndex(['photo_id'])
            ->addForeignKey('blog_post_id', 'blog_posts', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->addForeignKey('photo_id', 'photos', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ]);
        $this->addCommonColumns($blogPostsPhotos)->create();

        $contactMessages = $this->table('contact_messages');
        $contactMessages
            ->addColumn('name', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 150,
                'null' => false,
            ])
            ->addColumn('phone', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => true,
            ])
            ->addColumn('message', 'text', [
                'default' => null,
                'null' => false,
            ]);
        $this->addCommonColumns($contactMessages)->create();
    }

    /**
     * Adds the shared columns used by every table.
     *
     * @param \Migrations\Db\Table $table Table instance.
     * @return \Migrations\Db\Table
     */
    protected function addCommonColumns(Table $table): Table
    {
        return $table
            ->addColumn('visible', 'boolean', [
                'default' => true,
                'null' => false,
            ])
            ->addColumn('pos', 'integer', [
                'default' => 1000,
                'null' => false,
                'signed' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex(['visible', 'pos']);
    }
}
