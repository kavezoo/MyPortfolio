<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\ORM\Entity;

/**
 * PageBlock Entity
 *
 * @property int $id
 * @property int $page_id
 * @property string $block_type
 * @property string|null $layout
 * @property string|null $title
 * @property string|null $lead
 * @property string|null $body
 * @property string|null $quote
 * @property string|null $button_label
 * @property string|null $button_url
 * @property int|null $photo_id
 * @property int $page_block_items_count
 * @property int $photos_count
 * @property bool $visible
 * @property int $pos
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Page $page
 * @property \App\Model\Entity\Photo $featured_photo
 * @property \App\Model\Entity\PageBlockItem[] $page_block_items
 * @property \App\Model\Entity\Photo[] $photos
 */
class PageBlock extends Entity
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
        'page_id' => true,
        'block_type' => true,
        'layout' => true,
        'title' => true,
        'lead' => true,
        'body' => true,
        'quote' => true,
        'button_label' => true,
        'button_url' => true,
        'photo_id' => true,
        'page_block_items_count' => true,
        'photos_count' => true,
        'visible' => true,
        'pos' => true,
        'created' => true,
        'modified' => true,
        'page' => true,
        'featured_photo' => true,
        'page_block_items' => true,
        'photos' => true,
        '_translations' => true,
    ];
}
