<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 * @var \App\Model\Entity\ContactMessage $contactMessage
 * @var bool $sent
 */

$contactBlock = null;
foreach ($page->page_blocks ?? [] as $block) {
    if ($block->block_type === 'contact') {
        $contactBlock = $block;
        break;
    }
}
?>
<?= $this->element('hero', ['page' => $page]) ?>

<section class="section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-6">
                <?php if ($sent): ?>
                    <div class="alert alert-ok"><?= __('Thank you for your message. I will get back to you soon.') ?></div>
                <?php else: ?>
                    <?= $this->Form->create($contactMessage, [
                        'url' => ['lang' => $currentLang, 'controller' => 'ContactMessages', 'action' => 'add'],
                        'novalidate' => true,
                    ]) ?>
                    <?= $this->Form->control('name', [
                        'label' => ['text' => __('Name'), 'class' => 'form-label'],
                        'class' => 'form-control',
                    ]) ?>
                    <?= $this->Form->control('email', [
                        'label' => ['text' => __('Email'), 'class' => 'form-label'],
                        'class' => 'form-control',
                    ]) ?>
                    <?= $this->Form->control('phone', [
                        'label' => ['text' => __('Phone'), 'class' => 'form-label'],
                        'class' => 'form-control',
                    ]) ?>
                    <?= $this->Form->control('message', [
                        'label' => ['text' => __('Message'), 'class' => 'form-label'],
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'rows' => 6,
                    ]) ?>
                    <div class="mb-4"></div>
                    <?= $this->Form->button(__('Send'), ['class' => 'btn-more']) ?>
                    <?= $this->Form->end() ?>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <?php if ($contactBlock): ?>
                    <?= $this->element('blocks/contact', ['block' => $contactBlock]) ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
