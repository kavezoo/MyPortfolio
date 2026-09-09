<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page[]|\Cake\Collection\CollectionInterface $menuPages
 * @var array<string, string|null> $siteSettings
 * @var string $currentUrl
 * @var string $currentLang
 * @var array<string, array<string, string>> $languages
 */

$siteName = $siteSettings['site_name'] ?? 'Varga Zsolt';
$currentLanguage = $languages[$currentLang] ?? ['label' => 'Magyar', 'flag' => 'hu.png'];
?>
<header class="site-header">
    <nav class="navbar navbar-expand-lg site-nav">
        <div class="container-fluid site-nav-inner">
            <a class="navbar-brand site-logo" href="<?= $this->Locale->url('/') ?>"><?= h($siteName) ?></a>
            <button class="navbar-toggler site-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="<?= h(__('Menu')) ?>">
                <span class="navbar-toggler-icon"></span>
                <span class="toggler-label"><?= __('Menu') ?></span>
            </button>
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                    <?php foreach ($menuPages as $item): ?>
                        <?php
                        $itemPath = $item->url === '/' ? '/' : rtrim((string)$item->url, '/');
                        $isActive = $currentUrl === $itemPath;
                        ?>
                        <li class="nav-item">
                            <a class="nav-link<?= $isActive ? ' active' : '' ?>" href="<?= $this->Locale->url((string)$item->url) ?>"<?= $isActive ? ' aria-current="page"' : '' ?>>
                                <?= h($item->menu_label) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li class="nav-item dropdown lang-switch">
                        <a class="nav-link dropdown-toggle lang-switch-toggle" href="#" id="langMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="<?= h($currentLanguage['label']) ?>">
                            <?= $this->Html->image(
                                'flag/' . $currentLanguage['flag'],
                                ['alt' => $currentLanguage['label'], 'class' => 'lang-flag']
                            ) ?>
                            <span class="lang-code"><?= h(strtoupper($currentLang)) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end lang-switch-menu" aria-labelledby="langMenu">
                            <?php foreach ($languages as $code => $language): ?>
                                <li>
                                    <a class="dropdown-item lang-switch-item<?= $code === $currentLang ? ' is-active' : '' ?>" href="<?= $this->Locale->switchUrl($code) ?>" hreflang="<?= h($code) ?>" lang="<?= h($code) ?>">
                                        <?= $this->Html->image(
                                            'flag/' . $language['flag'],
                                            ['alt' => '', 'class' => 'lang-flag']
                                        ) ?>
                                        <span><?= h($language['label']) ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
