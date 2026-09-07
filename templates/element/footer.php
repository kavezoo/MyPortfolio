<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, string|null> $siteSettings
 */

$siteName = $siteSettings['site_name'] ?? 'Varga Zsolt';
$address = $siteSettings['address'] ?? '';
$instagram = $siteSettings['instagram_url'] ?? '';
$facebook = $siteSettings['facebook_url'] ?? '';
?>
<footer class="site-footer">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-lg-6 text-center text-lg-start">
                <p class="mb-0"><?= h($siteName) ?><?= $address !== '' ? ',&nbsp;' . h($address) : '' ?></p>
            </div>
            <div class="col-lg-6 text-center text-lg-end">
                <?php if ($instagram !== ''): ?>
                    <a href="<?= h($instagram) ?>" rel="nofollow" target="_blank">Instagram</a>
                <?php endif; ?>
                <?php if ($instagram !== '' && $facebook !== ''): ?>
                    <span class="footer-sep">|</span>
                <?php endif; ?>
                <?php if ($facebook !== ''): ?>
                    <a href="<?= h($facebook) ?>" rel="nofollow" target="_blank">facebook</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</footer>

<div class="viewer" id="photoViewer" hidden>
    <button type="button" class="viewer-close" data-viewer-close aria-label="<?= h(__('Close')) ?>">&times;</button>
    <div class="viewer-stage">
        <button type="button" class="viewer-nav viewer-prev" data-viewer-prev aria-label="<?= h(__('Previous photo')) ?>">&#8249;</button>
        <button type="button" class="viewer-nav viewer-next" data-viewer-next aria-label="<?= h(__('Next photo')) ?>">&#8250;</button>
        <div class="viewer-frame">
            <span class="viewer-media">
                <img class="viewer-photo" src="" alt="" draggable="false">
                <img class="viewer-shield" src="" alt="" draggable="false">
            </span>
        </div>
    </div>
    <aside class="viewer-meta">
        <p class="viewer-kicker" data-viewer-count></p>
        <h2 class="viewer-title"></h2>
        <p class="viewer-desc"></p>
        <dl class="viewer-facts"></dl>
        <div class="viewer-tags"></div>
    </aside>
</div>

<div class="pano-viewer" id="panoViewer" hidden>
    <button type="button" class="viewer-close" data-pano-close aria-label="<?= h(__('Close')) ?>">&times;</button>
    <div class="pano-stage">
        <button type="button" class="viewer-nav viewer-prev" data-pano-prev aria-label="<?= h(__('Previous panorama')) ?>">&#8249;</button>
        <button type="button" class="viewer-nav viewer-next" data-pano-next aria-label="<?= h(__('Next panorama')) ?>">&#8250;</button>
        <div class="pano-viewport">
            <div class="pano-track">
                <img class="pano-image" src="" alt="" draggable="false">
                <img class="pano-shield" src="" alt="" draggable="false">
            </div>
        </div>
        <p class="pano-hint"><?= __('Drag sideways · mouse wheel · arrow keys') ?></p>
    </div>
    <aside class="viewer-meta pano-meta">
        <p class="viewer-kicker" data-pano-count></p>
        <h2 class="viewer-title" data-pano-title></h2>
        <p class="viewer-desc" data-pano-desc></p>
        <dl class="viewer-facts" data-pano-facts></dl>
        <div class="viewer-tags" data-pano-tags></div>
    </aside>
</div>

<script>
window.SITE_I18N = <?= json_encode([
    'id' => __('ID'),
    'location' => __('Location'),
    'camera' => __('Camera'),
    'lens' => __('Lens'),
    'shutter' => __('Shutter'),
    'aperture' => __('Aperture'),
    'iso' => __('ISO'),
    'focal' => __('Focal length'),
    'date' => __('Date'),
    'time' => __('Time'),
    'resolution' => __('Resolution'),
], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->Html->script('site') ?>
