<?php
/**
 * @var \App\View\AppView $this
 * @var array<string, int> $counts
 */
$this->assign('title', __('Dashboard'));
?>
<div class="row row-deck row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"><?= __('Dashboard') ?></h3>
                <p class="text-secondary mb-0"><?= __('You can edit the portfolio content here. Full admin login comes later.') ?></p>
            </div>
        </div>
    </div>

    <?php
    $stats = [
        ['label' => __('Pages'), 'count' => $counts['pages'], 'url' => ['controller' => 'Pages', 'action' => 'index']],
        ['label' => __('Photos'), 'count' => $counts['photos'], 'url' => ['controller' => 'Photos', 'action' => 'index']],
        ['label' => __('Blog'), 'count' => $counts['blogPosts'], 'url' => ['controller' => 'BlogPosts', 'action' => 'index']],
        ['label' => __('Messages'), 'count' => $counts['contactMessages'], 'url' => ['controller' => 'ContactMessages', 'action' => 'index']],
        ['label' => __('Tags'), 'count' => $counts['tags'], 'url' => ['controller' => 'Tags', 'action' => 'index']],
        ['label' => __('Settings'), 'count' => $counts['settings'], 'url' => ['controller' => 'Settings', 'action' => 'index']],
    ];
    foreach ($stats as $stat):
    ?>
        <div class="col-sm-6 col-lg-4">
            <a class="card card-link" href="<?= $this->Url->build($stat['url']) ?>">
                <div class="card-body">
                    <div class="subheader"><?= h($stat['label']) ?></div>
                    <div class="h1 mb-0"><?= $this->Number->format($stat['count']) ?></div>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
