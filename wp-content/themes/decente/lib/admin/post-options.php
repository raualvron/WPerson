<?php

/**
 * Create post options (meta boxes)
 *
 * @package FrameShift
 * @suppackage Post Options API
 * @version 1.0.1
 */
 
add_action( 'init', 'frameshift_post_options' );
 
/**
 * Create post options array
 *
 * @since 1.0
 */
 
function frameshift_post_options() {

	// Initialize Post Options API
	
	require_once( trailingslashit( get_template_directory() ) . 'lib/admin/post-options-api.php' );
	
	$post_options = get_post_options_api( '1.0.1' );
    $post_fields = get_post_options_api_fields( '1.0.1' );
	
	/**
	 * Post layout
	 */
	 
	if( current_theme_supports( 'post-layout' ) ) {
	
		$post_options->register_post_options_section( 'layout', frameshift_post_layout_options( 'title' ) );
		
		foreach( frameshift_post_layout_options( 'post_types' ) as $p ) {
			$post_options->add_section_to_post_type( 'layout', $p );
		}
		
		$post_options->register_post_option(
			array(
				'id' 	   => '_layout',
				'title'    => frameshift_post_layout_options( 'sidebar_label' ),
				'section'  => 'layout',
				'callback' => 'post_options_api_page_layout_callback'
			)
		);
		
		// Get image sizes and create select data
		
		$image_sizes = array();		
		foreach ( frameshift_image_sizes() as $k => $v ) {		
			$image_sizes[$k] = $v['label'] . ' (' . $v['size']['w'] . 'x' . $v['size']['h'] . 'px)';		
		}
		
		$post_options->register_post_option(
		    array(
		    	'id' 	   => '_image_size',
		    	'title'    => frameshift_post_layout_options( 'image_size_label' ),
		    	'section'  => 'layout',
		    	'callback' => 'post_options_api_image_size_callback'
		    )
		);
		
		$post_options->register_post_option(
		    array(
		    	'id' 	   => '_image_align',
		    	'title'    => frameshift_post_layout_options( 'image_align_label' ),
		    	'section'  => 'layout',
		    	'callback' => 'post_options_api_image_align_callback'
		    )
		);
	
	}
	
	/**
	 * Portfolio details
	 */
	
	if( current_theme_supports( 'portfolio-details' ) ) {
	
		$post_options->register_post_options_section( 'details-portfolio', frameshift_portfolio_details_options( 'title' ) );
		
		foreach( frameshift_portfolio_details_options( 'post_types' ) as $p ) {
			$post_options->add_section_to_post_type( 'details-portfolio', $p );
		}
		
		$post_options->register_post_option(
			array(
				'id' 	   => '_project_description',
				'title'    => frameshift_portfolio_details_options( 'description_label' ),
				'section'  => 'details-portfolio',
				'callback' => $post_fields->textarea(
			    	array(
			    	    'rows' => 5
			    	)
			    )
			)
		);
		
		$post_options->register_post_option(
		    array(
		    	'id' 	   => '_project_short',
		    	'title'    => frameshift_portfolio_details_options( 'short_label' ),
		    	'section'  => 'details-portfolio',
		    	'callback' => $post_fields->text()
		    )
		);
		
		$post_options->register_post_option(
		    array(
		    	'id' 	   => '_project_client',
		    	'title'    => frameshift_portfolio_details_options( 'client_label' ),
		    	'section'  => 'details-portfolio',
		    	'callback' => $post_fields->text()
		    )
		);
		
		$post_options->register_post_option(
		    array(
		    	'id' 	   => '_project_url',
		    	'title'    => frameshift_portfolio_details_options( 'url_label' ),
		    	'section'  => 'details-portfolio',
		    	'callback' => $post_fields->text()
		    )
		);
	
	}
	
	/**
	 * Portfolio layout
	 */
	
	if( current_theme_supports( 'portfolio-layout' ) ) {
	
		$post_options->register_post_options_section( 'layout-portfolio', frameshift_portfolio_layout_options( 'title' ) );
		
		foreach( frameshift_portfolio_layout_options( 'post_types' ) as $p ) {
			$post_options->add_section_to_post_type( 'layout-portfolio', $p );
		}
		
		$post_options->register_post_option(
			array(
				'id' 	   => '_layout',
				'title'    => frameshift_portfolio_layout_options( 'sidebar_label' ),
				'section'  => 'layout-portfolio',
				'callback' => 'post_options_api_page_layout_callback'
			)
		);
		
		$post_options->register_post_option(
		    array(
		    	'id' 	   => '_image',
		    	'title'    => frameshift_portfolio_layout_options( 'image_display' ),
		    	'section'  => 'layout-portfolio',
		    	'callback' => 'post_options_api_image_display_callback'
		    )
		);
		
		$post_options->register_post_option(
	        array( 
	        	'id' 	   => '_lightbox',
	        	'title'    => frameshift_portfolio_layout_options( 'thumbnail_label' ),
	        	'section'  => 'layout-portfolio',
	        	'callback' => $post_fields->checkbox(
	        		array(
	        			'label' => frameshift_portfolio_layout_options( 'thumbnail_description' )
	        		)
	        	)
	        )
	    );
	
	}
    
    /**
	 * Post spaces
	 */
	
	if( current_theme_supports( 'post-spaces' ) ) {
		
		// Loop through existing spaces
		
		foreach( frameshift_post_spaces() as $space => $v ) {		
		
			// Set up section and apply to posts and pages
    		
    		$post_options->register_post_options_section( $space, $v['title'] );
    		
    		// Add section to post types
    		
    		foreach( $v['post_type'] as $post_type ) {
    			$post_options->add_section_to_post_type( $space, $post_type );
    		}
		
			// Set type and rows
		
			$space_type = ( $v['type'] == 'textarea' ) ? 'textarea' : 'text';
			$space_rows = ( isset( $v['rows'] ) ) ? (int) $v['rows'] : 5;
			
			// Create meta box
		
			$post_options->register_post_option(
			    array(
			    	'id' => $v['key'],
			    	'title' => $v['label'],
			    	'section' => $space,
			    	'callback' => $post_fields->$space_type(
			    		array(
			    			'description' => $v['description'],
			    			'rows'		  => $space_rows
			    		)
			    	)
			    )
			);
		
		} // endforeach
	
	} // endif current_theme_supports()
	
}

