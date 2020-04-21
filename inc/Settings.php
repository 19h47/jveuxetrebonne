<?php // phpcs:ignore
/**
 * Settings
 *
 * @package JVEB
 */

namespace JVEB;

/**
 * Settings
 *
 * @package JVEB
 */
class Settings {

	/**
	 * The unique identifier of this theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this theme.
	 */
	protected $theme_name;

	/**
	 * The version of the theme.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this theme.
	 */
	protected $theme_version;

	/**
	 * Construct function
	 *
	 * @param string $theme_name    The theme name.
	 * @param string $theme_version The the version.
	 * @access public
	 */
	public function __construct( string $theme_name, string $theme_version ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'init', array( $this, 'register_settings' ) );
	}


	/**
	 * Settings api init
	 *
	 * @return void
	 * @access public
	 * @see https://codex.wordpress.org/Function_Reference/add_settings_field
	 */
	public function init() : void {
		// Add the field with the names and function to use for our new
		// settings, put it in our new section.
		add_settings_field(
			'posts_on_front_page',
			__( 'Sur la page d\'accueil, montrer les articles', 'jveuxetrebonne' ),
			array( $this, 'field_callback_function' ),
			'reading',
			'default',
			array(
				'name'  => 'posts_on_front_page',
				'label' => __( 'Montrer les articles sur la page d\'accueil', 'jveuxetrebonne' ),
			)
		);
	}


	/**
	 * Settings section callback function
	 *
	 * This function is needed if we added a new section. This function
	 * will be run at the start of our section
	 *
	 * @return void
	 */
	public function section_callback_function() : void {
		echo '<p>Front page options</p>';
	}


	/**
	 * Callback function
	 *
	 * Creates a input text option.
	 *
	 * @param array $args Array of args.
	 * @return void
	 */
	public function field_callback_function( array $args ) : void {
		echo '<fieldset>';
		echo '<legend class="screen-reader-text"><span>' . esc_attr( $args['label'] ) . '</span></legend>';
		echo '<label>';
		echo '<input name="' . esc_attr( $args['name'] ) . '" type="checkbox" value="1"';
		echo get_option( 'posts_on_front_page' ) === '1' ? ' checked' : '';
		echo '/>';
		echo ' ' . esc_attr( $args['label'] );
		echo '</label></fieldset>';
	}


	/**
	 * Register our setting so that $_POST handling is done for us and our
	 * callback function just has to echo the <input>.
	 *
	 * @return void
	 */
	public function register_settings() : void {
		$args = array(
			'type'              => 'boolean',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => 1,
		);

		register_setting( 'reading', 'posts_on_front_page', $args );
	}
}
