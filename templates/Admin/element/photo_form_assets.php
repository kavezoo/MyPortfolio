<?php
/**
 * Tom Select + live EXIF fill for Photos add/edit forms.
 *
 * @var \App\View\AppView $this
 */

$exifUrl = $this->Url->build(['prefix' => 'Admin', 'controller' => 'Photos', 'action' => 'extractExif']);
$requiredSelectMessage = json_encode(__('This field cannot be left empty'));
$exifReadingMessage = json_encode(__('Reading EXIF…'));
$exifFilledMessage = json_encode(__('EXIF fields filled from the selected file. You can edit them before saving.'));
$exifFailedMessage = json_encode(__('Could not read EXIF from this file. Fill the fields manually if needed.'));
$exifUrlJson = json_encode($exifUrl);

$this->Html->css([
    'KvAdmin./vendor/tom-select/css/tom-select.bootstrap5.min',
], ['block' => 'css']);

$this->Html->script(['KvAdmin./vendor/tom-select/js/tom-select.complete.min'], ['block' => 'script']);

$this->Html->scriptBlock(<<<JS
document.addEventListener('DOMContentLoaded', function () {
    const requiredSelectMessage = {$requiredSelectMessage};
    const exifReadingMessage = {$exifReadingMessage};
    const exifFilledMessage = {$exifFilledMessage};
    const exifFailedMessage = {$exifFailedMessage};

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

    const fileInput = document.querySelector('input[type="file"][name="image_file"]');
    if (!fileInput) {
        return;
    }

    const fieldMap = {
        camera: 'input[name="camera"]',
        lens: 'input[name="lens"]',
        exposure: 'input[name="exposure"]',
        aperture: 'input[name="aperture"]',
        iso: 'input[name="iso"]',
        focal: 'input[name="focal"]',
        shot_date: 'input[name="shot_date"]',
        shot_time: 'input[name="shot_time"]',
        dimensions: 'input[name="dimensions"]'
    };

    const setField = function (name, value) {
        const el = document.querySelector(fieldMap[name]);
        if (!el || value === null || value === undefined || value === '') {
            return;
        }
        el.value = value;
        el.dispatchEvent(new Event('input', { bubbles: true }));
        el.dispatchEvent(new Event('change', { bubbles: true }));
    };

    const hint = (function () {
        const parent = fileInput.closest('.col-12') || fileInput.parentElement;
        return parent ? parent.querySelector('.form-hint') : null;
    })();

    fileInput.addEventListener('change', function () {
        const file = fileInput.files && fileInput.files[0];
        if (!file) {
            return;
        }

        const form = fileInput.closest('form');
        const tokenInput = form ? form.querySelector('input[name="_csrfToken"]') : null;
        const body = new FormData();
        body.append('image_file', file);
        if (tokenInput) {
            body.append('_csrfToken', tokenInput.value);
        }

        if (hint) {
            hint.textContent = exifReadingMessage;
        }

        fetch({$exifUrlJson}, {
            method: 'POST',
            body: body,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    return { ok: response.ok, data: data };
                });
            })
            .then(function (result) {
                if (!result.ok || !result.data || !result.data.success) {
                    throw new Error((result.data && result.data.message) || 'EXIF read failed');
                }
                const exif = result.data.exif || {};
                Object.keys(fieldMap).forEach(function (key) {
                    setField(key, exif[key]);
                });
                if (hint) {
                    hint.textContent = exifFilledMessage;
                }
            })
            .catch(function () {
                if (hint) {
                    hint.textContent = exifFailedMessage;
                }
            });
    });
});
JS, ['block' => 'footer']);
?>
