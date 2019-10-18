<?php
/**
 * Class Post
 *
 * @package JVEB
 */

namespace JVEB\PostTypes;

use Timber\{ Timber, PostQuery };
use WP_Post;

/**
 * Post
 */
class Post {

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
	 * @access   private
	 * @var      string    $version    The current version of this theme.
	 */
	private $theme_version;


	/**
	 * Construct function
	 *
	 * @param string $theme_name The name of the theme.
	 * @param string $theme_version The version of the theme.
	 *
	 * @access public
	 */
	public function __construct( string $theme_name, string $theme_version ) {
		$this->theme_name    = $theme_name;
		$this->theme_version = $theme_version;

		add_action( 'save_post', array( $this, 'save' ), 10, 3 );

		add_filter( 'manage_posts_columns', array( $this, 'add_custom_columns' ) );
		add_action( 'manage_posts_custom_column', array( $this, 'render_custom_columns' ), 10, 2 );

		add_action( 'admin_head', array( $this, 'css' ) );

		add_action( 'wp_ajax_nopriv_ajax_load_posts', array( $this, 'ajax_load_posts' ) );
		add_action( 'wp_ajax_ajax_load_posts', array( $this, 'ajax_load_posts' ) );

		add_action( 'rest_api_init', array( $this, 'rest_api_register_routes' ) );
	}


	/**
	 * Save
	 *
	 * Fires once a post has been saved.
	 *
	 * @param int     $post_id The post ID.
	 * @param WP_Post $post The post object.
	 * @param bool    $update Whether this is an existing post being updated or not.
	 * @link https://developer.wordpress.org/reference/hooks/save_post_post-post_type/
	 * @access public
	 */
	public function save( int $post_id, WP_Post $post, bool $update ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		delete_transient( 'jveb_slider_videos' );
		delete_transient( "jveb_relationship_post_$post_id" );
		delete_transient( 'jveb_sticky_posts' );
	}

	/**
	 * CSS
	 */
	public function css() {
		?>
		<style>

			.fixed .column-thumbnail {
				vertical-align: top;
				width: 80px;
			}
			.column-thumbnail a {
				display: block;
			}
			.column-thumbnail a img {
				display: inline-block;
				vertical-align: middle;
			}

		</style>
		<?php
	}


	/**
	 * Add custom columns
	 *
	 * @param $columns
	 */
	public function add_custom_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $value ) {
			if ( 'title' === $key ) {
				$new_columns['thumbnail'] = pll__( 'Image à la une' );
			}

			$new_columns[ $key ] = $value;
		}

		return $new_columns;
	}


	/**
	 * Render custom columns
	 *
	 * @param $column_name
	 * @param $post_id
	 */
	public function render_custom_columns( $column_name, $post_id ) {

		switch ( $column_name ) {
			case 'thumbnail':

				if ( get_the_post_thumbnail( $post_id ) ) {
					echo '<a href="' . get_edit_post_link( $post_id ) . '">';
					echo the_post_thumbnail( 'thumbnail' );
					echo '</a>';
				} else {
					echo '—';
				}

				break;
		}
	}


	/**
	 * Load posts with AJAX request.
	 */
	public function ajax_load_posts() {
		if ( ! isset( $_GET['nonce'] ) && ! wp_verify_nonce( sanitize_key( $_GET['nonce'] ), 'security' ) ) {
			return false;
		}

		$term_id        = sanitize_text_field( wp_unslash( $_GET['term_id'] ) ) ?? 0;
		$offset         = sanitize_text_field( wp_unslash( $_GET['offset'] ) ) ?? 0;
		$posts_per_page = sanitize_text_field( wp_unslash( $_GET['posts_per_page'] ) ) ?? 3;
		$post_template  = sanitize_text_field( wp_unslash( $_GET['post_template'] ) ) ?? 'tease';
		$exclude        = $_GET['exclude'];

		$args = array(
			'post_type'        => 'post',
			'posts_per_page'   => (int) $posts_per_page,
			'cat'              => (int) $term_id,
			'offset'           => (int) $offset,
			// 'ignore_sticky_posts' 	=> 1,
			'post__not_in'     => get_option( 'sticky_posts' ),
			'post_status'      => 'publish',
			'category__not_in' => array( 1335, 1383 ),
		);

		// Exclude some article on front page.
		if ( $exclude ) {
			$args['meta_query']	= array( // phpcs:ignore
				'relation' => 'OR',
				array(
					'key'   => 'exclude_from_loop',
					'value' => 0,
					'type'  => 'BOOLEAN',
				),
				array(
					'key'     => 'exclude_from_loop',
					'compare' => 'NOT EXISTS',
					'type'    => 'BOOLEAN',
				),
			);
		}

		$context          = Timber::get_context();
		$context['posts'] = new PostQuery( $args );

		Timber::render( "components/${ post_template }-posts.html.twig", $context );
		wp_die();
	}



	/**
	 * WP REST API register custom endpoints
	 *
	 * @since 1.0.0
	 */
	public function rest_api_register_routes() {
		register_rest_route(
			'jveb/v2',
			'/search',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'rest_api_search' ),
			)
		);
	}


	/**
	 * WP REST API search results.
	 *
	 * @param object $request
	 * @since 1.0.0
	 */
	public function rest_api_search( $request ) {

		if ( empty( $request['term'] ) ) {
			return;
		}

		$results = get_posts(
			array(
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
				's'                => $request['term'],
				// Exclude video category.
				'category__not_in' => array( 1335 ),
				'lang'             => $_GET['lang'],
			)
		);

		if ( empty( $results ) ) {
			return;
		}

		foreach ( $results as $post ) {
			$post->post_date_format = get_the_date( 'd\.m\.Y', $post->ID );

			$post_categories = array();
			$categories       = wp_get_post_terms( $post->ID, 'category', array( 'fields' => 'all' ) );

			foreach ( $categories as $term ) {

				$term_link = get_term_link( $term );

				if ( is_wp_error( $term_link ) ) {
					continue;
				}

				$post_categories[] = array(
					'term_id' => $term->term_id,
					'name'    => $term->name,
					'link'    => $term_link,
				);
			}

			$post->post_categories    = $post_categories;
			$post->link               = get_permalink( $post->ID );
			$post->post_thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'medium' );
		}

		return $results;
	}
}
