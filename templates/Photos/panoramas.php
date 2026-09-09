<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 * @var iterable<\App\Model\Entity\Photo> $photos
 * @var array<string> $tags
 * @var array<string> $cities
 */

$photos = $photos ?? [];
$total = count($photos);
$introBlocks = [];
foreach ($page->page_blocks ?? [] as $block) {
    if ($block->block_type === 'intro') {
        $introBlocks[] = $block;
    }
}
?>
<?= $this->element('hero', ['page' => $page]) ?>

<section class="section">
    <div class="container" id="panoApp" data-per-page="100">
        <?php foreach ($introBlocks as $block): ?>
            <?= $this->element('blocks/intro', compact('block')) ?>
        <?php endforeach; ?>

        <div class="gallery-toolbar">
            <div class="gallery-toolbar-top">
                <div>
                    <p class="gallery-label"><?= __('Tags') ?></p>
                    <div class="tag-list" role="list">
                        <button type="button" class="tag-chip is-active" data-tag="" role="listitem"><?= __('All') ?></button>
                        <?php foreach ($tags as $tag): ?>
                            <button type="button" class="tag-chip" data-tag="<?= h($tag) ?>" role="listitem">
                                <?= h($tag) ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="gallery-city">
                    <label class="gallery-label" for="panoCityFilter"><?= __('City') ?></label>
                    <select class="form-select gallery-select" id="panoCityFilter">
                        <option value=""><?= __('All cities') ?></option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?= h($city) ?>"><?= h($city) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <p class="gallery-count" data-pano-count-label data-count-label="<?= h(__('panorama')) ?>"><?= (int)$total ?> <?= __('panorama') ?></p>
        </div>

        <div class="pano-grid">
            <?php foreach ($photos as $photo): ?>
                <?= $this->Photo->frame($photo, 'photo-pano') ?>
            <?php endforeach; ?>
        </div>
        <p class="gallery-empty" hidden><?= __('No panoramas match this filter.') ?></p>
        <nav class="pager" data-pager aria-label="<?= h(__('Pagination')) ?>" hidden>
            <button type="button" class="pager-btn pager-arrow" data-pager-prev aria-label="<?= h(__('Previous page')) ?>">‹</button>
            <div class="pager-pages" data-pager-pages></div>
            <button type="button" class="pager-btn pager-arrow" data-pager-next aria-label="<?= h(__('Next page')) ?>">›</button>
        </nav>
    </div>
</section>
