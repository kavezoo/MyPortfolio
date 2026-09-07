<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Table;

/**
 * Helpers for loading/saving Translate (i18n) fields on admin forms.
 */
trait TranslatableFormTrait
{
    /**
     * Content locales available in admin translation tabs (HU / EN / DE).
     *
     * @return array<string, array{code: string, label: string}>
     */
    protected function contentLocales(): array
    {
        $languages = Configure::read('App.languages') ?: [];
        $locales = [];
        foreach ($languages as $code => $meta) {
            $locale = (string)($meta['locale'] ?? '');
            if ($locale === '') {
                continue;
            }
            $locales[$locale] = [
                'code' => (string)$code,
                'label' => (string)($meta['label'] ?? $code),
            ];
        }

        return $locales;
    }

    /**
     * Load an entity with all content-locale translations.
     *
     * @param \Cake\ORM\Table $table Table.
     * @param mixed $id Primary key.
     * @param array<string, mixed> $contain Contain.
     * @return \Cake\Datasource\EntityInterface
     */
    protected function getWithTranslations(Table $table, mixed $id, array $contain = []): EntityInterface
    {
        $alias = $table->getAlias();
        $locales = array_keys($this->contentLocales());

        $query = $table->find('translations', locales: $locales)
            ->where([$alias . '.id' => $id]);
        if ($contain !== []) {
            $query->contain($contain);
        }

        return $query->firstOrFail();
    }

    /**
     * Patch entity including `_translations` payload from the form.
     *
     * @param \Cake\ORM\Table $table Table.
     * @param \Cake\Datasource\EntityInterface $entity Entity.
     * @param array<string, mixed> $data Request data.
     * @param array<string, mixed> $options Patch options.
     * @return \Cake\Datasource\EntityInterface
     */
    protected function patchWithTranslations(
        Table $table,
        EntityInterface $entity,
        array $data,
        array $options = []
    ): EntityInterface {
        $options += [
            'translations' => true,
            'associated' => [],
        ];

        return $table->patchEntity($entity, $data, $options);
    }
}
