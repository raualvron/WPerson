<?php

/**
 * Create custom portfolio
 * post type and taxonomies
 *
 * @package FrameShift
 *
 */

/**
 * Register portfolio post type
 *
 * @since 1.0
 */
 
add_action( 'init', 'frameshift_register_portfolio' );

function frameshift_register_portfolio() {

	// Create labels for custom post type

    $labels = array( 
        'name' 				 => _x( 'Portfolio', 'portfolio', 'frameshift' ),
        'singular_name' 	 => _x( 'Portfolio Item', 'portfolio', 'frameshift' ),
        'add_new' 			 => _x( 'Add New', 'portfolio', 'frameshift' ),
        'add_new_item' 		 => _x( 'Add New Portfolio Item', 'portfolio', 'frameshift' ),
        'edit_item' 		 => _x( 'Edit Portfolio Item', 'portfolio', 'frameshift' ),
        'new_item' 			 => _x( 'New Portfolio Item', 'portfolio', 'frameshift' ),
        'view_item' 		 => _x( 'View Portfolio Item', 'portfolio', 'frameshift' ),
        'search_items' 		 => _x( 'Search Portfolio', 'portfolio', 'frameshift' ),
        'not_found' 		 => _x( 'No portfolio item found', 'portfolio', 'frameshift' ),
        'not_found_in_trash' => _x( 'No portfolio item found in Trash', 'portfolio', 'frameshift' ),
        'parent_item_colon'  => _x( 'Parent Portfolio Item:', 'portfolio', 'frameshift' ),
        'menu_name' 		 => _x( 'Portfolio', 'portfolio', 'frameshift' )
    );
    
    $labels = apply_filters( 'frameshift_portfolio_labels', $labels );
    
    // Set args for custom post type

    $args = array( 
        'labels' 				=> $labels,
        'hierarchical' 			=> false,        
        'supports' 				=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'trackbacks', 'custom-fields', 'comments', 'revisions' ),
        'taxonomies' 			=> array( 'skills' ),
        'public' 				=> true,
        'show_ui' 				=> true,
        'show_in_menu' 			=> true,
        'menu_position' 		=> 5,
        'menu_icon'       		=> FRAMESHIFT_ADMIN_IMAGES_URL . '/portfolio.png',
        'show_in_nav_menus' 	=> true,
        'publicly_queryable' 	=> true,
        'exclude_from_search' 	=> false,
        'has_archive' 			=> true,
        'query_var' 			=> true,
        'can_export' 			=> true,
        'rewrite' 				=> array( 'slug' => apply_filters( 'frameshift_portfolio_rewrite_slug', 'portfolio' ), 'with_front' => false ),
        'capability_type' 		=> 'post'
    );
    
    $args = apply_filters( 'frameshift_portfolio_args', $args );
    
    // Register custom post type

    register_post_type( 'portfolio', $args );
    
}

/**
 * Register skills custom taxonomy
 *
 * @since 1.0
 */

add_action( 'init', 'frameshift_register_taxonomy_skills' );

function frameshift_register_taxonomy_skills() {

	// Create labels for custom taxonomy

    $labels = array( 
        'name' 						 => _x( 'Skills', 'skills', 'frameshift' ),
        'singular_name' 			 => _x( 'Skill', 'skills', 'frameshift' ),
        'search_items' 				 => _x( 'Search Skills', 'skills', 'frameshift' ),
        'popular_items' 			 => _x( 'Popular Skills', 'skills', 'frameshift' ),
        'all_items' 				 => _x( 'All Skills', 'skills', 'frameshift' ),
        'parent_item' 				 => _x( 'Parent Skill', 'skills', 'frameshift' ),
        'parent_item_colon' 		 => _x( 'Parent Skill:', 'skills', 'frameshift' ),
        'edit_item' 				 => _x( 'Edit Skill', 'skills', 'frameshift' ),
        'update_item' 				 => _x( 'Update Skill', 'skills', 'frameshift' ),
        'add_new_item' 				 => _x( 'Add New Skill', 'skills', 'frameshift' ),
        'new_item_name' 			 => _x( 'New Skill', 'skills', 'frameshift' ),
        'separate_items_with_commas' => _x( 'Separate skills with commas', 'skills', 'frameshift' ),
        'add_or_remove_items' 		 => _x( 'Add or remove skills', 'skills', 'frameshift' ),
        'choose_from_most_used' 	 => _x( 'Choose from the most used skills', 'skills', 'frameshift' ),
        'menu_name' 				 => _x( 'Skills', 'skills', 'frameshift' )
    );
    
    $labels = apply_filters( 'frameshift_portfolio_skills_labels', $labels );
    
    // Set args for custom taxonomy

    $args = array( 
        'labels' 			=> $labels,
        'public' 			=> true,
        'show_in_nav_menus' => true,
        'show_ui' 			=> true,
        'show_tagcloud' 	=> true,
        'hierarchical' 		=> false,
        'rewrite' 			=> array( 'slug' => apply_filters( 'frameshift_portfolio_skills_rewrite_slug', 'skills' ), 'with_front' => false ),
        'query_var' 		=> true
    );
    
    $args = apply_filters( 'frameshift_portfolio_skills_args', $args );
    
    // Register custom taxonomy

    register_taxonomy( 'skills', array('portfolio'), $args );
    
}

