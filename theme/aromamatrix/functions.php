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
});

add_filter('loop_shop_columns', static fn (): int => 4);
add_filter('loop_shop_per_page', static fn (): int => 12);

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
