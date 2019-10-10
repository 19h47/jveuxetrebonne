<?php

/**
 * The Template for displaying all single posts
 *
 * @file 		single
 * @package  	jveb
 * @author 		Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

use Timber\{ Timber, Post, User };

$context = Timber::get_context();

$context['post']         = new Post();
$context['current_user'] = new User();

$templates = array( 'singles/index.twig' );

$context['categories'] = $context['post']->terms( 'category' );


$categories = get_the_category();
$context['is_ancestor_of_food'] = false;
foreach ( $categories as $category ) {

	if ( cat_is_ancestor_of( 445, $category->term_id ) ) {
		$context['is_ancestor_of_food'] = true;
		array_unshift( $templates, 'singles/cat-is-ancestor-of-food.twig' );
	}


	if ( 445 === $category->term_id ) {
		array_unshift( $templates, 'singles/category-food.twig' );
	}
}


Timber::render( $templates, $context );
