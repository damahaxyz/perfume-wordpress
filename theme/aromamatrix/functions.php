<?php
/**
 * Theme setup and assets.
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
    add_theme_support('custom-logo');
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
    $theme = wp_get_theme();
    $version = $theme->get('Version');
    $stylesheet_path = get_stylesheet_directory() . '/style.css';
    $woocommerce_path = get_template_directory() . '/assets/css/woocommerce.css';

    if (is_file($stylesheet_path)) {
        $version = (string) filemtime($stylesheet_path);
    }

    wp_enqueue_style(
        'aromamatrix',
        get_stylesheet_uri(),
        [],
        $version
    );

    if (class_exists('WooCommerce') && is_file($woocommerce_path)) {
        wp_enqueue_style(
            'aromamatrix-woocommerce',
            get_template_directory_uri() . '/assets/css/woocommerce.css',
            ['aromamatrix'],
            (string) filemtime($woocommerce_path)
        );
    }
});
