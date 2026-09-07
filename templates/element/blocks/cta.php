<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $block
 */
?>
<section class="section">
    <div class="container">
        <div class="cta-section">
            <?php if ($block->title): ?>
                <h2 class="section-title"><?= h($block->title) ?></h2>
            <?php endif; ?>
            <?php if ($block->body): ?>
                <p><?= nl2br(h($block->body)) ?></p>
            <?php endif; ?>
            <?php if ($block->button_label && $block->button_url): ?>
                <a class="btn-more mt-2" href="<?= $this->Locale->url((string)$block->button_url) ?>"><?= h($block->button_label) ?></a>
            <?php endif; ?>
        </div>
    </div>
</section>
