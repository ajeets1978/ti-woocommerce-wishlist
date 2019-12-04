<?php
/**
 * TI WooCommerce Wishlist integration with:
 *
 * @name WP Grid Builder
 *
 * @version 1.1.7
 *
 * @slug wp-grid-builder
 *
 * @url https://www.wpgridbuilder.com
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( defined( 'WPGB_VERSION' ) ) {

	/**
	 * Add input, select, option to allowed wp_kses_post tags
	 *
	 * @param array $tags Allowed tags, attributes, and/or entities.
	 * @param string $context Context to judge allowed tags by. Allowed values are 'post'.
	 *
	 * @return array
	 */
	function tinvwl_wpkses_post_tags( $tags, $context ) {
		if ( 'post' === $context ) {
			// form fields - input
			$tags['input'] = array(
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
				'value' => array(),
				'type'  => array(),
			);
			// select
			$tags['select'] = array(
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
				'value' => array(),
				'type'  => array(),
			);
			// select options
			$tags['option'] = array(
				'selected' => array(),
			);
		}

		return $tags;
	}

	add_action( 'wp_grid_builder/card/wrapper_start', 'tinvwl_wpkses_fix' );

	function tinvwl_wpkses_fix() {
		add_filter( 'wp_kses_allowed_html', 'tinvwl_wpkses_post_tags', 10, 2 );
	}

	add_action( 'wp_grid_builder/card/wrapper_end', 'tinvwl_wpkses_fix_remove' );

	function tinvwl_wpkses_fix_remove() {
		remove_filter( 'wp_kses_allowed_html', 'tinvwl_wpkses_post_tags', 10, 2 );
	}

	// Add custom block to show wishlist button.
	function tinvwl_wpgb_block_add_to_wishlist( $blocks ) {
		$blocks['wishlist_button'] = [
			'name'            => __( 'Wishlist Button', 'ti-woocommerce-wishlist' ),
			'render_callback' => 'tinvwl_wpgb_add_to_wishlist',
			'icon'            => TINVWL_URL . '/assets/img/heart-tinv.svg#tinv',
		];

		return $blocks;
	}

	add_filter( 'wp_grid_builder/blocks', 'tinvwl_wpgb_block_add_to_wishlist', 10, 1 );

	// Output wishlist button.
	function tinvwl_wpgb_add_to_wishlist() {

		$post = wpgb_get_post();

		if ( ! isset( $post->post_type ) && "product" !== $post->post_type ) {
			return;
		}

		// Output loop button.
		echo do_shortcode( '[ti_wishlists_addtowishlist product_id="' . $post->ID . '" loop="1"]' );
	}

}
