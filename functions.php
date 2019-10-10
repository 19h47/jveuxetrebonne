<?php
/**
 * JVEB functions and definitions
 *
 * @see https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package JVEB
 * @since   1.0.0
 *
 * Functions'prefix is jveb_
 */

/**
 * Autoload
 */
require_once get_template_directory() . '/vendor/autoload.php';

use JVEB\App as App;

$app = new App( 'JVEB', wp_get_theme()->Version ); // phpcs:ignore
