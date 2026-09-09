<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\BlogPostsPhoto $blogPostsPhoto
 * @var \Cake\Collection\CollectionInterface|string[] $blogPosts
 * @var \Cake\Collection\CollectionInterface|string[] $photos
 */
?>

<div class="page-header d-print-none mb-3 blogPostsPhotos">
    <div class="row align-items-center">
        <div class="col">
            <h2 class="page-title"><?= __('Új {0} felvitele', __('Blog Posts Photo')) ?></h2>
        </div>
        <div class="col-auto ms-auto">
            <?= $this->KvForm->linkCloseIndex() ?>
        </div>
    </div>
</div>

<div class="card blogPostsPhotos">
    <?= $this->Form->create($blogPostsPhoto) ?>

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
                        <?= $this->Form->control('blog_post_id', [
                            'options' => $blogPosts,
                            'label' => ['text' => __('Blog Post Id'), 'class' => 'form-label'],
                            'class' => 'form-select tom-select',
                            'empty' => '',
                            'value' => '',
                            'required' => true,
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $this->Form->control('photo_id', [
                            'options' => $photos,
                            'label' => ['text' => __('Photo Id'), 'class' => 'form-label'],
                            'class' => 'form-select tom-select',
                            'empty' => '',
                            'value' => '',
                            'required' => true,
                        ]) ?>
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
$this->Html->css([
    'KvAdmin./vendor/tom-select/css/tom-select.bootstrap5.min',
], ['block' => 'css']);

/*
$this->Html->css([
    'KvAdmin./vendor/flatpickr/dist/flatpickr.min',
], ['block' => 'css']);
*/


$this->Html->script(['KvAdmin./vendor/tom-select/js/tom-select.complete.min'], ['block' => 'script']);
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

// --- 2. Tom Select – egyszeres választó (BelongsTo / FK mezők) ---
$this->Html->scriptBlock(<<<'JS'
document.addEventListener('DOMContentLoaded', function () {
    const requiredSelectMessage = 'This field cannot be left empty';

    document.querySelectorAll('.tom-select:not(.multi-select)').forEach(function (element) {
        if (!element.tomselect && typeof TomSelect !== 'undefined') {
            const selectedOption = element.querySelector('option[selected]');
            const selectedValue = selectedOption && selectedOption.value !== '' ? selectedOption.value : '';
            const tomSelect = new TomSelect(element, {
                copyClassesToDropdown: false,
                create: false,
                allowEmptyOption: true,
                maxOptions: null,
                openOnFocus: true,
                placeholder: '',
                items: selectedValue ? [selectedValue] : [],
                wrapperClass: 'ts-wrapper form-select single'
            });

            const setEmptyNativeValue = function () {
                Array.from(element.options).forEach(function (option) {
                    option.selected = option.value === '';
                });
                element.value = '';
            };

            const toggleRequiredState = function (value) {
                if (!element.required) {
                    return;
                }
                const isEmpty = !value;
                tomSelect.wrapper.classList.toggle('is-invalid', isEmpty);
                let feedback = tomSelect.wrapper.parentElement.querySelector('.invalid-feedback.tom-select-required');
                if (isEmpty) {
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback tom-select-required d-block';
                        feedback.textContent = requiredSelectMessage;
                        tomSelect.wrapper.after(feedback);
                    }
                } else if (feedback) {
                    feedback.remove();
                }
            };

            tomSelect.on('change', function (value) {
                if (!value) {
                    setEmptyNativeValue();
                }
                toggleRequiredState(value);
            });

            if (!selectedValue) {
                tomSelect.clear(true);
                setEmptyNativeValue();
            }

            const form = element.closest('form');
            if (form && !form.dataset.tomSelectRequired) {
                form.dataset.tomSelectRequired = '1';
                form.addEventListener('submit', function (event) {
                    let firstInvalid = null;
                    form.querySelectorAll('select.tom-select[required]:not(.multi-select)').forEach(function (select) {
                        const ts = select.tomselect;
                        const value = ts ? ts.getValue() : select.value;
                        if (!value) {
                            event.preventDefault();
                            if (ts) {
                                ts.wrapper.classList.add('is-invalid');
                                let feedback = ts.wrapper.parentElement.querySelector('.invalid-feedback.tom-select-required');
                                if (!feedback) {
                                    feedback = document.createElement('div');
                                    feedback.className = 'invalid-feedback tom-select-required d-block';
                                    feedback.textContent = requiredSelectMessage;
                                    ts.wrapper.after(feedback);
                                }
                                if (!firstInvalid) {
                                    firstInvalid = ts;
                                }
                            }
                        }
                    });
                    if (firstInvalid) {
                        firstInvalid.focus();
                        firstInvalid.open();
                    }
                });
            }
        }
    });
});
JS, ['block' => 'footer']);

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
