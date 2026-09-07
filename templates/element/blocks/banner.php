<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $block
 */

$photo = $block->featured_photo;
$bg = $photo ? $photo->srcUrl() : '';
?>
<section class="banner-break parallax">
    <div class="parallax-bg" style="background-image: url('<?= h($bg) ?>');"></div>
</section>
