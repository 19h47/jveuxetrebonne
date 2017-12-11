<?php
/**
 * /category-videos
 *
 * @package  	WordPress
 * @subpackage  jveuxetrebonne
 * @author  	Jérémy Levron levronjeremy@19h47.fr
 */

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$context = Timber::get_context();
$context['posts'] = Timber::get_posts();

$category = $wp_query->get_queried_object();

$context['category'] = array(
	'term_id'		=> $category->term_id,
	'name'			=> $category->name,
	'description'	=> $category->description,
	'slug'			=> $category->slug
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

$templates = array( 'pages/category-videos.twig' );

Timber::render( $templates, $context );