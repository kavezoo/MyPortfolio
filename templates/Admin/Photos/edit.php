<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Photo $photo
 * @var \Cake\Collection\CollectionInterface|string[] $photoCategories
 * @var \Cake\Collection\CollectionInterface|string[] $tags
 * @var array<string, array{code: string, label: string}> $contentLocales
 * @var string $defaultLocale
 */
?>
<div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('Edit photo') ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card">
    <?= $this->Form->create($photo, ['type' => 'file']) ?>
    <div class="card-body">
        <div class="row g-3">
            <?php if (!empty($photo->filename) && $photo->filename !== 'pending'): ?>
                <div class="col-12">
                    <div class="form-label"><?= __('Current image') ?></div>
                    <img src="<?= h($photo->srcUrl()) ?>" alt="<?= h($photo->title) ?>" class="img-thumbnail" style="max-height: 180px;">
                    <div class="form-hint mt-1"><code><?= h($photo->filename) ?></code></div>
                </div>
            <?php endif; ?>

            <div class="col-12">
                <?= $this->Form->control('image_file', [
                    'type' => 'file',
                    'label' => ['text' => __('Replace image'), 'class' => 'form-label'],
                    'class' => 'form-control',
                    'required' => false,
                    'accept' => 'image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif',
                ]) ?>
                <div class="form-hint"><?= __('If you upload a new file, the old one is deleted and EXIF data (camera, lens, shutter, …) is refreshed from the new image.') ?></div>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('photo_category_id', [
                    'options' => $photoCategories,
                    'label' => ['text' => __('Category'), 'class' => 'form-label'],
                    'class' => 'form-select tom-select',
                    'empty' => true,
                    'required' => true,
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('original_name', [
                    'label' => ['text' => __('Original file name'), 'class' => 'form-label'],
                    'class' => 'form-control',
                    'placeholder' => __('Filled automatically from the uploaded file'),
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('slug', [
                    'label' => ['text' => __('Slug'), 'class' => 'form-label'],
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="col-12">
                <?= $this->element('i18n_locale_tabs', [
                    'idPrefix' => 'photo-i18n',
                    'fields' => [
                        ['name' => 'title', 'label' => __('Title'), 'required' => true],
                        ['name' => 'city', 'label' => __('City')],
                        ['name' => 'location', 'label' => __('Location')],
                        ['name' => 'description', 'label' => __('Description'), 'type' => 'textarea', 'rows' => 4],
                    ],
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('tags._ids', [
                    'options' => $tags,
                    'multiple' => true,
                    'label' => ['text' => __('Tags'), 'class' => 'form-label'],
                    'class' => 'form-select tom-select multi-select',
                    'empty' => false,
                ]) ?>
            </div>
            <div class="col-md-3">
                <div class="form-label"><?= __('In gallery') ?></div>
                <?= $this->KvForm->switch('in_gallery', ['label' => __('In gallery')]) ?>
            </div>
            <div class="col-md-3">
                <div class="form-label"><?= __('Visible') ?></div>
                <?= $this->KvForm->switch('visible', ['label' => __('Visible')]) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('pos', [
                    'type' => 'number',
                    'label' => ['text' => __('Position'), 'class' => 'form-label'],
                    'class' => 'form-control',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <?= $this->Form->button(__('Save'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<?= $this->element('photo_form_assets') ?>
