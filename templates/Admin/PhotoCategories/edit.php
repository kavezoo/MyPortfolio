<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PhotoCategory $photoCategory
 */
?>

<div class="page-header d-print-none mb-3 photoCategories">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('{0} módosítása', __('Photo Category')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card photoCategories">
    <?= $this->Form->create($photoCategory) ?>

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <?= $this->KvForm->linkTab(__('Datasheet'), '#tabs-datesheet', true) ?>
            </li>

            <?php /*
            <!-- Text mező tab fül – text oszlop hozzáadásakor vedd ki a kommentet és cseréld a mezőnevet -->
            <li class="nav-item" role="presentation">
                <?= $this->KvForm->linkTab(__('Body Html'), '#tabs-body_html') ?>
            </li>
            */ ?>

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
                    <div class="col-md-6">
                        <?= $this->Form->control('slug', ['label' => ['text' => __('Slug'), 'class' => 'form-label'], 'class' => 'form-control', 'required' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('photos_count', [
                            'type' => 'number',
                            'label' => ['text' => __('Photos Count'), 'class' => 'form-label'],
                            'class' => 'form-control',
                            'min' => 0,
                            'max' => 4294967295,
                            'step' => '1',
                        ]) ?>
                        <?php
                        /*
                         * Spinner változat – ha numberSpinner kell, kommentezd ki a fenti Form->control blokkot
                         * és vedd ki a kommentet az alábbi sorok körül:
                         *
                        <?= $this->KvForm->numberSpinner('photos_count', [
                            'label' => ['text' => __('Photos Count')],
                            'min' => 0,
                            'max' => 4294967295,
                            'step' => '1',
                        ]) ?>
                         */
                        ?>
                    </div>

                </div>
            </div> <!-- /#tabs-datesheet -->

            <?php /*
            <!-- Text mező tab tartalom – text oszlop hozzáadásakor vedd ki a kommentet -->
            <div class="tab-pane fade" id="tabs-body_html" role="tabpanel">
                <div class="row g-3">
                    <div class="col-12">
                        <?= $this->Form->control('body_html', [
                            'type' => 'textarea',
                            'id' => 'hugerte-body_html',
                            'label' => false,
                            'class' => 'form-control hugerte-editor',
                            'rows' => 14,
                        ]) ?>
                    </div>
                </div>
            </div>
            */ ?>

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
                            <?php
                            /*
                             * Spinner változat – ha numberSpinner kell, kommentezd ki a fenti Form->control blokkot
                             * és vedd ki a kommentet az alábbi sorok körül:
                             *
                            <?= $this->KvForm->numberSpinner('pos', ['label' => ['text' => __('Position')], 'min' => -10000, 'max' => 10000, 'step' => '1']) ?>
                             */
                            ?>
                        </div>
                    </div>
                </div>
            </div> <!-- /#tabs-settings -->

        </div> <!-- /.tab-content -->
    </div> <!-- /.card-body -->

    <div class="card-footer text-start">
        <!-- Mentés gomb -->
        <?= $this->KvForm->saveButton() ?>

        <!-- Mégse gomb: visszatér a jelenlegi controller index() akciójára -->
        <?= $this->KvForm->cancelButton() ?>
    </div>

    <?= $this->Form->end() ?>
</div>

<?php
/*
$this->Html->css([
    'KvAdmin./vendor/tom-select/css/tom-select.bootstrap5.min',
], ['block' => 'css']);
*/

/*
$this->Html->css([
    'KvAdmin./vendor/flatpickr/dist/flatpickr.min',
], ['block' => 'css']);
*/


/*
$this->Html->script([
    'KvAdmin./vendor/tom-select/js/tom-select.complete.min',
    'KvAdmin./vendor/imask/dist/imask.min',
    'KvAdmin./vendor/hugerte/hugerte.min',
    'KvAdmin./vendor/flatpickr/dist/flatpickr.min',
    'KvAdmin./vendor/flatpickr/dist/l10n/hu',
], ['block' => 'script']);
*/
?>

<?php
/*
// --- 1. Telefon maszk (IMask) – phone mező hozzáadásakor vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    const phoneElem = document.getElementById('phone');
    if (phoneElem && typeof IMask !== 'undefined') {
        IMask(phoneElem, { mask: '+{36} 00/000-00-00' });
    }
});
JS, ['block' => 'footer']);
*/

/*
// --- 2. Tom Select – egyszeres választó – FK/BelongsTo mező hozzáadásakor vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    const requiredSelectMessage = 'This field cannot be left empty';
    document.querySelectorAll('.tom-select:not(.multi-select)').forEach(function (element) {
        if (!element.tomselect && typeof TomSelect !== 'undefined') {
            new TomSelect(element, { wrapperClass: 'ts-wrapper form-select single' });
        }
    });
});
JS, ['block' => 'footer']);
*/

/*
// --- 3. Tom Select – többes választó – BelongsToMany hozzáadásakor vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.tom-select.multi-select').forEach(function (element) {
        if (!element.tomselect && typeof TomSelect !== 'undefined') {
            new TomSelect(element, { mode: 'multi', wrapperClass: 'ts-wrapper form-select multi' });
        }
    });
});
JS, ['block' => 'footer']);
*/

/*
// --- 4. HugeRTE szerkesztő – text mező / tab fül hozzáadásakor vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    if (typeof hugerte === 'undefined') {
        return;
    }
    document.querySelectorAll('.hugerte-editor').forEach(function (editorElement) {
        hugerte.init({ target: editorElement, height: 600 });
    });
});
JS, ['block' => 'footer']);
*/

/*
// --- 5a. Flatpickr – dátum és idő – datetime mező hozzáadásakor vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr === 'undefined') {
        return;
    }
    flatpickr('.flatpickr-datetime', {
        locale: 'hu',
        enableTime: true,
        time_24hr: true,
        dateFormat: 'Y-m-d H:i:S',
        altInput: true,
        altFormat: 'Y.m.d. H:i',
        allowInput: true,
        disableMobile: true
    });
});
JS, ['block' => 'footer']);
*/

/*
// --- 5b. Flatpickr – dátum – date mező hozzáadásakor vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr === 'undefined') {
        return;
    }
    flatpickr('.flatpickr-date', {
        locale: 'hu',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'Y.m.d.',
        allowInput: true,
        disableMobile: true
    });
});
JS, ['block' => 'footer']);
*/

/*
// --- 5c. Flatpickr – idő – time mező hozzáadásakor vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr === 'undefined') {
        return;
    }
    flatpickr('.flatpickr-time', {
        locale: 'hu',
        enableTime: true,
        noCalendar: true,
        time_24hr: true,
        dateFormat: 'H:i:S',
        altInput: true,
        altFormat: 'H:i',
        allowInput: true,
        disableMobile: true
    });
});
JS, ['block' => 'footer']);
*/

/*
// --- 6. Number Spinner gombok – spinner használathoz vedd ki a kommentet ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    document.body.addEventListener('click', function (e) {
        const button = e.target.closest('.input-group [data-action]');
        if (!button) {
            return;
        }

        const group = button.closest('.input-group');
        const input = group ? group.querySelector('input[type="number"]') : null;
        if (!input) {
            return;
        }

        const stepStr = input.step && input.step !== 'any' ? input.step : '1';
        const step = parseFloat(stepStr) || 1;
        const min = input.min !== '' ? parseFloat(input.min) : -Infinity;
        const max = input.max !== '' ? parseFloat(input.max) : Infinity;
        let currentVal = parseFloat(input.value) || 0;
        const decimals = stepStr.includes('.') ? stepStr.split('.')[1].length : 0;

        if (button.dataset.action === 'increment') {
            currentVal = Math.min(max, currentVal + step);
        } else if (button.dataset.action === 'decrement') {
            currentVal = Math.max(min, currentVal - step);
        }

        input.value = currentVal.toFixed(decimals);
        input.dispatchEvent(new Event('change', { bubbles: true }));
        input.dispatchEvent(new Event('input', { bubbles: true }));
    });
});
JS, ['block' => 'footer']);
*/
?>
