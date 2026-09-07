<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Core\Configure;
use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * Photo Entity
 *
 * @property int $id
 * @property string $uuid
 * @property int $photo_category_id
 * @property string $slug
 * @property string|null $code
 * @property string|null $original_name
 * @property string $filename
 * @property string $title
 * @property string|null $description
 * @property string|null $location
 * @property string|null $city
 * @property string|null $camera
 * @property string|null $lens
 * @property string|null $exposure
 * @property string|null $aperture
 * @property string|null $iso
 * @property string|null $focal
 * @property \Cake\I18n\Date|null $shot_date
 * @property \Cake\I18n\Time|null $shot_time
 * @property string|null $dimensions
 * @property bool $in_gallery
 * @property int $tags_count
 * @property bool $visible
 * @property int $pos
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\PhotoCategory $photo_category
 * @property \App\Model\Entity\Tag[] $tags
 * @property \App\Model\Entity\BlogPost[] $blog_posts
 * @property \App\Model\Entity\PageBlock[] $page_blocks
 * @property \App\Model\Entity\PageBlocksPhoto $_joinData
 */
class Photo extends Entity
{
    use TranslateTrait;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'uuid' => true,
        'photo_category_id' => true,
        'slug' => true,
        'code' => true,
        'original_name' => true,
        'filename' => true,
        'title' => true,
        'description' => true,
        'location' => true,
        'city' => true,
        'camera' => true,
        'lens' => true,
        'exposure' => true,
        'aperture' => true,
        'iso' => true,
        'focal' => true,
        'shot_date' => true,
        'shot_time' => true,
        'dimensions' => true,
        'in_gallery' => true,
        'tags_count' => true,
        'visible' => true,
        'pos' => true,
        'created' => true,
        'modified' => true,
        'photo_category' => true,
        'tags' => true,
        'blog_posts' => true,
        'page_blocks' => true,
    ];

    /**
     * Public image URL.
     *
     * @return string
     */
    public function srcUrl(): string
    {
        $filename = ltrim(str_replace('\\', '/', (string)$this->filename), '/');
        if ($filename === '' || $filename === 'pending') {
            return Router::url('/img/hero.jpg');
        }

        // New uploads: uploads/YYYY/MM/{id}.ext  →  /img/uploads/...
        // Legacy seed files may still be bare names under /img/
        return Router::url('/img/' . $filename);
    }

    /**
     * Transparent overlay URL used as a simple image shield.
     *
     * @return string
     */
    public function shieldUrl(): string
    {
        return Router::url('/protect/' . rawurlencode((string)$this->slug) . '.png');
    }

    /**
     * Shareable viewer URL for this photo.
     *
     * @param string|null $lang Language code.
     * @return string
     */
    public function shareUrl(?string $lang = null): string
    {
        $lang = $lang ?: (string)Configure::read('App.language', 'hu');

        return Router::url('/' . $lang . '/foto/' . $this->uuid);
    }

    /**
     * Absolute public image URL (for Open Graph / social share).
     *
     * @return string
     */
    public function absoluteSrcUrl(): string
    {
        $filename = ltrim(str_replace('\\', '/', (string)$this->filename), '/');
        if ($filename === '' || $filename === 'pending') {
            return Router::url('/img/hero.jpg', true);
        }

        return Router::url('/img/' . $filename, true);
    }

    /**
     * Absolute shareable viewer URL.
     *
     * @param string|null $lang Language code.
     * @return string
     */
    public function absoluteShareUrl(?string $lang = null): string
    {
        $lang = $lang ?: (string)Configure::read('App.language', 'hu');

        return Router::url('/' . $lang . '/foto/' . $this->uuid, true);
    }

    /**
     * Display identifier shown in the photo viewer (original file name / serial).
     *
     * @return string
     */
    public function displayCode(): string
    {
        $name = trim((string)($this->original_name ?? ''));
        if ($name !== '') {
            return $name;
        }

        $code = trim((string)($this->code ?? ''));
        if ($code !== '') {
            return $code;
        }

        return (string)$this->id;
    }

    /**
     * JSON payload used by the frontend photo / panorama viewer.
     *
     * @return array<string, mixed>
     */
    public function viewerPayload(): array
    {
        $tags = [];
        foreach ($this->tags ?? [] as $tag) {
            if ($tag->visible) {
                $tags[] = $tag->name;
            }
        }

        $sharePath = $this->shareUrl();

        return [
            'id' => $this->displayCode(),
            'uuid' => $this->uuid,
            'original_name' => $this->displayCode(),
            'slug' => $this->slug,
            'file' => $this->filename,
            'src' => $this->srcUrl(),
            'shield' => $this->shieldUrl(),
            'url' => $sharePath,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'tags' => $tags,
            'exif' => [
                'camera' => $this->camera,
                'lens' => $this->lens,
                'exposure' => $this->exposure,
                'aperture' => $this->aperture,
                'iso' => $this->iso,
                'focal' => $this->focal,
                'date' => $this->shot_date ? $this->shot_date->format('Y.m.d') : null,
                'time' => $this->shot_time ? $this->shot_time->format('H:i') : null,
                'dimensions' => $this->dimensions,
            ],
        ];
    }
}
