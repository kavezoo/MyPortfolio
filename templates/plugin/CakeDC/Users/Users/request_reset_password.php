<?php
/**
 * Tabler-stílusú jelszó-visszaállítás kérése.
 *
 * @var \App\View\AppView $this
 * @var \CakeDC\Users\Model\Entity\User $user
 */

$this->setLayout('auth');
$this->assign('title', __('Elfelejtett jelszó'));
?>
<div class="card card-md auth-card">
    <div class="card-body">
        <h2 class="h2 text-center mb-4"><?= __('Jelszó visszaállítása') ?></h2>
        <p class="text-secondary text-center mb-4">
            <?= __('Add meg az email címed, és küldünk egy linket a jelszó visszaállításához.') ?>
        </p>

        <?= $this->Form->create($user) ?>

        <div class="mb-3">
            <?= $this->Form->label('reference', __('Email cím'), ['class' => 'form-label']) ?>
            <?= $this->Form->control('reference', [
                'label' => false,
                'class' => 'form-control',
                'placeholder' => 'pelda@email.hu',
                'required' => true,
                'autofocus' => true,
                'type' => 'email',
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <div class="form-footer">
            <?= $this->Form->button(__('Visszaállító link küldése'), [
                'class' => 'btn btn-primary w-100',
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    <?= $this->Html->link(
        __('Vissza a bejelentkezéshez'),
        ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']
    ) ?>
</div>
