<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 */
?>
<?= $this->element('maintenance_banner') ?>
<?= $this->element('hero', ['page' => $page, 'parallax' => true]) ?>

<?php foreach ($page->page_blocks as $block): ?>
    <?= $this->element('blocks/' . $block->block_type, compact('block')) ?>
<?php endforeach; ?>
