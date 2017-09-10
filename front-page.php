<?php
/**
 * /front-page
 * 
 * @package  	WordPress
 * @subpackage  jveuxetrebonne
 * @author  	Jérémy Levron levronjeremy@19h47.fr
 */

if ( ! class_exists( 'Timber' ) ) {
	echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
	return;
}

$post_per_page = 5;
$context = Timber::get_context();
$context['posts'] = Timber::get_posts( 
	array( 
		'post_type' 			=> 'post', 
		// 'offset'				=> 0,
		'posts_per_page'		=> $post_per_page,
		// 'ignore_sticky_posts' 	=> 1,
		'post__not_in' 			=> get_option( 'sticky_posts' ),
		'post_status'		    => 'publish'
	) 
);

$count_query = new WP_Query( 
	array( 
		'post_type' 			=> 'post', 
		'posts_per_page'		=> -1,
		// 'ignore_sticky_posts' 	=> 1,
		'post__not_in' 			=>	get_option( 'sticky_posts' ),
		'post_status'		    => 'publish'

	) 
);

$context['post_per_page'] = $post_per_page;
$context['found_posts'] = $count_query->found_posts;


$templates = array( 'pages/front-page.twig' );

Timber::render( $templates, $context );