<?php
/**
 * Theme setup, assets, and storefront presentation.
 *
 * @package Aromamatrix
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', static function (): void {
    load_theme_textdomain('aromamatrix', get_template_directory() . '/languages');

    add_theme_support('automatic-feed-links');
    add_theme_support('custom-logo', [
        'height'      => 52,
        'width'       => 52,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('title-tag');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');

    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('wc-product-gallery-zoom');

    register_nav_menus([
        'primary' => __('Primary menu', 'aromamatrix'),
        'footer'  => __('Footer menu', 'aromamatrix'),
    ]);
});

add_action('wp_enqueue_scripts', static function (): void {
    $stylesheet_path = get_stylesheet_directory() . '/style.css';
    $woocommerce_path = get_template_directory() . '/assets/css/woocommerce.css';
    $script_path = get_template_directory() . '/assets/js/theme.js';

    wp_enqueue_style(
        'aromamatrix',
        get_stylesheet_uri(),
        [],
        is_file($stylesheet_path) ? (string) filemtime($stylesheet_path) : (string) wp_get_theme()->get('Version')
    );

    if (class_exists('WooCommerce') && is_file($woocommerce_path)) {
        wp_enqueue_style(
            'aromamatrix-woocommerce',
            get_template_directory_uri() . '/assets/css/woocommerce.css',
            ['aromamatrix'],
            (string) filemtime($woocommerce_path)
        );
    }

    if (is_file($script_path)) {
        wp_enqueue_script(
            'aromamatrix-theme',
            get_template_directory_uri() . '/assets/js/theme.js',
            [],
            (string) filemtime($script_path),
            true
        );
    }
});

/**
 * Render a useful English navigation before a WordPress menu is assigned.
 */
function aromamatrix_fallback_menu(): void
{
    $shop_url = class_exists('WooCommerce') ? wc_get_page_permalink('shop') : home_url('/#products');
    ?>
    <ul class="menu">
        <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'aromamatrix'); ?></a></li>
        <li><a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Products', 'aromamatrix'); ?></a></li>
        <li><a href="<?php echo esc_url(home_url('/#private-label')); ?>"><?php esc_html_e('Private Label', 'aromamatrix'); ?></a></li>
        <li><a href="https://www.aromamatrix.com/about"><?php esc_html_e('About the Factory', 'aromamatrix'); ?></a></li>
        <li><a href="https://www.aromamatrix.com/contact"><?php esc_html_e('Contact', 'aromamatrix'); ?></a></li>
    </ul>
    <?php
}

add_filter('woocommerce_enqueue_styles', static function (array $styles): array {
    unset($styles['woocommerce-general']);
    unset($styles['woocommerce-layout']);
    unset($styles['woocommerce-smallscreen']);

    return $styles;
});

add_action('wp', static function (): void {
    if (! class_exists('WooCommerce')) {
        return;
    }

    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    add_action('woocommerce_single_product_summary', 'aromamatrix_template_product_eyebrow', 4);
    add_action('woocommerce_single_product_summary', 'aromamatrix_template_sample_purchase_panel', 29);
    add_action('woocommerce_single_product_summary', 'aromamatrix_template_single_product_meta', 40);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_after_single_product_summary', 'aromamatrix_template_single_product_details', 10);
});

/**
 * Resolve the presentation type for fragrance and bottle catalog products.
 */
function aromamatrix_get_product_kind(WC_Product $product): string
{
    $terms = get_the_terms($product->get_id(), 'product_cat');

    if (! is_array($terms)) {
        return 'product';
    }

    foreach ($terms as $term) {
        if ('perfume-bottle' === $term->slug) {
            return 'bottle';
        }

        $ancestors = get_ancestors($term->term_id, 'product_cat', 'taxonomy');
        foreach ($ancestors as $ancestor_id) {
            $ancestor = get_term($ancestor_id, 'product_cat');
            if ($ancestor instanceof WP_Term && 'perfume-bottle' === $ancestor->slug) {
                return 'bottle';
            }
        }
    }

    return 'fragrance';
}

/**
 * Add a concise category cue above the product title.
 */
function aromamatrix_template_product_eyebrow(): void
{
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }

    $kind = aromamatrix_get_product_kind($product);
    $label = 'bottle' === $kind ? __('Perfume Bottle', 'aromamatrix') : __('Fragrance Direction', 'aromamatrix');
    ?>
    <span class="product-kind product-kind--<?php echo esc_attr($kind); ?>"><?php echo esc_html($label); ?></span>
    <?php
}

/**
 * Explain the sample purchase action, or show its setup state when no price exists.
 */
function aromamatrix_template_sample_purchase_panel(): void
{
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }

    $kind = aromamatrix_get_product_kind($product);
    $is_bottle = 'bottle' === $kind;
    ?>
    <div class="sample-purchase-panel<?php echo $product->is_purchasable() ? '' : ' sample-purchase-panel--pending'; ?>">
        <span class="sample-purchase-panel__label">
            <?php echo esc_html($is_bottle ? __('Bottle Sample', 'aromamatrix') : __('Fragrance Sample', 'aromamatrix')); ?>
        </span>
        <strong>
            <?php
            echo esc_html(
                $product->is_purchasable()
                    ? ($is_bottle ? __('Review the component before bulk selection.', 'aromamatrix') : __('Evaluate the scent before project development.', 'aromamatrix'))
                    : __('Online sample ordering is being prepared.', 'aromamatrix')
            );
            ?>
        </strong>
        <p>
            <?php
            echo esc_html(
                $is_bottle
                    ? __('One empty bottle sample is supplied per cart item unless stated otherwise.', 'aromamatrix')
                    : __('One standard fragrance evaluation sample is supplied per cart item.', 'aromamatrix')
            );
            ?>
        </p>
        <?php if (! $product->is_purchasable()) : ?>
            <a class="sample-purchase-panel__link" href="https://www.aromamatrix.com/contact?request=samples#inquiry-form">
                <?php esc_html_e('Request Sample Availability →', 'aromamatrix'); ?>
            </a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Show the public catalog SKU and buyer-useful product navigation.
 */
