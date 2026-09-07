<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\Core\Configure;
use Cake\View\Helper;

/**
 * Locale helper for language-prefixed URLs.
 */
class LocaleHelper extends Helper
{
    /**
     * @var array<string>
     */
    protected array $helpers = ['Url', 'Html'];

    /**
     * Current language code.
     *
     * @return string
     */
    public function current(): string
    {
        return (string)($this->getView()->get('currentLang')
            ?: Configure::read('App.language', 'hu'));
    }

    /**
     * Build a path with the active (or given) language prefix.
     *
     * @param string $path Path without language prefix (e.g. /galeria).
     * @param string|null $lang Language code.
     * @return string
     */
    public function path(string $path = '/', ?string $lang = null): string
    {
        $lang = $lang ?: $this->current();
        $path = $this->normalize($path);
        if ($path === '/') {
            return '/' . $lang;
        }

        return '/' . $lang . $path;
    }

    /**
     * Absolute app URL for a localized path.
     *
     * @param string $path Path without language prefix.
     * @param string|null $lang Language code.
     * @return string
     */
    public function url(string $path = '/', ?string $lang = null): string
    {
        return $this->Url->build($this->path($path, $lang));
    }

    /**
     * Same page in another language (keeps path after the language segment).
     *
     * @param string $lang Target language.
     * @return string
     */
    public function switchUrl(string $lang): string
    {
        $path = (string)($this->getView()->get('langPath') ?: '/');
        // Build a plain path URL (avoid Router persist/lang quirks).
        $localized = $this->path($path, $lang);

        return $this->Url->build($localized, ['fullBase' => false]);
    }

    /**
     * @param string $path Path.
     * @return string
     */
    protected function normalize(string $path): string
    {
        if ($path === '' || $path === '/') {
            return '/';
        }

        return '/' . trim($path, '/');
    }
}
