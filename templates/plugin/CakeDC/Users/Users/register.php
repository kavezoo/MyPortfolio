<?php
/**
 * Tabler-stílusú regisztráció.
 * A sablon kész, de a regisztráció jelenleg ki van kapcsolva (Users.Registration.active = false).
 *
 * @var \App\View\AppView $this
 * @var \CakeDC\Users\Model\Entity\User $user
 */

use Cake\Core\Configure;

$this->setLayout('auth');
$this->assign('title', __('Regisztráció'));
?>
<div class="card card-md auth-card">
    <div class="card-body">
        <h2 class="h2 text-center mb-4"><?= __('Fiók létrehozása') ?></h2>

        <?= $this->Form->create($user) ?>

        <div class="mb-3">
            <?= $this->Form->label('email', __('Email cím'), ['class' => 'form-label']) ?>
            <?= $this->Form->email('email', [
                'class' => 'form-control',
                'placeholder' => 'pelda@email.hu',
                'required' => true,
                'autofocus' => true,
                'label' => false,
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <div class="mb-3">
            <?= $this->Form->label('password', __('Jelszó'), ['class' => 'form-label']) ?>
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
            <?= $this->Form->label('password_confirm', __('Jelszó megerősítése'), ['class' => 'form-label']) ?>
            <?= $this->Form->password('password_confirm', [
                'class' => 'form-control',
                'required' => true,
                'autocomplete' => 'new-password',
                'label' => false,
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <div class="mb-3">
            <?= $this->Form->label('first_name', __('Keresztnév'), ['class' => 'form-label']) ?>
            <?= $this->Form->text('first_name', [
                'class' => 'form-control',
                'label' => false,
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <div class="mb-3">
            <?= $this->Form->label('last_name', __('Vezetéknév'), ['class' => 'form-label']) ?>
            <?= $this->Form->text('last_name', [
                'class' => 'form-control',
                'label' => false,
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <?php if (Configure::read('Users.Tos.required')): ?>
            <div class="mb-3">
                <label class="form-check">
                    <?= $this->Form->checkbox('tos', [
                        'class' => 'form-check-input',
                        'required' => true,
                        'hiddenField' => false,
                    ]) ?>
                    <span class="form-check-label"><?= __('Elfogadom a felhasználási feltételeket') ?></span>
                </label>
            </div>
        <?php endif; ?>

        <div class="form-footer">
            <?= $this->Form->button(__('Regisztráció'), [
                'class' => 'btn btn-primary w-100',
                'id' => 'btn-submit',
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<div class="text-center text-secondary mt-3">
    <?= __('Már van fiókod?') ?>
    <?= $this->Html->link(
        __('Bejelentkezés'),
        ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'login']
    ) ?>
</div>
