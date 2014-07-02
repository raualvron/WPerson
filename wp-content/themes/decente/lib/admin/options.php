<?php

/**
 * Create theme options arrays
 * for different tabs and merge
 * to return frameshift_options()
 *
 * @package FrameShift
 */
 
/**
 * Create theme options array
 * General options
 *
 * @since 1.0
 */

function frameshift_options_general() {
		
	$options_general = array();
		
	$options_general['heading_general'] = array(
		'name' => __( 'General', 'frameshift' ),
		'id'   => 'heading_general',
		'type' => 'heading'
	);
	
	$options_general['logo'] = array( 
		'name' => __( 'Logo', 'frameshift' ),
		'desc' => __( 'Please enter a URL or upload an image.', 'frameshift' ),
		'id'   => 'logo',
		'std'  => FRAMESHIFT_IMAGES . '/logo.png',
		'type' => 'upload'
	);
	
	$options_general['logo_text'] = array( 
		'name' => __( 'Text Logo', 'frameshift' ),
		'desc' => __( 'Please enter a text logo that will be displayed instead of the above image logo.', 'frameshift' ),
		'id'   => 'logo_text',
		'type' => 'text'
	);
	
	$options_general['favicon'] = array( 
		'name' => __( 'Favicon', 'frameshift' ),
		'desc' => __( 'Please enter a URL or upload an image.', 'frameshift' ),
		'id'   => 'favicon',
		'type' => 'upload'
	);
							
	$options_general['custom_rss'] = array( 
		'name' => __( 'Custom RSS', 'frameshift' ),
		'desc' => __( 'Please enter a custom RSS URL (e.g. Feedburner).', 'frameshift' ),
		'id'   => 'custom_rss',
		'type' => 'text'
	);	
							
	$options_general['custom_css'] = array( 
		'name' => __( 'Custom CSS', 'frameshift' ),
		'desc' => __( 'Add custom css to the head of your theme.', 'frameshift' ),
		'id'   => 'custom_css',
		'type' => 'textarea'
	); 
	
	$options_general['tracking'] = array( 
		'name' => __( 'Tracking Code', 'frameshift' ),
		'desc' => __( 'Insert your tracking code here (e.g. Google Analytics). This code will be added to the footer of the theme.', 'frameshift' ),
		'id'   => 'tracking',
		'type' => 'textarea'
	);
			
	return apply_filters( 'frameshift_options_general', $options_general );
	
}

/**
 * Create theme options array
 * Layout options
 *
 * @since 1.0
 */

function frameshift_options_layout() {

	/** Check theme support of layout options */

	if( ! current_theme_supports( 'options-layout' ) )
		return;
	
	/** Create options array */
		
	$options_layout = array();
		
	$options_layout['heading_layout'] = array(
		'name' => __( 'Layout', 'frameshift' ),
		'id'   => 'heading_layout',
		'type' => 'heading'
	);
	
	$options_layout['header_right'] = array( 
		'name' => __( 'Header Right', 'frameshift' ),
		'desc' => __( 'You can add custom HTML and/or shortcodes, which will be automatically inserted into your theme.', 'frameshift' ),
		'std'  => __( 'Add some info here', 'frameshift' ),
		'id'   => 'header_right',
		'rows' => 20,
		'type' => 'textarea'
	);
	
	if( FRAMESHIFT_LAYOUT == 'four' ) {
	
		$options_layout['archive_layout'] = array(
			'name' 	  => __( 'Archive Layout', 'frameshift' ),
			'desc' 	  => __( 'Please remember that the layout is also limited through the sidebar. If the archive sidebar is active, only option #1 and #3 will be possible.', 'frameshift' ),
			'id' 	  => 'archive_layout',
			'std' 	  => 'span9',
			'type' 	  => 'images',
			'options' => array(
				'span3'  => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-three.png',
				'span6'  => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-six.png',
				'span9'  => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-nine.png',
				'span12' => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-twelve.png'
			)
		);
	
	} else {
	
		$options_layout['archive_layout'] = array(
			'name' 	  => __( 'Archive Layout', 'frameshift' ),
			'desc' 	  => __( 'Please remember that the layout is also limited through the sidebar. If the archive sidebar is active, only option #1 and #3 will be possible.', 'frameshift' ),
			'id' 	  => 'archive_layout',
			'std' 	  => 'span8',
			'type' 	  => 'images',
			'options' => array(
				'span4'  => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-four.png',
				'span6'  => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-six.png',
				'span8'  => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-eight.png',
				'span12' => FRAMESHIFT_ADMIN_IMAGES_URL . '/archive-twelve.png'
			)
		);	
	
	}
	
	$options_layout['credit'] = array( 
		'name' => __( 'Credit', 'frameshift' ),
		'desc' => __( 'You can add custom HTML and/or shortcodes, which will be automatically inserted into your theme.', 'frameshift' ) . ' ' . __(' Available shortcodes', 'frameshift' ) . ': <code>[the_year]</code>, <code>[site_link]</code>, <code>[wordpress_link]</code>, <code>[themeshift_link]</code>, <code>[loginout_link]</code>',
		'std'  => '<div class="credit-top">' . "\n\t" . '<p>[the_year] - [site_link]</p>' . "\n" . '</div>' . "\n\n" . '<div class="credit-bottom">' . "\n\t" . '<p>Powered by [wordpress_link] - Built by [themeshift_link]</p>' . "\n" . '</div>',
		'id'   => 'credit',
		'rows' => 20,
		'type' => 'textarea'
	);
			
	return apply_filters( 'frameshift_options_layout', $options_layout );
	
}

