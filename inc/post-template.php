<?php

// Apply filter
add_filter( 'body_class', 'custom_body_class' );

function custom_body_class( $classes ) {

	if ( is_front_page() ) {
		$classes[] = 'Front-page';
	}

	if ( is_category() ) {
		$classes[] = 'Category';
	}

	if ( in_category( 1445 ) ) {
		$classes[] = 'Category-videos';
	}

	if ( in_category( 445 ) ) {
		$classes[] = 'Category-food';
	}

	if ( is_singular( 'post' ) ) {
		$classes[] = 'Single';
	}

	if ( is_page( 1023 ) ) {
		$classes[] = 'About';
	}

	return $classes;
}
