<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;

/**
 * Photos Controller
 *
 * @property \App\Model\Table\PhotosTable $Photos
 */
class PhotosController extends AppController
{
    /**
     * Gallery index
     *
     * @return void
     */
    public function index(): void
    {
        $page = $this->fetchTable('Pages')->getBySlug('galeria');
        $photos = $this->Photos->find('gallery')->all();

        $this->set(compact('page', 'photos'));
        $this->set('tags', $this->collectTags($photos));
        $this->set('cities', $this->collectCities($photos));
        $this->set('title', $page->title);
        $this->set('basePagePath', '/galeria');
    }

    /**
     * Panoramas listing
     *
     * @return void
     */
    public function panoramas(): void
    {
        $page = $this->fetchTable('Pages')->getBySlug('panoramak');
        $photos = $this->Photos->find('panoramas')->all();

        $this->set(compact('page', 'photos'));
        $this->set('tags', $this->collectTags($photos));
        $this->set('cities', $this->collectCities($photos));
        $this->set('title', $page->title);
        $this->set('basePagePath', '/panoramak');
    }

    /**
     * Open a single photo by UUID (shareable deep link).
     *
     * @param string $uuid Photo UUID.
     * @return void
     */
    public function view(string $uuid): void
    {
        $photo = $this->Photos->getByUuid($uuid);
        $isPano = $photo->photo_category && $photo->photo_category->slug === 'panorama';

        if ($isPano) {
            $page = $this->fetchTable('Pages')->getBySlug('panoramak');
            $photos = $this->Photos->find('panoramas')->all();
            $this->viewBuilder()->setTemplate('panoramas');
            $this->set('basePagePath', '/panoramak');
        } else {
            $page = $this->fetchTable('Pages')->getBySlug('galeria');
            $photos = $this->Photos->find('gallery')->all()->toList();
            $found = false;
            foreach ($photos as $item) {
                if ($item->uuid === $uuid) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $photos[] = $photo;
            }
            $this->viewBuilder()->setTemplate('index');
            $this->set('basePagePath', '/galeria');
        }

        $this->set(compact('page', 'photos'));
        $this->set('tags', $this->collectTags($photos));
        $this->set('cities', $this->collectCities($photos));
        $this->set('title', $photo->title . ' – ' . ($page->title ?? ''));
        $this->set('openPhotoUuid', $uuid);
        $this->set('sharePhoto', $photo);
    }

    /**
     * Transparent PNG shield matching the photo dimensions.
     *
     * @param string $slug Photo slug.
     * @return \Cake\Http\Response
     */
    public function shield(string $slug): Response
    {
        $photo = $this->Photos->find()
            ->where(['Photos.slug' => $slug, 'Photos.visible' => true])
            ->first();
        if (!$photo) {
            throw new NotFoundException('Not found');
        }

        $jpg = WWW_ROOT . 'img' . DS . $photo->filename;
        $size = is_file($jpg) ? @getimagesize($jpg) : false;
        $width = is_array($size) ? (int)$size[0] : 1;
        $height = is_array($size) ? (int)$size[1] : 1;

        $cacheDir = WWW_ROOT . 'protect';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $cache = $cacheDir . DS . $photo->slug . '.png';
        if (!is_file($cache)) {
            file_put_contents($cache, $this->makeTransparentPng($width, $height));
        }

        return $this->response
            ->withType('png')
            ->withHeader('Content-Disposition', 'inline; filename="' . $photo->slug . '.png"')
            ->withHeader('Cache-Control', 'public, max-age=31536000')
            ->withFile($cache);
    }

    /**
     * Unique visible tag names, sorted.
     *
     * @param iterable<\App\Model\Entity\Photo> $photos Photos.
     * @return array<string>
     */
    protected function collectTags(iterable $photos): array
    {
        $tags = [];
        foreach ($photos as $photo) {
            foreach ($photo->tags ?? [] as $tag) {
                if ($tag->visible) {
                    $tags[$tag->name] = $tag->name;
                }
            }
        }
        $list = array_values($tags);
        sort($list, SORT_FLAG_CASE | SORT_STRING);

        return $list;
    }

    /**
     * Unique cities, sorted.
     *
     * @param iterable<\App\Model\Entity\Photo> $photos Photos.
     * @return array<string>
     */
    protected function collectCities(iterable $photos): array
    {
        $cities = [];
        foreach ($photos as $photo) {
            if ($photo->city !== null && $photo->city !== '') {
                $cities[$photo->city] = $photo->city;
            }
        }
        $list = array_values($cities);
        sort($list, SORT_FLAG_CASE | SORT_STRING);

        return $list;
    }

    /**
     * Build a fully transparent PNG of the given size.
     *
     * @param int $width Width.
     * @param int $height Height.
     * @return string
     */
    protected function makeTransparentPng(int $width, int $height): string
    {
        $width = max(1, min($width, 8000));
        $height = max(1, min($height, 8000));
        $row = "\x00" . str_repeat("\x00", $width);
        $raw = str_repeat($row, $height);

        return "\x89PNG\r\n\x1a\n"
            . $this->pngChunk('IHDR', pack('NNCCCCC', $width, $height, 8, 3, 0, 0, 0))
            . $this->pngChunk('PLTE', "\x00\x00\x00")
            . $this->pngChunk('tRNS', "\x00")
            . $this->pngChunk('IDAT', gzcompress($raw, 9))
            . $this->pngChunk('IEND', '');
    }

    /**
     * @param string $type Chunk type.
     * @param string $data Chunk data.
     * @return string
     */
    protected function pngChunk(string $type, string $data): string
    {
        return pack('N', strlen($data)) . $type . $data . hash('crc32b', $type . $data, true);
    }
}
