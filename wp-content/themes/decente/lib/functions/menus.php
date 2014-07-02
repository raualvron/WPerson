<?php

/**
 * Built custom menus
 *
 * @package FrameShift
 *
 */
 
/**
 * Create menus array
 *
 * @since 1.0
 */
 
function frameshift_menus() {

	$frameshift_menus = array(
		'main' 	   => __( 'Main Menu', 'frameshift' ),
		'bottom'   => __( 'Bottom Menu', 'frameshift' )
	);
	
	return apply_filters( 'frameshift_menus', $frameshift_menus );

}
 
/**
 * Register custom menus
 *
 * @since 1.0
 */
 
add_action( 'frameshift_setup', 'frameshift_register_menus' );

function frameshift_register_menus() {

	foreach( frameshift_menus() as $menu => $label ) {
	
		register_nav_menu( 'menu-' . $menu, $label );	
	
	}

}

/**
 * Add option home to menu items
 *
 * @since 1.0
 */
 
add_filter( 'wp_page_menu_args', 'home_page_menu_item' );

function home_page_menu_item( $args ) {

	$args['show_home'] = true;
	
	return $args;
	
}

/**
 * Create menu with optional fallback
 *
 * @since 1.0
 */
 
function frameshift_menu( $menu_location, $menu_default = false ) {

	// Stop it if there is no menu location

	if( empty( $menu_location ) )
		return false;
		
	// Call custom menu if exists

	if( has_nav_menu( 'menu-' . $menu_location ) ) {
	
	    $frameshift_menu = wp_nav_menu( array( 'sort_column' => 'menu_order', 'container_class' => 'frameshift-menu frameshift-menu-'.$menu_location, 'menu_class' => 'sf-menu', 'theme_location' => 'menu-'.$menu_location, 'echo' => false ) );
	    
	}
	
	// Show link to custom menus admin page if default true
	
	if( ! has_nav_menu( 'menu-' . $menu_location ) && $menu_default === true ) {
	
		$frameshift_menu = '<div class="frameshift-menu frameshift-menu-' . $menu_location . '"><ul class="sf-menu">';
		$frameshift_menu .= '<li class="current-menu-item"><a href="' . home_url() . '/wp-admin/nav-menus.php">' . __('Create a custom menu', 'frameshift') . ' &rarr;</a></li>';
		$frameshift_menu .= '</ul></div>';
		
	}
	
	if( ! empty( $frameshift_menu ) )	
		return $frameshift_menu;
		
}