<?php
/**
 * /component-relationship-post
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

    	'post__not_in' 		=> array( $post->id ),
    	'cat'     			=> get_the_category($post->id)[0]->cat_ID,
        'order'             => 'DESC',
        'orderby'           => 'date',
        'post_type'         => 'post',
    	'posts_per_page'	=> 5,
    ) 
);

$context['post'] = new TimberPost();
$context['relationship'] = true;


Timber::render( 'components/relationship-posts.twig', $context );