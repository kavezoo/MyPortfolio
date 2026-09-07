<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Photo $photo
 */
?>
<div class="page-header d-print-none mb-3 photos">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('{0} megtekintése', __('Photo')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card mb-3 photos">
    <div class="card-header pe-3">
        <div class="row w-full align-items-center gy-2 gy-md-0">
            <div class="col">
                <h3 class="card-title mb-0"><?= h($photo->title) ?></h3>
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <div class="btn-list">
                    <?= $this->KvForm->actionEdit(['action' => 'edit', $photo->id]) ?>
                    <?= $this->KvForm->actionDelete(['action' => 'delete', $photo->id], (string)($photo->title ?? '')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-sm table-bordered-vertical">
            <tbody>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Uuid') ?></th>
                    <td><?= h($photo->uuid) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Photo Category') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($photo, 'photo_category', 'name', 'PhotoCategories', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Slug') ?></th>
                    <td><?= h($photo->slug) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Code') ?></th>
                    <td><?= h($photo->code) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Filename') ?></th>
                    <td><?= h($photo->filename) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Title') ?></th>
                    <td><?= h($photo->title) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Location') ?></th>
                    <td><?= h($photo->location) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('City') ?></th>
                    <td><?= h($photo->city) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Camera') ?></th>
                    <td><?= h($photo->camera) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Lens') ?></th>
                    <td><?= h($photo->lens) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Exposure') ?></th>
                    <td><?= h($photo->exposure) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Aperture') ?></th>
                    <td><?= h($photo->aperture) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Iso') ?></th>
                    <td><?= h($photo->iso) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Focal') ?></th>
                    <td><?= h($photo->focal) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Dimensions') ?></th>
                    <td><?= h($photo->dimensions) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Photos Title Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($photo, 'title_translation', 'locale', 'Photos_title_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Photos Description Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($photo, 'description_translation', 'locale', 'Photos_description_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Photos Location Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($photo, 'location_translation', 'locale', 'Photos_location_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Photos City Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($photo, 'city_translation', 'locale', 'Photos_city_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($photo->id) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Tags Count') ?></th>
                    <td><?= $this->Number->format($photo->tags_count) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pos') ?></th>
                    <td><?= $this->Number->format($photo->pos) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Shot Date') ?></th>
                    <td><?= h($photo->shot_date) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Shot Time') ?></th>
                    <td><?= h($photo->shot_time) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Created') ?></th>
                    <td><?= h($photo->created) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Modified') ?></th>
                    <td><?= h($photo->modified) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('In Gallery') ?></th>
                    <td><?= $photo->in_gallery ? '<span class=\"badge bg-green-lt\">' . __('Igen') . '</span>' : '<span class=\"badge bg-secondary-lt\">' . __('Nem') . '</span>' ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Visible') ?></th>
                    <td><?= $photo->visible ? '<span class=\"badge bg-green-lt\">' . __('Igen') . '</span>' : '<span class=\"badge bg-secondary-lt\">' . __('Nem') . '</span>' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <h4 class="m-0 mb-2"><?= __('Description') ?></h4>
            <div class="text-secondary"><?= $this->Text->autoParagraph(h($photo->description)); ?></div>
        </div>
    </div>
</div>

<?php
$hasRelatedRecords = false;
?>
<?php if (!empty($photo->tags)) { $hasRelatedRecords = true; } ?>
<?php if (!empty($photo->blog_posts)) { $hasRelatedRecords = true; } ?>
<?php if (!empty($photo->page_blocks)) { $hasRelatedRecords = true; } ?>
<?php if (!empty($photo->_i18n)) { $hasRelatedRecords = true; } ?>
<?php if ($hasRelatedRecords): ?>
<div class="card">
    <div class="card-header">
        <?php $isFirstRelatedTab = true; ?>
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <?php if (!empty($photo->tags)): ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link<?= $isFirstRelatedTab ? ' active' : '' ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#related-tags"
                    type="button"
                    role="tab"
                >
                    <?= __('Tags') ?>
                </button>
            </li>
            <?php $isFirstRelatedTab = false; ?>
            <?php endif; ?>
            <?php if (!empty($photo->blog_posts)): ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link<?= $isFirstRelatedTab ? ' active' : '' ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#related-blog_posts"
                    type="button"
                    role="tab"
                >
                    <?= __('Blog Posts') ?>
                </button>
            </li>
            <?php $isFirstRelatedTab = false; ?>
            <?php endif; ?>
            <?php if (!empty($photo->page_blocks)): ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link<?= $isFirstRelatedTab ? ' active' : '' ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#related-page_blocks"
                    type="button"
                    role="tab"
                >
                    <?= __('Page Blocks') ?>
                </button>
            </li>
            <?php $isFirstRelatedTab = false; ?>
            <?php endif; ?>
            <?php if (!empty($photo->_i18n)): ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link<?= $isFirstRelatedTab ? ' active' : '' ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#related-_i18n"
                    type="button"
                    role="tab"
                >
                    <?= __('I18n') ?>
                </button>
            </li>
            <?php $isFirstRelatedTab = false; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div class="card-body tab-content">
        <?php $isFirstRelatedPane = true; ?>
        <?php if (!empty($photo->tags)): ?>
        <div class="tab-pane<?= $isFirstRelatedPane ? ' active show' : '' ?>" id="related-tags" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
                    <thead>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Slug') ?></th>
                            <th><?= __('Photos Count') ?></th>
                            <th><?= __('Visible') ?></th>
                            <th><?= __('Pos') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions w-1"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photo->tags as $tag): ?>
                        <tr data-edit-url="<?= $this->Url->build(['controller' => 'Tags', 'action' => 'edit', $tag->id]) ?>">
                            <td><?= h($tag->id) ?></td>
                            <td><?= h($tag->name) ?></td>
                            <td><?= h($tag->slug) ?></td>
                            <td><?= h($tag->photos_count) ?></td>
                            <td><?= h($tag->visible) ?></td>
                            <td><?= h($tag->pos) ?></td>
                            <td><?= h($tag->created) ?></td>
                            <td><?= h($tag->modified) ?></td>
                            <td class="actions">
                                <div class="btn-list flex-nowrap align-items-center">
                                    <?= $this->KvForm->actionView(['controller' => 'Tags', 'action' => 'view', $tag->id], ['title' => __('View')]) ?>
                                    <?= $this->KvForm->actionEdit(['controller' => 'Tags', 'action' => 'edit', $tag->id], ['title' => __('Edit')]) ?>
                                    <?= $this->KvForm->actionDelete(['controller' => 'Tags', 'action' => 'delete', $tag->id], (string)($tag->id)) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $isFirstRelatedPane = false; ?>
        <?php endif; ?>
        <?php if (!empty($photo->blog_posts)): ?>
        <div class="tab-pane<?= $isFirstRelatedPane ? ' active show' : '' ?>" id="related-blog_posts" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
                    <thead>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Slug') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Published') ?></th>
                            <th><?= __('Photos Count') ?></th>
                            <th><?= __('Visible') ?></th>
                            <th><?= __('Pos') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions w-1"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photo->blog_posts as $blogPost): ?>
                        <tr data-edit-url="<?= $this->Url->build(['controller' => 'BlogPosts', 'action' => 'edit', $blogPost->id]) ?>">
                            <td><?= h($blogPost->id) ?></td>
                            <td><?= h($blogPost->slug) ?></td>
                            <td><?= h($blogPost->title) ?></td>
                            <td><?= h($blogPost->body) ?></td>
                            <td><?= h($blogPost->published) ?></td>
                            <td><?= h($blogPost->photos_count) ?></td>
                            <td><?= h($blogPost->visible) ?></td>
                            <td><?= h($blogPost->pos) ?></td>
                            <td><?= h($blogPost->created) ?></td>
                            <td><?= h($blogPost->modified) ?></td>
                            <td class="actions">
                                <div class="btn-list flex-nowrap align-items-center">
                                    <?= $this->KvForm->actionView(['controller' => 'BlogPosts', 'action' => 'view', $blogPost->id], ['title' => __('View')]) ?>
                                    <?= $this->KvForm->actionEdit(['controller' => 'BlogPosts', 'action' => 'edit', $blogPost->id], ['title' => __('Edit')]) ?>
                                    <?= $this->KvForm->actionDelete(['controller' => 'BlogPosts', 'action' => 'delete', $blogPost->id], (string)($blogPost->id)) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $isFirstRelatedPane = false; ?>
        <?php endif; ?>
        <?php if (!empty($photo->page_blocks)): ?>
        <div class="tab-pane<?= $isFirstRelatedPane ? ' active show' : '' ?>" id="related-page_blocks" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
                    <thead>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Page Id') ?></th>
                            <th><?= __('Block Type') ?></th>
                            <th><?= __('Layout') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Lead') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Quote') ?></th>
                            <th><?= __('Button Label') ?></th>
                            <th><?= __('Button Url') ?></th>
                            <th><?= __('Page Block Items Count') ?></th>
                            <th><?= __('Photos Count') ?></th>
                            <th><?= __('Visible') ?></th>
                            <th><?= __('Pos') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions w-1"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photo->page_blocks as $pageBlock): ?>
                        <tr data-edit-url="<?= $this->Url->build(['controller' => 'PageBlocks', 'action' => 'edit', $pageBlock->id]) ?>">
                            <td><?= h($pageBlock->id) ?></td>
                            <td><?= h($pageBlock->page_id) ?></td>
                            <td><?= h($pageBlock->block_type) ?></td>
                            <td><?= h($pageBlock->layout) ?></td>
                            <td><?= h($pageBlock->title) ?></td>
                            <td><?= h($pageBlock->lead) ?></td>
                            <td><?= h($pageBlock->body) ?></td>
                            <td><?= h($pageBlock->quote) ?></td>
                            <td><?= h($pageBlock->button_label) ?></td>
                            <td><?= h($pageBlock->button_url) ?></td>
                            <td><?= h($pageBlock->page_block_items_count) ?></td>
                            <td><?= h($pageBlock->photos_count) ?></td>
                            <td><?= h($pageBlock->visible) ?></td>
                            <td><?= h($pageBlock->pos) ?></td>
                            <td><?= h($pageBlock->created) ?></td>
                            <td><?= h($pageBlock->modified) ?></td>
                            <td class="actions">
                                <div class="btn-list flex-nowrap align-items-center">
                                    <?= $this->KvForm->actionView(['controller' => 'PageBlocks', 'action' => 'view', $pageBlock->id], ['title' => __('View')]) ?>
                                    <?= $this->KvForm->actionEdit(['controller' => 'PageBlocks', 'action' => 'edit', $pageBlock->id], ['title' => __('Edit')]) ?>
                                    <?= $this->KvForm->actionDelete(['controller' => 'PageBlocks', 'action' => 'delete', $pageBlock->id], (string)($pageBlock->id)) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $isFirstRelatedPane = false; ?>
        <?php endif; ?>
        <?php if (!empty($photo->_i18n)): ?>
        <div class="tab-pane<?= $isFirstRelatedPane ? ' active show' : '' ?>" id="related-_i18n" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
                    <thead>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Locale') ?></th>
                            <th><?= __('Model') ?></th>
                            <th><?= __('Field') ?></th>
                            <th><?= __('Content') ?></th>
                            <th class="actions w-1"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($photo->_i18n as $i18n): ?>
                        <tr data-edit-url="<?= $this->Url->build(['controller' => 'I18n', 'action' => 'edit', $i18n->id]) ?>">
                            <td><?= h($i18n->id) ?></td>
                            <td><?= h($i18n->locale) ?></td>
                            <td><?= h($i18n->model) ?></td>
                            <td><?= h($i18n->field) ?></td>
                            <td><?= h($i18n->content) ?></td>
                            <td class="actions">
                                <div class="btn-list flex-nowrap align-items-center">
                                    <?= $this->KvForm->actionView(['controller' => 'I18n', 'action' => 'view', $i18n->id], ['title' => __('View')]) ?>
                                    <?= $this->KvForm->actionEdit(['controller' => 'I18n', 'action' => 'edit', $i18n->id], ['title' => __('Edit')]) ?>
                                    <?= $this->KvForm->actionDelete(['controller' => 'I18n', 'action' => 'delete', $i18n->id], (string)($i18n->id)) ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php $isFirstRelatedPane = false; ?>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->element('KvAdmin.modal-delete') ?>

<?php
// --- Dupla kattintás a kapcsolt rekordok során: ugrás szerkesztésre ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.table tbody tr[data-edit-url]').forEach(function (row) {
        row.addEventListener('dblclick', function (e) {
            if (e.target.closest('a, button, input, select, textarea, label, .actions')) {
                return;
            }

            const editUrl = row.getAttribute('data-edit-url');
            if (editUrl) {
                window.location.href = editUrl;
            }
        });
    });
});
JS, ['block' => 'footer']);
?>