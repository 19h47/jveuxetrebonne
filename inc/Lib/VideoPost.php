<?php

namespace JVEB\Lib;

use Timber\{ Post };

/**
 * Single post
 */
class VideoPost extends Post {
	public function video() {
		$iframe = get_field( 'video' );

		return $iframe;
	}
}
