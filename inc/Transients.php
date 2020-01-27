<?php
/**
 * Transients class
 *
 * @package JVEB
 */

namespace JVEB;

use Timber\{ Timber };
use WP_Query;

/**
 * Transients class
 */
class Transients {

	/**
	 * Posts
	 *
	 * Get or set posts
	 *
	 * @return $transient
	 */
	public static function posts() : array {
		$transient = get_transient( 'jveb_posts_' . pll_current_language( 'slug' ) );

		if ( $transient ) {
			return $transient;
		}
		$posts = Timber::get_posts(
			array(
				'post_type'      => 'post',
				'posts_per_page' => -1,
			)
		);

		set_transient( 'jveb_posts_' . pll_current_language( 'slug' ), $posts );

		return $posts;
	}

	/**
	 * Get template instagram
	 *
	 * @return $template_instagram
	 */
	public static function get_template_instagram() {
		$transient = get_transient( 'jveb_template_instagram' );

		if ( $transient ) {
			return $transient;
		}

		$template_instagram = Timber::compile(
			'components/instagram-post.html.twig',
			array()
		);

		set_transient( 'jveb_template_instagram', $template_instagram, DAY_IN_SECONDS );
	}

	public static function front_page_posts_count() : string {
		$transient = get_transient( 'jveb_front_page_posts_count_' . pll_current_language( 'slug' ) );

		if ( $transient ) {
			return $transient;
		}

		$posts = new WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => -1,
				'post__not_in'   => get_option( 'sticky_posts' ),
				'post_status'    => 'publish',
				'meta_query'  => array( // phpcs:ignore
					'relation' => 'OR',
					array(
						'key'     => 'exclude_from_front_page',
						'value'   => '',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'exclude_from_front_page',
						'value'   => '0',
						'compare' => '==',
					),
				),
			)
		);

		set_transient( 'jveb_front_page_posts_count_' . pll_current_language( 'slug' ), $posts->found_posts );

		return $posts->found_posts;
	}
}
