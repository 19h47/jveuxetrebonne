<?php

/**
 * The Template for displaying all single posts
 *
 *
 * @package  WordPress
 * @subpackage  jveb
 */

$context = Timber::get_context();
$post = Timber::query_post();
$context['post'] = $post;
$context['current_user'] = new Timber\User();

$templates = array( 'singles/index.twig' ); 



// echo '<pre>' . var_dump(get_the_category()) . '</pre>';

$context['categories'] = $post->terms( 'category' );


$categories = get_the_category();
foreach ( $categories as $category ) {

	if ( cat_is_ancestor_of( 445, $category->term_id ) ) {
		
		array_unshift( $templates, 'singles/cat-is-ancestor-of-food.twig' );

	}


	if ( $category->term_id === 445 ) {

		array_unshift( $templates, 'singles/category-food.twig' );
	}
}


Timber::render( $templates, $context );