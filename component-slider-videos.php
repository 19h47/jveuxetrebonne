<?php

/**
 * /component-slider-videos
 *
 * Display last recent post
 *
 * @package     WordPress
 * @subpackage  jveb
 * @author      Jérémy Levron <levronjeremy@19h47.fr>
 */

$context = Timber::get_context();

global $post;


// get last post
$context['posts'] = Timber::get_posts(
	array( 
    	'posts_per_page'		=> 5,
		'post_type' 			=> 'post', 
		'cat'					=> 1335,
		'posts_per_page'		=> $post_per_page,
		'ignore_sticky_posts' 	=> 1,
		// 'post__not_in' 			=> get_option( 'sticky_posts' ),
	) 	
);

$context['post'] = new TimberPost();


Timber::render( 'partials/slider-videos.twig', $context );
