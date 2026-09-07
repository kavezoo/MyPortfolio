<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Utility\Text;
use App\Service\PhotoFileService;

/**
 * Loads the sample portfolio content into the database.
 */
class SeedPortfolioCommand extends Command
{
    /**
     * @var array<string, \App\Model\Entity\Photo>
     */
    protected array $photosBySlug = [];

    /**
     * @var array<string, \App\Model\Entity\Tag>
     */
    protected array $tagsByName = [];

    /**
     * @var array<string, \App\Model\Entity\PhotoCategory>
     */
    protected array $categoriesBySlug = [];

    /**
     * @var array<string, \App\Model\Entity\Page>
     */
    protected array $pagesBySlug = [];

    /**
     * @inheritDoc
     */
    public static function defaultName(): string
    {
        return 'seed_portfolio';
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription('Feltölti a mintaoldal tartalmát az adatbázisba.');

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $this->truncateAll();
        $this->seedSettings();
        $this->seedCategories();
        $this->seedPhotos();
        $this->seedPages();
        $this->seedHomeBlocks();
        $this->seedAboutBlock();
        $this->seedPanoramaIntro();
        $this->seedContactBlock();
        $this->seedBlogPosts();
        $this->seedPageTranslations();

        $io->success('A mintaoldal tartalma betöltődött.');

        return static::CODE_SUCCESS;
    }

    /**
     * Empty every content table so the seed can run repeatedly.
     *
     * @return void
     */
    protected function truncateAll(): void
    {
        $connection = $this->fetchTable('Photos')->getConnection();
        $connection->execute('SET FOREIGN_KEY_CHECKS = 0');
        foreach ([
            'i18n',
            'blog_posts_photos',
            'page_blocks_photos',
            'photos_tags',
            'page_block_items',
            'page_blocks',
            'blog_posts',
            'contact_messages',
            'pages',
            'photos',
            'tags',
            'photo_categories',
            'settings',
        ] as $table) {
            $connection->execute("TRUNCATE TABLE `{$table}`");
        }
        $connection->execute('SET FOREIGN_KEY_CHECKS = 1');
        $this->photosBySlug = [];
        $this->tagsByName = [];
        $this->categoriesBySlug = [];
        $this->pagesBySlug = [];
    }

    /**
     * @return void
     */
    protected function seedSettings(): void
    {
        $table = $this->fetchTable('Settings');
        $rows = [
            ['name' => 'site_name', 'label' => 'Oldal neve', 'value' => 'Varga Zsolt', 'pos' => 10],
            ['name' => 'address', 'label' => 'Cím', 'value' => '1013 budapest, clark ádám tér 1.', 'pos' => 20],
            ['name' => 'email', 'label' => 'E-mail', 'value' => 'zsolt.varga@pelda.hu', 'pos' => 30],
            ['name' => 'instagram_url', 'label' => 'Instagram', 'value' => 'https://www.instagram.com/exampleprofilelink/', 'pos' => 40],
            ['name' => 'facebook_url', 'label' => 'Facebook', 'value' => 'https://www.facebook.com/Example-Account-197494372329/', 'pos' => 50],
            ['name' => 'viewer_border_color', 'label' => 'Képnéző keret színe', 'value' => '#8a8a8a', 'pos' => 60],
            ['name' => 'viewer_border_width', 'label' => 'Képnéző keret vastagsága', 'value' => '2', 'pos' => 70],
            ['name' => 'default_description', 'label' => 'Alapértelmezett leírás', 'value' => 'Fénykép album – galéria és panorámák.', 'pos' => 80],
        ];
        foreach ($rows as $row) {
            $table->saveOrFail($table->newEntity($row));
        }
    }

    /**
     * @return void
     */
    protected function seedCategories(): void
    {
        $table = $this->fetchTable('PhotoCategories');
        $rows = [
            ['name' => 'portré', 'slug' => 'portre', 'pos' => 10],
            ['name' => 'természet', 'slug' => 'termeszet', 'pos' => 20],
            ['name' => 'város', 'slug' => 'varos', 'pos' => 30],
            ['name' => 'panoráma', 'slug' => 'panorama', 'pos' => 40],
            ['name' => 'háttér', 'slug' => 'hatter', 'pos' => 50],
        ];
        foreach ($rows as $row) {
            $category = $table->saveOrFail($table->newEntity($row));
            $this->categoriesBySlug[$category->slug] = $category;
        }
    }

