<?php
/**
 * Class Post
 *
 * @package JVEB
 */

namespace JVEB\PostTypes;

use Timber\{ Timber, PostQuery };
use WP_Post, WP_Query, WP_REST_Request;

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

		add_action( 'rest_api_init', array( $this, 'rest_api_register_routes' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_fields' ) );

		add_action( 'pre_get_posts', array( $this, 'exclude_posts_from_feed' ) );
	}


	/**
	 * Exclude posts from feed
	 *
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 * @return void
	 */
	public function exclude_posts_from_feed( WP_Query $query ) : void {
		if ( ! $query->is_feed ) {
			return;
		}

		if ( 'post' !== $query->get( 'post_type' ) ) {
			return;
		}

		$meta_query = array(
			'relation' => 'OR',
			array(
				'key'     => 'exclude_from_feed',
				'value'   => '',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => 'exclude_from_feed',
				'value'   => '0',
				'compare' => '==',
			),
		);

		$query->set( 'meta_query', $meta_query );
	}


	function register_rest_fields() : void {
		register_rest_field(
			array( 'post' ),
			'post_thumbnail_url',
			array(
				'get_callback'    => array( $this, 'get_rest_featured_image' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
		register_rest_field(
			array( 'post' ),
			'post_categories',
			array(
				'get_callback'    => array( $this, 'get_rest_categories' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
		register_rest_field(
			array( 'post' ),
			'post_date_format',
			array(
				'get_callback'    => array( $this, 'get_rest_date' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	public function get_rest_date( array $post, string $attr, object $request, string $object_type ) {
		return get_the_date( 'd\.m\.Y', $post['id'] );
	}


	public function get_rest_categories( array $post, string $attr, object $request, string $object_type ) {
		if ( ! $post['categories'] ) {
			return;
		}

		$post_categories = array();
		$categories      = get_the_terms( $post['id'], 'category' );

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

		return $post_categories;
	}


	public function get_rest_featured_image( $post, string $attr, object $request, string $object_type ) {
		if ( ! $post['featured_media'] ) {
			return;
		}

		return wp_get_attachment_image_src( $post['featured_media'], 'large' )[0];
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

		delete_transient( 'jveb_posts' );
		delete_transient( 'jveb_slider_videos' );
		delete_transient( "jveb_relationship_post_$post_id" );
		delete_transient( 'jveb_front_page_posts_' . pll_get_post_language( $post_id, 'slug' ) );
		delete_transient( 'jveb_sticky_posts' );
		delete_transient( 'jveb_front_page_posts_count_' . pll_get_post_language( $post_id, 'slug' ) );
	}

	/**
	 * CSS
	 *
	 * @return void
	 */
	public function css() : void {
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
	 * WP REST API register custom endpoints
	 *
	 * @since 1.0.0
	 */
	public function rest_api_register_routes() : void {
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
				'category__not_in' => array( 1411 ),
				'lang'             => $_GET['lang'],
			)
		);

		if ( empty( $results ) ) {
			return;
		}

		foreach ( $results as $post ) {
			$post->post_date_format = get_the_date( 'd\.m\.Y', $post->ID );

			$post_categories = array();
			$categories      = wp_get_post_terms( $post->ID, 'category', array( 'fields' => 'all' ) );

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
