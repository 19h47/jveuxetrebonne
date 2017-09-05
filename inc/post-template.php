<?php

// Apply filter
add_filter( 'body_class', 'custom_body_class' );

function custom_body_class( $classes ) {
	
	// Home
	if ( is_front_page() ) {
		$classes[] = 'Front-page';
	}


	// Category
	if ( is_category() ) {
		$classes[] = 'Category';
	}


	// Category videos
	if ( in_category( 1335 ) ) {
		$classes[] = 'Category-videos';
	}


	// Category food
	if ( in_category( 445 ) ) {
		$classes[] = 'Category-food';
	}


	// Single post
	if ( is_singular( 'post' ) ) {
		$classes[] = 'Single';
	}


	// About page
	if ( is_page( 1023 ) ) {
		$classes[] = 'About';
	}

	return $classes;
}