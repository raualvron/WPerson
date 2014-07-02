<?php

/**
 * Create general shortcodes with
 * Bootstrap classes
 *
 * @package FrameShift
 */
 
/**
 * Shortcode to output buttons
 *
 * @since 1.0
 */
 
add_shortcode( 'button', 'frameshift_button_shortcode' );

function frameshift_button_shortcode( $atts ) {

	$defaults = array( 
		'before' => '',
		'after'  => '',
		'label'	 => '[linktext]',
		'url' 	 => '',
		'id'	 => '',
		'class'  => 'btn',
		'size'	 => '',
		'icon'	 => '',
		'autop'  => '',
		'target' => '',
		'status' => ''
	);
	
	extract( shortcode_atts( $defaults, $atts ) );
	
	// Set link href
	$href = ! empty( $id ) ? get_permalink( (int) $id ) : esc_url( $url );
	
	// Set link class
	$class = ! empty( $class ) ? 'btn ' . $class : 'btn';
	
	// Set button size
	$class .= ! empty( $size ) ? ' btn-' . $size : false;
	
	// Set button icon
	$icon = ! empty( $icon ) ? '<i class="icon-' . $icon . '"></i> ' : false;
	
	// Set link target
	$target = ! empty( $target ) ? ' target="' . $target . '"' : false;
	
	// Set link status
	$class .= ( $status == 'disabled' ) ? ' disabled' : false;
	
	$output = sprintf( '%1$s<a class="%5$s" href="%4$s"%7$s>%6$s%3$s</a>%2$s', $before, $after, $label, $href, $class, $icon, $target );
	
	// Wrap in p tags
	if( $autop != 'false' )
		$output = wpautop( $output );

	return apply_filters( 'frameshift_button_shortcode', $output, $atts );

}

/**
 * Shortcode to output alert boxes
 *
 * @since 1.0
 */
 
add_shortcode( 'alert', 'frameshift_alert_shortcode' );

function frameshift_alert_shortcode( $atts, $content = null ) {

	$defaults = array(
		'class'   => 'alert alert-block',
		'width'   => '',
		'close'   => '',
		'heading' => ''
	);

    extract( shortcode_atts( $defaults, $atts ) );
    
    // Set class
    $class = ! empty( $class ) ? 'alert alert-block ' . $class : 'alert alert-block';
    
    // Set width
    $width = ! empty( $width ) ? ' style="width:' . $width . '"' : false;
    
    // Set close
    $close = ( $close == 'true' ) ? '<a class="close" data-dismiss="alert">&times;</a>' : false;
    
    // Set heading
    $heading = ! empty( $heading ) ? '<h3 class="alert-heading">' . $heading . '</h3>' : false;
		
	// Allow shortcodes in $content
	$content = do_shortcode( $content );
    
    $output = sprintf( '<div class="%2$s"%3$s>%4$s%5$s%1$s</div>', $content, $class, $width, $close, $heading );
    
    return apply_filters( 'frameshift_alert_shortcode', $output, $atts );

}