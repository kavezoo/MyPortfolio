<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $block
 * @var \App\Model\Entity\PageBlockItem[] $paragraphs
 * @var \App\Model\Entity\PageBlockItem[] $listItems
 */
?>
<div class="align-self-center">
    <?php if ($block->title): ?>
        <h2 class="section-title"><?= h($block->title) ?></h2>
    <?php endif; ?>
    <?php if ($block->body): ?>
        <p class="section-text"><?= nl2br(h($block->body)) ?></p>
    <?php endif; ?>
    <?php foreach ($paragraphs as $paragraph): ?>
        <p class="section-text"><?= nl2br(h($paragraph->body)) ?></p>
    <?php endforeach; ?>
    <?php if ($block->quote): ?>
        <blockquote class="section-quote">
            <p class="mb-0"><em><?= h($block->quote) ?></em></p>
        </blockquote>
    <?php endif; ?>
    <?php if ($listItems): ?>
        <ul class="about-list">
            <?php foreach ($listItems as $item): ?>
                <li><?= h($item->body) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($block->button_label && $block->button_url): ?>
        <a class="btn-more mt-3" href="<?= $this->Locale->url((string)$block->button_url) ?>"><?= h($block->button_label) ?></a>
    <?php endif; ?>
</div>
