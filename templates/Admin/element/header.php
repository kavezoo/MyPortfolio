<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                    <ul class="navbar-nav">
                        <li class="nav-item<?= ($controller ?? '') === 'Dashboard' ? ' active' : '' ?>">
                            <?= $this->Html->link(
                                '<span class="nav-link-icon d-md-none d-lg-inline-block">' . $this->Icon->outline('home') . '</span>' .
                                '<span class="nav-link-title">' . __('Dashboard') . '</span>',
                                ['prefix' => 'Admin', 'controller' => 'Dashboard', 'action' => 'index'],
                                ['escape' => false, 'class' => 'nav-link']
                            ) ?>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-content" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <?= $this->Icon->outline('file-text') ?>
                                </span>
                                <span class="nav-link-title"><?= __('Content') ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <?= $this->Html->link(__('Pages'), ['prefix' => 'Admin', 'controller' => 'Pages', 'action' => 'index'], ['class' => 'dropdown-item']) ?>
                                <?= $this->Html->link(__('Page blocks'), ['prefix' => 'Admin', 'controller' => 'PageBlocks', 'action' => 'index'], ['class' => 'dropdown-item']) ?>
                                <?= $this->Html->link(__('Block items'), ['prefix' => 'Admin', 'controller' => 'PageBlockItems', 'action' => 'index'], ['class' => 'dropdown-item']) ?>
                                <?= $this->Html->link(__('Blog'), ['prefix' => 'Admin', 'controller' => 'BlogPosts', 'action' => 'index'], ['class' => 'dropdown-item']) ?>
                            </div>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#navbar-media" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <?= $this->Icon->outline('photo') ?>
                                </span>
                                <span class="nav-link-title"><?= __('Photos') ?></span>
                            </a>
                            <div class="dropdown-menu">
                                <?= $this->Html->link(__('Photos'), ['prefix' => 'Admin', 'controller' => 'Photos', 'action' => 'index'], ['class' => 'dropdown-item']) ?>
                                <?= $this->Html->link(__('Photo categories'), ['prefix' => 'Admin', 'controller' => 'PhotoCategories', 'action' => 'index'], ['class' => 'dropdown-item']) ?>
                                <?= $this->Html->link(__('Tags'), ['prefix' => 'Admin', 'controller' => 'Tags', 'action' => 'index'], ['class' => 'dropdown-item']) ?>
                            </div>
                        </li>

                        <li class="nav-item<?= ($controller ?? '') === 'ContactMessages' ? ' active' : '' ?>">
                            <?= $this->Html->link(
                                '<span class="nav-link-icon d-md-none d-lg-inline-block">' . $this->Icon->outline('mail') . '</span>' .
                                '<span class="nav-link-title">' . __('Messages') . '</span>',
                                ['prefix' => 'Admin', 'controller' => 'ContactMessages', 'action' => 'index'],
                                ['escape' => false, 'class' => 'nav-link']
                            ) ?>
                        </li>

                        <li class="nav-item<?= ($controller ?? '') === 'Setup' ? ' active' : '' ?>">
                            <?= $this->Html->link(
                                '<span class="nav-link-icon d-md-none d-lg-inline-block">' . $this->Icon->outline('adjustments') . '</span>' .
                                '<span class="nav-link-title">' . __('Setup') . '</span>',
                                ['prefix' => 'Admin', 'controller' => 'Setup', 'action' => 'index'],
                                ['escape' => false, 'class' => 'nav-link']
                            ) ?>
                        </li>

                        <li class="nav-item<?= ($controller ?? '') === 'Settings' ? ' active' : '' ?>">
                            <?= $this->Html->link(
                                '<span class="nav-link-icon d-md-none d-lg-inline-block">' . $this->Icon->outline('settings') . '</span>' .
                                '<span class="nav-link-title">' . __('Settings') . '</span>',
                                ['prefix' => 'Admin', 'controller' => 'Settings', 'action' => 'index'],
                                ['escape' => false, 'class' => 'nav-link']
                            ) ?>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-md-auto">
                        <li class="nav-item">
                            <?= $this->Html->link(
                                '<span class="nav-link-icon d-md-none d-lg-inline-block">' . $this->Icon->outline('external-link') . '</span>' .
                                '<span class="nav-link-title">' . __('Public site') . '</span>',
                                '/',
                                ['escape' => false, 'class' => 'nav-link', 'target' => '_blank', 'rel' => 'noopener']
                            ) ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>

<?php
$this->Html->scriptBlock(
    "
    document.addEventListener('DOMContentLoaded', function () {
        const navbar = document.querySelector('.navbar-nav');
        if (!navbar) return;

        document.addEventListener('show.bs.dropdown', function () {
            navbar.classList.add('has-dropdown-open');
        });

        document.addEventListener('hidden.bs.dropdown', function () {
            setTimeout(function () {
                if (!navbar.querySelector('.dropdown-menu.show')) {
                    navbar.classList.remove('has-dropdown-open');
                }
            }, 50);
        });
    });
    ",
    ['block' => 'script']
);
?>
