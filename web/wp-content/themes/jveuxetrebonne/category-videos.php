<?php
/**
 * @file category-videos
 * @package JVEB
 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr)
 */

use Timber\{ Timber, Post };

$context = Timber::get_context();

$context['posts'] = Timber::get_posts();

$category = $wp_query->get_queried_object();

$context['category'] = array(
	'term_id'     => $category->term_id,
	'name'        => $category->name,
	'description' => $category->description,
	'slug'        => $category->slug,
);

$count_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => -1,
		'category__in'   => $category->term_id,
	)
);

$context['per_page']    = 9;
$context['found_posts'] = $count_query->found_posts;

$templates = array( 'pages/category-videos.html.twig' );

Timber::render( $templates, $context );
