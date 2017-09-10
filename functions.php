<?php
/**
 * JVEB functions and definitions
 *
 * @see https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage pup
 * @since 1.0
 *
 * Functions'prefix is jveb_
 */
 

/**
 * Autoload
 */
include get_template_directory() . '/vendor/autoload.php';


/**
 * Timber
 * 
 * Instanciate Timber
 *
 * @see         https://github.com/timber/timber
 * @version     1.3.0
 */
$timber = new \Timber\Timber(); 


/**
 * Dirname
 * 
 * Tell Timber where are views
 */
Timber::$dirname = array( 'views' );


/**
 * Constant
 */
define( 'CONTACT_FORM_ID', 63 );


/**
 * class JVEB
 */
class JVEB extends TimberSite {

    /**
     * The name of the theme
     *
     * @access private
     */
    private $theme_name;


    /**
     * The version of this theme
     *
     * @access private
     */
    private $theme_version;


    /**
     * Initialize the class and set its properties.
     *
     * @param  $theme_name
     * @param  $theme_version
     * @access public
     */
    public function __construct( $theme_name, $theme_version ) {
        
        $this->theme_name = $theme_name;
        $this->theme_version = $theme_version;
        $this->setup();
        $this->load_dependencies();

        add_filter( 'timber_context', array( $this, 'add_to_context' ) );
        add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );

