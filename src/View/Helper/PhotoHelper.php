<?php
declare(strict_types=1);

namespace App\View\Helper;

use App\Model\Entity\Photo;
use Cake\View\Helper;

/**
 * Photo helper
 *
 * Renders the protected image stack used across the public site.
 */
class PhotoHelper extends Helper
{
    /**
     * @var array<string>
     */
    protected array $helpers = ['Html'];

    /**
     * Clickable photo frame for the gallery / home / panorama viewers.
     *
     * @param \App\Model\Entity\Photo $photo Photo entity.
     * @param string $extraClass Extra CSS classes (photo-tall, photo-thumb, ...).
     * @return string
     */
    public function frame(Photo $photo, string $extraClass = 'photo-tall'): string
    {
        $isPano = $photo->photo_category && $photo->photo_category->slug === 'panorama';
        $jsClass = $isPano ? 'js-panorama' : 'js-photo';
        $tags = [];
        foreach ($photo->tags ?? [] as $tag) {
            if ($tag->visible) {
                $tags[] = $tag->name;
            }
        }

        $payload = h(json_encode($photo->viewerPayload(), JSON_UNESCAPED_UNICODE));
        $class = h(trim("photo-frame {$jsClass} {$extraClass}"));
        $tagsAttr = h(implode(',', $tags));
        $cityAttr = h((string)$photo->city);

        return '<button type="button" class="' . $class . '" data-photo="' . $payload
            . '" data-tags="' . $tagsAttr . '" data-city="' . $cityAttr
            . '" aria-label="' . h(__('Open {0}', $photo->title)) . '">'
            . $this->stack($photo)
            . '</button>';
    }

    /**
     * Image + shield overlay.
     *
     * @param \App\Model\Entity\Photo $photo Photo entity.
     * @return string
     */
    public function stack(Photo $photo): string
    {
        $alt = h((string)$photo->title);
        $src = h($photo->srcUrl());
        $shield = h($photo->shieldUrl());

        return '<span class="photo-stack">'
            . '<img class="photo-real" src="' . $src . '" alt="' . $alt . '" loading="lazy" draggable="false">'
            . '<img class="photo-shield" src="' . $shield . '" alt="" draggable="false">'
            . '</span>';
    }
}
