<?php
/**
 * /component-sticky-post
 *
 * Display last sticky post
 *
 * @package     WordPress
 * @subpackage  jveb
 * @author      Jérémy Levron <levronjeremy@19h47.fr>
 */

$context = Timber::get_context();


// get last post
$context['posts'] = Timber::get_posts( 
    array( 
    	'ignore_sticky_posts' 	=> 1,
        'order'             	=> 'DESC',
        'orderby'           	=> 'date',
        'post_type'         	=> 'post',
    	'post__in'  			=> get_option( 'sticky_posts' ),
        'posts_per_page'    	=> 1
    ) 
);


Timber::render( 'components/sticky-post.twig', $context );