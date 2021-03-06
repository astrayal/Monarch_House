<?php
/**
 * Related Products
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.2.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $product, $woocommerce_loop;

if (empty($product) || !$product->exists()) {
    return;
}

$related = wc_get_related_products($product->get_id(), $posts_per_page);

if (sizeof($related) == 0) return;
$args = apply_filters('woocommerce_related_products_args', array(
    'post_type' => 'product',
    'ignore_sticky_posts' => 1,
    'no_found_rows' => 1,
    'posts_per_page' => $posts_per_page,
    'orderby' => $orderby,
    'post__in' => $related,
    'post__not_in' => array($product->get_id())
));

$products = new WP_Query($args);

$woocommerce_loop['columns'] = $columns;

if ($products->have_posts()) : ?>

    <div class="product-related">

            <h3 class="title"><?php esc_html_e('Related Products', 'iwjob'); ?></h3>

		
		<div class="row">
			<?php woocommerce_product_loop_start(); ?>

			<?php while ($products->have_posts()) : $products->the_post(); ?>
				<div class="col-md-4 col-sm-6 col-xs-12 product-related-item">
						<?php wc_get_template_part('content', 'product'); ?>
				</div>
			<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
		</div>
    </div>

<?php endif;

wp_reset_postdata();
