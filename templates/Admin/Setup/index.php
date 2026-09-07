<?php
/**
 * @var \App\View\AppView $this
 * @var bool $maintenanceMode
 * @var string $maintenanceMessage
 */
?>
<div class="page-header d-print-none mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('Setup') ?></h2>
            <div class="text-secondary mt-1">
                <?= __('Oldalszintű kapcsolók és üzemeltetési beállítások.') ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <?= $this->Form->create(null) ?>
    <div class="card-header">
        <h3 class="card-title"><?= __('Karbantartás') ?></h3>
    </div>
    <div class="card-body">
        <p class="text-secondary mb-4">
            <?= __('Ha be van kapcsolva, a főoldalon megjelenik a karbantartási üzenet.') ?>
        </p>

        <div class="mb-4">
            <label class="form-label"><?= __('Karbantartás mód') ?></label>
            <div class="pt-1">
                <?= $this->KvForm->switch('maintenance_mode', [
                    'label' => __('Bekapcsolva'),
                    'size' => '3',
                    'checked' => $maintenanceMode,
                ]) ?>
            </div>
        </div>

        <div class="mb-0">
            <?= $this->Form->control('maintenance_message', [
                'label' => ['text' => __('Üzenet a főoldalon'), 'class' => 'form-label'],
                'class' => 'form-control',
                'type' => 'textarea',
                'rows' => 3,
                'value' => $maintenanceMessage,
            ]) ?>
        </div>
    </div>
    <div class="card-footer text-end">
        <?= $this->Html->link(
            __('Összes beállítás'),
            ['controller' => 'Settings', 'action' => 'index'],
            ['class' => 'btn btn-outline-secondary me-2']
        ) ?>
        <?= $this->Form->button(__('Mentés'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>