    /**
     * @return void
     */
    protected function seedPhotos(): void
    {
        $photosTable = $this->fetchTable('Photos');
        $tagsTable = $this->fetchTable('Tags');
        $photosTagsTable = $this->fetchTable('PhotosTags');
        $files = new PhotoFileService();
        $catalog = require CONFIG . 'Seeds' . DS . 'data' . DS . 'catalog.php';
        $pos = 10;

        foreach ($catalog as $slug => $item) {
            $exif = $item['exif'] ?? [];
            $shotDate = null;
            if (!empty($exif['date'])) {
                $shotDate = str_replace('.', '-', (string)$exif['date']);
            }
            $minutes = 8 * 60 + (abs(crc32($slug)) % (11 * 60));
            $shotTime = sprintf('%02d:%02d:00', intdiv($minutes, 60), $minutes % 60);

            $photo = $photosTable->saveOrFail($photosTable->newEntity([
                'photo_category_id' => $this->categoriesBySlug[$item['category']]->id,
                'slug' => $slug,
                'code' => $slug,
                'filename' => 'pending',
                'title' => $item['title'],
                'description' => $item['description'] ?? null,
                'location' => $item['location'] ?? null,
                'city' => $this->photoCity($item),
                'camera' => $exif['camera'] ?? null,
                'lens' => $exif['lens'] ?? null,
                'exposure' => $exif['exposure'] ?? null,
                'aperture' => $exif['aperture'] ?? null,
                'iso' => $exif['iso'] ?? null,
                'focal' => $exif['focal'] ?? null,
                'shot_date' => $shotDate,
                'shot_time' => $shotTime,
                'in_gallery' => $item['category'] !== 'panorama',
                'pos' => $pos,
            ]));

            $source = WWW_ROOT . 'img' . DS . basename((string)$item['src']);
            $photo->filename = $files->importExistingFile((int)$photo->id, $source);
            $photosTable->saveOrFail($photo);

            $this->photosBySlug[$slug] = $photo;
            $pos += 10;

            foreach ($item['tags'] ?? [] as $tagName) {
                $tag = $this->tagByName($tagsTable, (string)$tagName);
                $photosTagsTable->saveOrFail($photosTagsTable->newEntity([
                    'photo_id' => $photo->id,
                    'tag_id' => $tag->id,
                    'pos' => 1000,
                ]));
            }
        }

        $bannerPos = 10;
        foreach ([
            ['slug' => 'hero', 'filename' => 'hero.jpg', 'title' => 'Kezdőlap'],
            ['slug' => 'banner-portrek', 'filename' => 'banner-portrek.jpg', 'title' => 'Portrék banner'],
            ['slug' => 'banner-varos', 'filename' => 'banner-varos.jpg', 'title' => 'Város banner'],
            ['slug' => 'banner-cta', 'filename' => 'banner-cta.jpg', 'title' => 'Időpont banner'],
            ['slug' => 'banner-termeszet', 'filename' => 'banner-termeszet.jpg', 'title' => 'Természet banner'],
        ] as $banner) {
            $photo = $photosTable->saveOrFail($photosTable->newEntity([
                'photo_category_id' => $this->categoriesBySlug['hatter']->id,
                'slug' => $banner['slug'],
                'code' => $banner['slug'],
                'filename' => 'pending',
                'title' => $banner['title'],
                'in_gallery' => false,
                'pos' => $bannerPos,
            ]));
            $source = WWW_ROOT . 'img' . DS . $banner['filename'];
            $photo->filename = $files->importExistingFile((int)$photo->id, $source);
            $photosTable->saveOrFail($photo);
            $this->photosBySlug[$banner['slug']] = $photo;
            $bannerPos += 10;
        }
    }

