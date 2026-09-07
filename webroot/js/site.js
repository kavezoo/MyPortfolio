$(function () {
        var $nav = $('.site-nav');
        function updateNav() {
            $nav.toggleClass('scrolled', $(window).scrollTop() > 40);
        }
        $(window).on('scroll', updateNav);
        updateNav();
        $('#mainMenu')
            .on('show.bs.collapse', function () { $nav.addClass('menu-open'); })
            .on('hide.bs.collapse', function () { $nav.removeClass('menu-open'); });

        var parallaxSections = Array.prototype.slice.call(document.querySelectorAll('.parallax'));
        var parallaxTicking = false;
        var reduceMotionMq = window.matchMedia('(prefers-reduced-motion: reduce)');

        function updateParallax() {
            parallaxTicking = false;
            if (reduceMotionMq.matches || !parallaxSections.length) {
                return;
            }

            var vh = window.innerHeight || document.documentElement.clientHeight;

            parallaxSections.forEach(function (section) {
                var bg = section.querySelector('.parallax-bg');
                if (!bg) {
                    return;
                }

                var rect = section.getBoundingClientRect();
                if (rect.bottom < -80 || rect.top > vh + 80) {
                    return;
                }

                // A háttér ~fele sebességgel követi a görgetést → látható „ablak” parallax
                // (hero és banner ugyanígy; a nagyobb layer miatt nincs üres sáv)
                var speed = section.classList.contains('hero') ? 0.55 : 0.45;
                var y = Math.round(-rect.top * speed);
                bg.style.transform = 'translate3d(0, ' + y + 'px, 0)';
            });
        }

        function requestParallax() {
            if (!parallaxTicking) {
                parallaxTicking = true;
                window.requestAnimationFrame(updateParallax);
            }
        }

        if (parallaxSections.length) {
            window.addEventListener('scroll', requestParallax, { passive: true });
            window.addEventListener('resize', requestParallax, { passive: true });
            if (reduceMotionMq.addEventListener) {
                reduceMotionMq.addEventListener('change', requestParallax);
            }
            updateParallax();
        }

        var $viewer = $('#photoViewer');
        var $img = $viewer.find('.viewer-photo');
        var $shield = $viewer.find('.viewer-shield');
        var photos = [];
        var index = 0;
        var siteLang = document.body.getAttribute('data-lang') || 'hu';
        var basePagePath = document.body.getAttribute('data-base-path') || ('/' + siteLang);
        var syncingHistory = false;

        function parsePhoto($el) {
            try {
                return JSON.parse($el.attr('data-photo'));
            } catch (e) {
                return null;
            }
        }

        function photoShareUrl(photo) {
            if (!photo) {
                return basePagePath;
            }
            if (photo.url) {
                return photo.url;
            }
            var id = photo.uuid || photo.id;
            return id ? ('/' + siteLang + '/foto/' + id) : basePagePath;
        }

        function facebookShareHref(photo) {
            var path = photoShareUrl(photo);
            var absolute = /^https?:\/\//i.test(path) ? path : (window.location.origin + path);
            return 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(absolute);
        }

        function updateFacebookShare($link, photo) {
            if (!$link.length) {
                return;
            }
            if (!photo) {
                $link.attr('href', '#').attr('aria-disabled', 'true');
                return;
            }
            $link.attr('href', facebookShareHref(photo)).removeAttr('aria-disabled');
        }

        $(document).on('click', '[data-viewer-facebook], [data-pano-facebook]', function (e) {
            e.stopPropagation();
        });

        function setShareUrl(photo, replace) {
            if (syncingHistory || !window.history || !window.history.pushState) {
                return;
            }
            var url = photo ? photoShareUrl(photo) : basePagePath;
            var state = photo ? { photoUuid: photo.uuid || photo.id || null } : { photoUuid: null };
            if (replace) {
                window.history.replaceState(state, '', url);
            } else {
                window.history.pushState(state, '', url);
            }
        }

        function fact(label, value) {
            if (!value) {
                return '';
            }
            return '<dt>' + label + '</dt><dd>' + $('<div>').text(value).html() + '</dd>';
        }

        var $media = $viewer.find('.viewer-media');
        var $frame = $viewer.find('.viewer-frame');

        function fitPhoto() {
            var el = $img[0];
            var frame = $frame[0];
            if (!el || !el.naturalWidth || !frame) {
                return;
            }
            var borderW = parseFloat(getComputedStyle($media[0]).borderTopWidth) || 0;
            var border = borderW * 2;
            var maxW = Math.max(1, frame.clientWidth - border);
            var maxH = Math.max(1, frame.clientHeight - border);
            var ratio = el.naturalWidth / el.naturalHeight;
            var w = maxW;
            var h = w / ratio;
            if (h > maxH) {
                h = maxH;
                w = h * ratio;
            }
            w = Math.floor(w);
            h = Math.floor(h);
            $img.css({ width: w + 'px', height: h + 'px', maxWidth: 'none', maxHeight: 'none' });
            $shield.css({ width: w + 'px', height: h + 'px', maxWidth: 'none', maxHeight: 'none' });
            $media.css({ width: w + 'px', height: h + 'px' });
        }

        function show(i, options) {
            if (!photos.length) {
                return;
            }
            options = options || {};
            index = (i + photos.length) % photos.length;
            var photo = photos[index];
            var exif = photo.exif || {};
            $img.off('load.viewerSync').one('load.viewerSync', fitPhoto);
            $img.attr({ src: photo.src, alt: photo.title || '' });
            $shield.attr({ src: photo.shield || '', alt: '' });
            if ($img[0].complete && $img[0].naturalWidth) {
                fitPhoto();
            }
            $viewer.find('.viewer-title').text(photo.title || '');
            $viewer.find('.viewer-desc').text(photo.description || '');
            $viewer.find('[data-viewer-count]').text((index + 1) + ' / ' + photos.length);
            var t = window.SITE_I18N || {};
            $viewer.find('.viewer-facts').html(
                fact(t.id || 'ID', photo.original_name || '') +
                fact(t.location || 'Location', photo.location) +
                fact(t.camera || 'Camera', exif.camera) +
                fact(t.lens || 'Lens', exif.lens) +
                fact(t.shutter || 'Shutter', exif.exposure) +
                fact(t.aperture || 'Aperture', exif.aperture) +
                fact(t.iso || 'ISO', exif.iso) +
                fact(t.focal || 'Focal length', exif.focal) +
                fact(t.date || 'Date', exif.date) +
                fact(t.time || 'Time', exif.time) +
                fact(t.resolution || 'Resolution', exif.dimensions)
            );
            var tags = (photo.tags || []).map(function (tag) {
                return '<span class="viewer-tag">' + $('<div>').text(tag).html() + '</span>';
            }).join('');
            $viewer.find('.viewer-tags').html(tags);
            updateFacebookShare($viewer.find('[data-viewer-facebook]'), photo);
            if (!options.skipUrl) {
                setShareUrl(photo, !!options.replaceUrl);
            }
        }

        function openViewer(startIndex, list, options) {
            options = options || {};
            photos = list;
            $viewer.prop('hidden', false).addClass('is-open');
            $('body').addClass('viewer-open');
            show(startIndex, options);
            requestAnimationFrame(fitPhoto);
        }

        function closeViewer(options) {
            options = options || {};
            $viewer.removeClass('is-open').prop('hidden', true);
            $('body').removeClass('viewer-open');
            $img.off('load.viewerSync').attr({ src: '', alt: '' }).css({ width: '', height: '', maxWidth: '', maxHeight: '' });
            $shield.attr({ src: '', alt: '' }).css({ width: '', height: '', maxWidth: '', maxHeight: '' });
            $media.css({ width: '', height: '' });
            if (!options.skipUrl) {
                setShareUrl(null, !!options.replaceUrl);
            }
        }

        $(window).on('resize.viewerShield', function () {
            if ($viewer.hasClass('is-open')) {
                fitPhoto();
            }
        });

        $('.js-photo').on('click', function () {
            var $visible = $('.js-photo').filter(':visible');
            var list = $visible.map(function () {
                return parsePhoto($(this));
            }).get().filter(Boolean);
            openViewer($visible.index(this), list);
        });

        function photosFromPost($post) {
            try {
                return JSON.parse($post.attr('data-photos') || '[]');
            } catch (e) {
                return [];
            }
        }

        function updateThumbArrows($wrap) {
            var track = $wrap.find('.blog-thumbs-track')[0];
            if (!track) {
                return;
            }
            var max = track.scrollWidth - track.clientWidth;
            $wrap.find('[data-thumbs-prev]').prop('disabled', track.scrollLeft <= 2);
            $wrap.find('[data-thumbs-next]').prop('disabled', max <= 2 || track.scrollLeft >= max - 2);
        }

        function setBlogHero($post, index) {
            var list = photosFromPost($post);
            if (!list[index]) {
                return;
            }
            var photo = list[index];
            var $hero = $post.find('.js-blog-hero');
            $hero.find('.photo-real').attr({ src: photo.src, alt: photo.title || '' });
            $hero.find('.photo-shield').attr({ src: photo.shield || '', alt: '' });
            $hero.attr('aria-label', (photo.title || '') + ' megnyitása');
            $post.attr('data-index', index);
            $post.find('.js-blog-thumb').removeClass('is-active')
                .filter('[data-index="' + index + '"]').addClass('is-active');
            var active = $post.find('.blog-thumbs-track .js-blog-thumb.is-active')[0];
            if (active && active.scrollIntoView) {
                active.scrollIntoView({ behavior: 'smooth', inline: 'nearest', block: 'nearest' });
            }
        }

        function openBlogViewer($post, startIndex) {
            var list = photosFromPost($post);
            if (!list.length) {
                return;
            }
            var index = typeof startIndex === 'number'
                ? startIndex
                : (parseInt($post.attr('data-index'), 10) || 0);
            openViewer(index, list);
        }

        $('.js-blog-hero').on('click', function () {
            openBlogViewer($(this).closest('.blog-post'));
        });

        $('.js-blog-thumb').on('click', function (e) {
            e.preventDefault();
            var $btn = $(this);
            setBlogHero($btn.closest('.blog-post'), parseInt($btn.attr('data-index'), 10) || 0);
        });

        $('[data-thumbs-prev]').on('click', function () {
            var $wrap = $(this).closest('.blog-thumbs-wrap');
            var track = $wrap.find('.blog-thumbs-track')[0];
            if (track) {
                track.scrollBy({ left: -170, behavior: 'smooth' });
            }
        });

        $('[data-thumbs-next]').on('click', function () {
            var $wrap = $(this).closest('.blog-thumbs-wrap');
            var track = $wrap.find('.blog-thumbs-track')[0];
            if (track) {
                track.scrollBy({ left: 170, behavior: 'smooth' });
            }
        });

        $('.blog-thumbs-track').on('scroll', function () {
            updateThumbArrows($(this).closest('.blog-thumbs-wrap'));
        });

        $('.blog-thumbs-wrap').each(function () {
            updateThumbArrows($(this));
        });

        $(window).on('resize.blogThumbs', function () {
            $('.blog-thumbs-wrap').each(function () {
                updateThumbArrows($(this));
            });
        });

        function setupFilterPager(config) {
            var $root = config.$root;
            var $items = config.$items;
            var $chips = config.$chips;
            var $city = config.$city;
            var $count = config.$count;
            var $empty = config.$empty;
            var $pager = $root.find('[data-pager]');
            var $pages = $pager.find('[data-pager-pages]');
            var countLabel = config.countLabel || $count.data('count-label') || 'photo';
            var perPage = parseInt($root.attr('data-per-page'), 10) || 8;
            var page = 1;

            function matchingItems() {
                var tag = $chips.filter('.is-active').data('tag') || '';
                var city = $city.val() || '';
                return $items.filter(function () {
                    var tags = String($(this).data('tags') || '').split(',');
                    var photoCity = String($(this).data('city') || '');
                    var tagOk = !tag || tags.indexOf(tag) !== -1;
                    var cityOk = !city || photoCity === city;
                    return tagOk && cityOk;
                });
            }

            function pageList(totalPages, current) {
                if (totalPages <= 7) {
                    return Array.from({ length: totalPages }, function (_, i) { return i + 1; });
                }
                var pages = [1];
                var start = Math.max(2, current - 1);
                var end = Math.min(totalPages - 1, current + 1);
                if (start > 2) {
                    pages.push('…');
                }
                for (var i = start; i <= end; i++) {
                    pages.push(i);
                }
                if (end < totalPages - 1) {
                    pages.push('…');
                }
                pages.push(totalPages);
                return pages;
            }

            function render() {
                var $match = matchingItems();
                var total = $match.length;
                var totalPages = Math.max(1, Math.ceil(total / perPage) || 1);
                if (page > totalPages) {
                    page = totalPages;
                }
                if (page < 1) {
                    page = 1;
                }

                $items.hide();
                if (total) {
                    $match.slice((page - 1) * perPage, page * perPage).show();
                }

                $count.text(total + ' ' + countLabel);
                $empty.prop('hidden', total > 0);

                if (total === 0 || totalPages <= 1) {
                    $pager.prop('hidden', true);
                    return;
                }

                $pager.prop('hidden', false);
                $pager.find('[data-pager-prev]').prop('disabled', page <= 1);
                $pager.find('[data-pager-next]').prop('disabled', page >= totalPages);

                var html = pageList(totalPages, page).map(function (item) {
                    if (item === '…') {
                        return '<span class="pager-ellipsis">…</span>';
                    }
                    var active = item === page ? ' is-active' : '';
                    return '<button type="button" class="pager-btn pager-num' + active + '" data-page="' + item + '">' + item + '</button>';
                }).join('');
                $pages.html(html);
            }

            $chips.on('click', function () {
                $chips.removeClass('is-active');
                $(this).addClass('is-active');
                page = 1;
                render();
            });
            $city.on('change', function () {
                page = 1;
                render();
            });
            $pager.on('click', '[data-page]', function () {
                page = parseInt($(this).attr('data-page'), 10) || 1;
                render();
                $root[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            $pager.on('click', '[data-pager-prev]', function () {
                if (page > 1) {
                    page -= 1;
                    render();
                    $root[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
            $pager.on('click', '[data-pager-next]', function () {
                page += 1;
                render();
                $root[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            render();
        }

        var $gallery = $('#galleryApp');
        if ($gallery.length) {
            setupFilterPager({
                $root: $gallery,
                $items: $gallery.find('.js-photo'),
                $chips: $gallery.find('.tag-chip'),
                $city: $gallery.find('#cityFilter'),
                $count: $gallery.find('[data-gallery-count]'),
                $empty: $gallery.find('.gallery-empty')
            });
        }

        var $panoApp = $('#panoApp');
        if ($panoApp.length) {
            setupFilterPager({
                $root: $panoApp,
                $items: $panoApp.find('.js-panorama'),
                $chips: $panoApp.find('.tag-chip'),
                $city: $panoApp.find('#panoCityFilter'),
                $count: $panoApp.find('[data-pano-count-label]'),
                $empty: $panoApp.find('.gallery-empty')
            });
        }

        $('[data-viewer-close]').on('click', function (e) {
            e.stopPropagation();
            closeViewer();
        });
        $('[data-viewer-prev]').on('click', function (e) {
            e.stopPropagation();
            show(index - 1);
        });
        $('[data-viewer-next]').on('click', function (e) {
            e.stopPropagation();
            show(index + 1);
        });

        $(document).on('keydown', function (e) {
            if ($viewer.hasClass('is-open')) {
                if (e.key === 'Escape') {
                    closeViewer();
                } else if (e.key === 'ArrowLeft') {
                    show(index - 1);
                } else if (e.key === 'ArrowRight') {
                    show(index + 1);
                }
                return;
            }
            if ($pano.hasClass('is-open')) {
                if (e.key === 'Escape') {
                    closePano();
                } else if (e.key === 'ArrowLeft') {
                    if (e.shiftKey) {
                        showPano(panoIndex - 1);
                    } else {
                        setPanoOffset(panoOffset + Math.round($panoViewport.width() * 0.12));
                    }
                } else if (e.key === 'ArrowRight') {
                    if (e.shiftKey) {
                        showPano(panoIndex + 1);
                    } else {
                        setPanoOffset(panoOffset - Math.round($panoViewport.width() * 0.12));
                    }
                }
            }
        });

        var stage = $viewer.find('.viewer-stage')[0];
        var startX = 0;
        var startY = 0;
        var tracking = false;
        var axis = null;
        var ignoreClick = false;

        $viewer.find('.viewer-frame').on('click', function () {
            if (ignoreClick) {
                ignoreClick = false;
                return;
            }
            show(index + 1);
        });

        stage.addEventListener('touchstart', function (e) {
            if (e.touches.length !== 1) {
                return;
            }
            tracking = true;
            axis = null;
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }, { passive: true });

        stage.addEventListener('touchmove', function (e) {
            if (!tracking) {
                return;
            }
            var dx = e.touches[0].clientX - startX;
            var dy = e.touches[0].clientY - startY;
            if (axis === null && (Math.abs(dx) > 8 || Math.abs(dy) > 8)) {
                axis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
            }
            if (axis === 'x') {
                e.preventDefault();
            }
        }, { passive: false });

        stage.addEventListener('touchend', function (e) {
            if (!tracking) {
                return;
            }
            tracking = false;
            if (axis !== 'x') {
                axis = null;
                return;
            }
            var dx = e.changedTouches[0].clientX - startX;
            axis = null;
            if (Math.abs(dx) < 40) {
                return;
            }
            ignoreClick = true;
            show(dx > 0 ? index - 1 : index + 1);
        }, { passive: true });

        // --- Panoráma néző ---
        var $pano = $('#panoViewer');
        var $panoImg = $pano.find('.pano-image');
        var $panoShield = $pano.find('.pano-shield');
        var $panoTrack = $pano.find('.pano-track');
        var $panoViewport = $pano.find('.pano-viewport');
        var panos = [];
        var panoIndex = 0;
        var panoOffset = 0;
        var panoMin = 0;
        var panoDragging = false;
        var panoStartX = 0;
        var panoStartOffset = 0;

        function layoutPano() {
            var img = $panoImg[0];
            var viewport = $panoViewport[0];
            if (!img || !img.naturalWidth || !viewport) {
                return;
            }
            var vh = viewport.clientHeight;
            var vw = viewport.clientWidth;
            var scale = vh / img.naturalHeight;
            var w = Math.round(img.naturalWidth * scale);
            var h = vh;
            var minW = Math.round(vw * 2);
            if (w < minW) {
                scale = minW / img.naturalWidth;
                w = minW;
                h = Math.round(img.naturalHeight * scale);
            }
            $panoImg.add($panoShield).css({ width: w + 'px', height: h + 'px' });
            $panoTrack.css({ width: w + 'px', height: h + 'px' });
            panoMin = Math.min(0, vw - w);
            var y = Math.round((vh - h) / 2);
            $panoTrack.data('y', y);
            setPanoOffset(panoOffset);
        }

        function setPanoOffset(value) {
            panoOffset = Math.min(0, Math.max(panoMin, value));
            var y = $panoTrack.data('y') || 0;
            $panoTrack.css('transform', 'translate3d(' + panoOffset + 'px, ' + y + 'px, 0)');
        }

        function showPano(i, options) {
            if (!panos.length) {
                return;
            }
            options = options || {};
            panoIndex = (i + panos.length) % panos.length;
            var photo = panos[panoIndex];
            var exif = photo.exif || {};
            panoOffset = 0;
            $panoImg.off('load.pano').one('load.pano', function () {
                layoutPano();
                setPanoOffset(0);
            });
            $panoImg.attr({ src: photo.src, alt: photo.title || '' });
            $panoShield.attr({ src: photo.shield || '', alt: '' });
            if ($panoImg[0].complete && $panoImg[0].naturalWidth) {
                layoutPano();
                setPanoOffset(0);
            }
            $pano.find('[data-pano-count]').text((panoIndex + 1) + ' / ' + panos.length);
            $pano.find('[data-pano-title]').text(photo.title || '');
            $pano.find('[data-pano-desc]').text(photo.description || '');
            var t = window.SITE_I18N || {};
            $pano.find('[data-pano-facts]').html(
                fact(t.id || 'ID', photo.original_name || '') +
                fact(t.location || 'Location', photo.location) +
                fact(t.camera || 'Camera', exif.camera) +
                fact(t.lens || 'Lens', exif.lens) +
                fact(t.shutter || 'Shutter', exif.exposure) +
                fact(t.aperture || 'Aperture', exif.aperture) +
                fact(t.iso || 'ISO', exif.iso) +
                fact(t.focal || 'Focal length', exif.focal) +
                fact(t.date || 'Date', exif.date) +
                fact(t.time || 'Time', exif.time) +
                fact(t.resolution || 'Resolution', exif.dimensions)
            );
            var tags = (photo.tags || []).map(function (tag) {
                return '<span class="viewer-tag">' + $('<div>').text(tag).html() + '</span>';
            }).join('');
            $pano.find('[data-pano-tags]').html(tags);
            updateFacebookShare($pano.find('[data-pano-facebook]'), photo);
            if (!options.skipUrl) {
                setShareUrl(photo, !!options.replaceUrl);
            }
        }

        function openPano(startIndex, list, options) {
            options = options || {};
            panos = list;
            $pano.prop('hidden', false).addClass('is-open');
            $('body').addClass('viewer-open');
            showPano(startIndex, options);
            requestAnimationFrame(layoutPano);
        }

        function closePano(options) {
            options = options || {};
            $pano.removeClass('is-open').prop('hidden', true);
            $('body').removeClass('viewer-open');
            $panoImg.off('load.pano').attr({ src: '', alt: '' }).css({ width: '', height: '' });
            $panoShield.attr({ src: '', alt: '' }).css({ width: '', height: '' });
            $panoTrack.css({ width: '', height: '', transform: '' });
            panoOffset = 0;
            if (!options.skipUrl) {
                setShareUrl(null, !!options.replaceUrl);
            }
        }

        $('.js-panorama').on('click', function () {
            var $items = $('.js-panorama').filter(':visible');
            var list = $items.map(function () {
                return parsePhoto($(this));
            }).get().filter(Boolean);
            openPano($items.index(this), list);
        });

        $('[data-pano-close]').on('click', closePano);
        $('[data-pano-prev]').on('click', function () { showPano(panoIndex - 1); });
        $('[data-pano-next]').on('click', function () { showPano(panoIndex + 1); });

        $(window).on('resize.pano', function () {
            if ($pano.hasClass('is-open')) {
                layoutPano();
            }
        });

        $panoViewport.on('mousedown', function (e) {
            if (e.button !== 0) {
                return;
            }
            panoDragging = true;
            panoStartX = e.clientX;
            panoStartOffset = panoOffset;
            $panoViewport.addClass('is-dragging');
            e.preventDefault();
        });
        $(document).on('mousemove.pano', function (e) {
            if (!panoDragging) {
                return;
            }
            setPanoOffset(panoStartOffset + (e.clientX - panoStartX));
        });
        $(document).on('mouseup.pano', function () {
            panoDragging = false;
            $panoViewport.removeClass('is-dragging');
        });

        $panoViewport.on('wheel', function (e) {
            if (!$pano.hasClass('is-open')) {
                return;
            }
            e.preventDefault();
            var delta = e.originalEvent.deltaY || e.originalEvent.deltaX;
            setPanoOffset(panoOffset - delta);
        });

        var panoTouchId = null;
        $panoViewport[0].addEventListener('touchstart', function (e) {
            if (e.changedTouches.length !== 1) {
                return;
            }
            panoTouchId = e.changedTouches[0].identifier;
            panoDragging = true;
            panoStartX = e.changedTouches[0].clientX;
            panoStartOffset = panoOffset;
            $panoViewport.addClass('is-dragging');
        }, { passive: true });
        $panoViewport[0].addEventListener('touchmove', function (e) {
            if (!panoDragging) {
                return;
            }
            var touch = null;
            for (var i = 0; i < e.changedTouches.length; i++) {
                if (e.changedTouches[i].identifier === panoTouchId) {
                    touch = e.changedTouches[i];
                    break;
                }
            }
            if (!touch) {
                return;
            }
            e.preventDefault();
            setPanoOffset(panoStartOffset + (touch.clientX - panoStartX));
        }, { passive: false });
        $panoViewport[0].addEventListener('touchend', function () {
            panoDragging = false;
            panoTouchId = null;
            $panoViewport.removeClass('is-dragging');
        }, { passive: true });

        function collectVisiblePhotos(selector) {
            return $(selector).filter(':visible').map(function () {
                return parsePhoto($(this));
            }).get().filter(Boolean);
        }

        function indexByUuid(list, uuid) {
            for (var i = 0; i < list.length; i++) {
                if ((list[i].uuid || list[i].id) === uuid) {
                    return i;
                }
            }
            return -1;
        }

        function openPhotoByUuid(uuid, options) {
            if (!uuid) {
                return false;
            }
            options = options || {};
            var panoList = collectVisiblePhotos('.js-panorama');
            var panoPos = indexByUuid(panoList, uuid);
            if (panoPos >= 0) {
                openPano(panoPos, panoList, options);
                return true;
            }
            var photoList = collectVisiblePhotos('.js-photo');
            var photoPos = indexByUuid(photoList, uuid);
            if (photoPos >= 0) {
                openViewer(photoPos, photoList, options);
                return true;
            }
            return false;
        }

        var openUuid = document.body.getAttribute('data-open-photo');
        if (openUuid) {
            openPhotoByUuid(openUuid, { replaceUrl: true });
        }

        $(window).on('popstate.photoShare', function (e) {
            syncingHistory = true;
            var state = e.originalEvent.state || {};
            var uuid = state.photoUuid || null;
            if (!uuid) {
                var match = window.location.pathname.match(/\/foto\/([0-9a-fA-F-]{36})\/?$/);
                uuid = match ? match[1] : null;
            }
            if (uuid) {
                if ($viewer.hasClass('is-open') || $pano.hasClass('is-open')) {
                    var list = $pano.hasClass('is-open') ? panos : photos;
                    var pos = indexByUuid(list, uuid);
                    if (pos >= 0) {
                        if ($pano.hasClass('is-open')) {
                            showPano(pos, { skipUrl: true });
                        } else {
                            show(pos, { skipUrl: true });
                        }
                        syncingHistory = false;
                        return;
                    }
                }
                closeViewer({ skipUrl: true });
                closePano({ skipUrl: true });
                openPhotoByUuid(uuid, { skipUrl: true });
            } else {
                closeViewer({ skipUrl: true });
                closePano({ skipUrl: true });
            }
            syncingHistory = false;
        });
    });