/**
 * Create theme options array
 * Social options
 *
 * @since 1.0
 */

function frameshift_options_social() {

	/** Check theme support of social options */

	if( ! current_theme_supports( 'options-social' ) )
		return;
	
	/** Create options array */
		
	$options_social = array();
		
	$options_social['heading_social'] = array(
		'name' => __( 'Social', 'frameshift' ),
		'id'   => 'heading_social',
		'type' => 'heading'
	);
	
	/** Loop through social icons for info */
	
	$social_icons = array();
	foreach( frameshift_social_icons() as $k => $v ) {
		$social_icons[$k] = '<img src="' . $v['icon'] . '" alt="" />';
	}
	
	$social_icons = implode( '&nbsp;&nbsp;', $social_icons );
	
	$options_social['icon_info'] = array( 
	    'name' => __( 'Available Icons', 'frameshift' ),
	    'desc' => $social_icons,
	    'type' => 'info'
	);
	
	/** Loop through social icons for icons and links */
	
	$social_icons = array();
	foreach( frameshift_social_icons() as $k => $v ) {
		$social_icons[$k] = $v['name'];
	}
	
	array_unshift( $social_icons, '' );
	
	$nr = apply_filters( 'frameshift_social_icons_nr', 5 );
	
	for( $i = 1; $i <= $nr; $i++ ) {
	
		$std_icon = '';
		$std_link = '';
	
		/** Set first default to RSS */	
		
		if( $i == 1 ) {
			$std_icon = 'rss';
			$std_link = get_bloginfo_rss( 'rss2_url' );
		}
	
		$options_social['icon_' . $i] = array( 
			'name' 	  => __( 'Social', 'frameshift' ) . ' #' . $i . ' ' . __( 'Icon', 'frameshift' ),
			'desc' 	  => __( 'Please select an icon.', 'frameshift' ),
			'std' 	  => $std_icon,
			'id' 	  => 'icon_' . $i,
			'type' 	  => 'select',
			'options' => $social_icons
		);
		
		$options_social['icon_' . $i . '_link'] = array( 
			'name' => __( 'Social', 'frameshift' ) . ' #' . $i . ' ' . __( 'Link', 'frameshift' ),
			'desc' => __( 'Please enter the URL to your social account.', 'frameshift' ),
			'std'  => $std_link,
			'id'   => 'icon_' . $i . '_link',
			'type' => 'text'
		);	
	
	}
			
	return apply_filters( 'frameshift_options_social', $options_social );
	
}
 
/**
 * Merge option tabs and
 * return frameshift_options()
 *
 * @since 1.0
 */
 
function frameshift_options() {

	if( is_array( frameshift_options_general() ) )
		$options_general = frameshift_options_general();
		
	if( is_array( frameshift_options_layout() ) )
		$options_layout = frameshift_options_layout();
		
	if( is_array( frameshift_options_social() ) )
		$options_social = frameshift_options_social();
	
    $options = array_merge(
    	(array) $options_general,
    	(array) $options_layout,
    	(array) $options_social
    );
    
    return apply_filters( 'frameshift_options', $options );

}