<?php
/**
 * Functionality related to CSS in inn.org's content
 *
 * @since 0.1
 */

namespace INN\Plugin\CSS;

/**
 * Filter upon WordPress' safe_style_css array
 *
 * @param Array $styles the allowable styles
 * @return Array
 * @link https://developer.wordpress.org/reference/hooks/safe_style_css/
 * @link https://wordpress.stackexchange.com/questions/173526/why-is-wp-kses-not-keeping-style-attributes-as-expected/195433#195433
 * @link https://secure.helpscout.net/conversation/671498742/2532/?folderId=1219602 because of this
 */
function safe_style_css( $styles ) {
	$styles[] = 'columns';
	$styles[] = 'column-width';
	$styles[] = 'column-count';

	return $styles;
}
add_action( 'safe_style_css', __NAMESPACE__ . '\safe_style_css' ) );
