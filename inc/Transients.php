<?php
/**
 * Transients class
 *
 * @package JVEB
 */

namespace JVEB;

use Timber\{ Timber };

/**
 * Transients class
 */
class Transients {
	/**
	 * Get posts
	 *
	 * @return $transient
	 */
	public static function get_posts() {
		$transient = get_transient( 'jveb_posts' );

		if ( $transient ) {
			return $transient;
		}

		$posts = Timber::get_posts(
			array(
				'post_type'      => 'post',
				'posts_per_page' => -1,
			)
		);

		set_transient( 'jveb_posts', $posts, DAY_IN_SECONDS );
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
}
