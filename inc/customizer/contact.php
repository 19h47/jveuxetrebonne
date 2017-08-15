<?php
add_action( 'customize_register', 'jveuxetrebonne_customize_contact' );
/**
 * Contact
 * 
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function jveuxetrebonne_customize_contact( $wp_customize ) {
    
    /**
     * Add contact section
     */
    $wp_customize->add_section( 
    	'contact', 
    	array(
        	'title' 		=> __( 'Coordonnées', 'jveb' ),
        	'description'	=> __( 'Réglages des coordonnées', 'jveb' ),
    	) 
    );


   	/**
   	 * Add Contact settings and controls in related section
   	 */
   	$wp_customize->add_setting( 
   		'address', 
   		array(
        	'type'      => 'option',
        	'transport' => 'postMessage',
    	) 
    );


    $wp_customize->add_control( 
    	'address', 
    	array(
        	'label'     	=> __( 'Adresse', 'jveb' ),
        	'description'	=> __( 'Indiquer l\'adresse postale', 'jveb'),
        	'type'      	=> 'textarea',
        	'section'   	=> 'contact',
        	'settings'  	=> 'address',
    	) 
    );

    
    /**
     * Phone
     */
    $wp_customize->add_setting( 
           'phone', 
           array(
           'type'      => 'option',
           'transport' => 'postMessage',
       ) 
    );


    $wp_customize->add_control( 
       'phone', 
       array(
           'label'         => __( 'Numéro de téléphone', 'jveb' ),
           'description'   => __( 'Indiquer le numéro de téléphone', 'jveb'),
           'section'       => 'contact',
           'settings'      => 'phone',
       ) 
    );


    /**
     * Facebook
     */
    $wp_customize->add_setting( 
   		'facebook', 
   		array(
        	'type'      => 'option',
        	'transport' => 'postMessage',
    	) 
    );


    $wp_customize->add_control( 
    	'facebook', 
    	array(
        	'label'     	=> __( 'Facebook', 'jveb' ),
        	'description'	=> __( 'Indiquer l\'URL du compte Facebook', 'jveb'),
        	'section'   	=> 'contact',
        	'settings'  	=> 'facebook',
    	) 
    );


    /**
     * Twitter
     */
    $wp_customize->add_setting( 
   		'twitter', 
   		array(
        	'type'      => 'option',
        	'transport' => 'postMessage',
    	) 
    );


    $wp_customize->add_control( 
    	'twitter', 
    	array(
        	'label'     	=> __( 'Twitter', 'jveb' ),
        	'description'	=> __( 'Indiquer l\'URL du compte Twitter', 'jveb'),
        	'section'   	=> 'contact',
        	'settings'  	=> 'twitter',
    	) 
    );


    /**
     * Instagram
     */
    $wp_customize->add_setting( 
        'instagram', 
        array(
            'type'      => 'option',
            'transport' => 'postMessage',
        ) 
    );
    $wp_customize->add_control( 
        'instagram', 
        array(
            'label'         => __( 'Instagram', 'jveb' ),
            'description'   => __( 'Indiquer l\'URL du compte Instagram', 'jveb'),
            'section'       => 'contact',
            'settings'      => 'instagram',
        ) 
    );


    /**
     * Pinterest
     */
    $wp_customize->add_setting( 
        'pinterest', 
        array(
            'type'      => 'option',
            'transport' => 'postMessage',
        ) 
    );
    $wp_customize->add_control( 
        'pinterest', 
        array(
            'label'         => __( 'Pinterest', 'jveb' ),
            'description'   => __( 'Indiquer l\'URL du compte Pinterest', 'jveb'),
            'section'       => 'contact',
            'settings'      => 'pinterest',
        ) 
    );


    /**
     * YouTube
     */
    $wp_customize->add_setting( 
        'youtube', 
        array(
            'type'      => 'option',
            'transport' => 'postMessage',
        ) 
    );
    $wp_customize->add_control( 
        'youtube', 
        array(
            'label'         => __( 'YouTube', 'jveb' ),
            'description'   => __( 'Indiquer l\'URL du compte YouTube', 'jveb'),
            'section'       => 'contact',
            'settings'      => 'youtube',
        ) 
    );
}