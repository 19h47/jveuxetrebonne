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
    $output = "<div class=\"Slider-post js-slider-post\">";
    $output .= "<ul class=\"Slider-post__items js-slider-post-container\">";
    
    $i = 1;
    
    foreach ($attachments as $id => $attachment) {
        $img = wp_get_attachment_image_src( $id, 'large' );
        $alt = get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true);

        if ( empty( $alt )) {
            $alt = $attachment->post_title;
        }

        if ( empty( $alt )) {
            $alt = $attachment->post_excerpt;
        }
        
        $output .= "<li class=\"Slider-post__item\">";
        $output .= "<img class=\"Slider-post__img\" src=\"{$img[0]}\" alt=\"{$alt}\">\n";
        $output .= "</li>";

        $i++;
    }
 
    $output .= "</ul>";

    $count_attachments = count( $attachments );
    // Progression
    if ( $count_attachments > 1 ) {
        $output .= "<div class=\"Slider-post__progressbar js-slider-post-progressbar\" role=\"progressbar\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>";

        // Navigation
        $output .="<div class=\"Slider-post__navigation\">";

        $output .= "<button class=\"previous js-slider-post-previous\"></button>";
        $output .= "<button class=\"next js-slider-post-next\"></button>";

        $output .= "<span class=\"count\"><i class=\"count-inner js-slider-post-count\" data-count=\"1\">/{$count_attachments}</i></span>";

        $output .= "</div>";
    }


    $output .= "</div>";
 
    return $output;
}
