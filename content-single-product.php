<nav>
	<?php
		
	//Save the current product object, to reinstate for other functions after The Loop
	$current_product = $post;

	//Setup the query args - these may look different for you. Here, we're getting all 'product' type posts, under a specific product category (terms under taxonomy 'product_cat' for WooCommerce), sorting them by the SKU custom field, and setting 'nopaging' to true so we can get all of them (and not the first page only).
	$args = array(
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $term->slug,
			),
		),
		'orderby'   => 'meta_value',
		'meta_key'  => '_sku',
		'order'     => 'desc',
		'nopaging'  => 'true',
	);
	$product_counter = new WP_Query($args);

	//Begin The Loop
	if($product_counter->have_posts()) : 
		while($product_counter->have_posts()) : 
			$product_counter->the_post();

			//Only the post from The Loop that matches our current product is useful to us
			if ( $post->ID == $current_product->ID ) {
	?>
	
	//Set up "previous" arrow
	<div class="previous-product-arrow">
		<?php
				//make sure we actualy have a previous product to go to
				if ($product_counter->current_post > 0) {
					$previous_post_ID = $product_counter->posts[$product_counter->current_post - 1]->ID;
		?>
		<a href="<?php echo get_permalink($previous_post_ID); ?>"><i class="fa fa-angle-left"></i></a>
		<?php 
				} 
		?>
	</div>
	
	//Get the order of the current product in the total amount of products eligible for iterating
	<div class="product-iterator"><?php echo ($product_counter->current_post + 1 ).' ΑΠΟ '.$product_counter->found_posts.' '.$term->name; ?></div>
	
	//Set up "next" arrow
	<div class="next-product-arrow">
		<?php
				//make sure we actualy have a next product to go to
				if ( $product_counter->current_post + 1 <= $product_counter->found_posts - 1 ) {
					$next_post_ID = $product_counter->posts[$product_counter->current_post + 1]->ID;
		?>
		<a href="<?php echo get_permalink($next_post_ID); ?>"><i class="fa fa-angle-right"></i></a>
	</div>
		<?php 
					}
				}
				endwhile;
			endif;
			$post = $current_product;
		?>
</nav>
