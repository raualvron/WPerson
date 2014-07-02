<?php

/**
 * Create post options (meta boxes)
 *
 * @package FrameShift
 * @suppackage Post Options API
 * @version 1.0.1
 */
 
add_action( 'init', 'frameshift_portfolio_options' );
 
/**
 * Create post options array
 *
 * @since 1.0
 */

function frameshift_portfolio_options() {

	/** Initialize Post Options API */

	require_once( trailingslashit( get_template_directory() ) . 'lib/admin/post-options-api.php' );
	
	$post_options = get_post_options_api( '1.0.1' );
    $post_fields = get_post_options_api_fields( '1.0.1' );
	
	/**
	 * Post layout
	 */
	 
	if( current_theme_supports( 'portfolio-layout' ) ) {
	
		$post_options->register_post_options_section( 'layout', frameshift_portfolio_layout_options( 'title' ) );
		
		foreach( frameshift_portfolio_layout_options( 'post_types' ) as $p ) {
			$post_options->add_section_to_post_type( 'layout', $p );
		}
		
		$post_options->register_post_option(
			array(
				'id' 	   => '_layout',
				'title'    => frameshift_portfolio_layout_options( 'sidebar_label' ),
				'section'  => 'layout',
				'callback' => 'portfolio_options_api_page_layout_callback'
			)
		);
	
	}
	
}

/**
 * Post options layout callback
 *
 * @since 1.0
 */

function portfolio_options_api_page_layout_callback( $args ) {

	$layouts = array(
		'sidebar-right' => __( 'Right sidebar', 'frameshift' ),
		'full-width' 	=> __( 'No sidebar', 'frameshift' ),
		'sidebar-left'  => __( 'Left sidebar', 'frameshift' )
	);
	
	$layouts = apply_filters( 'frameshift_post_options_layouts', $layouts );
	
	/* Set default layout */
	
	if( empty( $args['value'] ) )
		$args['value'] = 'sidebar-right';
	
	foreach( $layouts as $layout => $caption ): ?>
	
	<div style="float: left; margin-right: 15px">
		<label style="float: left; clear: both;">
			<input <?php echo checked( $layout == $args['value'] ); ?> type="radio" name="<?php echo $args['name_attr']; ?>" value="<?php echo $layout; ?>" style="margin-bottom: 4px;" /><br />
			<img src="<?php echo FRAMESHIFT_ADMIN_IMAGES_URL . '/' . $layout . '.png'; ?>" class="layout-img" /><br />
			<span class="description" style="margin-top: 5px; float: left;"><?php echo $caption; ?></span>
		</label>
	</div>
	
	<?php endforeach;

}

/**
 * Helper function to get
 * post layout options
 *
 * @since 1.0
 */
 
function frameshift_portfolio_layout_options( $option = '' ) {

	$layout_args = array(
	    'title' 				  => __( 'Layout', 'frameshift' ),
	    'post_types' 			  => array( 'portfolio' ),
	    'sidebar_label' 		  => __( 'Sidebar', 'frameshift' )
	);
	
	$layout_args = apply_filters( 'frameshift_post_layout_args', $layout_args );
	
	if( ! empty( $option ) ) {
		return $layout_args[$option];
	} else {
		return $layout_args;
	}

}

/**
 * Add post option CSS to admin head
 *
 * @since 1.0
 */    		
			
add_action( 'admin_head', 'frameshift_portfolio_options_styles' );

function frameshift_portfolio_options_styles() {
	
	if( is_admin() && current_theme_supports( 'portfolio-layout' ) )
    	wp_enqueue_style( 'post-options', FRAMESHIFT_ADMIN_URL . '/css/admin-style.css' );
    			
}