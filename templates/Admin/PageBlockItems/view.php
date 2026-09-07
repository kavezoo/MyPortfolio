<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlockItem $pageBlockItem
 */
?>
<div class="page-header d-print-none mb-3 pageBlockItems">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('{0} megtekintése', __('Page Block Item')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card mb-3 pageBlockItems">
    <div class="card-header pe-3">
        <div class="row w-full align-items-center gy-2 gy-md-0">
            <div class="col">
                <h3 class="card-title mb-0"><?= h($pageBlockItem->item_type) ?></h3>
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <div class="btn-list">
                    <?= $this->KvForm->actionEdit(['action' => 'edit', $pageBlockItem->id]) ?>
                    <?= $this->KvForm->actionDelete(['action' => 'delete', $pageBlockItem->id], (string)($pageBlockItem->item_type ?? '')) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table table-sm table-bordered-vertical">
            <tbody>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Block') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlockItem, 'page_block', 'title', 'PageBlocks', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Item Type') ?></th>
                    <td><?= h($pageBlockItem->item_type) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Url') ?></th>
                    <td><?= h($pageBlockItem->url) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Page Block Items Body Translation') ?></th>
                    <td><?= $this->KvForm->linkRelatedRecord($pageBlockItem, 'body_translation', 'locale', 'PageBlockItems_body_translation', 'id') ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Id') ?></th>
                    <td><?= $this->Number->format($pageBlockItem->id) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Pos') ?></th>
                    <td><?= $this->Number->format($pageBlockItem->pos) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Created') ?></th>
                    <td><?= h($pageBlockItem->created) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Modified') ?></th>
                    <td><?= h($pageBlockItem->modified) ?></td>
                </tr>
                <tr>
                    <th class="w-1 text-nowrap"><?= __('Visible') ?></th>
                    <td><?= $pageBlockItem->visible ? '<span class=\"badge bg-green-lt\">' . __('Igen') . '</span>' : '<span class=\"badge bg-secondary-lt\">' . __('Nem') . '</span>' ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <h4 class="m-0 mb-2"><?= __('Body') ?></h4>
            <div class="text-secondary"><?= $this->Text->autoParagraph(h($pageBlockItem->body)); ?></div>
        </div>
    </div>
</div>

<?php
$hasRelatedRecords = false;
?>
<?php if (!empty($pageBlockItem->_i18n)) { $hasRelatedRecords = true; } ?>
<?php if ($hasRelatedRecords): ?>
<div class="card">
    <div class="card-header">
        <?php $isFirstRelatedTab = true; ?>
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <?php if (!empty($pageBlockItem->_i18n)): ?>
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
        <?php if (!empty($pageBlockItem->_i18n)): ?>
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
                        <?php foreach ($pageBlockItem->_i18n as $i18n): ?>
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