/**
 * Flush rewrite rules
 *
 * @since 1.0
 */

add_action( 'load-themes.php', 'frameshift_flush_rewrite_rules' );

function frameshift_flush_rewrite_rules() {

	global $pagenow, $wp_rewrite;

	if ( 'themes.php' == $pagenow && isset( $_GET['activated'] ) )
		$wp_rewrite->flush_rules();
}

add_filter( 'wp_unique_post_slug_is_bad_flat_slug', 'portfolio_is_bad_flat_slug', 10, 3 );
function portfolio_is_bad_flat_slug( $is_bad_flat_slug, $slug, $post_type ) {
    if ( $slug == 'portfolio' )
        return true;
    return $is_bad_flat_slug;
}

/**
 * Create project details
 *
 * @since 1.0
 */
 
function frameshift_project_details() {

	$project_details = '';

	// Project description
	
	$project_description = get_post_meta( get_the_ID(), '_project_description', true );
	
	if( ! empty( $project_description ) ) {
		
		$project_details .= '<div class="portfolio-details-text">' . "\n";
		$project_details .= apply_filters( 'the_content', $project_description ) . "\n";
		$project_details .= '</div>' . "\n";
		
	}
	
	// Project client
	
	$project_client = get_post_meta( get_the_ID(), '_project_client', true );
	
	if( ! empty( $project_client ) ) {
		
		$project_details .= '<div class="project-details-client">' . "\n";
		$project_details .= '<span>' . apply_filters( 'frameshift_project_details_client', __( 'Client', 'frameshift' ) . ':' ) . '</span>';
		$project_details .= $project_client . "\n";
		$project_details .= '</div>' . "\n";
		
	}
	
	// Project date
	
	$project_details .= '<div class="project-details-date">' . "\n";
	$project_details .= '<span>' . apply_filters( 'frameshift_project_details_date', __( 'Date', 'frameshift' ) . ':' ) . '</span>';
	$project_details .= get_the_date() . "\n";
	$project_details .= '</div>' . "\n";
	
	// Project terms (skills)
	
	$project_details .= get_the_term_list( get_the_ID(), 'skills', '<div class="project-details-skills"><span>' . __( 'Skills', 'frameshift' ) . ':</span>', ', ', '</div>' );
	
	// Project URL
	
	$project_url = get_post_meta( get_the_ID(), '_project_url', true );
	
	if( ! empty( $project_client ) ) {
		
		$project_details .= '<div class="project-details-url">' . "\n";
		$project_details .= '<i class="icon-external-link"></i>' . "\n";
		$project_details .= '<a href="' . $project_url . '">' . apply_filters( 'frameshift_project_details_view', __( 'View Project', 'frameshift' ) ) . '</a>' . "\n";
		$project_details .= '</div>' . "\n";
		
	}
	
	return apply_filters( 'frameshift_project_details', $project_details );
	
}

/**
 * Add classes to prev and next links
 *
 * @since 1.0
 */

add_filter( 'previous_post_link', 'frameshift_next_link' );

function frameshift_next_link( $link ) {
	
	$link = str_replace( '<a', '<a class="arr arr-right" title="' . __( 'Previous', 'frameshift' ) . '"', $link );

	return $link;
	
}

add_filter( 'next_post_link', 'frameshift_prev_link' );

function frameshift_prev_link( $link ) {
	
	$link = str_replace( '<a', '<a class="arr arr-left" title="' . __( 'Next', 'frameshift' ) . '"', $link );

	return $link;
	
}


/**
 * Set number of projects on one page
 *
 * @since 1.0
 */

add_action( 'pre_get_posts', 'frameshift_portfolio_number' );

function frameshift_portfolio_number( $query ) {

	$projects_number = apply_filters( 'frameshift_portfolio_number', 12 );
	
    if ( $query->is_tax )
		$query->set( 'posts_per_page', $projects_number );
		
	return $query;
}