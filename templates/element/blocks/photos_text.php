<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $block
 */

$photos = $block->photos ?? [];
$items = $block->page_block_items ?? [];
$listItems = [];
$paragraphs = [];
foreach ($items as $item) {
    if ($item->item_type === 'list') {
        $listItems[] = $item;
    } else {
        $paragraphs[] = $item;
    }
}
$layout = $block->layout ?: 'two-left';
?>
<section class="section">
    <div class="container">
        <?php if ($layout === 'text-split'): ?>
            <div class="row g-3 align-items-stretch">
                <div class="col-lg-4 d-flex">
                    <?= $this->element('blocks/photos_text_copy', compact('block', 'paragraphs', 'listItems')) ?>
                </div>
                <div class="col-lg-4">
                    <?php if (!empty($photos[0])): ?>
                        <?= $this->Photo->frame($photos[0], $photos[0]->_joinData->css_class ?? 'photo-tall') ?>
                    <?php endif; ?>
                </div>
                <div class="col-lg-4">
                    <div class="stack-gap">
                        <?php foreach (array_slice($photos, 1) as $photo): ?>
                            <?= $this->Photo->frame($photo, $photo->_joinData->css_class ?? 'photo-mid') ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-3 align-items-stretch">
                <?php foreach ($photos as $photo): ?>
                    <div class="col-md-4">
                        <?= $this->Photo->frame($photo, $photo->_joinData->css_class ?? 'photo-tall') ?>
                    </div>
                <?php endforeach; ?>
                <div class="col-md-4 d-flex">
                    <?= $this->element('blocks/photos_text_copy', compact('block', 'paragraphs', 'listItems')) ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
