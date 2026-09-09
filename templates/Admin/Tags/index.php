<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\> $tags
 * @var int|null $lastViewedId
 * @var int|null $scrollToId
 * @var string|null $search
 * @var array<string, mixed>|null $parentContext
 */
use Cake\I18n\I18n;

$showId            = false;
$showCounterFields = false;
$showVisible       = true;
$showCreated       = false;
$showModified      = false;
$showPos           = false;

$this->assign('title', __('Tags'));
$this->element('KvAdmin.pagination_templates');

$lastViewedId = $lastViewedId ?? $session->read('LastViewed.' . $prefix . '.tag_id');
$scrollToId = $scrollToId ?? $session->read('ScrollTo.' . $prefix . '.tag_id') ?? $lastViewedId;

// 🔍 Keresési kiemelő segédfüggvény
$highlight = function (?string $text) use ($search): string {
    if ($text === null || $text === '') {
        return '';
    }

    $escapedText = h($text);
    if (!empty($search)) {
        $cleanSearch = preg_quote(trim($search), '/');
        return preg_replace('/(' . $cleanSearch . ')/iu', '<mark class="search-highlight">$1</mark>', $escapedText);
    }
    return $escapedText;
};
?>

<div class="card">
    <?php if (!empty($parentContext)): ?>
    <div class="card-status-top bg-azure"></div>
    <div class="card-header border-bottom-0 pb-0">
        <div class="d-flex flex-wrap align-items-center gap-2">
            <span class="badge bg-azure-lt"><?= __('Szűrt lista') ?></span>
            <span class="text-secondary">
                <?= __('{0} rekordhoz tartozó {1}', h($parentContext['label']), __('Tags')) ?>
            </span>
            <?= $this->KvForm->linkParentView($parentContext) ?>
            <?= $this->KvForm->linkFullList() ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="card-header pe-3">
        <div class="row w-full align-items-center gy-2 gy-md-0">

            <!-- Új tag gomb -->
            <div class="col-auto">
                <?= $this->KvForm->linkAddNew(['controller' => 'Tags', 'action' => 'add'], __('tag')) ?>
            </div>

            <!-- Cím és infó blokk -->
            <div class="col">
                <h3 class="card-title mb-0"><?= __('Tags') ?></h3>
                <p class="text-secondary m-0"><?= __('Edit line double click on the line') ?></p>
            </div>

            <!-- Kereső űrlap -->
            <div class="col-12 col-md-auto ms-md-auto">
                <?php
                $searchClearUrl = [
                    'controller' => 'Tags',
                    'action' => 'index',
                    '?' => ['clear' => 'search'],
                ];
                if (!empty($parentContext)) {
                    $searchClearUrl['?'][$parentContext['foreignKey']] = $parentContext['foreignKeyValue'];
                    $searchClearUrl['?']['parent_filter'] = $parentContext['parentFilter'];
                }
                ?>
                <?= $this->KvForm->search('search', $searchClearUrl) ?>
            </div>

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter card-table table-hover table-sm table-bordered-vertical">
            <thead>
                <tr>
<?php if (isset($showId) && $showId): ?>
                    <th class="integer id"><?= $this->Paginator->sort('id', '#') ?></th>
<?php endif; ?>
                    <th class="string name"><?= $this->Paginator->sort('name') ?></th>
                    <th class="string slug"><?= $this->Paginator->sort('slug') ?></th>
<?php if (isset($showCounterFields) && $showCounterFields): ?>
                    <th class="integer photos_count"><?= $this->Paginator->sort('photos_count') ?></th>
<?php endif; ?>
<?php if (isset($showVisible) && $showVisible): ?>
                    <th class="boolean visible"><?= $this->Paginator->sort('visible', __('Visible')) ?></th>
<?php endif; ?>
<?php if (isset($showPos) && $showPos): ?>
                    <th class="integer pos"><?= $this->Paginator->sort('pos', __('Pos')) ?></th>
<?php endif; ?>
<?php if ((isset($showCreated) && $showCreated) || (isset($showModified) && $showModified)): ?>
                    <th class="datetime">
<?php if (isset($showCreated) && $showCreated): ?>
                        <?= $this->Paginator->sort('created', __('Létrehozva')) ?>
<?php endif; ?>
<?php if ((isset($showCreated) && $showCreated) && (isset($showModified) && $showModified)): ?>
                        <br>
<?php endif; ?>
<?php if (isset($showModified) && $showModified): ?>
                        <?= $this->Paginator->sort('modified', __('Módosítva')) ?>
<?php endif; ?>
                    </th>
<?php endif; ?>
                    <th class="child-actions w-1"><?= __('Related') ?></th>
                    <th class="actions w-1"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tags) && count($tags) > 0): ?>
                <?php foreach ($tags as $tag): ?>
                <?php $isLastViewed = (!empty($lastViewedId) && $lastViewedId == $tag->id); ?>
                <tr
                    id="row-<?= (int)$tag->id ?>"
                    class="<?= $isLastViewed ? 'last-viewed' : '' ?>"
                    data-edit-url="<?= $this->Url->build(['action' => 'edit', $tag->id]) ?>"
                >
