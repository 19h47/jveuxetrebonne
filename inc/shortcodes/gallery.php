<?php

add_filter( 'post_gallery', 'slider_shortcode', 10, 3 );

/**
 * Gallery shortcode to slider
 * 
 * @see  http://wpsites.org/change-gallery-output-wordpress-10510/
 * @see  https://core.trac.wordpress.org/browser/trunk/src/wp-includes/media.php#L1597
 * @author Jérémy Levron <levronjeremy@19h47.fr>
 */
function slider_shortcode( $output, $attr ) {
    global $post;
 
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }
    $atts = shortcode_atts( array(
        'order'         => 'ASC',
        'orderby'       => 'menu_order ID',
        'id'            => $post->ID,
        'itemtag'       => 'dl',
        'icontag'       => 'dt',
        'columns'       => 3,
        'size'          => 'thumbnail',
        'include'       => '',
        'exclude'       => ''
    ), $attr, 'gallery' );
 
    $id = intval( $atts['id'] );
 
    $_attachments = get_posts(
        array(
            'include'           => $atts['include'], 
            'post_status'       => 'inherit', 
            'post_type'         => 'attachment', 
            'post_mime_type'    => 'image', 
            'order'             => $atts['order'], 
            'orderby'           => $atts['orderby']
        )
    );
    
    $attachments = array();
    foreach ( $_attachments as $key => $val ) {
        $attachments[$val->ID] = $_attachments[$key];
    }

    if ( empty( $attachments ) ) {
        return '';
    }   
    
    $context = Timber::get_context();

    $context['post'] = array();
    $context['post']['gallery'] = array();

    
    foreach ( $attachments as $id => $attachment ) {

        array_push( $context['post']['gallery'], $attachment->ID ); 
    }


    $count_attachments = count( $attachments );
    // Progression
    if ( $count_attachments > 1 ) {

        $context['post']['count_attachments'] = $count_attachments;
    }

    return Timber::compile( "partials/slider-post.twig", $context );
}
