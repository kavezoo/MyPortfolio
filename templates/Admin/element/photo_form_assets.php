<?php
/**
 * Tom Select for Photos add/edit forms.
 *
 * @var \App\View\AppView $this
 */

$requiredSelectMessage = json_encode(__('This field cannot be left empty'));

$this->Html->css([
    'KvAdmin./vendor/tom-select/css/tom-select.bootstrap5.min',
], ['block' => 'css']);

$this->append('css', <<<'CSS'
<style>
/* Photo tags multi-select: taller control + dropdown (≈2× default) */
.photos-form .ts-wrapper.multi .ts-control {
    min-height: 6.5rem;
    align-items: flex-start;
}
.photos-form .ts-wrapper.multi .ts-dropdown .ts-dropdown-content {
    max-height: 400px;
}
</style>
CSS
);

$this->Html->script(['KvAdmin./vendor/tom-select/js/tom-select.complete.min'], ['block' => 'script']);

$this->Html->scriptBlock(<<<JS
document.addEventListener('DOMContentLoaded', function () {
    const requiredSelectMessage = {$requiredSelectMessage};

    document.querySelectorAll('.tom-select:not(.multi-select)').forEach(function (element) {
        if (element.tomselect || typeof TomSelect === 'undefined') {
            return;
        }
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
    });

    document.querySelectorAll('.tom-select.multi-select').forEach(function (element) {
        if (element.tomselect || typeof TomSelect === 'undefined') {
            return;
        }
        new TomSelect(element, {
            plugins: {
                remove_button: { title: 'Remove' },
                clear_button: { title: 'Clear all' }
            },
            persist: false,
            create: false,
            mode: 'multi',
            copyClassesToDropdown: false,
            wrapperClass: 'ts-wrapper form-select multi'
        });
    });

    // Original file name: fill from selected upload (not editable)
    const imageInput = document.querySelector('input[type="file"][name="image_file"]');
    const originalNameInput = document.querySelector('input[name="original_name"]');
    if (imageInput && originalNameInput) {
        imageInput.addEventListener('change', function () {
            const file = imageInput.files && imageInput.files[0];
            if (file && file.name) {
                originalNameInput.value = file.name;
            }
        });
    }
});
JS, ['block' => 'footer']);
?>