<?php if (isset($showId) && $showId): ?>
                    <td class="integer id"><?= h($tag->id) ?></td>
<?php endif; ?>
                    <td class="string name"><?= $highlight($tag->name) ?></td>
                    <td class="string slug"><?= $highlight($tag->slug) ?></td>
<?php if (isset($showCounterFields) && $showCounterFields): ?>
                    <td class="integer photos_count text-end"><?= $this->Number->format($tag->photos_count) ?></td>
<?php endif; ?>
<?php if (isset($showVisible) && $showVisible): ?>
                    <td class="boolean visible"><?= $tag->visible ? '<span class="badge bg-green-lt">' . __('Igen') . '</span>' : '<span class="badge bg-secondary-lt">' . __('Nem') . '</span>' ?></td>
<?php endif; ?>
<?php if (isset($showPos) && $showPos): ?>
                    <td class="integer pos"><?= h($tag->pos) ?></td>
<?php endif; ?>
<?php if ((isset($showCreated) && $showCreated) || (isset($showModified) && $showModified)): ?>
                    <td class="datetime text-nowrap">
<?php if (isset($showCreated) && $showCreated): ?>
                        <small class="d-block text-muted"><?= h($tag->created?->format('Y-m-d H:i')) ?></small>
<?php endif; ?>
<?php if (isset($showModified) && $showModified): ?>
                        <span><?= h($tag->modified?->format('Y-m-d H:i')) ?></span>
<?php endif; ?>
                    </td>
<?php endif; ?>
                    <td class="child-actions">
                        <div class="btn-list flex-nowrap align-items-center">
                            <?= $this->KvForm->linkChildList('I18n', 'foreign_key', $tag->id, 'Tags', __('I18n')) ?>
                        </div>
                    </td>
                    <td class="actions">
                        <div class="btn-list flex-nowrap align-items-center">
                            <?= $this->KvForm->actionView(['action' => 'view', $tag->id]) ?>
                            <?= $this->KvForm->actionEdit(['action' => 'edit', $tag->id]) ?>
                            <?= $this->KvForm->actionDelete(['action' => 'delete', $tag->id], (string)($tag->name ?? '')) ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="100" class="text-center py-4 text-muted">
                        <?= __('Nincs megjeleníthető adat.') ?>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?= $this->element('KvAdmin.pagination') ?>

</div>

<?= $this->element('KvAdmin.modal-delete') ?>

<?php
// --- 1. Kereső gyorsgombok (Ctrl + K) ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('advanced-table-search');
    const shortcutHint = document.getElementById('search-shortcut-hint');

    if (searchInput) {
        document.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && (e.key === 'k' || e.key === 'K')) {
                e.preventDefault();
                searchInput.focus();
                searchInput.select();
            }
        });

        if (shortcutHint) {
            searchInput.addEventListener('focus', function () {
                shortcutHint.innerHTML = 'Enter &crarr;';
            });

            searchInput.addEventListener('blur', function () {
                shortcutHint.textContent = 'ctrl + K';
            });
        }

        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                this.blur();
            }
        });
    }

    const clearBtn = document.getElementById('btn-clear-search');
    if (clearBtn) {
        clearBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            window.location.href = this.getAttribute('href');
        });
    }
});
JS, ['block' => 'script']);

// --- 2. Dupla kattintás soron: ugrás szerkesztésre ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.table tbody tr[data-edit-url]').forEach(function (row) {
        row.addEventListener('dblclick', function (e) {
            if (e.target.closest('a, button, input, select, textarea, label, .actions, .child-actions')) {
                return;
            }

            const editUrl = row.getAttribute('data-edit-url');
            if (editUrl) {
                window.location.href = editUrl;
            }
        });
    });
});
JS, ['block' => 'script']);

if (!empty($scrollToId)) {
    // --- 3. Automatikus görgetés az utolsó megtekintett rekordhoz ---
    $this->Html->scriptBlock(<<<JS
document.addEventListener('DOMContentLoaded', function () {
    let targetRow = document.getElementById('row-{$scrollToId}');
    if (!targetRow) {
        targetRow = document.querySelector('.table tr.last-viewed') || document.querySelector('.table tbody tr');
    }

    if (targetRow) {
        const headerOffset = 120;
        const rowPosition = targetRow.getBoundingClientRect().top;
        const offsetPosition = rowPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
        });
    }
});
JS, ['block' => 'script']);
}
?>

<?php /*
// --- 3. Automatikus görgetés – mindig aktív változat: vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    let targetRow = document.querySelector('.table tr.last-viewed') || document.querySelector('.table tbody tr');
    if (targetRow) {
        const headerOffset = 120;
        const rowPosition = targetRow.getBoundingClientRect().top;
        window.scrollTo({ top: rowPosition + window.pageYOffset - headerOffset, behavior: 'smooth' });
    }
});
JS, ['block' => 'script']);
*/ ?>