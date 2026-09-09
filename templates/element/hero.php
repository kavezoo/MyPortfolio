<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 * @var bool $parallax
 */

$parallax = $parallax ?? false;
$heroPhoto = $page->hero_photo ?? null;
$bg = $heroPhoto ? $heroPhoto->srcUrl() : '';
?>
<?php if ($parallax): ?>
<section class="hero parallax">
    <div class="parallax-bg" style="background-image: url('<?= h($bg) ?>');"></div>
    <div class="hero-content">
        <h1><?= h($page->hero_title) ?></h1>
        <?php if ($page->hero_lead): ?>
            <p class="lead"><?= h($page->hero_lead) ?></p>
        <?php endif; ?>
    </div>
</section>
<?php else: ?>
<section class="page-hero" style="background-image: url('<?= h($bg) ?>');">
    <div class="page-hero-content">
        <h1><?= h($page->hero_title) ?></h1>
        <?php if ($page->hero_lead): ?>
            <p class="lead"><?= h($page->hero_lead) ?></p>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>
