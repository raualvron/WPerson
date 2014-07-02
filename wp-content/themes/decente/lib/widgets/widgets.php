<?php

/**
 * Register widget areas and
 * require widget files.
 *
 * @package FrameShift
 */
 
/**
 * Create widget areas array
 *
 * @since 1.0
 */
 
function frameshift_widget_areas() {

	$frameshift_widget_areas = array(
	
		'sidebar' => array(
			'name' 			=> __('General Sidebar', 'frameshift' ),
			'description' 	=> __('This is the primary sidebar to display the same widgets on all pages.', 'frameshift' ),
			'id' 			=> 'sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'sidebar-archive' => array(
			'name' 			=> __('Archive Sidebar', 'frameshift' ),
			'description' 	=> __('This is the sidebar on category, tag, author, date and search pages. If empty, archives will be displayed without sidebar.', 'frameshift' ),
			'id' 			=> 'sidebar-archive',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'sidebar-post' => array(
			'name' 			=> __('Post Sidebar', 'frameshift' ),
			'description' 	=> __('This is the sidebar on single post pages. If empty, posts will be displayed without sidebar.', 'frameshift' ),
			'id' 			=> 'sidebar-post',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'sidebar-page' => array(
			'name' 			=> __('Page Sidebar', 'frameshift' ),
			'description' 	=> __('This is the sidebar on static pages. If empty, pages will be displayed without sidebar.', 'frameshift' ),
			'id' 			=> 'sidebar-page',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'sidebar-portfolio' => array(
			'name' 			=> __('Portfolio Sidebar', 'frameshift' ),
			'description' 	=> __('This is the sidebar on portfolio pages.', 'frameshift' ),
			'id' 			=> 'sidebar-portfolio',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'home-top' => array(
			'name' 			=> __('Home Page Top', 'frameshift' ),
			'description' 	=> __('Top Content on the home page', 'frameshift' ),
			'id' 			=> 'home-top',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'home' => array(
			'name' 			=> __('Home Page Content', 'frameshift' ),
			'description' 	=> __('Main Content on the home page', 'frameshift' ),
			'id' 			=> 'home',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'sidebar-home' => array(
			'name' 			=> __('Home Page Sidebar', 'frameshift' ),
			'description' 	=> __('The sidebar on the home page', 'frameshift' ),
			'id' 			=> 'sidebar-home',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'home-bottom' => array(
			'name' 			=> __('Home Page Bottom', 'frameshift' ),
			'description' 	=> __('Bottom Content on the home page', 'frameshift' ),
			'id' 			=> 'home-bottom',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget' 	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		),
		
		'footer' => array(
			'name' 			=> __('Footer', 'frameshift' ),
			'description' 	=> __('Footer widget area', 'frameshift' ),
			'id' 			=> 'ffooter',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
			'after_widget'	=> '</div></div>',
			'before_title' 	=> '<h4 class="title">',
			'after_title' 	=> '</h4>'
		)
	
	);
	
	return apply_filters( 'frameshift_widget_areas', $frameshift_widget_areas );

}

/**
 * Register widget areas
 *
 * @since 1.0
 */
 
add_action( 'frameshift_setup', 'frameshift_register_widget_areas' );

function frameshift_register_widget_areas() {

	foreach( frameshift_widget_areas() as $widget_area ) {
	
		register_sidebar( $widget_area );
	
	}
}

/**
 * Create widths for widget settings.
 *
 * Array keys are bootstrap classes.
 * Array values are labels for widget settings.
 *
 * @since 1.0
 */
 
function frameshift_widget_widths() {

	if( FRAMESHIFT_LAYOUT == 'four' ) {

		$widget_widths = array(	
			'span12' => '4/4',
			'span9'  => '3/4',
			'span6'  => '2/4',
			'span3'  => '1/4'	
		);
	
	} else {
		
		$widget_widths = array(	
			'span12' => '3/3',
			'span8'  => '2/3',
			'span4'  => '1/3'	
		);
		
	}
	
	return apply_filters( 'frameshift_widget_widths', $widget_widths );

}

/**
 * Require widget files
 *
 * @since 1.0
 */

require_once( FRAMESHIFT_LIB_DIR . '/widgets/latest.php' );
require_once( FRAMESHIFT_LIB_DIR . '/widgets/latest-work.php' );
require_once( FRAMESHIFT_LIB_DIR . '/widgets/spaces.php' );
require_once( FRAMESHIFT_LIB_DIR . '/widgets/post-spaces.php' );
require_once( FRAMESHIFT_LIB_DIR . '/widgets/slider.php' );
require_once( FRAMESHIFT_LIB_DIR . '/widgets/divider.php' );
require_once( FRAMESHIFT_LIB_DIR . '/widgets/calltoaction.php' );

/**
 *  Activate shortcodes in widets
 *
 * @since 1.0
 */

add_filter( 'widget_text', 'do_shortcode' );