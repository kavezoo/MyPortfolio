<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Photo $photo
 * @var \Cake\Collection\CollectionInterface|string[] $photoCategories
 * @var \Cake\Collection\CollectionInterface|string[] $tags
 */
?>
<div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('Add photo') ?></h2>
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
            <div class="col-12">
                <?= $this->Form->control('image_file', [
                    'type' => 'file',
                    'label' => ['text' => __('Image file'), 'class' => 'form-label'],
                    'class' => 'form-control',
                    'required' => true,
                    'accept' => 'image/jpeg,image/png,image/webp,image/gif,.jpg,.jpeg,.png,.webp,.gif',
                ]) ?>
                <div class="form-hint"><?= __('EXIF data (camera, lens, exposure, date, …) is filled in automatically from the file.') ?></div>
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
                <?= $this->Form->control('title', [
                    'label' => ['text' => __('Title'), 'class' => 'form-label'],
                    'class' => 'form-control',
                    'required' => true,
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('slug', [
                    'label' => ['text' => __('Slug'), 'class' => 'form-label'],
                    'class' => 'form-control',
                    'placeholder' => __('Optional – generated from title'),
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('city', [
                    'label' => ['text' => __('City'), 'class' => 'form-label'],
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('location', [
                    'label' => ['text' => __('Location'), 'class' => 'form-label'],
                    'class' => 'form-control',
                ]) ?>
            </div>
            <div class="col-12">
                <?= $this->Form->control('description', [
                    'type' => 'textarea',
                    'rows' => 4,
                    'label' => ['text' => __('Description'), 'class' => 'form-label'],
                    'class' => 'form-control',
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
                <?= $this->KvForm->switch('in_gallery', ['label' => __('In gallery'), 'value' => '1', 'checked' => true]) ?>
            </div>
            <div class="col-md-3">
                <div class="form-label"><?= __('Visible') ?></div>
                <?= $this->KvForm->switch('visible', ['label' => __('Visible'), 'value' => '1', 'checked' => true]) ?>
            </div>

            <div class="col-12"><hr class="my-2"><h3 class="mb-0"><?= __('EXIF (auto-filled)') ?></h3></div>
            <div class="col-md-6">
                <?= $this->Form->control('camera', ['label' => ['text' => __('Camera'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-6">
                <?= $this->Form->control('lens', ['label' => ['text' => __('Lens'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('exposure', ['label' => ['text' => __('Shutter'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('aperture', ['label' => ['text' => __('Aperture'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('iso', ['label' => ['text' => __('ISO'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('focal', ['label' => ['text' => __('Focal length'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('shot_date', ['type' => 'date', 'label' => ['text' => __('Date'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('shot_time', ['type' => 'time', 'label' => ['text' => __('Time'), 'class' => 'form-label'], 'class' => 'form-control', 'step' => 1]) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('dimensions', ['label' => ['text' => __('Resolution'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
            </div>
            <div class="col-md-4">
                <?= $this->Form->control('pos', [
                    'type' => 'number',
                    'label' => ['text' => __('Position'), 'class' => 'form-label'],
                    'class' => 'form-control',
                    'value' => $photo->pos ?: 1000,
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
