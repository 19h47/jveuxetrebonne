<?php
add_action( 'customize_register', 'jveb_customize_title_tagline' );

/**
 * Add settings to title-tagline
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function jveb_customize_title_tagline( $wp_customize ) {

	$wp_customize->add_setting(
		'background_image_newsletter',
		array(
			'type'      => 'option',
			'transport' => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'background_image_newsletter',
			array(
				'label'       => __( 'Newsletter image', 'jveuxetrebonne' ),
				'description' => __( 'Télécharger l\'image de fond du formulaire d\'inscription à la newsletter', 'jveuxetrebonne'),
				'section'     => 'title_tagline',
				'settings'    => 'background_image_newsletter',
			)
		)
	);
}