/**
 * Post options layout callback
 *
 * @since 1.0
 */

function post_options_api_page_layout_callback( $args ) {

	$layouts = array(
		'sidebar-right' => __( 'Right sidebar', 'frameshift' ),
		'full-width' 	=> __( 'No sidebar', 'frameshift' ),
		'sidebar-left'  => __( 'Left sidebar', 'frameshift' )
	);
	
	$layouts = apply_filters( 'frameshift_post_options_layouts', $layouts );
	
	// Set default layout
	
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
 * Post image size callback
 *
 * @since 1.0
 */

function post_options_api_image_size_callback( $args ) {

	// Get image sizes and create select data
		
	$image_sizes = array();		
	foreach ( frameshift_image_sizes() as $k => $v ) {		
	    $image_sizes[$k] = $v['label'] . ' (' . $v['size']['w'] . 'x' . $v['size']['h'] . 'px)';		
	}
	
	// Set default layout
	
	if( empty( $args['value'] ) )
		$args['value'] = frameshift_get_layout_image( 'size_single' );
	
	?>
	
	<select name="<?php echo $args['name_attr']; ?>">
	<?php foreach ( $image_sizes as $value => $caption ): ?>
	    <option <?php echo selected( $value == $args['value'] ); ?> value="<?php echo $value; ?>"><?php echo $caption; ?></option>
	<?php endforeach; ?>
	</select><br />
	<span class="description"><?php echo frameshift_post_layout_options( 'image_size_description' ); ?></span>
	
	<?php

}

/**
 * Post image align callback
 *
 * @since 1.0
 */

function post_options_api_image_align_callback( $args ) {
	
	// Set default layout
	
	if( empty( $args['value'] ) )
		$args['value'] = frameshift_get_layout_image( 'align_single' );
	
	?>
	
	<select name="<?php echo $args['name_attr']; ?>">
	<?php foreach ( frameshift_post_layout_options( 'image_align_options' ) as $value => $caption ): ?>
	    <option <?php echo selected( $value == $args['value'] ); ?> value="<?php echo $value; ?>"><?php echo $caption; ?></option>
	<?php endforeach; ?>
	</select><br />
	<span class="description"><?php echo frameshift_post_layout_options( 'image_align_description' ); ?></span>
	
	<?php

}

/**
 * Image display callback (portfolio)
 *
 * @since 1.0
 */

function post_options_api_image_display_callback( $args ) {
	
	// Set default option
	
	if( empty( $args['value'] ) )
		$args['value'] = 'featured';
	
	?>
	
	<select name="<?php echo $args['name_attr']; ?>">
	<?php foreach ( frameshift_portfolio_layout_options( 'image_display_options' ) as $value => $caption ): ?>
	    <option <?php echo selected( $value == $args['value'] ); ?> value="<?php echo $value; ?>"><?php echo $caption; ?></option>
	<?php endforeach; ?>
	</select><br />
	<span class="description"><?php echo frameshift_portfolio_layout_options( 'image_display_description' ); ?></span>
	
	<?php

}

/**
 * Helper function to get
 * post layout options
 *
 * @since 1.0
 */
 
function frameshift_post_layout_options( $option = '' ) {

	$layout_args = array(
	    'title' 				  => __( 'Layout', 'frameshift' ),
	    'post_types' 			  => array( 'post', 'page' ),
	    'sidebar_label' 		  => __( 'Sidebar', 'frameshift' ),
	    'image_size_label' 		  => __( 'Image Size', 'frameshift' ),
	    'image_size_description'  => __( 'Keep in mind that the image size is limited by the layout.', 'frameshift' ),
	    'image_align_label' 	  => __( 'Image Align', 'frameshift' ),
	    'image_align_description' => __( 'Select your preferred image alignment.', 'frameshift' ),
	    'image_align_options'	  => array(
	    	'left'  => __( 'left', 'frameshift' ),
	    	'right' => __( 'right', 'frameshift' ),
	    	'none'  => __( 'none', 'frameshift' )
	    )
	);
	
	$layout_args = apply_filters( 'frameshift_post_layout_args', $layout_args );
	
	if( ! empty( $option ) ) {
		return $layout_args[$option];
	} else {
		return $layout_args;
	}

}

/**
 * Helper function to get
 * portfolio layout options
 *
 * @since 1.0
 */
 
function frameshift_portfolio_layout_options( $option = '' ) {

	$layout_args = array(
	    'title' 	    			=> __( 'Layout', 'frameshift' ),
	    'post_types'    			=> array( 'portfolio' ),
	    'sidebar_label' 			=> __( 'Sidebar', 'frameshift' ),
	    'image_display' 			=> __( 'Image', 'frameshift' ),
	    'image_display_description' => __( 'Select your favorite image display option.', 'frameshift' ),
	    'image_display_options' 	=> array(
	    	'featured' 	  => __( 'Featured Image', 'frameshift' ),
	    	'thumbnail'	  => __( 'Thumbnail (only archives)', 'frameshift')
	    ),
	    'thumbnail_label' 			=> __( 'Thumbnail', 'frameshift' ),
	    'thumbnail_description' 	=> __( 'Open thumbnail image in lightbox on archive pages', 'frameshift' ),
	);
	
	$layout_args = apply_filters( 'frameshift_portfolio_layout_args', $layout_args );
	
	if( ! empty( $option ) ) {
		return $layout_args[$option];
	} else {
		return $layout_args;
	}

}

/**
 * Helper function to get
 * portfolio details options
 *
 * @since 1.0
 */
 
function frameshift_portfolio_details_options( $option = '' ) {

	$details_args = array(
	    'title' 	   		=> __( 'Project Details', 'frameshift' ),
	    'post_types'   		=> array( 'portfolio' ),
	    'description_label' => __( 'Description', 'frameshift' ),
	    'short_label' 		=> __( 'Short Description', 'frameshift' ),
	    'client_label' 		=> __( 'Client', 'frameshift' ),
	    'url_label'  		=> __( 'Project URL', 'frameshift' )
	);
	
	$details_args = apply_filters( 'frameshift_portfolio_details_args', $details_args );
	
	if( ! empty( $option ) ) {
		return $details_args[$option];
	} else {
		return $details_args;
	}

}

/**
 * Add post option CSS to admin head
 *
 * @since 1.0
 */    		
			
add_action( 'admin_head', 'frameshift_post_options_styles' );

function frameshift_post_options_styles() {
	
	if( is_admin() && current_theme_supports( 'post-layout' ) )
    	wp_enqueue_style( 'post-options', FRAMESHIFT_ADMIN_URL . '/css/admin-style.css' );
    			
}