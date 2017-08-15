<?php
/**
 * Project class
 */
class Project {

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
        
        $this->register_post_type();

        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'admin_head', array( $this, 'css' ) );
        add_filter( 'dashboard_glance_items', array( $this, 'at_a_glance' ) );
        
        add_filter( 'manage_project_posts_columns', array( $this, 'add_custom_columns' ) );
        add_action( 'manage_project_posts_custom_column' , array( $this, 'render_custom_columns' ), 10, 2 );

        add_filter( 'gettext', array( $this, 'change_excerpt_name' ), 10, 2 );

        // Ajax
        add_action( 'wp_ajax_nopriv_ajax_load_projects', array( $this, 'ajax_load_projects' ) );
		add_action( 'wp_ajax_ajax_load_projects', array( $this, 'ajax_load_projects' ) );

		// Pre get post
		// add_action( 'pre_get_posts', array( $this, 'pre_get_projects' ) );

	}


	/**
	 * Change text for the post excerpt
	 *
	 * @since 	1.0.0
	 * @param 	string 	$translated_text
	 * @param 	string 	$text
	 * @param 	string 	$domain
	 * @return 	string 
	 */
	function change_excerpt_name( $translated_text, $untranslated_text ) {

		switch ( $untranslated_text ) {
			case 'Excerpt' :
				
				$translated_text = __( 'Sous-titre', $this->theme_name );

				break;

			case 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="%s">Learn more about manual excerpts</a>.' :

				$translated_text = '';

				break;
		}
	    
	    return $translated_text;
	}

	
	/**
	 * Register Custom Post Type
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Projets', 'Projet Nom pluriel', $this->theme_name ),
	        'singular_name'         => _x( 'Projet', 'Projet Nom singulier', $this->theme_name ),
	        'menu_name'             => __( 'Projets', $this->theme_name ),
	        'name_admin_bar'        => __( 'Projet', $this->theme_name ),
	        'parent_item_colon'     => __( '', $this->theme_name ),
	        'all_items'             => __( 'Tous les projets', $this->theme_name ),
	        'add_new_item'          => __( 'Ajouter un nouveau projet', $this->theme_name ),
	        'add_new'               => __( 'Ajouter', $this->theme_name ),
	        'new_item'              => __( 'Nouveau projet', $this->theme_name ),
	        'edit_item'             => __( 'Modifier le projet', $this->theme_name ),
	        'update_item'           => __( 'Mettre à jour le projet', $this->theme_name ),
	        'view_item'             => __( 'Voir le projet', $this->theme_name ),
	        'view_items'            => __( 'Voir les projets', $this->theme_name ),
	        'search_items'          => __( 'Chercher parmi les projets', $this->theme_name ),
	        'not_found'             => __( 'Aucun projet trouvé.', $this->theme_name ),
	        'not_found_in_trash'    => __( 'Aucun projet trouvé dans la corbeille.', $this->theme_name ),
	        'featured_image'        => __( 'Image à la une', $this->theme_name ),
	        'set_featured_image'    => __( 'Mettre une image à la une', $this->theme_name ),
	        'remove_featured_image' => __( 'Retirer l\'image mise en avant', $this->theme_name ),
	        'use_featured_image'    => __( 'Mettre une image à la une', $this->theme_name ),
	        'insert_into_item'      => __( 'Insérer dans le projet', $this->theme_name ),
	        'uploaded_to_this_item' => __( 'Ajouter à ce projet', $this->theme_name ),
	        'items_list'            => __( 'Liste des projets', $this->theme_name ),
	        'items_list_navigation' => __( 'Navigation de liste des projets', $this->theme_name ),
	        'filter_items_list'     => __( 'Filtrer la liste des projets', $this->theme_name ),
		);
		$rewrite = array(
	        'slug'                	=> 'projets',
	        'with_front'          	=> true,
	        'pages'               	=> true,
	        'feeds'               	=> true,
	    );
		$args = array(
			'label'               	=> 'project',
	        'description'         	=> __( 'Les projets', $this->theme_name ),
	        'labels'              	=> $labels,
	        'supports'            	=> array( 'title', 'excerpt', 'editor', 'revisions', 'thumbnail', ),
	        'taxonomies'          	=> array( 'project_tag', 'project_client' ),
	        'hierarchical'        	=> false,
	        'public'              	=> true,
	        'show_ui'             	=> true,
	        'show_in_nav_menus'   	=> true,
	        'show_in_menu'        	=> true,
	        'show_in_admin_bar'   	=> true,
	        'show_in_rest'   		=> true,
	        'menu_position'       	=> 5,
	        'menu_icon'           	=> 'dashicons-portfolio',
	        'can_export'          	=> true,
	        'has_archive'         	=> true,
	        'exclude_from_search' 	=> false,
	        'publicly_queryable'  	=> true,
	        'rewrite'             	=> $rewrite,
	        'capability_type'     	=> 'post',
		);
		register_post_type( 'project', $args );
	}


	/**
	 * css
	 */
	public function css() {    
	?>
	    <style>
	        #dashboard_right_now .project-count:before { content: "\f322"; }
	        .fixed .column-colors { 

	            vertical-align: top;
	        }
	        .column-colors .color-indicator { 
	            border: none !important; 
	            border-radius: 50% !important; 
	            height: 26px !important; 
	            width: 26px !important; 
	            margin-left: 2px; 
	            margin-right: 2px;
	            float: left;
	        }

	        .post-type-project #postexcerpt .inside {
	        	margin-bottom: -14px;
	        }

	    </style>
	<?php
	}


	/**
	 * "At a glance" items (dashboard widget): add the projects.
	 */
	public function at_a_glance( $items ) {
	    $post_type = 'project';
	    $post_status = 'publish';

	    $object = get_post_type_object( $post_type );
	    
	    $num_posts = wp_count_posts( $post_type );

	    if ( ! $num_posts || ! isset ( $num_posts->{$post_status} ) || 0 === (int) $num_posts->{$post_status} ) {
	        
	        return $items;
	    }

	    $text = sprintf(
	        _n( '%1$s %4$s%2$s', '%1$s %4$s%3$s', $num_posts->{$post_status} ), 
	        number_format_i18n( $num_posts->{$post_status} ), 
	        strtolower( $object->labels->singular_name ), 
	        strtolower( $object->labels->name ),
	        'pending' === $post_status ? 'Pending ' : ''
	    );

	    if ( current_user_can( $object->cap->edit_posts ) ) {
	        $items[] = sprintf( '<a class="%1$s-count" href="edit.php?post_status=%2$s&post_type=%1$s">%3$s</a>', $post_type, $post_status, $text );
	    
	    } else {
	        $items[] = sprintf( '<span class="%1$s-count">%s</span>', $text );
	    }
	    
	    return $items;
	}


	/**
	 * Add custom columns
	 * 
	 * @param $columns
	 */
	public function add_custom_columns( $columns ) {
	    
	    // unset( $columns['title'] );

	    return array_merge( $columns, 
	    	array( 
	    		'colors' => __( 'Couleurs', $this->theme_name ),
	    		'sticky' => __( 'Mis en avant', $this->theme_name )
	    	) 
	    );
	}


	/**
	 * Render custom columns
	 * 
	 * @param $column_name 
	 * @param $post_id     
	 */
	public function render_custom_columns( $column_name, $post_id ) {

	    switch ( $column_name ) {

		    case 'colors' :
		    	
		    	if( have_rows( 'colors', $post_id ) ) {

		    		while ( have_rows( 'colors', $post_id ) ) { the_row();
			        	

			        	echo '<div id="colors-' . $post_id . '" class="color-indicator" style="background-color:';

			        	the_sub_field( 'color' );

			        	echo '"></div>';
		        	}

		        	
		    	} else {
		    		echo '—';
		    	}

		        break;

		    case 'sticky' :

				if ( get_field( 'enable_sticky' ) === true ) {

					_e( 'Mis en avant', $this->theme_name );

				} else {
					echo '—';
				}

			break;
	    }
	}


	/**
	 * pre_get_projets
	 */
	function pre_get_projects( $query ) {
	   	
	   	if ( is_admin() && !$query->is_main_query() ) {
   	        return;
   	    }

        $query->set( 'posts_per_page', -1 );

	}


	/**
	 * Load projects with AJAX request.	 
	 */
	function ajax_load_projects() {

		$tag = isset( $_GET['tag'] ) ? $_GET['tag'] : 0;
		$offset = isset( $_GET['offset'] ) ? $_GET['offset'] : 0;


		$args = array(
			'post_type'         => 'project',
			'posts_per_page'    => 16,
			'offset'            => (int) $offset,
		);
	    
		if ( $tag != 0 ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' 	=> 'project_tag',
					'field' 	=> 'term_id',
					'terms' 	=> (int) $tag
				)
		    );
		}

		$context = Timber::get_context();
		$context['projects']['posts'] = Timber::get_posts($args);

		Timber::render( 'partials/tease-project.twig', $context );

		wp_die();

	}
}