function aromamatrix_template_single_product_meta(): void
{
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }
    ?>
    <div class="product_meta">
        <?php do_action('woocommerce_product_meta_start'); ?>

        <?php if ($product->get_sku()) : ?>
            <span class="sku_wrapper">
                <?php esc_html_e('SKU:', 'woocommerce'); ?>
                <span class="sku"><?php echo esc_html($product->get_sku()); ?></span>
            </span>
        <?php endif; ?>

        <?php
        echo wc_get_product_category_list(
            $product->get_id(),
            ', ',
            '<span class="posted_in">' . _n(
                'Category:',
                'Categories:',
                count($product->get_category_ids()),
                'woocommerce'
            ) . ' ',
            '</span>'
        ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        echo wc_get_product_tag_list(
            $product->get_id(),
            ', ',
            '<span class="tagged_as">' . _n(
                'Tag:',
                'Tags:',
                count($product->get_tag_ids()),
                'woocommerce'
            ) . ' ',
            '</span>'
        ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>

        <?php do_action('woocommerce_product_meta_end'); ?>
    </div>
    <?php
}

/**
 * Present the description and fragrance attributes as one continuous section.
 */
function aromamatrix_template_single_product_details(): void
{
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }

    $description = $product->get_description();
    $has_attributes = $product->has_attributes();
    $kind = aromamatrix_get_product_kind($product);
    $details_title = 'bottle' === $kind ? __('Bottle Details', 'aromamatrix') : __('Fragrance Details', 'aromamatrix');
    $attributes_title = 'bottle' === $kind ? __('Bottle Specifications', 'aromamatrix') : __('Fragrance Attributes', 'aromamatrix');

    if (! $description && ! $has_attributes) {
        return;
    }
    ?>
    <section class="aromamatrix-product-details aromamatrix-product-details--<?php echo esc_attr($kind); ?>" aria-labelledby="aromamatrix-product-details-title">
        <div class="aromamatrix-product-details__heading">
            <span><?php esc_html_e('Product Evaluation', 'aromamatrix'); ?></span>
            <h2 id="aromamatrix-product-details-title"><?php echo esc_html($details_title); ?></h2>
        </div>

        <div class="aromamatrix-product-details__grid">
            <?php if ($description) : ?>
                <div class="aromamatrix-product-details__description">
                    <?php echo wp_kses_post(apply_filters('the_content', $description)); ?>
                </div>
            <?php endif; ?>

            <?php if ($has_attributes) : ?>
                <aside class="aromamatrix-product-details__attributes" aria-label="<?php echo esc_attr($attributes_title); ?>">
                    <h3><?php echo esc_html($attributes_title); ?></h3>
                    <?php wc_display_product_attributes($product); ?>
                </aside>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

add_filter('woocommerce_product_single_add_to_cart_text', static function (string $text): string {
    global $product;

    if (! $product instanceof WC_Product) {
        return $text;
    }

    return 'bottle' === aromamatrix_get_product_kind($product)
        ? __('Add Bottle Sample', 'aromamatrix')
        : __('Add Sample to Cart', 'aromamatrix');
});

add_filter('woocommerce_product_add_to_cart_text', static function (string $text, WC_Product $product): string {
    if (! $product->is_purchasable()) {
        return __('View Details', 'aromamatrix');
    }

    return 'bottle' === aromamatrix_get_product_kind($product)
        ? __('Add Bottle Sample', 'aromamatrix')
        : __('Add Sample', 'aromamatrix');
}, 10, 2);

add_filter('woocommerce_add_to_cart_fragments', static function (array $fragments): array {
    ob_start();
    ?>
    <span class="header-cart__count"><?php echo esc_html((string) WC()->cart->get_cart_contents_count()); ?></span>
    <?php
    $fragments['.header-cart__count'] = (string) ob_get_clean();

    return $fragments;
});

add_filter('loop_shop_columns', static fn (): int => 4);
add_filter('loop_shop_per_page', static fn (): int => 12);

remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_price', 20);

add_action('woocommerce_before_shop_loop_item_title', static function (): void {
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }

    $kind = aromamatrix_get_product_kind($product);
    $label = 'bottle' === $kind ? __('Bottle', 'aromamatrix') : __('Fragrance', 'aromamatrix');
    ?>
    <span class="loop-product-kind loop-product-kind--<?php echo esc_attr($kind); ?>"><?php echo esc_html($label); ?></span>
    <?php
}, 9);

add_action('woocommerce_after_shop_loop_item_title', static function (): void {
    global $product;

    if (! $product instanceof WC_Product) {
        return;
    }

    $sku = $product->get_sku();

    if ('' === $sku) {
        return;
    }
    ?>
    <span class="loop-product-sku"><?php echo esc_html(sprintf(__('SKU: %s', 'aromamatrix'), $sku)); ?></span>
    <?php
}, 7);

add_filter('woocommerce_output_related_products_args', static function (array $args): array {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;

    return $args;
});

add_filter('woocommerce_breadcrumb_defaults', static function (array $defaults): array {
    $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'aromamatrix') . '">';
    $defaults['wrap_after'] = '</nav>';

    return $defaults;
});

add_filter('excerpt_more', static fn (): string => '…');

add_action('after_setup_theme', static function (): void {
    add_image_size('aromamatrix-product-card', 720, 860, true);
    add_image_size('aromamatrix-collection', 900, 1120, true);
});
