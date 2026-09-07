<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\ORM\Entity;

/**
 * Page Entity
 *
 * @property int $id
 * @property string $slug
 * @property string|null $menu_label
 * @property string $url
 * @property string $title
 * @property string|null $meta_description
 * @property string|null $hero_title
 * @property string|null $hero_lead
 * @property int|null $hero_photo_id
 * @property string|null $body
 * @property string $template
 * @property int $page_blocks_count
 * @property bool $visible
 * @property int $pos
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Photo $hero_photo
 * @property \App\Model\Entity\PageBlock[] $page_blocks
 */
class Page extends Entity
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
        'slug' => true,
        'menu_label' => true,
        'url' => true,
        'title' => true,
        'meta_description' => true,
        'hero_title' => true,
        'hero_lead' => true,
        'hero_photo_id' => true,
        'body' => true,
        'template' => true,
        'page_blocks_count' => true,
        'visible' => true,
        'pos' => true,
        'created' => true,
        'modified' => true,
        'hero_photo' => true,
        'page_blocks' => true,
    ];
}