        parent::__construct();
    }


    /**
     * Load dependencies description
     * 
     * @access private
     */
    private function load_dependencies() {

        // require_once get_template_directory() . '/inc/reset.php';
        require_once get_template_directory() . '/inc/utilities.php';
        require_once get_template_directory() . '/inc/acf-location.php';
        require_once get_template_directory() . '/inc/post-types/_includes.php';
        require_once get_template_directory() . '/inc/taxonomies/_includes.php';
        require_once get_template_directory() . '/inc/customizer/_includes.php';
        require_once get_template_directory() . '/inc/post-template.php';
        require_once get_template_directory() . '/inc/shortcodes/gallery.php';
        require_once get_template_directory() . '/inc/shortcodes/rich-content.php';
        require_once get_template_directory() . '/inc/shortcodes/image-content.php';
        require_once get_template_directory() . '/inc/reset.php';

        
        // if ( is_admin() ) new Admin( $this->get_theme_name(), $this->get_theme_version() );
        new acf_location_rule( 'category_parents' );
        new Custom_Post_Types( $this->get_theme_name(), $this->get_theme_version() );
        new Custom_Taxonomies( $this->get_theme_name(), $this->get_theme_version() );
    }


    /**
     * Add to Twig
     */
    public function add_to_twig( $twig ) {

        if ( function_exists( 'pll__' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'pll__', function( $string ) {
                return pll__( $string );
            } ) );
        }


        if ( function_exists( 'pll_e' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'pll_e', function( $string ) {
                return pll_e( $string );
            } ) );
        }


        if ( function_exists( 'pll_the_languages' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'pll_the_languages', function( $args = '' ) {
                return pll_the_languages( $args );
            } ) );
        }


        if ( function_exists( 'post_class' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'post_class', function( $args = '' ) {
                return post_class( $args );
            } ) );
        }


        if ( function_exists( 'body_class' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'body_class', function( $args = '' ) {
                return body_class( $args );
            } ) );
        }


        if ( function_exists( 'html_class' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'html_class', function( $args = '' ) {
                return html_class( $args );
            } ) );
        }


        if ( function_exists( 'comment_class' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'comment_class', function( $args = '' ) {
                return comment_class( $args );
            } ) );
        }


        if ( function_exists( 'get_avatar' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'get_avatar', function( $args = '' ) {
                return get_avatar( $args );
            } ) );
        }


        if( function_exists( 'wp_login_url' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'wp_login_url', function( $args = '' ) {
                return wp_login_url( $args );
            } ) );
        };


        if( function_exists( 'wp_logout_url' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'wp_logout_url', function( $args = '' ) {
                return wp_logout_url( $args );
            } ) );
        };


        if ( ! function_exists( 'youtube_id' ) ) {
            $twig->addFunction( new \Twig_SimpleFunction( 'youtube_id', function( $args = '' ) {
                return youtube_id( $args );
            } ) );
        };

        return $twig;
    }


    /**
     * Add to context
     *
     * @return  $context
     * @access  public
     */
    public function add_to_context( $context ) {

        /**
         * Menus
         */
        $menus = get_registered_nav_menus();
        foreach ( $menus as $menu => $value ) {
            $context['menu'][$menu] = new TimberMenu( $value );
        }


        // Add socials to context
        $socials = array();
        $socials_name = [ 'YouTube', 'Facebook', 'Twitter', 'Instagram' ];

        foreach ( $socials_name as $name ) {
            ${ strtolower( $name ) } = array(
                'slug'  => strtolower( $name ),
                'name'  => $name,
                'url'   => get_option( strtolower( $name ) )
            );

            $socials[strtolower( $name )] = ${ strtolower( $name ) };
        }

        // Add $socials to $context
        $context['contact']['socials'] = $socials;
        

        // Address
        if ( get_option( 'address' ) ) {

            $context['contact']['address'] = nl2br( get_option( 'address' ) );
        }

        // Phone
        if ( get_option( 'phone' ) ) {

            $context['contact']['phone'] = get_option( 'phone' );
        }

        // Banner
        if ( get_option( 'banner' ) ) {

            $context['banner'] = get_option( 'banner' );
        }


        // Permalink
        $page_args = array();
        $pages = get_pages( $page_args );

        foreach ( $pages as $page ) {

            // Replace `-` by `_`
            $slug = str_replace('-', '_', $page->post_name);

            $context['permalink'][$slug] = get_page_link( $page->ID );
        }
        $context['permalink']['current_url'] = get_permalink();


        // Share
        $shares = array(
            array(
                'slug'  => 'facebook',
                'name' => pll__( 'Partager sur Facebook' ),
                'url'  => ''
            ),
            array(
                'slug'  => 'twitter',
                'name' => pll__( 'Partager sur Twitter' ),
                'url'  => ''
            ),
            array(
                'slug'  => 'google-plus',
                'name' => pll__( 'Partager sur Google+' ),
                'url'  => ''
            ),
            array(
                'slug'  => 'envelope',
                'name' => pll__( 'Partager par Mail' ),
                'url'  => ''
            )
        );

        foreach ( $shares as $share ) {
            $context['contact']['shares'][$share['slug']] = $share;
        }

        // Block for slider videos
        $context['slider_videos'] = Timber::get_sidebar( 'component-slider-videos.php' );

        // Block for sticky post
        $context['sticky_post'] = Timber::get_sidebar( 'component-sticky-post.php' );

        // Block for relationship post
        $context['relationship_post'] = Timber::get_sidebar( 'component-relationship-post.php' );

       
        return $context;
    }


    /**
     * Setup
     * 
     * @access public
     */
    public function setup() {

        /*
         * Let WordPress manage the document title.
         */
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


        /**
         * Register nav menus
         */
        register_nav_menus( 
            array(
                'main'          => pll__( 'Main' ),
                'categories'    => pll__( 'Catégories' ),
            ) 
        );



        /**
         * Widget
         */
        register_sidebar( 
            array(
                'name'  => __( 'Newsletter', $this->theme_name ),
                'id'    => 'newsletter'
            ) 
        );


        /**
         * Add excerpt on page
         *
         * @see  https://codex.wordpress.org/Function_Reference/add_post_type_support
         */
        add_post_type_support( 'page', 'excerpt' );


        /*
         * This theme styles the visual editor to resemble the theme style,
         * specifically font, colors, and column width.
         */
        add_editor_style( array( ) );

        add_action( 'wp_head', array( $this, 'javascript_detection' ), 2 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


        /**
         * Add favicons
         */
        add_action( 'wp_head', array( $this, 'favicons' ) );

    }


    /**
     * Enqueue styles.
     * 
     * @access public
     */
    public function enqueue_style() {

        /**
         * Add custom fonts, used in the main stylesheet.
         */
        $webfonts = array();
        foreach ( $this->webfonts() as $name => $url ) {
            wp_register_style( 'font-' . $name, $url, array(), null );
            $webfonts[] = 'font-' . $name;
        }


        /**
         * Theme stylesheet
         */
        wp_register_style(  $this->theme_name . '-global', get_template_directory_uri() . '/dist/css/global.css', $webfonts, null );


        wp_enqueue_style( $this->theme_name . '-global' );
    }


    /**
     * Enqueue scripts
     *
     * @access public
     */
    public function enqueue_scripts() {
        
        /**
         * Remove wp-embed script from WordPress
         */
        wp_deregister_script( 'wp-embed' );


        /**
         * Remove native version of jQuery and use custom CDN version instead
         */
        wp_deregister_script( 'jquery' );
        wp_register_script( 'jquery', '//code.jquery.com/jquery-3.1.1.min.js', false, null, true );


        // jQuery Auto Complete
        wp_register_script(
            'jquery-ui',
            '//code.jquery.com/ui/1.12.1/jquery-ui.min.js',
            array( 'jquery' ),
            null,
            true
        );


        // Mailpoet
        wp_register_script(
            'wysija_validation_engine_fr',
            plugins_url() . '/wysija-newsletters/js/validate/languages/jquery.validationEngine-fr.js',
            array( 'jquery' ),
            '2.7.11.3',
            true
        );


        wp_register_script(
            'wysija_validation_engine',
            plugins_url() . '/wysija-newsletters/js/validate/jquery.validationEngine.js',
            array( 'jquery' ),
            '2.7.11.3',
            true
        );

        wp_register_script(
            'wysija_front_subscribers',
            plugins_url() . '/wysija-newsletters/js/front-subscribers.js',
            array( 'jquery' ),
            '2.7.11.3',
            true
        );

        wp_localize_script(
            'wysija_front_subscribers',
            'wysijaAJAX', 
            array(
                'action'        => 'wysija_ajax',
                'controller'    => 'subscribers',
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'loadingTrans'  => pll__( 'Chargement...' )
            )
        );


        wp_enqueue_script( 'wysija_validation_engine_fr' );
        wp_enqueue_script( 'wysija_validation_engine' );
        wp_enqueue_script( 'wysija_front_subscribers' );



        wp_register_script( 
            $this->theme_name . '-main', 
            get_template_directory_uri() . '/dist/js/bundle.js', 
            array( 
                'jquery', 
                'jquery-ui' 
            ), 
            null, 
            true 
        );

        $cat_is_ancestor_of_food = get_categories(
            array( 
                'parent'    => 445 
            )
        );

        $cat_is_ancestor_of_food_array = array();
        foreach ( $cat_is_ancestor_of_food as $cat ) {
            array_push( $cat_is_ancestor_of_food_array, $cat->term_id );
        }


        wp_localize_script( 
            $this->theme_name . '-main', 
            'wp', 
            array(
                'template_directory_uri'    => get_template_directory_uri(),
                'base_url'                  => site_url(),
                'home_url'                  => home_url( '/' ),
                'ajax_url'                  => admin_url( 'admin-ajax.php' ),
                'api_url'                   => home_url( 'wp-json' ),
                'search_api'                => home_url( 'wp-json/jveb/v2/search' ),
                'current_url'               => get_permalink(),
                'cat_is_ancestor_of_food'   => $cat_is_ancestor_of_food_array,
                'template_instagram'        => file_get_contents( Timber::compile_string( get_template_directory_uri() . '/views/components/instagram-post.twig', array() ) )
            )
        );


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
            'poppins'   => 'https://fonts.googleapis.com/css?family=Poppins:400,500,600,700',
            'lora'      => 'https://fonts.googleapis.com/css?family=Lora:400,700'
        );
    }


    /**
     * Handles JavaScript detection.
     * Adds a `js` class to the root `<html>` element when JavaScript is detected.
     *
     * @access public
     */
    public function javascript_detection() {
    ?>
        <script src="<?php echo get_template_directory_uri() ?>/dist/js/min/feature.min.js"></script>
        <script>
            document.documentElement.className = document.documentElement.className.replace('no-js', 'js');
            
            if (feature.touch && !navigator.userAgent.match(/Trident\/(6|7)\./)) {
                document.documentElement.className = document.documentElement.className.replace('no-touch', 'touch');
            }
        </script>
    <?php
    }


    /**
     * Add all favicon metas in <head>
     *
     * @see  http://realfavicongenerator.net/
     */
    function favicons() {

        ?>
        <?php
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

}


/**
 * Begins execution of the theme.
 */
$theme = new JVEB( 'jveb', '1.0.0' );