    /**
     * @return void
     */
    protected function seedPages(): void
    {
        $table = $this->fetchTable('Pages');
        $rows = [
            [
                'slug' => 'home',
                'menu_label' => 'kezdőlap',
                'url' => '/',
                'title' => 'Varga Zsolt – fénykép album',
                'meta_description' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae.',
                'hero_title' => 'Varga Zsolt',
                'hero_lead' => 'fénykép album',
                'hero_photo_id' => $this->photosBySlug['hero']->id,
                'template' => 'home',
                'pos' => 10,
            ],
            [
                'slug' => 'galeria',
                'menu_label' => 'galéria',
                'url' => '/galeria',
                'title' => 'Galéria – Varga Zsolt',
                'hero_title' => 'galéria',
                'hero_lead' => 'bélyegképek',
                'hero_photo_id' => $this->photosBySlug['banner-portrek']->id,
                'template' => 'gallery',
                'pos' => 20,
            ],
            [
                'slug' => 'panoramak',
                'menu_label' => 'panorámák',
                'url' => '/panoramak',
                'title' => 'Panorámák – Varga Zsolt',
                'hero_title' => 'panorámák',
                'hero_lead' => 'húzd oldalra a felfedezéshez',
                'hero_photo_id' => $this->photosBySlug['panorama-1']->id,
                'template' => 'panoramas',
                'pos' => 30,
            ],
            [
                'slug' => 'blog',
                'menu_label' => 'blog',
                'url' => '/blog',
                'title' => 'Blog – Varga Zsolt',
                'meta_description' => 'Bejegyzések a fényképezésről – képekkel, dátummal és képnézővel.',
                'hero_title' => 'blog',
                'hero_lead' => 'bejegyzések a képek mögött',
                'hero_photo_id' => $this->photosBySlug['banner-cta']->id,
                'template' => 'blog',
                'pos' => 40,
            ],
            [
                'slug' => 'rolam',
                'menu_label' => 'rólam',
                'url' => '/rolam',
                'title' => 'Rólam – Varga Zsolt',
                'hero_title' => 'rólam',
                'hero_photo_id' => $this->photosBySlug['termeszet-2']->id,
                'template' => 'about',
                'pos' => 50,
            ],
            [
                'slug' => 'kapcsolat',
                'menu_label' => 'kapcsolat',
                'url' => '/kapcsolat',
                'title' => 'Kapcsolat – Varga Zsolt',
                'hero_title' => 'kapcsolat',
                'hero_photo_id' => $this->photosBySlug['varos-6']->id,
                'template' => 'contact',
                'pos' => 60,
            ],
        ];
        foreach ($rows as $row) {
            $page = $table->saveOrFail($table->newEntity($row));
            $this->pagesBySlug[$page->slug] = $page;
        }
    }

    /**
     * @return void
     */
    protected function seedHomeBlocks(): void
    {
        $homeId = $this->pagesBySlug['home']->id;

        $portraits = $this->saveBlock([
            'page_id' => $homeId,
            'block_type' => 'photos_text',
            'layout' => 'two-left',
            'title' => 'portrék',
            'body' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae.',
            'quote' => 'Accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae.',
            'button_label' => 'mutass többet',
            'button_url' => '/galeria',
            'pos' => 10,
        ]);
        $this->saveBlockItem($portraits->id, 'paragraph', 'Illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo nemo enim ipsam voluptatem quia voluptas sit aspernatur aut.', 10);
        $this->attachPhotos($portraits->id, [
            ['slug' => 'portre-1', 'css_class' => 'photo-tall', 'pos' => 10],
            ['slug' => 'portre-2', 'css_class' => 'photo-tall', 'pos' => 20],
        ]);

        $this->saveBlock([
            'page_id' => $homeId,
            'block_type' => 'banner',
            'photo_id' => $this->photosBySlug['banner-portrek']->id,
            'pos' => 20,
        ]);

        $nature = $this->saveBlock([
            'page_id' => $homeId,
            'block_type' => 'photos_text',
            'layout' => 'text-split',
            'title' => 'természet',
            'body' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab.',
            'quote' => 'Rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta.',
            'button_label' => 'mutass többet',
            'button_url' => '/galeria',
            'pos' => 30,
        ]);
        $this->attachPhotos($nature->id, [
            ['slug' => 'termeszet-1', 'css_class' => 'photo-tall', 'pos' => 10],
            ['slug' => 'termeszet-2', 'css_class' => 'photo-mid', 'pos' => 20],
            ['slug' => 'termeszet-3', 'css_class' => 'photo-mid flex-grow-1', 'pos' => 30],
        ]);

        $this->saveBlock([
            'page_id' => $homeId,
            'block_type' => 'banner',
            'photo_id' => $this->photosBySlug['banner-varos']->id,
            'pos' => 40,
        ]);

        $city = $this->saveBlock([
            'page_id' => $homeId,
            'block_type' => 'photos_text',
            'layout' => 'two-left',
            'title' => 'városi tájkép',
            'body' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis.',
            'button_label' => 'mutass többet',
            'button_url' => '/galeria',
            'pos' => 50,
        ]);
        $this->saveBlockItem($city->id, 'paragraph', 'Sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa.', 10);
        foreach (['Berlin', 'Barcelona', 'Budapest'] as $i => $name) {
            $this->saveBlockItem($city->id, 'list', $name, ($i + 2) * 10);
        }
        $this->attachPhotos($city->id, [
            ['slug' => 'varos-1', 'css_class' => 'photo-tall', 'pos' => 10],
            ['slug' => 'varos-2', 'css_class' => 'photo-tall', 'pos' => 20],
        ]);

        $this->saveBlock([
            'page_id' => $homeId,
            'block_type' => 'banner',
            'photo_id' => $this->photosBySlug['banner-cta']->id,
            'pos' => 60,
        ]);

        $this->saveBlock([
            'page_id' => $homeId,
            'block_type' => 'cta',
            'title' => 'időpontfoglalás',
            'body' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt neque.',
            'button_label' => 'kapcsolat',
            'button_url' => '/kapcsolat',
            'pos' => 70,
        ]);
    }

