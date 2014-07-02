<?php

/**
 * Create shortcodes for
 * footer-related stuff
 *
 * @package FrameShift
 */
 
/**
 * Shortcode to output the year
 *
 * @since 1.0
 */
 
add_shortcode( 'the_year', 'frameshift_year_shortcode' );

function frameshift_year_shortcode( $atts ) {

	$defaults = array( 
	    'before' => '&copy; ',
	    'after'  => '',
	    'first'  => '',
	    'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	$first_year = ( ! empty( $first ) && $first != date( 'Y' ) ) ? $first . ' &ndash; ' : false;

	$output = sprintf( '<%5$s class="the-year">%1$s%4$s%3$s%2$s</%5$s>', $before, $after, date( 'Y' ), $first_year, $wrap );

	return apply_filters( 'frameshift_year_shortcode', $output, $atts );

}

/**
 * Shortcode to output the site link
 *
 * @since 1.0
 */
 
add_shortcode( 'site_link', 'frameshift_site_link_shortcode' );

function frameshift_site_link_shortcode( $atts ) {

	$defaults = array( 
		'before' => '',
		'after'  => '',
		'url' 	 => home_url(),
		'label'  => get_bloginfo( 'name' ),
		'title'  => get_bloginfo( 'description' ),
		'target' => ''
	);
	
	extract( shortcode_atts( $defaults, $atts ) );
	
	$target = ( ! empty( $target ) ) ? ' target="' . $target . '"' : false;

	$output = sprintf( '%1$s<a href="%3$s" title="%5$s"%6$s>%4$s</a>%2$s', $before, $after, $url, $label, $title, $target );

	return apply_filters( 'frameshift_site_link_shortcode', $output, $atts );

}

/**
 * Shortcode to output WordPress link
 *
 * @since 1.0
 */
 
add_shortcode( 'wordpress_link', 'frameshift_wordpress_link_shortcode' );

function frameshift_wordpress_link_shortcode( $atts ) {

	$defaults = array( 
		'before' => '',
		'after'  => '',
		'url' 	 => 'http://wordpress.org',
		'label'  => 'WordPress',
		'title'  => 'WordPress',
		'target' => ''
	);
	
	extract( shortcode_atts( $defaults, $atts ) );
	
	$target = ( ! empty( $target ) ) ? ' target="' . $target . '"' : false;

	$output = sprintf( '%1$s<a href="%3$s" title="%5$s"%6$s>%4$s</a>%2$s', $before, $after, $url, $label, $title, $target );

	return apply_filters( 'frameshift_wordpress_link_shortcode', $output, $atts );

}

/**
 * Shortcode to output ThemeShift link
 *
 * @since 1.0
 */
 
add_shortcode( 'themeshift_link', 'frameshift_themeshift_link_shortcode' );

function frameshift_themeshift_link_shortcode( $atts ) {

	$defaults = array( 
		'before' => '',
		'after'  => '',
		'url' 	 => 'http://themeshift.com',
		'label'  => 'ThemeShift',
		'title'  => 'Professional WordPress Themes',
		'target' => ''
	);
	
	extract( shortcode_atts( $defaults, $atts ) );
	
	$target = ( ! empty( $target ) ) ? ' target="' . $target . '"' : false;

	$output = sprintf( '%1$s<a href="%3$s" title="%5$s"%6$s>%4$s</a>%2$s', $before, $after, $url, $label, $title, $target );

	return apply_filters( 'frameshift_themeshift_link_shortcode', $output, $atts );

}

/**
 * Shortcode to output login/out link
 *
 * @since 1.0
 */

add_shortcode( 'loginout_link', 'frameshift_loginout_link_shortcode' );

function frameshift_loginout_link_shortcode( $atts ) {
	
	$defaults = array(
		'redirect' => wp_get_referer(),
		'before'   => '',
		'after'    => ''
	);
	
	extract( shortcode_atts( $defaults, $atts ) );
	
	if ( ! is_user_logged_in() ) {
		$link = '<a href="' . esc_url( wp_login_url( $redirect ) ) . '">' . __( 'Log in', 'frameshift' ) . '</a>';
	} else {
		$link = '<a href="' . esc_url( wp_logout_url( $redirect ) ) . '">' . __( 'Log out', 'frameshift' ) . '</a>';
	}
	
	$output = sprintf( '%1$s<span>%3$s</span>%2$s', $before, $after, apply_filters( 'loginout', $link ) );

	return apply_filters( 'frameshift_loginout_link_shortcode', $output, $atts );

}