<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PageBlock $block
 */
?>
<div class="row justify-content-center mb-4">
    <div class="col-lg-8">
        <p><?= nl2br(h((string)$block->body)) ?></p>
    </div>
</div>
