<?php // phpcs:ignore
/**
 * Class Admin
 *
 * @package JVEB
 */

namespace JVEB;

/**
 * Admin class
 *
 * @author  Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr/)
 */
class Admin {

	/**
	 * The unique identifier of this theme.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var string $plugin_name The string used to uniquely identify this theme.
	 */
	protected $theme_name;


	/**
	 * The version of the theme.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this theme.
	 */
	private $theme_version;


	/**
	 * Construct function
	 *
	 * @param string $theme_name The theme name.
	 * @param string $theme_version The theme version.
	 *
	 * @access public
	 */
	public function __construct( string $theme_name, string $theme_version ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;

		add_filter( 'admin_footer_text', array( $this, 'set_admin_footer_text' ) );
	}


	/**
	 * Set admin footer text
	 *
	 * @see https://developer.wordpress.org/reference/hooks/admin_footer_text/
	 * @author Jérémy Levron <jeremylevron@19h47.fr> (https://19h47.fr/)
	 * @return string $content The content that will be printed.
	 */
	public function set_admin_footer_text() {
		return __( 'Thank you for creating with <a href="https://www.19h47.fr/" target="_blank">19h47</a> and <a href="http://www.lesindiens.fr/" target="_blank">Les Indiens</a>. ✌️', 'JVEB' );
	}
}
