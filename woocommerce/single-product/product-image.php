<?php
defined('ABSPATH') || exit;

global $product;
if (!$product) return;

$attachment_ids = $product->get_gallery_image_ids();
$featured_id    = $product->get_image_id();

// Zbierz wszystkie slajdy: najpierw okładka, potem galeria
$slides = [];
if ($featured_id) $slides[] = $featured_id;
if ($attachment_ids) $slides = array_merge($slides, $attachment_ids);

// Fallback gdy brak zdjęć
if (empty($slides)) {
    echo '<div class="product-media ">';
    echo
    // tnl_custom_sale_badge() . 
    wc_placeholder_img('full');
    echo '</div>';
    return;
}

?>
<div class="product-media">
    <!-- MAIN -->
    <?php //tnl_custom_sale_badge(); 
    ?>
    <div class="swiper product-gallery js-product-gallery" aria-label="<?php esc_attr_e('Galeria produktu', 'your-textdomain'); ?>">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $i => $id): ?>
                <div class="swiper-slide">
                    <?php
                    // duży obraz z lazy + srcset
                    echo wp_get_attachment_image(
                        $id,
                        'full',
                        false,
                        [
                            'class'          => 'product-gallery__img swiper-lazy',
                            'loading'        => $i === 0 ? 'eager' : 'lazy',
                            'decoding'       => 'async',
                            'data-src'       => wp_get_attachment_image_url($id, 'large'),
                            'data-srcset'    => wp_get_attachment_image_srcset($id, 'large'),
                            'sizes'          => '(min-width: 768px) 600px, 100vw',
                            'fetchpriority'  => $i === 0 ? 'high' : null,
                            'alt'            => trim(get_post_meta($id, '_wp_attachment_image_alt', true)) ?: get_the_title($id),
                        ]
                    );
                    ?>
                    <div class="swiper-lazy-preloader"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination (dynamic bullets) -->
        <div class="swiper-pagination js-gallery-pagination" aria-label="<?php esc_attr_e('Paginacja galerii', 'your-textdomain'); ?>"></div>

        <!-- Arrows -->
        <div class="swiper-button-prev js-gallery-prev" aria-label="<?php esc_attr_e('Poprzednie zdjęcie', 'your-textdomain'); ?>"></div>
        <div class="swiper-button-next js-gallery-next" aria-label="<?php esc_attr_e('Następne zdjęcie', 'your-textdomain'); ?>"></div>
    </div>


</div>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const galleryEl = document.querySelector('.js-product-gallery');
        if (!galleryEl) return;

        const thumbsEl = document.querySelector('.js-product-thumbs');

        // THUMBS
        let thumbsSwiper = null;
        if (thumbsEl) {
            thumbsSwiper = new Swiper(thumbsEl, {
                slidesPerView: 'auto', // auto = dopasuje do szerokości miniaturek
                spaceBetween: 8,
                freeMode: true,
                watchSlidesProgress: true,
                slideToClickedSlide: true,
                a11y: {
                    enabled: true
                }
            });
        }

        // MAIN
        const mainSwiper = new Swiper(galleryEl, {
            slidesPerView: 1,
            spaceBetween: 16,
            loop: false, // zwykle false dla galerii produktu
            preloadImages: false,
            lazy: {
                loadPrevNext: true,
                loadPrevNextAmount: 2
            },
            on: {
                init(swiper) {
                    swiper.slides.forEach((slide) => {
                        slide.querySelectorAll('.swiper-lazy-preloader, .swiper-lazy-preloader-white')
                            .forEach((el) => el.remove());
                    });
                },
                lazyImageLoad(swiper, slideEl) {
                    slideEl.querySelectorAll('.swiper-lazy-preloader, .swiper-lazy-preloader-white')
                        .forEach((el) => el.remove());
                },
            },
            watchSlidesProgress: true,

            // UX
            keyboard: {
                enabled: true,
                onlyInViewport: true
            },
            mousewheel: {
                forceToAxis: true
            },
            grabCursor: true,

            // Pagination z dynamic bullets + numery slajdów
            pagination: {
                el: galleryEl.querySelector('.js-gallery-pagination'),
                clickable: true,
                dynamicBullets: true,
                dynamicMainBullets: 1, // 1–3 w zależności jak chcesz
                renderBullet: (index, className) =>
                    `<span class="${className}"><b>${String(index + 1).padStart(2, '0')}</b></span>`,
            },

            navigation: {
                nextEl: galleryEl.querySelector('.js-gallery-next'),
                prevEl: galleryEl.querySelector('.js-gallery-prev'),
            },

            // Miniaturki jako thumbs
            thumbs: thumbsSwiper ? {
                swiper: thumbsSwiper
            } : undefined,

            // A11y
            a11y: {
                enabled: true,
                containerRoleDescriptionMessage: 'Galeria zdjęć produktu',
                slideRole: 'group',
            },

            // RWD
            breakpoints: {
                768: {
                    spaceBetween: 20
                },
                1024: {
                    spaceBetween: 24
                },
            },

            // RTL (jeśli używasz)
            // direction: document.dir === 'rtl' ? 'rtl' : 'ltr',
        });

        // Fallback: gdy 1 slajd – ukryj paginację/strzałki
        if (mainSwiper.slides.length <= 1) {
            galleryEl.querySelector('.js-gallery-pagination')?.classList.add('is-hidden');
            galleryEl.querySelector('.js-gallery-prev')?.classList.add('is-hidden');
            galleryEl.querySelector('.js-gallery-next')?.classList.add('is-hidden');
            thumbsEl?.classList.add('is-hidden');
        }
    });
</script>
<style>
    .product-media {
        --radius: 16px;
    }

    .product-gallery,
    .product-thumbs {
        width: 100%;
    }

    .product-gallery {
        padding-bottom: 1.5rem;
    }

    .product-gallery .swiper-slide {
        border-radius: var(--radius);
        overflow: hidden;
    }

    .product-gallery__img,
    .product-gallery .product-gallery__img {
        display: block;
        width: 90vmax;
        height: auto;
        aspect-ratio: 4/3;
        object-fit: cover;
    }

    .product-thumbs {
        margin-top: .75rem;
    }

    .product-thumbs .swiper-slide {
        width: 96px;
        height: 72px;
        border-radius: 10px;
        overflow: hidden;
        opacity: .7;
    }

    .product-thumbs .swiper-slide-thumb-active {
        opacity: 1;
        outline: 2px solid var(--color-accent, #ff2e6a);
    }

    .product-thumbs__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .swiper-pagination.js-gallery-pagination {
        bottom: 0;
    }

    /* Strzałki */
    .product-gallery .swiper-button-prev,
    .product-gallery .swiper-button-next {
        width: 40px;
        height: 40px;
        border-radius: 999px;
        backdrop-filter: blur(4px);
        background: rgba(0, 0, 0, .35);
        color: #fff;
        transform: translateY(-50%);
    }

    .product-gallery .swiper-button-prev:after,
    .product-gallery .swiper-button-next:after {
        font-size: 16px;
    }

    @media (max-width: 767px) {

        .product-gallery .swiper-button-prev,
        .product-gallery .swiper-button-next {
            display: none;
        }
    }
</style>