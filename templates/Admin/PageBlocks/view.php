<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $pageBlock
 */
?>
<div class="page-header d-print-none mb-3 pageBlocks">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('{0} megtekintése', __('Page Block')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card mb-3 pageBlocks">
    <div class="card-header pe-3">
        <div class="row w-full align-items-center gy-2 gy-md-0">
            <div class="col">
                <h3 class="card-title mb-0"><?= h($pageBlock->title) ?></h3>
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <div class="btn-list">
                    <?= $this->KvForm->actionEdit(['action' => 'edit', $pageBlock->id]) ?>
                    <?= $this->KvForm->actionDelete(['action' => 'delete', $pageBlock->id], (string)($pageBlock->title ?? '')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-sm table-bordered-vertical">
            <tbody>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlock, 'page', 'title', 'Pages', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Block Type') ?></th>
                    <td><?= h($pageBlock->block_type) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Layout') ?></th>
                    <td><?= h($pageBlock->layout) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Title') ?></th>
                    <td><?= h($pageBlock->title) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Lead') ?></th>
                    <td><?= h($pageBlock->lead) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Button Label') ?></th>
                    <td><?= h($pageBlock->button_label) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Button Url') ?></th>
                    <td><?= h($pageBlock->button_url) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Featured Photo') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlock, 'featured_photo', 'title', 'Photos', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Blocks Title Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlock, 'title_translation', 'locale', 'PageBlocks_title_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Blocks Lead Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlock, 'lead_translation', 'locale', 'PageBlocks_lead_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Blocks Body Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlock, 'body_translation', 'locale', 'PageBlocks_body_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Blocks Quote Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlock, 'quote_translation', 'locale', 'PageBlocks_quote_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Blocks Button Label Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlock, 'button_label_translation', 'locale', 'PageBlocks_button_label_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($pageBlock->id) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Block Items Count') ?></th>
                    <td><?= $this->Number->format($pageBlock->page_block_items_count) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Photos Count') ?></th>
                    <td><?= $this->Number->format($pageBlock->photos_count) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pos') ?></th>
                    <td><?= $this->Number->format($pageBlock->pos) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Created') ?></th>
                    <td><?= h($pageBlock->created) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Modified') ?></th>
                    <td><?= h($pageBlock->modified) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Visible') ?></th>
                    <td><?= $pageBlock->visible ? '<span class=\"badge bg-green-lt\">' . __('Igen') . '</span>' : '<span class=\"badge bg-secondary-lt\">' . __('Nem') . '</span>' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <h4 class="m-0 mb-2"><?= __('Body') ?></h4>
            <div class="text-secondary"><?= $this->Text->autoParagraph(h($pageBlock->body)); ?></div>
        </div>
        <div class="mb-3">
            <h4 class="m-0 mb-2"><?= __('Quote') ?></h4>
            <div class="text-secondary"><?= $this->Text->autoParagraph(h($pageBlock->quote)); ?></div>
        </div>
    </div>
</div>

<?php
$hasRelatedRecords = false;
?>
<?php if (!empty($pageBlock->photos)) { $hasRelatedRecords = true; } ?>
<?php if (!empty($pageBlock->_i18n)) { $hasRelatedRecords = true; } ?>
<?php if (!empty($pageBlock->page_block_items)) { $hasRelatedRecords = true; } ?>
<?php if ($hasRelatedRecords): ?>
<div class="card">
    <div class="card-header">
        <?php $isFirstRelatedTab = true; ?>
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <?php if (!empty($pageBlock->photos)): ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link<?= $isFirstRelatedTab ? ' active' : '' ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#related-photos"
                    type="button"
                    role="tab"
                >
                    <?= __('Photos') ?>
                </button>
            </li>
            <?php $isFirstRelatedTab = false; ?>
            <?php endif; ?>
            <?php if (!empty($pageBlock->_i18n)): ?>
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
            <?php if (!empty($pageBlock->page_block_items)): ?>
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link<?= $isFirstRelatedTab ? ' active' : '' ?>"
                    data-bs-toggle="tab"
                    data-bs-target="#related-page_block_items"
                    type="button"
                    role="tab"
                >
                    <?= __('Page Block Items') ?>
                </button>
            </li>
            <?php $isFirstRelatedTab = false; ?>
            <?php endif; ?>
        </ul>
    </div>
    <div class="card-body tab-content">
        <?php $isFirstRelatedPane = true; ?>
        <?php if (!empty($pageBlock->photos)): ?>
        <div class="tab-pane<?= $isFirstRelatedPane ? ' active show' : '' ?>" id="related-photos" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
                    <thead>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Uuid') ?></th>
                            <th><?= __('Photo Category Id') ?></th>
                            <th><?= __('Slug') ?></th>
                            <th><?= __('Code') ?></th>
                            <th><?= __('Filename') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Location') ?></th>
                            <th><?= __('City') ?></th>
                            <th><?= __('Camera') ?></th>
                            <th><?= __('Lens') ?></th>
                            <th><?= __('Exposure') ?></th>
                            <th><?= __('Aperture') ?></th>
                            <th><?= __('Iso') ?></th>
                            <th><?= __('Focal') ?></th>
                            <th><?= __('Shot Date') ?></th>
                            <th><?= __('Shot Time') ?></th>
                            <th><?= __('Dimensions') ?></th>
                            <th><?= __('In Gallery') ?></th>
                            <th><?= __('Tags Count') ?></th>
                            <th><?= __('Visible') ?></th>
                            <th><?= __('Pos') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions w-1"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pageBlock->photos as $photo): ?>
                        <tr data-edit-url="<?= $this->Url->build(['controller' => 'Photos', 'action' => 'edit', $photo->id]) ?>">
                            <td><?= h($photo->id) ?></td>
                            <td><?= h($photo->uuid) ?></td>
                            <td><?= h($photo->photo_category_id) ?></td>
                            <td><?= h($photo->slug) ?></td>
                            <td><?= h($photo->code) ?></td>
                            <td><?= h($photo->filename) ?></td>
                            <td><?= h($photo->title) ?></td>
                            <td><?= h($photo->description) ?></td>
                            <td><?= h($photo->location) ?></td>
                            <td><?= h($photo->city) ?></td>
                            <td><?= h($photo->camera) ?></td>
                            <td><?= h($photo->lens) ?></td>
                            <td><?= h($photo->exposure) ?></td>
                            <td><?= h($photo->aperture) ?></td>
                            <td><?= h($photo->iso) ?></td>
                            <td><?= h($photo->focal) ?></td>
                            <td><?= h($photo->shot_date) ?></td>
                            <td><?= h($photo->shot_time) ?></td>
                            <td><?= h($photo->dimensions) ?></td>
                            <td><?= h($photo->in_gallery) ?></td>
                            <td><?= h($photo->tags_count) ?></td>
                            <td><?= h($photo->visible) ?></td>
                            <td><?= h($photo->pos) ?></td>
                            <td><?= h($photo->created) ?></td>
                            <td><?= h($photo->modified) ?></td>
                            <td class="actions">
                                <div class="btn-list flex-nowrap align-items-center">
                                    <?= $this->KvForm->actionView(['controller' => 'Photos', 'action' => 'view', $photo->id], ['title' => __('View')]) ?>
                                    <?= $this->KvForm->actionEdit(['controller' => 'Photos', 'action' => 'edit', $photo->id], ['title' => __('Edit')]) ?>
                                    <?= $this->KvForm->actionDelete(['controller' => 'Photos', 'action' => 'delete', $photo->id], (string)($photo->id)) ?>
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
        <?php if (!empty($pageBlock->_i18n)): ?>
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
                        <?php foreach ($pageBlock->_i18n as $i18n): ?>
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
        <?php if (!empty($pageBlock->page_block_items)): ?>
        <div class="tab-pane<?= $isFirstRelatedPane ? ' active show' : '' ?>" id="related-page_block_items" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
                    <thead>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Item Type') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Url') ?></th>
                            <th><?= __('Visible') ?></th>
                            <th><?= __('Pos') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions w-1"><?= __('Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pageBlock->page_block_items as $pageBlockItem): ?>
                        <tr data-edit-url="<?= $this->Url->build(['controller' => 'PageBlockItems', 'action' => 'edit', $pageBlockItem->id]) ?>">
                            <td><?= h($pageBlockItem->id) ?></td>
                            <td><?= h($pageBlockItem->item_type) ?></td>
                            <td><?= h($pageBlockItem->body) ?></td>
                            <td><?= h($pageBlockItem->url) ?></td>
                            <td><?= h($pageBlockItem->visible) ?></td>
                            <td><?= h($pageBlockItem->pos) ?></td>
                            <td><?= h($pageBlockItem->created) ?></td>
                            <td><?= h($pageBlockItem->modified) ?></td>
                            <td class="actions">
                                <div class="btn-list flex-nowrap align-items-center">
                                    <?= $this->KvForm->actionView(['controller' => 'PageBlockItems', 'action' => 'view', $pageBlockItem->id], ['title' => __('View')]) ?>
                                    <?= $this->KvForm->actionEdit(['controller' => 'PageBlockItems', 'action' => 'edit', $pageBlockItem->id], ['title' => __('Edit')]) ?>
                                    <?= $this->KvForm->actionDelete(['controller' => 'PageBlockItems', 'action' => 'delete', $pageBlockItem->id], (string)($pageBlockItem->id)) ?>
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