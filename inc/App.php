<?php
/**
 * App
 *
 * @package JVEB
 */

namespace JVEB;

use Dotenv\{ Dotenv };
use Timber\{ Timber, Menu, Helper };

use JVEB\{ Admin, Helpers, Transients, Settings, PostTypes, Taxonomies, Lib };

use Twig\{ TwigFunction };

// @TODO include in namespace JVEB
use acf_location_rule;

Timber::$dirname = array( 'dist', 'views' );

$dotenv = Dotenv::create( get_template_directory() );
$dotenv->load();

define( 'CONTACT_FORM_ID', 63 );


/**
 * Class App
 */
class App extends Timber {

	/**
	 * The name of the theme
	 *
	 * @access private
	 * @var $theme_name
	 */
	private $theme_name;


	/**
	 * The version of this theme
	 *
	 * @access private
	 * @var $theme_version
	 */
	private $theme_version;


	/**
	 * The version of this theme
	 *
	 * @access private
	 * @var $theme_manifest
	 */
	private $theme_manifest;


	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $theme_name The theme name.
	 * @param string $theme_version The theme version.
	 * @access public
	 */
	public function __construct( string $theme_name, string $theme_version ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;

		Transients::get_template_instagram();

		$this->setup();
		$this->load_dependencies();

		$this->theme_manifest = Helpers::get_theme_manifest();

		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );

