<?php
/**
 * Tabler auth layout (page-center) — login / jelszó-visszaállítás / regisztráció.
 *
 * @var \App\View\AppView $this
 */
$siteName = $siteSettings['site_name'] ?? 'Varga Zsolt';
$pageTitle = $this->fetch('title') ?: __('Admin');
?>
<!doctype html>
<html lang="hu">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <title><?= h($siteName) ?> · <?= h($pageTitle) ?></title>
    <?= $this->Html->css(['KvAdmin.tabler.min', 'KvAdmin.main', 'auth']) ?>
    <?= $this->fetch('css') ?>
</head>
<body class="d-flex flex-column auth-body">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <div class="text-center mb-4">
                <?= $this->Html->link(
                    h($siteName),
                    '/',
                    ['class' => 'navbar-brand navbar-brand-autodark fs-2 fw-bold auth-brand']
                ) ?>
            </div>

            <?= $this->Flash->render() ?>
            <?= $this->Flash->render('auth') ?>

            <?= $this->fetch('content') ?>
        </div>
    </div>

    <?= $this->Html->script(['KvAdmin.tabler.min']) ?>
    <?= $this->fetch('script') ?>
</body>
</html>
