<?php
/**
 * Storefront landing page.
 *
 * @package Aromamatrix
 */

get_header();

$woocommerce_ready = class_exists('WooCommerce');
$shop_url = $woocommerce_ready ? wc_get_page_permalink('shop') : '#products';
$categories = [];
$products = [];

if ($woocommerce_ready) {
    $categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'number'     => 4,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ]);

    if (is_wp_error($categories)) {
        $categories = [];
    }

    $products = wc_get_products([
        'status'  => 'publish',
        'limit'   => 8,
        'orderby' => 'date',
        'order'   => 'DESC',
    ]);
}

$fallback_categories = [
    [
        'name'        => __('Fine Fragrance', 'aromamatrix'),
        'description' => __('Eau de parfum, eau de toilette and extrait formats.', 'aromamatrix'),
    ],
    [
        'name'        => __('Body Fragrance', 'aromamatrix'),
        'description' => __('Body mist and lighter fragrance formats.', 'aromamatrix'),
    ],
    [
        'name'        => __('Home Fragrance', 'aromamatrix'),
        'description' => __('Room spray and coordinated scent collections.', 'aromamatrix'),
    ],
    [
        'name'        => __('Packaging Solutions', 'aromamatrix'),
        'description' => __('Bottles, pumps, caps, decoration and boxes.', 'aromamatrix'),
    ],
];
?>
<main id="primary" class="site-main site-main--wide">
    <section class="home-hero">
        <div class="home-hero__inner aroma-container">
            <div class="home-hero__copy">
                <span class="section-kicker"><?php esc_html_e('Private Label Perfume Collection', 'aromamatrix'); ?></span>
                <h1><?php esc_html_e('A collection built for your name.', 'aromamatrix'); ?></h1>
                <p class="home-hero__lead">
                    <?php esc_html_e('Explore production-ready fragrance formats, bottles and packaging directions—then customize the scent, decoration and presentation for your market.', 'aromamatrix'); ?>
                </p>
                <div class="home-hero__actions">
                    <a class="aroma-button aroma-button--light aroma-button--arrow" href="<?php echo esc_url($shop_url); ?>">
                        <?php esc_html_e('Explore Products', 'aromamatrix'); ?>
                    </a>
                    <a class="aroma-button" href="https://www.aromamatrix.com/contact">
                        <?php esc_html_e('Request a Quote', 'aromamatrix'); ?>
                    </a>
                </div>
                <div class="home-hero__proof">
                    <span><?php esc_html_e('Typical MOQ from 500 pcs', 'aromamatrix'); ?></span>
                    <span><?php esc_html_e('Samples in 5–7 days', 'aromamatrix'); ?></span>
                    <span><?php esc_html_e('Worldwide export support', 'aromamatrix'); ?></span>
                </div>
            </div>
            <div class="bottle-stage" aria-hidden="true">
                <span class="hero-bottle hero-bottle--three"></span>
                <span class="hero-bottle hero-bottle--two"></span>
                <span class="hero-bottle hero-bottle--one"></span>
            </div>
        </div>
    </section>

    <section class="trust-strip" aria-label="<?php esc_attr_e('Manufacturing capabilities', 'aromamatrix'); ?>">
        <div class="trust-strip__inner aroma-container">
            <div class="trust-item"><strong>10,000+</strong><span><?php esc_html_e('Formula Library', 'aromamatrix'); ?></span></div>
            <div class="trust-item"><strong>6,000+ m²</strong><span><?php esc_html_e('Factory Area', 'aromamatrix'); ?></span></div>
            <div class="trust-item"><strong>ISO 22716</strong><span><?php esc_html_e('Quality System', 'aromamatrix'); ?></span></div>
            <div class="trust-item"><strong>Global</strong><span><?php esc_html_e('DG Shipping Support', 'aromamatrix'); ?></span></div>
        </div>
    </section>

    <section class="home-section home-section--white">
        <div class="aroma-container">
            <div class="section-heading">
                <div>
                    <span class="section-kicker"><?php esc_html_e('Build Your Range', 'aromamatrix'); ?></span>
                    <h2><?php esc_html_e('Choose a product direction.', 'aromamatrix'); ?></h2>
                </div>
                <p><?php esc_html_e('Start with a proven product format and adapt the fragrance, components and finish to create a coherent brand collection.', 'aromamatrix'); ?></p>
            </div>

            <div class="collection-grid">
                <?php if ($categories) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <?php
                        $thumbnail_id = (int) get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image = $thumbnail_id ? wp_get_attachment_image($thumbnail_id, 'aromamatrix-collection', false, ['class' => 'collection-card__image']) : '';
                        ?>
                        <a class="collection-card" href="<?php echo esc_url(get_term_link($category)); ?>">
                            <?php echo $image ? wp_kses_post($image) : '<span class="collection-card__placeholder" aria-hidden="true"></span>'; ?>
                            <span class="collection-card__overlay">
                                <span class="collection-card__count">
                                    <?php
                                    printf(
                                        esc_html(_n('%s product', '%s products', $category->count, 'aromamatrix')),
                                        esc_html(number_format_i18n($category->count))
                                    );
                                    ?>
                                </span>
                                <h3><?php echo esc_html($category->name); ?></h3>
                                <span class="collection-card__link"><?php esc_html_e('View Collection →', 'aromamatrix'); ?></span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach ($fallback_categories as $category) : ?>
                        <a class="collection-card" href="<?php echo esc_url($shop_url); ?>">
                            <span class="collection-card__placeholder" aria-hidden="true"></span>
                            <span class="collection-card__overlay">
                                <span class="collection-card__count"><?php echo esc_html($category['description']); ?></span>
                                <h3><?php echo esc_html($category['name']); ?></h3>
                                <span class="collection-card__link"><?php esc_html_e('Explore Products →', 'aromamatrix'); ?></span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="products" class="home-section">
        <div class="aroma-container">
            <div class="section-heading">
                <div>
                    <span class="section-kicker"><?php esc_html_e('Latest Products', 'aromamatrix'); ?></span>
                    <h2><?php esc_html_e('Production-ready ideas.', 'aromamatrix'); ?></h2>
                </div>
                <a class="aroma-button aroma-button--outline aroma-button--arrow" href="<?php echo esc_url($shop_url); ?>">
                    <?php esc_html_e('View All', 'aromamatrix'); ?>
                </a>
            </div>

            <div class="product-showcase">
                <?php if ($products) : ?>
                    <?php foreach ($products as $product) : ?>
                        <article class="aroma-product">
                            <a class="aroma-product__media" href="<?php echo esc_url($product->get_permalink()); ?>">
                                <?php if ($product->is_featured()) : ?>
                                    <span class="aroma-product__badge"><?php esc_html_e('Featured', 'aromamatrix'); ?></span>
                                <?php endif; ?>
                                <?php
                                $image_id = $product->get_image_id();
                                echo $image_id
                                    ? wp_kses_post(wp_get_attachment_image($image_id, 'aromamatrix-product-card'))
                                    : '<span class="aroma-product__placeholder" aria-hidden="true"></span>';
                                ?>
                            </a>
                            <span class="aroma-product__category">
                                <?php echo wp_kses_post(wc_get_product_category_list($product->get_id(), ', ')); ?>
                            </span>
                            <h3><a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a></h3>
                            <div class="aroma-product__meta">
                                <span>
                                    <?php
                                    echo esc_html(
                                        'bottle' === aromamatrix_get_product_kind($product)
                                            ? __('Bottle sample', 'aromamatrix')
                                            : __('Fragrance sample', 'aromamatrix')
                                    );
                                    ?>
                                </span>
                                <?php if ($product->get_price_html()) : ?>
                                    <span class="price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="catalog-empty">
                        <h3><?php esc_html_e('Your product catalogue is ready for its first collection.', 'aromamatrix'); ?></h3>
                        <p><?php esc_html_e('Add products in WooCommerce with an English title, category, image and description. They will appear here automatically.', 'aromamatrix'); ?></p>
                        <?php if (current_user_can('edit_products')) : ?>
                            <a class="aroma-button" href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>"><?php esc_html_e('Add First Product', 'aromamatrix'); ?></a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section id="private-label" class="service-banner">
        <div class="service-banner__media" role="img" aria-label="<?php esc_attr_e('Private label perfume collection', 'aromamatrix'); ?>"></div>
        <div class="service-banner__content">
            <span class="section-kicker"><?php esc_html_e('Private Label Manufacturing', 'aromamatrix'); ?></span>
            <h2><?php esc_html_e('From selected product to branded collection.', 'aromamatrix'); ?></h2>
            <p><?php esc_html_e('Use the catalogue as a starting point. Our factory team coordinates fragrance direction, component sourcing, decoration, filling, packing and export preparation.', 'aromamatrix'); ?></p>
            <ul class="service-list">
                <li><?php esc_html_e('Fragrance selection', 'aromamatrix'); ?></li>
                <li><?php esc_html_e('Bottle and pump sourcing', 'aromamatrix'); ?></li>
                <li><?php esc_html_e('Logo and surface decoration', 'aromamatrix'); ?></li>
                <li><?php esc_html_e('Labels and outer packaging', 'aromamatrix'); ?></li>
                <li><?php esc_html_e('Filling and quality control', 'aromamatrix'); ?></li>
                <li><?php esc_html_e('DG export coordination', 'aromamatrix'); ?></li>
            </ul>
            <div>
                <a class="aroma-button aroma-button--light aroma-button--arrow" href="https://www.aromamatrix.com/contact">
                    <?php esc_html_e('Start a Project', 'aromamatrix'); ?>
                </a>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
