<?php

/**
 * /component-slider-videos
 *
 * Display the last 5 recent post in the video category
 *
 * @package  	jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (http://19h47.fr)
 */

$context = Timber::get_context();

global $post;

// get last post
$context['posts'] = Timber::get_posts(
	array(
    	'posts_per_page'		=> 5,
		'post_type' 			=> 'post',
		'category__in'			=> 1335,
		'posts_per_page'		=> 5,
		'ignore_sticky_posts' 	=> 1,
		// 'post__not_in' 			=> get_option( 'sticky_posts' ),
	)
);

$post = new TimberPost();
$context['post'] = $post;


Timber::render( 'partials/slider-videos.html.twig', $context );
