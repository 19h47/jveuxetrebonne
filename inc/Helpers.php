<?php

namespace JVEB;

class Helpers {
	/**
	 * Get theme manifest
	 *
	 * @return bool|array
	 */
	public static function get_theme_manifest() {
		$request = wp_remote_get( get_template_directory_uri() . '/dist/manifest.json' );
		$body    = wp_remote_retrieve_body( $request );

		return json_decode( $body, true );
	}
}
