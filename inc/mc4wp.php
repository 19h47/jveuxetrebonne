<?php
/**
 * MC4WP
 */

use Timber\{ Timber };

add_filter( 'mc4wp_form_content', 'jveb_form_content', 5, 3 );

/**
 * Form content
 *
 * @param  string             $content The form content.
 * @param  MC4WP_Form         $form    MC4WP_Form instance.
 * @param  MC4WP_Form_Element $element MC4WP_Form_Element instance.
 * @return string                      [description]
 */
function jveb_form_content( string $content, MC4WP_Form $form, MC4WP_Form_Element $element ) : string {
	$content = Timber::compile( 'partials/form.html.twig', array(), false );

	return $content;
}
