<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ContactMessage $contactMessage
 */
?>

<div class="page-header d-print-none mb-3 contactMessages">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('Új {0} felvitele', __('Contact Message')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card contactMessages">
    <?= $this->Form->create($contactMessage) ?>

    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <?= $this->KvForm->linkTab(__('Datasheet'), '#tabs-datesheet', true) ?>
            </li>

            <li class="nav-item" role="presentation">
                <?= $this->KvForm->linkTab(__('Message'), '#tabs-message') ?>
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
                    <div class="col-md-6">
                        <?= $this->Form->control('email', ['label' => ['text' => __('Email'), 'class' => 'form-label'], 'class' => 'form-control', 'required' => true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('phone', ['label' => ['text' => __('Phone'), 'class' => 'form-label'], 'class' => 'form-control']) ?>
                    </div>

                </div>
            </div> <!-- /#tabs-datesheet -->

            <!-- Text tab: message -->
            <div class="tab-pane fade" id="tabs-message" role="tabpanel">
                <div class="row g-3">
                    <div class="col-12">
                        <?= $this->Form->control('message', [
                            'type' => 'textarea',
                            'id' => 'hugerte-message',
                            'label' => false,
                            'class' => 'form-control hugerte-editor',
                            'rows' => 14,
                        ]) ?>
                    </div>
                </div>
            </div> <!-- /#tabs-message -->

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
                                <?= $this->KvForm->switch('visible', ['label' => __('Active / Visible'), 'size' => '3', 'checked' => true]) ?>
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
                                'value' => '1000',
                            ]) ?>
                            <?php
                            /*
                             * Spinner változat – ha numberSpinner kell, kommentezd ki a fenti Form->control blokkot
                             * és vedd ki a kommentet az alábbi sorok körül:
                             *
                            <?= $this->KvForm->numberSpinner('pos', ['label' => ['text' => __('Position')], 'min' => -10000, 'max' => 10000, 'step' => '1', 'value' => '1000']) ?>
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


$this->Html->script(['KvAdmin./vendor/imask/dist/imask.min', 'KvAdmin./vendor/hugerte/hugerte.min'], ['block' => 'script']);
?>

<?php
// --- 1. Telefon maszk (IMask) ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    const phoneElem = document.getElementById('phone');
    if (phoneElem && typeof IMask !== 'undefined') {
        IMask(phoneElem, { mask: '+{36} 00/000-00-00' });
    }
});
JS, ['block' => 'footer']);

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

// --- 4. HugeRTE szerkesztő (text mezők / tab fülök) ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    if (typeof hugerte === 'undefined') {
        return;
    }

    const isDarkMode = document.body.getAttribute('data-bs-theme') === 'dark';

    document.querySelectorAll('.hugerte-editor').forEach(function (editorElement) {
        hugerte.init({
            target: editorElement,
            height: 600,
            menubar: 'file edit view insert format tools table help',
            statusbar: true,
            promotion: false,
            branding: false,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'codesample'
            ],
            toolbar: [
                'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | removeformat',
                'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image media codesample | code preview fullscreen'
            ],
            content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.5; color: ' + (isDarkMode ? '#f8fafc' : '#1e293b') + '; background-color: ' + (isDarkMode ? '#1b2431' : '#ffffff') + '; padding: 0.4375rem 0.75rem; margin: 0; } body > *:first-child { margin-top: 0 !important; } p { margin: 0 0 0.5rem 0; }',
            font_family_formats: 'Arial=Arial,Helvetica,sans-serif; Segoe UI=Segoe UI,Roboto,Helvetica,sans-serif; Courier New=courier new,courier,monospace; Georgia=georgia,palatino; Times New Roman=times new roman,times;',
            font_size_formats: '10px 12px 14px 16px 18px 24px 36px',
            skin: isDarkMode ? 'oxide-dark' : 'oxide',
            content_css: isDarkMode ? 'dark' : 'default',
            setup: function (editor) {
                editor.on('change keyup paste', function () {
                    editor.save();
                });
                editor.on('init', function () {
                    editor.getDoc().body.style.fontFamily = 'Arial, sans-serif';
                    editor.getDoc().body.style.fontSize = '14px';
                });
            }
        });
    });

    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(function (tabEl) {
        tabEl.addEventListener('shown.bs.tab', function () {
            document.querySelectorAll('.hugerte-editor').forEach(function (el) {
                const editor = hugerte.get(el.id);
                if (editor) {
                    editor.execCommand('mceRepaint');
                }
            });
        });
    });
});
JS, ['block' => 'footer']);

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
