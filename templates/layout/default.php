<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page[]|\Cake\Collection\CollectionInterface $menuPages
 * @var array<string, string|null> $siteSettings
 * @var string $currentUrl
 * @var \App\Model\Entity\Page|null $page
 * @var string|null $title
 * @var string $currentLang
 * @var string|null $openPhotoUuid
 * @var string|null $basePagePath
 */

$siteName = $siteSettings['site_name'] ?? 'Varga Zsolt';
$pageTitle = $title ?? $siteName;
$pageDescription = $page->meta_description ?? ($siteSettings['default_description'] ?? __('Photo album – gallery and panoramas.'));
$viewerBorderColor = $siteSettings['viewer_border_color'] ?? '#8a8a8a';
$viewerBorderWidth = max(0, (int)($siteSettings['viewer_border_width'] ?? 2));
$basePagePath = $basePagePath ?? ($this->get('langPath') ?: '/');
?>
<!DOCTYPE html>
<html lang="<?= h($currentLang ?? 'hu') ?>">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($pageTitle) ?></title>
    <?= $this->Html->meta('description', $pageDescription) ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,400;0,600;1,400&family=Raleway:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?= $this->Html->css('style') ?>
    <style>
        :root {
            --viewer-border-color: <?= h($viewerBorderColor) ?>;
            --viewer-border-width: <?= (int)$viewerBorderWidth ?>px;
        }
    </style>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body
    class="has-hero"
    data-lang="<?= h($currentLang ?? 'hu') ?>"
    data-base-path="<?= h($this->Locale->path($basePagePath)) ?>"
    <?php if (!empty($openPhotoUuid)): ?>data-open-photo="<?= h($openPhotoUuid) ?>"<?php endif; ?>
>
    <?= $this->element('header') ?>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
    <?= $this->element('footer') ?>
    <?= $this->fetch('script') ?>
</body>
</html>
