<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\> $photos
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

$this->assign('title', __('Photos'));
$this->element('KvAdmin.pagination_templates');

$lastViewedId = $lastViewedId ?? $session->read('LastViewed.' . $prefix . '.photo_id');
$scrollToId = $scrollToId ?? $session->read('ScrollTo.' . $prefix . '.photo_id') ?? $lastViewedId;

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
                <?= __('{0} rekordhoz tartozó {1}', h($parentContext['label']), __('Photos')) ?>
            </span>
            <?= $this->KvForm->linkParentView($parentContext) ?>
            <?= $this->KvForm->linkFullList() ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="card-header pe-3">
        <div class="row w-full align-items-center gy-2 gy-md-0">

            <!-- Új photo gomb -->
            <div class="col-auto">
                <?= $this->KvForm->linkAddNew(['controller' => 'Photos', 'action' => 'add'], __('photo')) ?>
            </div>

            <!-- Cím és infó blokk -->
            <div class="col">
                <h3 class="card-title mb-0"><?= __('Photos') ?></h3>
                <p class="text-secondary m-0"><?= __('Edit line double click on the line') ?></p>
            </div>

            <!-- Kereső űrlap -->
            <div class="col-12 col-md-auto ms-md-auto">
                <?php
                $searchClearUrl = [
                    'controller' => 'Photos',
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
                    <th class="string image"><?= __('Image') ?></th>
                    <th class="string photo_category_id"><?= $this->Paginator->sort('photo_category_id') ?></th>
                    <th class="string slug"><?= $this->Paginator->sort('slug') ?></th>
                    <th class="string title">
                        <?= $this->Paginator->sort('title', __('Title')) ?>
                        <br><small class="text-secondary fw-normal"><?= __('City') ?> · <?= __('Location') ?></small>
                    </th>
                    <th class="string camera">
                        <?= $this->Paginator->sort('camera', __('Camera')) ?>
                        <br><small class="text-secondary fw-normal"><?= __('Lens') ?></small>
                    </th>
                    <th class="string exposure">
                        <?= __('Exposure') ?>
                        <br><small class="text-secondary fw-normal"><?= __('Aperture') ?></small>
                        <br><small class="text-secondary fw-normal">ISO</small>
                        <br><small class="text-secondary fw-normal"><?= __('Focal length') ?></small>
                    </th>
                    <th class="date shot_date">
                        <?= $this->Paginator->sort('shot_date', __('Date')) ?>
                        <br><small class="text-secondary fw-normal"><?= __('Time') ?></small>
                    </th>
                    <th class="string dimensions"><?= $this->Paginator->sort('dimensions') ?></th>
                    <th class="boolean in_gallery"><?= $this->Paginator->sort('in_gallery') ?></th>
<?php if (isset($showCounterFields) && $showCounterFields): ?>
                    <th class="integer tags_count"><?= $this->Paginator->sort('tags_count') ?></th>
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
                <?php if (!empty($photos) && count($photos) > 0): ?>
                <?php foreach ($photos as $photo): ?>
                <?php $isLastViewed = (!empty($lastViewedId) && $lastViewedId == $photo->id); ?>
                <tr
                    id="row-<?= (int)$photo->id ?>"
                    class="<?= $isLastViewed ? 'last-viewed' : '' ?>"
                    data-edit-url="<?= $this->Url->build(['action' => 'edit', $photo->id]) ?>"
                >
<?php if (isset($showId) && $showId): ?>
                    <td class="integer id"><?= h($photo->id) ?></td>
<?php endif; ?>
                    <td class="string image py-2">
                        <?php if (!empty($photo->filename) && $photo->filename !== 'pending'): ?>
                            <a href="<?= h($photo->srcUrl()) ?>" target="_blank" rel="noopener" title="<?= h($photo->filename) ?>">
                                <img
                                    src="<?= h($photo->srcUrl()) ?>"
                                    alt="<?= h($photo->title) ?>"
                                    class="rounded border"
                                    style="max-height: 160px; max-width: 200px; width: auto; height: auto; display: block; object-fit: contain;"
                                    loading="lazy"
                                >
                            </a>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="string photo_category_id">
                        <?= $this->KvForm->linkBelongsToCell($photo, 'photo_category', 'name', 'PhotoCategories', 'id') ?>
                    </td>
                    <td class="string slug"><?= $highlight($photo->slug) ?></td>
                    <td class="string title">
                        <strong><?= $highlight($photo->title) ?></strong>
                        <?php if ($photo->original_name): ?>
                            <br><span class="text-secondary"><code><?= $highlight($photo->original_name) ?></code></span>
                        <?php endif; ?>
                        <?php if ($photo->city): ?>
                            <br><span class="text-secondary"><?= $highlight($photo->city) ?></span>
                        <?php endif; ?>
                        <?php if ($photo->location): ?>
                            <br><span class="text-secondary"><?= $highlight($photo->location) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="string camera">
                        <?= $highlight($photo->camera) ?: '<span class="text-muted">—</span>' ?>
                        <?php if ($photo->lens): ?>
                            <br><span class="text-secondary"><?= $highlight($photo->lens) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="string exposure text-nowrap">
                        <?php if ($photo->exposure): ?>
                            <div><?= $highlight($photo->exposure) ?></div>
                        <?php endif; ?>
                        <?php if ($photo->aperture): ?>
                            <div class="text-secondary"><?= $highlight($photo->aperture) ?></div>
                        <?php endif; ?>
                        <?php if ($photo->iso !== null && $photo->iso !== ''): ?>
                            <div class="text-secondary">ISO <?= $highlight((string)$photo->iso) ?></div>
                        <?php endif; ?>
                        <?php if ($photo->focal): ?>
                            <div class="text-secondary"><?= $highlight($photo->focal) ?></div>
                        <?php endif; ?>
                        <?php if (!$photo->exposure && !$photo->aperture && ($photo->iso === null || $photo->iso === '') && !$photo->focal): ?>
                            <span class="text-muted">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="date text-nowrap">
                        <?= h($photo->shot_date?->format('Y-m-d')) ?: '<span class="text-muted">—</span>' ?>
                        <?php if ($photo->shot_time): ?>
                            <br><span class="text-secondary"><?= h(is_object($photo->shot_time) ? $photo->shot_time->format('H:i:s') : $photo->shot_time) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="string dimensions"><?= $highlight($photo->dimensions) ?></td>
                    <td class="boolean in_gallery"><?= $photo->in_gallery ? '<span class="badge bg-green-lt">' . __('Igen') . '</span>' : '<span class="badge bg-secondary-lt">' . __('Nem') . '</span>' ?></td>
<?php if (isset($showCounterFields) && $showCounterFields): ?>
                    <td class="integer tags_count text-end"><?= $this->Number->format($photo->tags_count) ?></td>
<?php endif; ?>
<?php if (isset($showVisible) && $showVisible): ?>
                    <td class="boolean visible"><?= $photo->visible ? '<span class="badge bg-green-lt">' . __('Igen') . '</span>' : '<span class="badge bg-secondary-lt">' . __('Nem') . '</span>' ?></td>
<?php endif; ?>
<?php if (isset($showPos) && $showPos): ?>
                    <td class="integer pos"><?= h($photo->pos) ?></td>
<?php endif; ?>
<?php if ((isset($showCreated) && $showCreated) || (isset($showModified) && $showModified)): ?>
                    <td class="datetime text-nowrap">
<?php if (isset($showCreated) && $showCreated): ?>
                        <small class="d-block text-muted"><?= h($photo->created?->format('Y-m-d H:i')) ?></small>
<?php endif; ?>
<?php if (isset($showModified) && $showModified): ?>
                        <span><?= h($photo->modified?->format('Y-m-d H:i')) ?></span>
<?php endif; ?>
                    </td>
<?php endif; ?>
                    <td class="child-actions">
                        <div class="btn-list flex-nowrap align-items-center">
                            <?= $this->KvForm->linkChildList('I18n', 'foreign_key', $photo->id, 'Photos', __('I18n')) ?>
                        </div>
                    </td>
                    <td class="actions">
                        <div class="btn-list flex-nowrap align-items-center">
                            <?= $this->KvForm->actionView(['action' => 'view', $photo->id]) ?>
                            <?= $this->KvForm->actionEdit(['action' => 'edit', $photo->id]) ?>
                            <?= $this->KvForm->actionDelete(['action' => 'delete', $photo->id], (string)($photo->title ?? '')) ?>
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