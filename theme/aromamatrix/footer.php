<?php
/**
 * Site footer.
 *
 * @package Aromamatrix
 */

$shop_url = class_exists('WooCommerce') ? wc_get_page_permalink('shop') : home_url('/#products');
?>
<footer class="site-footer">
    <div class="site-footer__main aroma-container">
        <div class="footer-brand">
            <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('AROMAMATRIX home', 'aromamatrix'); ?>">
                <img
                    class="footer-brand__logo"
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/aromamatrix-wordmark.svg'); ?>"
                    width="1200"
                    height="300"
                    alt="<?php esc_attr_e('AROMAMATRIX Perfume Factory', 'aromamatrix'); ?>"
                >
            </a>
            <p><?php esc_html_e('An international B2B perfume manufacturing program coordinating private label, OEM and ODM projects from product selection through production and export preparation.', 'aromamatrix'); ?></p>
        </div>

        <div class="footer-column">
            <h2><?php esc_html_e('Products', 'aromamatrix'); ?></h2>
            <ul>
                <li><a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('All Products', 'aromamatrix'); ?></a></li>
                <li><a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Fine Fragrance', 'aromamatrix'); ?></a></li>
                <li><a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Body Fragrance', 'aromamatrix'); ?></a></li>
                <li><a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('Home Fragrance', 'aromamatrix'); ?></a></li>
            </ul>
        </div>

        <div class="footer-column">
            <h2><?php esc_html_e('Services', 'aromamatrix'); ?></h2>
            <ul>
                <li><a href="<?php echo esc_url(home_url('/#private-label')); ?>"><?php esc_html_e('Private Label', 'aromamatrix'); ?></a></li>
                <li><a href="https://www.aromamatrix.com/process#oem"><?php esc_html_e('OEM', 'aromamatrix'); ?></a></li>
                <li><a href="https://www.aromamatrix.com/process#odm"><?php esc_html_e('ODM', 'aromamatrix'); ?></a></li>
                <li><a href="https://www.aromamatrix.com/#packaging-solutions"><?php esc_html_e('Packaging Support', 'aromamatrix'); ?></a></li>
            </ul>
        </div>

        <div class="footer-column footer-column--contact">
            <h2><?php esc_html_e('Project Desk', 'aromamatrix'); ?></h2>
            <p><?php esc_html_e('Guangzhou, China · UTC+8', 'aromamatrix'); ?></p>
            <p><a href="mailto:sales@aromamatrix.com">sales@aromamatrix.com</a></p>
            <p><a href="https://wa.me/8613135123123" target="_blank" rel="noopener noreferrer">WhatsApp +86 131 3512 3123</a></p>
        </div>
    </div>

    <div class="site-footer__bottom aroma-container">
        <p>
            <?php
            printf(
                esc_html__('© %s AROMAMATRIX. All rights reserved.', 'aromamatrix'),
                esc_html(wp_date('Y'))
            );
            ?>
        </p>
        <p><?php esc_html_e('B2B Perfume Manufacturing · ISO 22716 & GMPC', 'aromamatrix'); ?></p>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
