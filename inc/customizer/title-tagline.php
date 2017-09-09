<?php
add_action( 'customize_register', 'jveb_customize_title_tagline' );
/**
 * 
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function jveb_customize_title_tagline( $wp_customize ) {
   /**
     * Add settings to title-tagline
     */
    $wp_customize->add_setting( 
        'banner',
        array(
            'type'      => 'option',
            'transport' => 'postMessage',
        ) 
    );
    $wp_customize->add_control( 
        new WP_Customize_Image_Control( 
            $wp_customize, 
            'logo', 
            array(
                'label'         => __( 'Bannière', 'jveb' ),
                'description'   => __( 'Télécharger la bannière du site', 'jveb'),
                'section'       => 'title_tagline',
                'settings'      => 'banner'
            )
        ) 
    );
}