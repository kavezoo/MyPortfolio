<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, string|null> $siteSettings
 */

$enabled = in_array(
    strtolower(trim((string)($siteSettings['maintenance_mode'] ?? '0'))),
    ['1', 'true', 'yes', 'on'],
    true,
);
if (!$enabled) {
    return;
}

$message = trim((string)($siteSettings['maintenance_message'] ?? ''));
if ($message === '') {
    $message = __('Az oldal jelenleg karbantartás alatt áll.');
}
?>
<div class="maintenance-banner" role="status">
    <div class="container">
        <strong class="maintenance-banner-label"><?= __('Karbantartás') ?></strong>
        <span class="maintenance-banner-text"><?= h($message) ?></span>
    </div>
</div>
