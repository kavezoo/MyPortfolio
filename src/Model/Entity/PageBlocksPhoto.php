<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * PageBlocksPhoto Entity
 *
 * @property int $id
 * @property int $page_block_id
 * @property int $photo_id
 * @property string|null $css_class
 * @property bool $visible
 * @property int $pos
 * @property \Cake\I18n\DateTime|null $created
 * @property \Cake\I18n\DateTime|null $modified
 *
 * @property \App\Model\Entity\PageBlock $page_block
 * @property \App\Model\Entity\Photo $photo
 */
class PageBlocksPhoto extends Entity
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
        'page_block_id' => true,
        'photo_id' => true,
        'css_class' => true,
        'visible' => true,
        'pos' => true,
        'created' => true,
        'modified' => true,
        'page_block' => true,
        'photo' => true,
    ];
}
