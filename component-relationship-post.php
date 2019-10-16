<?php

/**
 *
 * Display last recent post in same category as the current post
 *
 * @file        component-relationship-post
 * @package     jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (http://19H47.fr)
 */

$context = Timber::get_context();

global $post;

// get last post
$context['posts'] = Timber::get_posts(
    array(
    	'post__not_in' 		=> array( $post->id ),
    	'cat'     			=> get_the_category( $post->id )[0]->cat_ID,
        'order'             => 'DESC',
        'orderby'           => 'date',
        'post_type'         => 'post',
    	'posts_per_page'	=> 5,
    )
);

$context['post'] = new TimberPost();
$context['relationship'] = true;


Timber::render( 'components/relationship-posts.html.twig', $context );
