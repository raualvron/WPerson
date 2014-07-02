<?php

/**
 * Built the footer with widget area and
 * credit bottom line with copyright etc.
 *
 * @package FrameShift
 *
 */
 
/**
 * Built credit area and add to
 * frameshift_footer_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_footer_after', 'frameshift_do_subfooter' );
 
function frameshift_do_subfooter() {

	// Open layout wrap
	frameshift_layout_wrap( 'subfooter-wrap' );
	
	// Loop through social icons
		
	$nr = apply_filters( 'frameshift_social_icons_nr', 5 );
	
	$social_icons = array();
	
	for( $i = 1; $i <= $nr; $i++ ) {				    
	    $social_icons[] = frameshift_get_social_icon( frameshift_get_option( 'icon_' . $i ) );				    
	}
	
	// Remove empty elements
	$social_icons = array_filter( $social_icons );
	
	$output = '<div class="social-icons">';
	
	if( ! empty( $social_icons ) ) {					
	    $i = 1;														
	    foreach( $social_icons as $k => $v ) {				
	        $social_link = frameshift_get_option( 'icon_' . $i . '_link' );				    	
	        $output .= '<a href="' . $social_link . '" target="_blank" title="' . $v['title'] . '" class="social-icon social-icon-' . $v['id'] . '"><img src="' . $v['icon'] . '" alt="" /></a>' . "\n";				    		
	        $i++;				    		
	    }				    
	} else {
	    $social_icon = frameshift_get_social_icon( 'rss' );
	    $output .= '<a href="' . get_bloginfo_rss( 'rss2_url' ) . '" target="_blank" title="' . $social_icon['title'] . '" class="social-icon social-icon-' . $social_icon['id'] . '"><img src="' . $social_icon['icon'] . '" alt="" /></a>' . "\n";
	}
	
	$output .= '</div><!-- .social-icons -->';
	
	if( ! empty( $output ) )
		echo apply_filters( 'frameshift_social_icons_top', $output );
		
	if( has_nav_menu( 'menu-bottom' ) ) { ?>

		<div id="bottom-menu">		
			<?php echo frameshift_menu( 'bottom' ); ?>	
		</div><!-- #bottom-menu --><?php		
	
	}
    
    // Close layout wrap	
	frameshift_layout_wrap( 'subfooter-wrap', 'close' );

}

/**
 * Add credit info below subfooter
 * with frameshift_footer_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_after', 'frameshift_do_credit' );

function frameshift_do_credit() {
	
	// Show credit info

	$credit = frameshift_get_option( 'credit', true );
	
	if( ! empty( $credit ) ) {
	
		// Open layout wrap
		frameshift_layout_wrap( 'credit-wrap' ); ?>

		<div id="credit" class="clearfix">			    
		    <?php echo do_shortcode( $credit ); ?>
		</div><!-- #credit --><?php
		
		// Close layout wrap	
		frameshift_layout_wrap( 'credit-wrap', 'close' );
		
	}

}

/**
 * Add tracking to theme footer
 *
 * @since 1.0
 */

add_action( 'frameshift_after', 'frameshift_do_tracking' );

function frameshift_do_tracking() {

	$tracking = frameshift_get_option( 'tracking' );

	if( ! empty( $tracking ) )
		echo stripslashes( $tracking ) . "\n\n";
	
}