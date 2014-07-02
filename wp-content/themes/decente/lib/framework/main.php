<?php

/**
 * Built the general layout with main top, main bottom
 * area and main middle (content & sidebar).
 *
 * @package FrameShift
 *
 */

/**
 * Function to add structural wraps around
 * basic layout elements.
 *
 * @since 1.0
 */
 
function frameshift_layout_wrap( $wrap_id = '', $close = '' ) {

	if ( empty( $wrap_id ) )
		return;
		
	$layout_wrap = '';
		
	if ( $close != 'close' ) {	
		$layout_wrap .= '<div id="' . $wrap_id . '" class="wrap">' . "\n";		
		$layout_wrap .= "\t" . '<div class="container">' . "\n";		
	} else {		
		$layout_wrap .= "\t" . '</div><!-- .container -->' . "\n";		
		$layout_wrap .= '</div><!-- #' . $wrap_id . ' -->' . "\n\n";
	}
	
	echo apply_filters( 'frameshift_layout_wrap', $layout_wrap );

}

/**
 * Built main top widget area
 * currently only on home page.
 *
 * @since 1.0
 */
 
add_action( 'frameshift_main_before', 'frameshift_do_main_top' );
 
function frameshift_do_main_top() {

	$args = array(
		'only_front' => true
	);
	
	$args = apply_filters( 'frameshift_do_main_top_args', $args );

	// Only on home page?
	if( ! is_front_page() && $args['only_front'] == true )
		return;
	
	// Only if widget area active
	if( ! is_active_sidebar( 'home-top') )
		return;

	// Open layout wrap
	frameshift_layout_wrap( 'main-top-wrap' ); ?>

	<div id="main-top" class="clearfix">
		<?php dynamic_sidebar( 'home-top' ); ?>
	</div><!-- #main-top --><?php
    
    // Close layout wrap
	frameshift_layout_wrap( 'main-top-wrap', 'close' );

}

/**
 * Built main bottom widget area
 * currently only on home page.
 *
 * @since 1.0
 */
 
add_action( 'frameshift_main_after', 'frameshift_do_main_bottom' );
 
function frameshift_do_main_bottom() {

	$args = array(
		'only_front' => true
	);
	
	$args = apply_filters( 'frameshift_do_main_bottom_args', $args );

	// Only on home page?
	if( ! is_front_page() && $args['only_front'] == true )
		return;
	
	// Only if widget area active
	if( ! is_active_sidebar( 'home-bottom') )
		return;	

	// Open layout wrap
	frameshift_layout_wrap( 'main-bottom-wrap' ); ?>

	<div id="main-bottom" class="clearfix">
		<?php dynamic_sidebar( 'home-bottom' ); ?>
	</div><!-- #main-bottom --><?php
    
    // Close layout wrap
	frameshift_layout_wrap( 'main-bottom-wrap', 'close' );

}