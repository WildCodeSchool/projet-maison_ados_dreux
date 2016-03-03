<?php

add_filter( 'woocommerce_product_tabs', 'EWD_UFAQ_Woo_FAQ_Tab' );
function EWD_UFAQ_Woo_FAQ_Tab( $tabs ) {
	global $product;

	$WooCommerce_FAQs = get_option("EWD_UFAQ_WooCommerce_FAQs");

	$Product_Post = get_post($product->get_id());
	$Category = get_term_by('name', $Product_Post->post_title, 'ufaq-category');
	
	$args = array(
		'post_type' => 'ufaq',
		'post_count' => 2,
		'tax_query' => array(
			'taxonomy' => 'ufaq-category',
			'field' => 'name',
			'terms' => $Category->slug
		)
	);

	$Posts = get_posts($args);
	
	if (sizeOf($Posts) > 0 and $WooCommerce_FAQs == "Yes") {

		$tabs['faq_tab'] = array(
			'title' 	=> __( 'FAQs', 'EWD_UFAQ' ),
			'priority' 	=> 50,
			'callback' 	=> 'EWD_UFAQ_Woo_FAQ_Tab_Content'
		);
	
		return $tabs;
	}

}

function EWD_UFAQ_Woo_FAQ_Tab_Content() {

	global $product;
	
	$Product_Post = get_post($product->get_id());
	$Category = get_term_by('name', $Product_Post->post_title, 'ufaq-category');

	echo '<h2>FAQs</h2>';
	echo do_shortcode("[ultimate-faqs include_category='". $Category->slug . "']");;
	
}

?>