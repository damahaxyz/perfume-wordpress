<?php
/**
 * Site footer.
 *
 * @package Aromamatrix
 */
?>
<footer class="site-footer">
    <div class="site-footer__inner">
        <?php
        printf(
            /* translators: %s: current year. */
            esc_html__('© %s AROMAMATRIX. All rights reserved.', 'aromamatrix'),
            esc_html(wp_date('Y'))
        );
        ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
