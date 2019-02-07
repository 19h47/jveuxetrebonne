<?php
/**
 * @file 		category
 * @package  	jveb
 * @author  	Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::get_context();
$context['posts'] = Timber::get_posts();

$category = $wp_query->get_queried_object();

$childrens = get_terms(
	$category->taxonomy,
	array(
		'parent'		=> $category->term_id,
		'hide_empty' 	=> false
	)
);

// Add ACF thumbnail field to WP_TERM if exist
foreach ($childrens as $children) {
	$thumbnail = get_field( 'thumbnail', $children);

	if ( ! empty( $thumbnail ) ) {

		$children->thumbnail = $thumbnail;
	}
}

// print_r($children);

$context['category'] = array(
	'term_id'		=> $category->term_id,
	'name'			=> $category->name,
	'description'	=> $category->description,
	'slug'			=> $category->slug,
	'children'		=> ! empty( $childrens ) ? $childrens : false
);

$count_query = new WP_Query(
	array(
		'post_type' 			=> 'post',
		'posts_per_page'		=> -1,
		'category__in'			=> $category->term_id

	)
);



$context['post_per_page'] = 9;
$context['found_posts'] = $count_query->found_posts;

$templates = array( 'pages/category.twig' );

Timber::render( $templates, $context );