    /**
     * @return void
     */
    protected function seedAboutBlock(): void
    {
        $block = $this->saveBlock([
            'page_id' => $this->pagesBySlug['rolam']->id,
            'block_type' => 'text_photo',
            'title' => 'Varga Zsolt',
            'body' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Sed ut perspiciatis unde omnis iste natus.',
            'photo_id' => $this->photosBySlug['portre-8']->id,
            'pos' => 10,
        ]);
        foreach (['2019 legjobb állatos fotója', '2020-as különdíj', 'Top 10 feltörekvő fotós'] as $i => $text) {
            $this->saveBlockItem($block->id, 'list', $text, ($i + 1) * 10);
        }
        $this->saveBlockItem($block->id, 'paragraph', 'Voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo nemo enim ipsam voluptatem quia.', 40);
        $this->saveBlockItem($block->id, 'paragraph', 'Unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab.', 50);
    }

    /**
     * @return void
     */
    protected function seedPanoramaIntro(): void
    {
        $this->saveBlock([
            'page_id' => $this->pagesBySlug['panoramak']->id,
            'block_type' => 'intro',
            'body' => 'A panorámák szélesebb látószöget mutatnak. Kattints egy képre, majd húzd vízszintesen – egérrel, ujjal vagy a nyilakkal –, hogy körbenézz a felvételen.',
            'pos' => 10,
        ]);
    }

    /**
     * @return void
     */
    protected function seedContactBlock(): void
    {
        $block = $this->saveBlock([
            'page_id' => $this->pagesBySlug['kapcsolat']->id,
            'block_type' => 'contact',
            'title' => 'foglalj időpontot',
            'body' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae.',
            'pos' => 10,
        ]);
        $this->saveBlockItem($block->id, 'list', '1013 budapest, clark ádám tér 1.', 10);
        $this->saveBlockItem($block->id, 'link', 'zsolt.varga@pelda.hu', 20, 'mailto:zsolt.varga@pelda.hu');
        $this->saveBlockItem($block->id, 'link', 'instagram', 30, 'https://www.instagram.com/exampleprofilelink/');
        $this->saveBlockItem($block->id, 'link', 'facebook', 40, 'https://www.facebook.com/Example-Account-197494372329/');
    }

    /**
     * @return void
     */
    protected function seedBlogPosts(): void
    {
        $postsTable = $this->fetchTable('BlogPosts');
        $joinTable = $this->fetchTable('BlogPostsPhotos');
        $posts = [
            [
                'slug' => 'hajnali-mezok',
                'title' => 'Hajnali mezők',
                'published' => '2024-06-19 05:42:00',
                'body' => 'Itt kezdődhet a szöveged. Kattints ide, és kezdheted is az írást. Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
                'photos' => ['termeszet-1', 'termeszet-2', 'termeszet-3', 'termeszet-4', 'termeszet-5', 'termeszet-6', 'termeszet-7', 'termeszet-8'],
                'pos' => 10,
            ],
            [
                'slug' => 'ablakfeny',
                'title' => 'Ablakfény és portrék',
                'published' => '2024-04-27 16:18:00',
                'body' => 'Természetes oldalfényben készült sorozat, lágy átmenetekkel. Accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo nemo enim ipsam voluptatem.',
                'photos' => ['portre-1', 'portre-2', 'portre-3', 'portre-4', 'portre-5', 'portre-6'],
                'pos' => 20,
            ],
            [
                'slug' => 'varosi-tengelyek',
                'title' => 'Városi tengelyek',
                'published' => '2024-07-02 19:05:00',
                'body' => 'A város ritmusa keskeny utcákból és homlokzatokból. Sit voluptatem accusantium doloremque laudantium totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae. Illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
                'photos' => ['varos-1', 'varos-2', 'varos-3', 'varos-4', 'varos-5', 'varos-6', 'varos-7', 'varos-8'],
                'pos' => 30,
            ],
        ];

        foreach ($posts as $data) {
            $photoSlugs = $data['photos'];
            unset($data['photos']);
            $post = $postsTable->saveOrFail($postsTable->newEntity($data));
            foreach ($photoSlugs as $i => $slug) {
                $joinTable->saveOrFail($joinTable->newEntity([
                    'blog_post_id' => $post->id,
                    'photo_id' => $this->photosBySlug[$slug]->id,
                    'pos' => ($i + 1) * 10,
                ]));
            }
        }
    }

