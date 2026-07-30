<?php
/**
 * Site header.
 *
 * @package Aromamatrix
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text" href="#primary">
    <?php esc_html_e('Skip to content', 'aromamatrix'); ?>
</a>
<header class="site-header">
    <div class="site-header__inner">
        <a class="site-branding" href="<?php echo esc_url(home_url('/')); ?>">
            <?php bloginfo('name'); ?>
        </a>
        <nav class="primary-navigation" aria-label="<?php esc_attr_e('Primary menu', 'aromamatrix'); ?>">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
            ]);
            ?>
        </nav>
    </div>
</header>
