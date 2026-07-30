<?php
/**
 * WooCommerce wrapper.
 *
 * @package Aromamatrix
 */

get_header();
?>
<main id="primary" class="site-main shop-main">
    <?php woocommerce_content(); ?>
</main>
<?php
get_footer();