    /**
     * @param array<string, mixed> $data Block data.
     * @return \App\Model\Entity\PageBlock
     */
    protected function saveBlock(array $data): \App\Model\Entity\PageBlock
    {
        $table = $this->fetchTable('PageBlocks');

        /** @var \App\Model\Entity\PageBlock $block */
        $block = $table->saveOrFail($table->newEntity($data));

        return $block;
    }

    /**
     * @param int $blockId Block id.
     * @param string $type Item type.
     * @param string $body Body.
     * @param int $pos Position.
     * @param string|null $url Optional URL.
     * @return void
     */
    protected function saveBlockItem(int $blockId, string $type, string $body, int $pos, ?string $url = null): void
    {
        $table = $this->fetchTable('PageBlockItems');
        $table->saveOrFail($table->newEntity([
            'page_block_id' => $blockId,
            'item_type' => $type,
            'body' => $body,
            'url' => $url,
            'pos' => $pos,
        ]));
    }

    /**
     * @param int $blockId Block id.
     * @param array<int, array{slug: string, css_class: string, pos: int}> $photos Photos.
     * @return void
     */
    protected function attachPhotos(int $blockId, array $photos): void
    {
        $table = $this->fetchTable('PageBlocksPhotos');
        foreach ($photos as $item) {
            $table->saveOrFail($table->newEntity([
                'page_block_id' => $blockId,
                'photo_id' => $this->photosBySlug[$item['slug']]->id,
                'css_class' => $item['css_class'],
                'pos' => $item['pos'],
            ]));
        }
    }

    /**
     * @param \App\Model\Table\TagsTable $tagsTable Tags table.
     * @param string $name Tag name.
     * @return \App\Model\Entity\Tag
     */
    protected function tagByName(\App\Model\Table\TagsTable $tagsTable, string $name): \App\Model\Entity\Tag
    {
        if (!isset($this->tagsByName[$name])) {
            $slug = Text::slug($name);
            $this->tagsByName[$name] = $tagsTable->saveOrFail($tagsTable->newEntity([
                'name' => $name,
                'slug' => $slug !== '' ? $slug : $name,
                'pos' => 1000,
            ]));
        }

        return $this->tagsByName[$name];
    }

    /**
     * @param array<string, mixed> $photo Catalog row.
     * @return string
     */
    protected function photoCity(array $photo): string
    {
        if (!empty($photo['city'])) {
            return (string)$photo['city'];
        }

        $location = (string)($photo['location'] ?? '');
        foreach (['Barcelona', 'Berlin', 'Budapest'] as $city) {
            if (stripos($location, $city) !== false) {
                return $city;
            }
        }

        return '';
    }

    /**
     * Seed page field translations into the i18n table.
     *
     * @return void
     */
    protected function seedPageTranslations(): void
    {
        $translations = require CONFIG . 'Seeds' . DS . 'data' . DS . 'page_translations.php';
        $connection = $this->fetchTable('Pages')->getConnection();

        foreach ($translations as $locale => $bySlug) {
            foreach ($bySlug as $slug => $fields) {
                $pageId = $this->pagesBySlug[$slug]->id ?? null;
                if (!$pageId) {
                    continue;
                }
                foreach ($fields as $field => $content) {
                    $connection->insert('i18n', [
                        'locale' => $locale,
                        'model' => 'Pages',
                        'foreign_key' => $pageId,
                        'field' => $field,
                        'content' => $content,
                    ]);
                }
            }
        }
    }
}
