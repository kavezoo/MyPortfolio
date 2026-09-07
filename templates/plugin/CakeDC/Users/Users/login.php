<?php
/**
 * Tabler-stílusú bejelentkezés (KvAdmin / Tabler sign-in minta).
 *
 * @var \App\View\AppView $this
 */

use Cake\Core\Configure;

$this->setLayout('auth');
$this->assign('title', __('Bejelentkezés'));

$rememberMeField = (string)Configure::read('Users.Key.Data.rememberMe', 'remember_me');
?>
<div class="card card-md auth-card">
    <div class="card-body">
        <h2 class="h2 text-center mb-4"><?= __('Bejelentkezés a fiókodba') ?></h2>

        <?= $this->Form->create(null, ['class' => 'auth-login-form']) ?>

        <div class="mb-3">
            <?= $this->Form->label('email', __('Email cím'), ['class' => 'form-label']) ?>
            <?= $this->Form->email('email', [
                'class' => 'form-control',
                'placeholder' => 'pelda@email.hu',
                'required' => true,
                'autofocus' => true,
                'autocomplete' => 'username',
                'label' => false,
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <div class="mb-2">
            <label class="form-label" for="password">
                <?= __('Jelszó') ?>
                <span class="form-label-description">
                    <?= $this->Html->link(
                        __('Elfelejtettem a jelszavam'),
                        ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'requestResetPassword']
                    ) ?>
                </span>
            </label>
            <?= $this->Form->password('password', [
                'class' => 'form-control',
                'placeholder' => __('A jelszavad'),
                'required' => true,
                'autocomplete' => 'current-password',
                'label' => false,
                'id' => 'password',
                'templates' => ['inputContainer' => '{{content}}'],
            ]) ?>
        </div>

        <?php if (Configure::read('Users.RememberMe.active')): ?>
            <div class="mb-2">
                <label class="form-check">
                    <?= $this->Form->checkbox($rememberMeField, [
                        'class' => 'form-check-input',
                        'checked' => (bool)Configure::read('Users.RememberMe.checked'),
                        'hiddenField' => false,
                    ]) ?>
                    <span class="form-check-label"><?= __('Emlékezz rám ezen az eszközön') ?></span>
                </label>
            </div>
        <?php endif; ?>

        <div class="form-footer">
            <?= $this->Form->button(__('Bejelentkezés'), [
                'class' => 'btn btn-primary w-100',
                'type' => 'submit',
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>

    <?php /*
    // Facebook + Google social login — később kapcsoljuk be (Users.Social.login + OAuth clientId/secret)
    <div class="hr-text"><?= __('vagy') ?></div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col">
                <?= $this->Html->link(
                    $this->Html->tag(
                        'span',
                        $this->Html->image('KvAdmin./static/brands/facebook.svg', [
                            'alt' => '',
                        ]),
                        ['class' => 'auth-social-icon']
                    ) . h(__('Facebook')),
                    '/auth/facebook',
                    [
                        'class' => 'btn btn-auth-facebook w-100 d-inline-flex align-items-center justify-content-center',
                        'escape' => false,
                    ]
                ) ?>
            </div>
            <div class="col">
                <?= $this->Html->link(
                    $this->Html->tag(
                        'span',
                        $this->Html->image('KvAdmin./static/brands/google.svg', [
                            'alt' => '',
                        ]),
                        ['class' => 'auth-social-icon']
                    ) . h(__('Google')),
                    '/auth/google',
                    [
                        'class' => 'btn btn-auth-google w-100 d-inline-flex align-items-center justify-content-center',
                        'escape' => false,
                    ]
                ) ?>
            </div>
        </div>
    </div>
    */ ?>
</div>

<?php /*
// Regisztráció — sablon kész, de jelenleg kikapcsolva (Users.Registration.active = false)
<div class="text-center text-secondary mt-3">
    <?= __('Még nincs fiókod?') ?>
    <?= $this->Html->link(
        __('Regisztráció'),
        ['plugin' => 'CakeDC/Users', 'controller' => 'Users', 'action' => 'register'],
        ['tabindex' => '-1']
    ) ?>
</div>
*/ ?>
