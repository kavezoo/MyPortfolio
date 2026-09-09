<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $block
 */

$photo = $block->featured_photo;
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
?>
<section class="section">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <?php if ($photo): ?>
                    <?= $this->Photo->frame($photo, 'photo-tall') ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <?php if ($block->title): ?>
                    <h2 class="section-title"><?= h($block->title) ?></h2>
                <?php endif; ?>
                <?php if ($block->body): ?>
                    <p><?= nl2br(h($block->body)) ?></p>
                <?php endif; ?>
                <?php if ($listItems): ?>
                    <ul class="about-list">
                        <?php foreach ($listItems as $item): ?>
                            <li><?= h($item->body) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php foreach ($paragraphs as $paragraph): ?>
                    <p><?= nl2br(h($paragraph->body)) ?></p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
