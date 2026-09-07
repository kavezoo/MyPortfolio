<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Page $page
 * @var iterable<\App\Model\Entity\BlogPost> $blogPosts
 */
?>
<?= $this->element('hero', ['page' => $page]) ?>

<section class="section">
    <div class="container">
        <?php foreach ($blogPosts as $post): ?>
            <?php
            $photoEntities = $post->photos ?? [];
            if (!$photoEntities) {
                continue;
            }
            $payload = [];
            foreach ($photoEntities as $photo) {
                $payload[] = $photo->viewerPayload();
            }
            $hero = $photoEntities[0];
            $photosJson = h(json_encode($payload, JSON_UNESCAPED_UNICODE));
            $iso = $post->published ? $post->published->format('c') : null;
            ?>
            <article class="blog-post" id="post-<?= h($post->slug) ?>" data-photos="<?= $photosJson ?>" data-index="0">
                <div class="row g-4 align-items-start">
                    <div class="col-lg-7">
                        <button type="button" class="photo-frame blog-hero js-blog-hero" aria-label="<?= h(__('Open {0}', $hero->title)) ?>">
                            <?= $this->Photo->stack($hero) ?>
                        </button>
                        <?php if (count($photoEntities) > 1): ?>
                            <div class="blog-thumbs-wrap">
                                <button type="button" class="blog-thumbs-arrow" data-thumbs-prev aria-label="<?= h(__('Previous photos')) ?>">‹</button>
                                <div class="blog-thumbs-track" tabindex="0" aria-label="<?= h(__('Post photos')) ?>">
                                    <?php foreach ($photoEntities as $i => $thumb): ?>
                                        <button type="button" class="photo-frame blog-thumb js-blog-thumb<?= $i === 0 ? ' is-active' : '' ?>" data-index="<?= $i ?>" aria-label="<?= h($thumb->title) ?>">
                                            <?= $this->Photo->stack($thumb) ?>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <button type="button" class="blog-thumbs-arrow" data-thumbs-next aria-label="<?= h(__('Next photos')) ?>">›</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-5">
                        <h2 class="section-title blog-title"><?= h($post->title) ?></h2>
                        <p class="blog-meta">
                            <time<?= $iso ? ' datetime="' . h($iso) . '"' : '' ?>>
                                <?= $post->published ? h($post->published->format('Y.m.d')) : '' ?>
                                ·
                                <?= $post->published ? h($post->published->format('H:i')) : '' ?>
                            </time>
                        </p>
                        <p class="section-text"><?= h($post->body) ?></p>
                        <p class="blog-hint"><?= __('Click a photo to view it larger.') ?></p>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>
