<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Setting $setting
 */
?>

<div class="page-header d-print-none mb-3 settings">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('{0} módosítása', __('Setting')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card settings">
    <?= $this->Form->create($setting) ?>

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <?= $this->KvForm->linkTab(__('Datasheet'), '#tabs-datesheet', true) ?>
            </li>

            <li class="nav-item ms-auto" role="presentation">
                <?= $this->KvForm->linkTabSettings() ?>
            </li>
        </ul>
    </div>

    <div class="card-body pb-4">
        <div class="tab-content">

            <!-- 1. Datasheet TAB -->
            <div class="tab-pane fade show active" id="tabs-datesheet" role="tabpanel">
                <div class="row g-3">
                    <div class="col-md-6">
                        <?= $this->Form->control('name', ['label' => ['text' => __('Name'), 'class' => 'form-label'], 'class' => 'form-control', 'required' => true]) ?>
                    </div>
                    <div class="col-12">
                        <?= $this->element('i18n_locale_tabs', [
                            'idPrefix' => 'setting-i18n',
                            'fields' => [
                                ['name' => 'label', 'label' => __('Label'), 'required' => true],
                                [
                                    'name' => 'value',
                                    'label' => __('Value'),
                                    'type' => 'textarea',
                                    'rows' => 6,
                                    'col' => 'col-12',
                                    'class' => 'form-control font-monospace',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div> <!-- /#tabs-datesheet -->

            <!-- 3. Settings TAB -->
            <div class="tab-pane fade" id="tabs-settings" role="tabpanel">
                <div class="mb-3">
                    <h4 class="card-title mb-1"><?= __('Beállítások') ?></h4>
                    <div class="text-secondary small"><?= __('Itt állítható a megjelenés és az alapértelmezett sorrend.') ?></div>
                </div>

                <div class="row g-3">
                    <!-- Láthatóság -->
                    <div class="col-12">
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label"><?= __('Visible') ?></label>
                            <div class="pt-2">
                                <?= $this->KvForm->switch('visible', ['label' => __('Active / Visible'), 'size' => '3']) ?>
                            </div>
                        </div>
                    </div>
                    <!-- Pozíció -->
                    <div class="col-12">
                        <div class="col-sm-6 col-md-4">
                            <?= $this->Form->control('pos', [
                                'type' => 'number',
                                'label' => ['text' => __('Position'), 'class' => 'form-label'],
                                'class' => 'form-control',
                                'min' => -10000,
                                'max' => 10000,
                                'step' => '1',
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div> <!-- /#tabs-settings -->

        </div> <!-- /.tab-content -->
    </div> <!-- /.card-body -->

    <div class="card-footer text-start">
        <?= $this->KvForm->saveButton() ?>
        <?= $this->KvForm->cancelButton() ?>
    </div>

    <?= $this->Form->end() ?>
</div>
