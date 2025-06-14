<?php

/**
 * Helper Functions
 *
 * @package     WPeMatico\PluginName\Functions
 * @since       2.0.0
 */
// Exit if accessed directly
if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/**
 * Outputs a specific post's view count.  This is a wrapper function for wpecounter_get_post_views().  It simply 
 * prints the output of that function to the screen.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return void
 */
function wpecounter_post_views( $args = array() ) {
	echo wp_kses_post( wpecounter_get_post_views( $args ) );
}

/**
 * Template tag for getting a specific post's view count.  It will default to the current post in The 
 * Loop.  To use the 'text' argument, either pass a nooped plural using _n_noop() or a single text string.
 *
 * @since  1.0.0
 * @access public
 * @param  array  $args
 * @return string
 */
function wpecounter_get_post_views( $args = array() ) {

	$defaults = array(
		'post_id' => get_the_ID(),
		'before'  => '',
		'after'   => '',
		/* Translators: %s is the number of views a post has. */
		'text'    => '%s', //_n_noop( '%s View', '%s Views', 'wpecounter' ),
		'wrap'    => '<span %s>%s</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	if ( ! isset( $WPeCounterViews ) ) {
		$WPeCounterViews = new WPeCounterViews();
	}

	$views = $WPeCounterViews->get_post_views_count( $args['post_id'] );

	// If 'text' is empty, use default value
	$text = ( $args['text'] === '' ) ? $defaults['text'] : $args['text'];
	$text = is_array( $text ) ? translate_nooped_plural( $text, $views ) : $text;

	// Prepare output
	$attributes = 'class="wpecounter" itemprop="interactionCount" itemscope="itemscope" itemtype="http://schema.org/UserPageVisits"';
	$views_text = sprintf( esc_html( $text ), esc_html( $views ) );
	$html = sprintf( 
		$args['wrap'], 
			$attributes, 
			$views_text 
			);

	return wp_kses_post( $args['before'] ) . $html . wp_kses_post( $args['after'] );
}
