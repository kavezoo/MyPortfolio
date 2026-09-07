<?php
/**
 * Language tabs for Translate fields (HU / EN / DE).
 *
 * @var \App\View\AppView $this
 * @var array<int, array<string, mixed>> $fields Field definitions.
 * @var string $idPrefix Unique HTML id prefix for this tab group.
 * @var array<string, array{code: string, label: string}>|null $contentLocales
 * @var string|null $defaultLocale
 */

use Cake\Core\Configure;

$fields = $fields ?? [];
$idPrefix = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($idPrefix ?? 'i18n')) ?: 'i18n';
$defaultLocale = (string)($defaultLocale ?? Configure::read('App.defaultLocale', 'hu_HU'));
$contentLocales = $contentLocales ?? [];
if ($contentLocales === []) {
    foreach (Configure::read('App.languages') ?: [] as $code => $meta) {
        $locale = (string)($meta['locale'] ?? '');
        if ($locale === '') {
            continue;
        }
        $contentLocales[$locale] = [
            'code' => (string)$code,
            'label' => (string)($meta['label'] ?? $code),
        ];
    }
}

if ($fields === [] || $contentLocales === []) {
    return;
}
?>
<div class="i18n-locale-tabs mb-3">
    <ul class="nav nav-tabs" data-bs-toggle="tabs" role="tablist">
        <?php $i = 0; foreach ($contentLocales as $locale => $meta): ?>
            <li class="nav-item" role="presentation">
                <a
                    href="#<?= h($idPrefix) ?>-<?= h($locale) ?>"
                    class="nav-link<?= $i === 0 ? ' active' : '' ?>"
                    data-bs-toggle="tab"
                    role="tab"
                    aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                >
                    <?= h($meta['label']) ?>
                    <span class="text-secondary small ms-1"><?= h(strtoupper($meta['code'])) ?></span>
                </a>
            </li>
        <?php $i++; endforeach; ?>
    </ul>

    <div class="tab-content border border-top-0 p-3">
        <?php $i = 0; foreach ($contentLocales as $locale => $meta): ?>
            <?php
            $isDefault = $locale === $defaultLocale;
            $paneId = $idPrefix . '-' . $locale;
            ?>
            <div class="tab-pane fade<?= $i === 0 ? ' show active' : '' ?>" id="<?= h($paneId) ?>" role="tabpanel">
                <div class="row g-3">
                    <?php foreach ($fields as $field): ?>
                        <?php
                        $name = (string)($field['name'] ?? '');
                        if ($name === '') {
                            continue;
                        }
                        $inputName = $isDefault ? $name : '_translations.' . $locale . '.' . $name;
                        $type = (string)($field['type'] ?? 'text');
                        $col = (string)($field['col'] ?? ($type === 'textarea' ? 'col-12' : 'col-md-6'));
                        $control = [
                            'label' => ['text' => $field['label'] ?? $name, 'class' => 'form-label'],
                            'class' => $field['class'] ?? ($type === 'textarea' ? 'form-control' : 'form-control'),
                        ];
                        if ($type === 'textarea') {
                            $control['type'] = 'textarea';
                            $control['rows'] = (int)($field['rows'] ?? 4);
                        }
                        if (!empty($field['required']) && $isDefault) {
                            $control['required'] = true;
                        }
                        if (!empty($field['placeholder'])) {
                            $control['placeholder'] = $field['placeholder'];
                        }
                        if (!empty($field['id'])) {
                            $control['id'] = $field['id'] . '-' . $meta['code'];
                        }
                        if (!empty($field['editor'])) {
                            $control['class'] = trim(($control['class'] ?? '') . ' hugerte-editor');
                            $control['id'] = ($field['id'] ?? 'hugerte-' . $name) . '-' . $meta['code'];
                        }
                        ?>
                        <div class="<?= h($col) ?>">
                            <?= $this->Form->control($inputName, $control) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php $i++; endforeach; ?>
    </div>
</div>
