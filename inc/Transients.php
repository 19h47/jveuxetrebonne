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
}
