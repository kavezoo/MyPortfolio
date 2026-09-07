<?php
/**
 * Admin topheader user dropdown.
 *
 * @var \App\View\AppView $this
 */

use CakeDC\Users\Utility\UsersUrl;

$identity = $this->getRequest()->getAttribute('identity');
$user = $identity ? $identity->getOriginalData() : null;

$displayName = __('Admin');
$roleLabel = __('Admin');
if ($user) {
    $parts = array_filter([
        (string)($user['first_name'] ?? ''),
        (string)($user['last_name'] ?? ''),
    ]);
    if ($parts) {
        $displayName = implode(' ', $parts);
    } elseif (!empty($user['email'])) {
        $displayName = (string)$user['email'];
    } elseif (!empty($user['username'])) {
        $displayName = (string)$user['username'];
    }

    $role = (string)($user['role'] ?? '');
    $roleLabel = $role !== '' ? \Cake\Utility\Inflector::humanize($role) : __('Admin');
}
?>
            <!-- Felhasználói fiók / Menü -->
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="<?= __('Open user menu') ?>" aria-expanded="false">
                    <span class="avatar avatar-sm" style="background-image: url('<?= $this->Url->assetUrl('KvAdmin./static/avatars/000m.jpg') ?>')"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div><?= h($displayName) ?></div>
                        <div class="mt-1 small text-secondary"><?= h($roleLabel) ?></div>
                    </div>
                </a>

                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <?= $this->Html->link(
                        '<span>' . __('Profile') . '</span>' . $this->Icon->outline('user', ['class' => 'icon text-muted ms-auto']),
                        UsersUrl::actionUrl('profile'),
                        ['escape' => false, 'class' => 'dropdown-item d-flex align-items-center justify-content-between']
                    ) ?>

                    <?= $this->Html->link(
                        '<span>' . __('Change Password') . '</span>' . $this->Icon->outline('key', ['class' => 'icon text-muted ms-auto']),
                        UsersUrl::actionUrl('changePassword'),
                        ['escape' => false, 'class' => 'dropdown-item d-flex align-items-center justify-content-between']
                    ) ?>

                    <?= $this->Html->link(
                        '<span>' . __('Setup') . '</span>' . $this->Icon->outline('adjustments', ['class' => 'icon text-muted ms-auto']),
                        ['prefix' => 'Admin', 'controller' => 'Setup', 'action' => 'index'],
                        ['escape' => false, 'class' => 'dropdown-item d-flex align-items-center justify-content-between']
                    ) ?>

                    <div class="dropdown-divider"></div>

                    <?= $this->Html->link(
                        '<span>' . __('Logout') . '</span>' . $this->Icon->outline('logout', ['class' => 'icon text-muted ms-auto']),
                        UsersUrl::actionUrl('logout'),
                        ['escape' => false, 'class' => 'dropdown-item d-flex align-items-center justify-content-between']
                    ) ?>
                </div>
            </div>
