<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
?>
<div class="page-header d-print-none mb-3 pages">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('{0} megtekintése', __('Page')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card mb-3 pages">
    <div class="card-header pe-3">
        <div class="row w-full align-items-center gy-2 gy-md-0">
            <div class="col">
                <h3 class="card-title mb-0"><?= h($page->title) ?></h3>
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <div class="btn-list">
                    <?= $this->KvForm->actionEdit(['action' => 'edit', $page->id]) ?>
                    <?= $this->KvForm->actionDelete(['action' => 'delete', $page->id], (string)($page->title ?? '')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-sm table-bordered-vertical">
            <tbody>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Slug') ?></th>
                    <td><?= h($page->slug) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Menu Label') ?></th>
                    <td><?= h($page->menu_label) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Url') ?></th>
                    <td><?= h($page->url) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Title') ?></th>
                    <td><?= h($page->title) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Hero Title') ?></th>
                    <td><?= h($page->hero_title) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Hero Lead') ?></th>
                    <td><?= h($page->hero_lead) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Hero Photo') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($page, 'hero_photo', 'title', 'Photos', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Template') ?></th>
                    <td><?= h($page->template) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pages Menu Label Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($page, 'menu_label_translation', 'locale', 'Pages_menu_label_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pages Title Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($page, 'title_translation', 'locale', 'Pages_title_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pages Meta Description Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($page, 'meta_description_translation', 'locale', 'Pages_meta_description_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pages Hero Title Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($page, 'hero_title_translation', 'locale', 'Pages_hero_title_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pages Hero Lead Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($page, 'hero_lead_translation', 'locale', 'Pages_hero_lead_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pages Body Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($page, 'body_translation', 'locale', 'Pages_body_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($page->id) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Blocks Count') ?></th>
                    <td><?= $this->Number->format($page->page_blocks_count) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pos') ?></th>
                    <td><?= $this->Number->format($page->pos) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Created') ?></th>
                    <td><?= h($page->created) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Modified') ?></th>
                    <td><?= h($page->modified) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Visible') ?></th>
                    <td><?= $page->visible ? '<span class=\"badge bg-green-lt\">' . __('Igen') . '</span>' : '<span class=\"badge bg-secondary-lt\">' . __('Nem') . '</span>' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <h4 class="m-0 mb-2"><?= __('Meta Description') ?></h4>
            <div class="text-secondary"><?= $this->Text->autoParagraph(h($page->meta_description)); ?></div>
        </div>
        <div class="mb-3">
            <h4 class="m-0 mb-2"><?= __('Body') ?></h4>
            <div class="text-secondary"><?= $this->Text->autoParagraph(h($page->body)); ?></div>
        </div>
    </div>
</div>

<?php
$hasRelatedRecords = false;
?>
<?php if (!empty($page->_i18n)) { $hasRelatedRecords = true; } ?>
<?php if (!empty($page->page_blocks)) { $hasRelatedRecords = true; } ?>
<?php if ($hasRelatedRecords): ?>
<div class="card">
    <div class="card-header">
        <?php $isFirstRelatedTab = true; ?>
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <?php if (!empty($page->_i18n)): ?>
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
            <?php if (!empty($page->page_blocks)): ?>
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
        </ul>
    </div>
    <div class="card-body tab-content">
        <?php $isFirstRelatedPane = true; ?>
        <?php if (!empty($page->_i18n)): ?>
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
                        <?php foreach ($page->_i18n as $i18n): ?>
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
        <?php if (!empty($page->page_blocks)): ?>
        <div class="tab-pane<?= $isFirstRelatedPane ? ' active show' : '' ?>" id="related-page_blocks" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
                    <thead>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Block Type') ?></th>
                            <th><?= __('Layout') ?></th>
                            <th><?= __('Title') ?></th>
                            <th><?= __('Lead') ?></th>
                            <th><?= __('Body') ?></th>
                            <th><?= __('Quote') ?></th>
                            <th><?= __('Button Label') ?></th>
                            <th><?= __('Button Url') ?></th>
                            <th><?= __('Photo Id') ?></th>
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
                        <?php foreach ($page->page_blocks as $pageBlock): ?>
                        <tr data-edit-url="<?= $this->Url->build(['controller' => 'PageBlocks', 'action' => 'edit', $pageBlock->id]) ?>">
                            <td><?= h($pageBlock->id) ?></td>
                            <td><?= h($pageBlock->block_type) ?></td>
                            <td><?= h($pageBlock->layout) ?></td>
                            <td><?= h($pageBlock->title) ?></td>
                            <td><?= h($pageBlock->lead) ?></td>
                            <td><?= h($pageBlock->body) ?></td>
                            <td><?= h($pageBlock->quote) ?></td>
                            <td><?= h($pageBlock->button_label) ?></td>
                            <td><?= h($pageBlock->button_url) ?></td>
                            <td><?= h($pageBlock->photo_id) ?></td>
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