<?php
/**
 * Tabler-stílusú új jelszó megadása (reset token után / jelszócsere).
 *
 * @var \App\View\AppView $this
 * @var \CakeDC\Users\Model\Entity\User $user
 * @var bool $validatePassword
 */

use Cake\Core\Configure;

$this->setLayout('auth');
$this->assign('title', __('Új jelszó'));
?>
<div class="card card-md auth-card">
    <div class="card-body">
        <h2 class="h2 text-center mb-4"><?= __('Új jelszó megadása') ?></h2>

        <?= $this->Form->create($user) ?>

        <?php if (!empty($validatePassword)): ?>
            <div class="mb-3">
                <?= $this->Form->label('current_password', __('Jelenlegi jelszó'), ['class' => 'form-label']) ?>
                <?= $this->Form->password('current_password', [
                    'class' => 'form-control',
                    'required' => true,
                    'autocomplete' => 'current-password',
                    'label' => false,
                    'templates' => ['inputContainer' => '{{content}}'],
                ]) ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <?= $this->Form->label('password', __('Új jelszó'), ['class' => 'form-label']) ?>
            <?= $this->Form->password('password', [
                'class' => 'form-control',
                'required' => true,
                'id' => 'new-password',
                'autocomplete' => 'new-password',
                'label' => false,
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <div class="mb-3">
            <?= $this->Form->label('password_confirm', __('Új jelszó megerősítése'), ['class' => 'form-label']) ?>
            <?= $this->Form->password('password_confirm', [
                'class' => 'form-control',
                'required' => true,
                'autocomplete' => 'new-password',
                'label' => false,
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <div class="form-footer">
            <?= $this->Form->button(__('Jelszó mentése'), [
                'class' => 'btn btn-primary w-100',
                'id' => 'btn-submit',
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
