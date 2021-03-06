<?php
/**
 * Display last recent post in same category as the current post
 *
 * @package     jveb
 * @author      Jérémy Levron <jeremylevron@19h47.fr> (https://19H47.fr)
 */

use Timber\{ Timber, Post, Helper };

$context = Timber::get_context();

$current_post_id = get_queried_object_id();

$context['posts'] = Helper::transient(
	"jveb_relationship_post_$current_post_id",
	function() use ( $current_post_id ) {
		$relationship_post = Timber::get_posts(
			array(
				'post__not_in'   => array( $current_post_id ),
				'cat'            => join(
					array_map(
						function ( $category ) {
							return $category->cat_ID;
						},
						get_the_category( $current_post_id )
					),
					', '
				),
				'order'          => 'DESC',
				'orderby'        => 'date',
				'post_type'      => 'post',
				'posts_per_page' => 5,
			)
		);

		return $relationship_post;
	},
);

$context['post']         = new Post();
$context['relationship'] = true;


Timber::render( 'components/relationship-posts.html.twig', $context );
