<?php
/**
 * Site header.
 *
 * @package Aromamatrix
 */

$cart_count = class_exists('WooCommerce') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#111111">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#primary">
    <?php esc_html_e('Skip to content', 'aromamatrix'); ?>
</a>
<div class="site-notice">
    <?php esc_html_e('Private Label · OEM · ODM · Global Dangerous-Goods Shipping Support', 'aromamatrix'); ?>
</div>
<header class="site-header">
    <div class="site-header__inner aroma-container">
        <a class="site-branding" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('AROMAMATRIX home', 'aromamatrix'); ?>">
            <img
                class="site-branding__symbol"
                src="https://www.aromamatrix.com/assets/images/brand/aromamatrix-logo-square.png"
                width="52"
                height="52"
                alt=""
            >
            <span class="site-branding__copy">
                <span class="site-branding__name">AROMAMATRIX</span>
                <span class="site-branding__tagline"><?php esc_html_e('B2B Perfume Manufacturing', 'aromamatrix'); ?></span>
            </span>
        </a>

        <div class="header-actions">
            <nav id="site-navigation" class="primary-navigation" aria-label="<?php esc_attr_e('Primary menu', 'aromamatrix'); ?>">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => 'aromamatrix_fallback_menu',
                    'depth'          => 1,
                ]);
                ?>
            </nav>

            <?php if (class_exists('WooCommerce')) : ?>
                <a class="header-cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="<?php esc_attr_e('View cart', 'aromamatrix'); ?>">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h2l1.5 10h9.8l1.7-7H7M9 20a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm8 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"/></svg>
                    <span class="header-cart__count"><?php echo esc_html((string) $cart_count); ?></span>
                </a>
            <?php endif; ?>

            <button class="menu-toggle" type="button" aria-controls="site-navigation" aria-expanded="false">
                <span></span><span></span><span></span>
                <span class="screen-reader-text"><?php esc_html_e('Open menu', 'aromamatrix'); ?></span>
            </button>
        </div>
    </div>
</header>