		parent::__construct();
	}


	/**
	 * Load dependencies description
	 *
	 * @return void
	 * @access private
	 */
	private function load_dependencies() : void {
		require_once get_template_directory() . '/inc/utilities.php';
		require_once get_template_directory() . '/inc/acf-location.php';
		require_once get_template_directory() . '/inc/wp-rocket.php';
		require_once get_template_directory() . '/inc/mc4wp.php';
		require_once get_template_directory() . '/inc/customizer/_includes.php';
		require_once get_template_directory() . '/inc/post-template.php';
		require_once get_template_directory() . '/inc/shortcodes/rich-content.php';
		require_once get_template_directory() . '/inc/shortcodes/image-content.php';
		require_once get_template_directory() . '/inc/blocks/gallery.php';
		require_once get_template_directory() . '/inc/reset.php';

		if ( is_admin() ) {
			new Admin( $this->get_theme_name(), $this->get_theme_version() );
		}

		new acf_location_rule( 'category_parents' );
		new PostTypes\Post( $this->get_theme_name(), $this->get_theme_version() );
		new PostTypes\Recipe( $this->get_theme_name(), $this->get_theme_version() );
		new Taxonomies\RecipeCategory( $this->get_theme_name(), $this->get_theme_version() );
		new Settings( $this->get_theme_name(), $this->get_theme_version() );

	}


	/**
	 * Add to Twig
	 *
	 * @return object $twig
	 * @param object $twig The Twig object.
	 */
	public function add_to_twig( object $twig ) : object {
		if ( function_exists( 'pll__' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'pll__',
					function ( string $string ) : string {
						return pll__( $string );
					}
				)
			);
		}

		if ( function_exists( 'pll_e' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'pll_e',
					function ( $string ) {
						return pll_e( $string );
					}
				)
			);
		}

		if ( function_exists( 'pll_the_languages' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'pll_the_languages',
					function ( $args = '' ) {
						return pll_the_languages( $args );
					}
				)
			);
		}

		if ( function_exists( 'post_class' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'post_class',
					function ( $args = '' ) {
						return post_class( $args );
					}
				)
			);
		}

		if ( function_exists( 'body_class' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'body_class',
					function ( $args = '' ) {
						return body_class( $args );
					}
				)
			);
		}

		if ( function_exists( 'html_class' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'html_class',
					function ( $args = '' ) {
						return html_class( $args );
					}
				)
			);
		}

		if ( function_exists( 'comment_class' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'comment_class',
					function ( $args = '' ) {
						return comment_class( $args );
					}
				)
			);
		}

		if ( function_exists( 'get_avatar' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'get_avatar',
					function ( $args = '' ) {
						return get_avatar( $args );
					}
				)
			);
		}

		if ( function_exists( 'wp_login_url' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'wp_login_url',
					function ( $args = '' ) {
						return wp_login_url( $args );
					}
				)
			);
		};

		if ( function_exists( 'wp_logout_url' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'wp_logout_url',
					function ( $args = '' ) {
						return wp_logout_url( $args );
					}
				)
			);
		};

		if ( ! function_exists( 'youtube_id' ) ) {
			$twig->addFunction(
				new TwigFunction(
					'youtube_id',
					function ( $args = '' ) {
						return youtube_id( $args );
					}
				)
			);
		};

		return $twig;
	}


	/**
	 * Add to context
	 *
	 * @param array $context The array context.
	 * @return array $context
	 * @access  public
	 */
	public function add_to_context( array $context ) : array {
		// Menus.
		$menus = get_registered_nav_menus();
		foreach ( $menus as $menu => $value ) {
			$context['menu'][ $menu ] = new Menu( $menu );
		}

		// Add socials to context.
		$socials      = array();
		$socials_name = array( 'YouTube', 'Facebook', 'Twitter', 'Instagram' );

		foreach ( $socials_name as $name ) {
			${ strtolower( $name ) }        = array(
				'slug' => strtolower( $name ),
				'name' => $name,
				'url'  => get_option( strtolower( $name ) ),
			);
			$socials[ strtolower( $name ) ] = ${ strtolower( $name ) };
		}

		// Add $socials to $context.
		$context['contact']['socials'] = $socials;

		// Address.
		if ( get_option( 'address' ) ) {
			$context['contact']['address'] = nl2br( get_option( 'address' ) );
		}

		// Phone.
		if ( get_option( 'phone' ) ) {
			$context['contact']['phone'] = get_option( 'phone' );
		}

		// Banner.
		if ( get_option( 'banner' ) ) {
			$context['banner'] = get_option( 'banner' );
		}

		// Permalink.
		$page_args = array();
		$pages     = get_pages( $page_args );

		foreach ( $pages as $page ) {
			// Replace `-` by `_`.
			$slug = str_replace( '-', '_', $page->post_name );

			$context['permalink'][ $slug ] = get_page_link( $page->ID );
		}
		$context['permalink']['current_url'] = get_permalink();

		// Share.
		$shares = array(
			array(
				'slug' => 'facebook',
				'name' => __( 'Partager sur Facebook' ),
				'url'  => 'https://www.facebook.com/sharer.php?u=',
			),
			array(
				'slug' => 'twitter',
				'name' => __( 'Partager sur Twitter' ),
				'url'  => 'https://twitter.com/intent/tweet?url=',
			),
			array(
				'slug' => 'google-plus',
				'name' => __( 'Partager sur Google+' ),
				'url'  => 'https://plus.google.com/share?url=',
			),
			array(
				'slug' => 'envelope',
				'name' => __( 'Partager par Mail' ),
				'url'  => 'mailto:?&amp;body=',
			),
		);

		foreach ( $shares as $share ) {
			$context['contact']['shares'][ $share['slug'] ] = $share;
		}

		// Block for slider videos.
		$context['slider_videos'] = Timber::get_sidebar( 'component-slider-videos.php' );

		// Block for sticky post.
		$context['sticky_post'] = Timber::get_sidebar( 'component-sticky-post.php' );

		// Block for relationship post.
		$context['relationship_post'] = Timber::get_sidebar( 'component-relationship-post.php' );

		$context['manifest']                    = $this->get_theme_manifest();
		$context['comments_per_page']           = get_option( 'comments_per_page' );
		$context['background_image_newsletter'] = get_option( 'background_image_newsletter' );
		$context['current_language']            = pll_current_language();
		$context['production']                  = getenv( 'PRODUCTION' ) === 'false' ? false : true;

		$context['mc4wp_form'] = mc4wp_show_form( 4609, array(), false );

		return $context;
	}


	/**
	 * Setup
	 *
	 * @return void
	 * @access public
	 */
	public function setup() : void {

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @see https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Register nav menus.
		register_nav_menus(
			array(
				'main'       => __( 'Main' ),
				'categories' => __( 'Catégories' ),
			)
		);

		// Widget.
		register_sidebar(
			array(
				'name' => __( 'Newsletter', 'JVEB' ),
				'id'   => 'newsletter',
			)
		);

		/**
		 * Add excerpt on page
		 *
		 * @see  https://codex.wordpress.org/Function_Reference/add_post_type_support
		 */
		add_post_type_support( 'page', 'excerpt' );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	/**
	 * Enqueue styles.
	 *
	 * @access public
	 */
	public function enqueue_style() {
		wp_dequeue_style( 'wp-block-library' );

		// Add custom fonts, used in the main stylesheet.
		$webfonts = array();
		foreach ( $this->webfonts() as $name => $url ) {
			wp_register_style( "font-$name", $url, array(), '1.0.0' );
			$webfonts[] = "font-$name";
		}

		// Theme stylesheet.
		wp_register_style(
			"$this->theme_name-main",
			get_template_directory_uri() . '/' . $this->get_theme_manifest()['main.css'],
			$webfonts,
			'1.0.0'
		);

		wp_enqueue_style( "$this->theme_name-main" );
	}


	/**
	 * Enqueue scripts
	 *
	 * @access public
	 */
	public function enqueue_scripts() {
		global $wp;

		// Remove wp-embed script from WordPress.
		wp_deregister_script( 'wp-embed' );

		// Remove native version of jQuery and use custom CDN version instead.
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//code.jquery.com/jquery-3.4.1.min.js', false, $this->get_theme_version(), true );

		// jQuery Auto Complete.
		wp_register_script(
			'jquery-ui',
			'//code.jquery.com/ui/1.12.1/jquery-ui.min.js',
			array( 'jquery' ),
			$this->get_theme_version(),
			true
		);

		wp_enqueue_script(
			'feature',
			'//cdnjs.cloudflare.com/ajax/libs/feature.js/1.0.1/feature.min.js',
			array(),
			$this->get_theme_version(),
			false
		);
		wp_add_inline_script(
			'feature',
			'document.documentElement.className=document.documentElement.className.replace("no-js","js"),feature.touch&&!navigator.userAgent.match(/Trident\/(6|7)\./)&&(document.documentElement.className=document.documentElement.className.replace("no-touch","touch"));'
		);

		wp_register_script(
			$this->theme_name . '-main',
			get_template_directory_uri() . '/' . $this->get_theme_manifest()['main.js'],
			array( 'jquery', 'jquery-ui', 'feature', 'wp-util' ),
			$this->get_theme_version(),
			true
		);

		wp_localize_script(
			$this->theme_name . '-main',
			'jveb',
			array(
				'template_directory_uri' => get_template_directory_uri(),
				'base_url'               => site_url(),
				'home_url'               => home_url( '/' ),
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'api_url'                => home_url( 'wp-json' ),
				'search_api'             => home_url( 'wp-json/jveb/v2/search' ),
				'current_url'            => home_url( add_query_arg( array(), $wp->request ) ),
				'nonce'                  => wp_create_nonce( 'security' ),
				'template_instagram'     => get_transient( 'jveb_template_instagram' ),
			)
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		wp_enqueue_script( $this->theme_name . '-main' );
	}



	/**
	 * List webfonts used by the theme.
	 *
	 * @return array
	 * @access public
	 */
	public function webfonts() {
		return array(
			'poppins' => 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap',
			'lora'    => 'https://fonts.googleapis.com/css?family=Lora:400,700&display=swap',
		);
	}


	/**
	 * Retrieve the version number of the theme.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_theme_version() {
		return $this->theme_version;
	}


	/**
	 * The name of the theme used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_theme_name() {
		return $this->theme_name;
	}


	/**
	 * Retrieve the manifest of the theme.
	 *
	 * @since  1.0.0
	 * @return array The manifest of the plugin.
	 */
	private function get_theme_manifest() : array {
		return $this->theme_manifest;
	}
}
