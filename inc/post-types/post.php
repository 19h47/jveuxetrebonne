<?php
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
	 * @access public
	 */
	public function __construct( $theme_name, $theme_version ) {
		$this->theme_name = $theme_name;
        $this->theme_version = $theme_version;

        add_filter( 'manage_posts_columns', array( $this, 'add_custom_columns' ) );
        add_action( 'manage_posts_custom_column' , array( $this, 'render_custom_columns' ), 10, 2 );

        add_action( 'admin_head', array( $this, 'css' ) );
        
        // Ajax
        add_action( 'wp_ajax_nopriv_ajax_load_posts', array( $this, 'ajax_load_posts' ) );
		add_action( 'wp_ajax_ajax_load_posts', array( $this, 'ajax_load_posts' ) ); 

		add_action( 'wp_ajax_nopriv_ajax_load_recipes', array( $this, 'ajax_load_recipes' ) );
		add_action( 'wp_ajax_ajax_load_recipes', array( $this, 'ajax_load_recipes' ) ); 

		// Rest Api 
		
		add_action( 'rest_api_init', array( $this, 'rest_api_register_routes' ) );
		// add_action( 'rest_api_init', array( $this, 'rest_insert_tag_links' ) );
    }


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
        
        // unset( $columns['title'] );

        $new_columns = array();
      	
      	foreach( $columns as $key => $value ) {
            
            if ( $key === 'title' ){
              	$new_columns['thumbnail'] = pll__( 'Image à la une' );
            }

          	$new_columns[$key] = $value;
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

    	    case 'thumbnail' :
    	    	
    	    	if ( get_the_post_thumbnail( $post_id ) ) {

	   				echo '<a href="' . get_edit_post_link( $post_id ) . '">';
			        echo the_post_thumbnail( 'thumbnail' );
			        echo '</a>';

    	    	} else {
    	    		echo '—';
    	    	}

    	        break;

    		break;
        }
    }


	/**
	 * Load posts with AJAX request.	 
	 */
	public function ajax_load_posts() {

		$tag = isset( $_GET['tag'] ) ? $_GET['tag'] : 0;
		$offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;
		$posts_per_page = isset( $_GET['posts_per_page'] ) ? $_GET['posts_per_page'] : 3;
		$post_template = isset( $_GET['post_template'] ) ? $_GET['post_template'] : 'tease';
		$exclude = $_GET['exclude'];
		
		// $sticky = get_option( 'sticky_posts' );

		$args = array(
			'post_type'        	=> 'post',
			'posts_per_page'   	=> (int) $posts_per_page,
			'cat' 				=> (int) $tag,
			'offset'           	=> (int) $offset,
			// 'ignore_sticky_posts' 	=> 1,
			'post__not_in' 		=> get_option( 'sticky_posts' ),
			'post_status' 		=> 'publish'
		);

		// Exclude some article on front page
		if ( $exclude ) {
			$args['meta_query']	= array(
				'relation' 	=> 'OR',
				array(
					'key'		=> 'exclude_from_loop',
					'value'		=> 0,
					'type' 		=> 'BOOLEAN'
				),
				array(
		            'key' 		=> 'exclude_from_loop',
		            'compare' 	=> 'NOT EXISTS',
		            'type' 		=> 'BOOLEAN'
		        )
			);
		}

			
		// var_dump($args);


		$context = Timber::get_context();
		$context['posts'] = Timber::get_posts($args);
		Timber::render( "components/${ post_template }-posts.twig", $context );
		wp_die();
	}


	/**
	 * Load recipes with AJAX request.	 
	 */
	public function ajax_load_recipes() {
		
		$id = isset( $_GET['id'] ) ? $_GET['id'] : 0;

		if ( $id === 0 ) {
			return;
		}

		$args = array(
			'post_type'	=> 'post',
			'p'         => $id,
		);
		// var_dump($args);

		$context = Timber::get_context();
		$context['post'] = Timber::get_post($args);

		Timber::render( "partials/recipe.twig", $context );

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
				'methods'  	=> 'GET',
				'callback' 	=> array( $this, 'rest_api_search' ),
			) 
		);
	}


	/**
	 * WP REST API search results.
	 *
	 * @since 1.0.0
	 * @param object $request
	 */
	public function rest_api_search( $request ) {
		
		if ( empty( $request['term'] ) ) {
			return;
		}

		$results = get_posts( array(
			'post_type'     	=> 'post',
			'post_status'   	=> 'publish',
			'posts_per_page'	=> -1,
			's'             	=> $request['term'],
			// Exclude video catgory
			'category__not_in' 	=> array( 1335 )
		) );

		if ( empty( $results ) ) {
			return;
		}

		foreach ( $results as $post ) {
			
			// Date
			$post->post_date_format = get_the_date( 'd\.m\.Y', $post->ID );

			// Categories
			$post_categories = array();
			$categories = wp_get_post_terms( $post->ID, 'category', array( 'fields' => 'all' ) );

			foreach ( $categories as $term ) {

			    $term_link = get_term_link( $term );

			    if ( is_wp_error( $term_link ) ) {
			        continue;
			    }

			    $post_categories[] = array(
			    	'term_id'	=>$term->term_id, 
			    	'name'		=>$term->name, 
			    	'link'		=>$term_link
			    );
			}

			$post->post_categories = $post_categories;

			// Permalink
			$post->link = get_permalink( $post->ID );

			// Thumbnail
			$post->post_thumbnail_url = get_the_post_thumbnail_url( $post->ID );
		}

		return $results;
	}
}