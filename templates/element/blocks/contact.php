<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $block
 */
?>
<?php if ($block->title): ?>
    <h2 class="section-title"><?= h($block->title) ?></h2>
<?php endif; ?>
<?php if ($block->body): ?>
    <p><?= nl2br(h($block->body)) ?></p>
<?php endif; ?>
<?php if (!empty($block->page_block_items)): ?>
    <ul class="contact-list">
        <?php foreach ($block->page_block_items as $item): ?>
            <li>
                <?php if ($item->url): ?>
                    <a href="<?= h($item->url) ?>"<?= str_starts_with((string)$item->url, 'http') ? ' rel="nofollow" target="_blank"' : '' ?>><?= h($item->body) ?></a>
                <?php else: ?>
                    <?= h($item->body) ?>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
