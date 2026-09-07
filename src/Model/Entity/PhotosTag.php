<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PhotosTag Entity
 *
 * @property int $id
 * @property int $photo_id
 * @property int $tag_id
 * @property bool $visible
 * @property int $pos
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\Photo $photo
 * @property \App\Model\Entity\Tag $tag
 */
class PhotosTag extends Entity
{
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
        'photo_id' => true,
        'tag_id' => true,
        'visible' => true,
        'pos' => true,
        'created' => true,
        'modified' => true,
        'photo' => true,
        'tag' => true,
    ];
}
