<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Behavior\Translate\TranslateTrait;
use Cake\ORM\Entity;

/**
 * BlogPost Entity
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $body
 * @property \Cake\I18n\DateTime|null $published
 * @property int $photos_count
 * @property bool $visible
 * @property int $pos
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Photo[] $photos
 */
class BlogPost extends Entity
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
        'title' => true,
        'body' => true,
        'published' => true,
        'photos_count' => true,
        'visible' => true,
        'pos' => true,
        'created' => true,
        'modified' => true,
        'photos' => true,
        '_translations' => true,
    ];
}
