<?php

/**
 * Built general post output
 *
 * @package FrameShift
 */

/**
 * Add portfolio navigation links to
 * frameshift_portfolio_title_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_portfolio_title_after', 'frameshift_do_portfolio_title_nav' );

function frameshift_do_portfolio_title_nav() {

	// Stop if not on single portfolio pages
	if( ! is_singular() || is_page_template( 'page-tpl-portfolio.php' ) || is_page_template( 'page-tpl-portfolio-2.php' ) )
		return; ?>	
			
	<div class="title-nav clearfix"><?php
		
		next_post_link( '%link', __( 'Previous', 'frameshift' ) );
		
		if( is_pagetemplate_active( 'page-tpl-portfolio.php' ) ) {
		    $portfolio_url = apply_filters( 'frameshift_portfolio_url', get_pagetemplate_permalink( 'page-tpl-portfolio.php' ) );
		} elseif( is_pagetemplate_active( 'page-tpl-portfolio-2.php' ) ) {
			$portfolio_url = apply_filters( 'frameshift_portfolio_url', get_pagetemplate_permalink( 'page-tpl-portfolio-2.php' ) );
		}
		
		if( ! empty( $portfolio_url ) )
			echo '<a href="' . $portfolio_url . '" class="arr arr-grid">' . __( 'All', 'frameshift'  ) . '</a>';
				
		previous_post_link( '%link', __( 'Next', 'frameshift' ) ); ?>
	    		
	</div><?php	
	
}

/**
 * Add portfolio Quicksand filter to
 * frameshift_portfolio_title_inside hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_title_after', 'frameshift_do_portfolio_title_filter' );

function frameshift_do_portfolio_title_filter() {

	// Stop if not on single portfolio pages
	if( ! is_page_template( 'page-tpl-portfolio.php' ) && ! is_page_template( 'page-tpl-portfolio-2.php' ) )
		return; ?>	
			
	<div id="filter">
	
	    <?php
	    	// display portfolio terms
	    	$terms = get_terms( array( 'skills' ), array( 'orderby' => 'count', 'order' => 'DESC' ) );					
	    	if( ! empty( $terms ) ) {
	    		echo apply_filters( 'frameshift_portfolio_filter_text', false );
	    		echo '<a href="#" class="all">' . apply_filters( 'frameshift_portfolio_filter_all', __( 'All' , 'frameshift' ) ) . '</a>';
	    		foreach( $terms as $term ) {
	    			echo '<a class="' . $term->slug . '" href="#">' . $term->name . '</a>';
	    		}
	    	}
	    ?>
	    
	</div><?php	
	
}

/**
 * Add portfolio image on archive pages to
 * frameshift_portfolio_title_before hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_portfolio_title_before', 'frameshift_do_portfolio_image_archive' );
add_action( 'frameshift_widget_portfolio_title_before', 'frameshift_do_portfolio_image_archive', 10, 2 );

function frameshift_do_portfolio_image_archive( $instance = '', $widget_width = '' ) {

	// Stop if not on portfolio archive pages
	if( ! is_page_template( 'page-tpl-portfolio.php' ) && ! is_page_template( 'page-tpl-portfolio-2.php' ) && empty( $instance ) && ! is_tax( 'skills' ) )
		return;
		
	global $post;
		
	// Check if there is a video custom field
		
	$video = get_post_meta( $post->ID, 'video', true );
	
	if( ! empty( $video ) && ! has_post_thumbnail( $post->ID ) ) {
		echo apply_filters( 'the_content', $video );
		return;
	}
	
	// Create optional image overlay
	$overlay = apply_filters( 'frameshift_portfolio_image_overlay', false );
		
	if( has_post_thumbnail( $post->ID ) ) {
	
		// Optionally open thumbnail in lightbox		
	
		if( get_post_meta( $post->ID, '_lightbox', true ) ) {
		    $thumbnail_id = get_post_thumbnail_id( get_the_ID() );
		    $permalink = wp_get_attachment_image_src( $thumbnail_id, 'original' );
		    $permalink = $permalink[0];
		} else {
			$permalink = get_permalink( $post->ID );
		}
		
		// Set image size depending on page template
		$image_size = ( is_page_template( 'page-tpl-portfolio.php' ) || $widget_width == frameshift_get_span( 'small' ) || is_tax( 'skills' ) ) ? 'post-thumbnail' : 'full';
		
        echo '<div class="post-image portfolio-image"><a href="' . $permalink . '" title="' . the_title_attribute('echo=0') . '">' . get_the_post_thumbnail( $post->ID, $image_size, array( 'alt' => the_title_attribute('echo=0'), 'title' => the_title_attribute('echo=0') ) ) . $overlay . '</a></div><!-- .portfolio-image -->' . "\n";
        
    }
	
}

/**
 * Add portfolio short description to
 * frameshift_portfolio_title_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_portfolio_title_after', 'frameshift_do_portfolio_short' );
add_action( 'frameshift_widget_portfolio_title_after', 'frameshift_do_portfolio_short' );

function frameshift_do_portfolio_short( $instance ) {

	// Stop if on single portfolio pages
	if( ! is_page_template( 'page-tpl-portfolio.php' ) && ! is_page_template( 'page-tpl-portfolio-2.php' ) && empty( $instance ) && ! is_tax( 'skills' ) )
		return;
		
	// Stop if disabled through widget settings
	if( ! empty( $instance ) && ! $instance['project_description'] )
		return;
		
	global $post;
		
	// Stop if short description is emtpy
	
	$project_short = get_post_meta( $post->ID, '_project_short', true );
	
	if( empty( $project_short ) )
		return; ?>	
			
	<div class="portfolio-short"><?php
	
		echo $project_short; ?>
	    		
	</div><?php
	
}

/**
 * Add portfolio image on archive pages to
 * frameshift_portfolio_title_before hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_portfolio_content_before', 'frameshift_do_portfolio_image_single' );

function frameshift_do_portfolio_image_single() {

	// Stop if not on portfolio single page
	if( ! is_singular() )
		return;
		
	// Check if there is a video custom field
		
	$video = get_post_meta( get_the_ID(), 'video', true );
	
	if( ! empty( $video ) ) {
		echo apply_filters( 'the_content', $video );
		return;
	}
		
	// Stop if thumbnail only on archives
	$image_display = get_post_meta( get_the_ID(), '_image', true );
	if( $image_display == 'thumbnail' )
		return;	
		
	if( get_post_meta( get_the_ID(), '_image', true ) == 'slider' ) {
     	echo do_shortcode( '[image_slider]' );   
    } elseif( has_post_thumbnail( get_the_ID() ) ) {
    	$image_size = get_post_meta( get_the_ID(), '_layout', true ) == 'full-width' ? 'full' : 'big';
        echo '<div class="post-image portfolio-image">' . get_the_post_thumbnail( get_the_ID(), $image_size, array( 'alt' => the_title_attribute('echo=0'), 'title' => the_title_attribute('echo=0') ) ) . '</div><!-- .portfolio-image -->' . "\n";
    }	
	
}

/**
 * Add portfolio archive link to
 * frameshift_widget_latest_work_title_inside hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_widget_latest_work_title_inside', 'frameshift_do_portfolio_link_archive' );

function frameshift_do_portfolio_link_archive( $instance = '' ) {

	// Stop if disabled in widget settings
	if( ! $instance['portfolio_link'] )
		return;

	// Display link to portfolio archive
					    	
	if( is_pagetemplate_active( 'page-tpl-portfolio.php' ) ) {
	    $portfolio_url = apply_filters( 'frameshift_portfolio_url', get_pagetemplate_permalink( 'page-tpl-portfolio.php' ) );
	} elseif( is_pagetemplate_active( 'page-tpl-portfolio-2.php' ) ) {
	    $portfolio_url = apply_filters( 'frameshift_portfolio_url', get_pagetemplate_permalink( 'page-tpl-portfolio-2.php' ) );
	}
	
	// Check if filter to special term exists
	
	if( ! empty( $instance['filter'] ) ) {
		$get_taxonomy = explode( ',', $instance['filter'] );
		$portfolio_url = get_term_link( $get_taxonomy[1], $get_taxonomy[0] );
	}
	
	// If there is a link, display it
	    
	if( ! empty( $portfolio_url ) )
	    echo '<a href="' . $portfolio_url . '" class="arr arr-grid">' . __( 'All', 'frameshift'  ) . '</a>';

}

/**
 * Add portfolio description to sidebar with
 * frameshift_sidebar_portfolio_widgets_before hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_sidebar_portfolio_widgets_before', 'frameshift_do_portfolio_description_sidebar' );

function frameshift_do_portfolio_description_sidebar() {

	// Stop if not single portfolio item
	if( ! is_singular() || is_page_template( 'page-tpl-portfolio.php' ) || is_page_template( 'page-tpl-portfolio-2.php' ) )
		return;

	// Stop if portfolio item has full width layout
	if( get_post_meta( get_the_ID(), '_layout', true ) == 'full-width' )
		return; ?>
		
	<div id="portfolio-details-wrap" class="widget-wrap">
	    
        <div id="portfolio-details" class="widget clearfix">
        
        	<div class="widget-inner">
        		
            	<h3 class="title"><?php echo apply_filters( 'frameshift_project_details_title', __( 'Project Details', 'frameshift' ) ); ?></h3>
            	
            	<?php echo frameshift_project_details(); ?>
            	
            </div><!-- .widget-inner -->
            
        </div><!-- .widget -->
    
    </div><!-- .widget-wrap --><?php	
	
}

/**
 * Add portfolio description to content area with
 * frameshift_portfolio_content_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_portfolio_content_after', 'frameshift_do_portfolio_description_content' );

function frameshift_do_portfolio_description_content() {

	// Stop if not single portfolio item
	if( ! is_singular() || is_page_template( 'page-tpl-portfolio.php' ) || is_page_template( 'page-tpl-portfolio-2.php' ) )
		return;

	// Stop if portfolio item has NOT full width layout
	if( get_post_meta( get_the_ID(), '_layout', true ) != 'full-width' )
		return; ?>
		
	<div id="portfolio-details-wrap" class="widget-wrap">
	    
        <div id="portfolio-details" class="widget clearfix">
        
        	<div class="widget-inner">
        		
            	<h3 class="title"><?php echo apply_filters( 'frameshift_project_details_title', __( 'Project Details', 'frameshift' ) ); ?></h3>
            	
            	<?php echo frameshift_project_details(); ?>
            	
            </div><!-- .widget-inner -->
            
        </div><!-- .widget -->
    
    </div><!-- .widget-wrap --><?php	
	
}

/**
 * Add post paging for <!--nextpage--> quicktag to
 * frameshift_post_content_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_portfolio_content_after', 'frameshift_do_portfolio_link_pages', 10 );

function frameshift_do_portfolio_link_pages() {

	// Stop if not on single posts or pages
	if( ! is_singular() || is_page_template( 'page-tpl-blog.php' ) )
		return;

	$args = array(
    	'before'           => '<div class="pagination post-pagination"><ul>',
    	'after'            => '</ul></div>',
    	'link_before'      => '<span>',
    	'link_after'       => '</span>',
    	'next_or_number'   => 'number',
    	'nextpagelink'     => __( 'Next page', 'frameshift' ),
    	'previouspagelink' => __( 'Previous page', 'frameshift' ),
    	'pagelink'         => '%',
    	'echo'             => 0
    );
    
    $args = apply_filters( 'frameshift_do_link_pages_args', $args );
    
    /**
     * Hacky way to create a list of wp_link_pages()
     * Unfortunately you cannot place anything before and after a tags
     */
    
    $output = str_replace( '<a', '<li><a', wp_link_pages( $args ) );
    $output = str_replace( '</a>', '</a></li>', $output );
    $output = str_replace( ' <span>', ' <li class="active"><a href="#">', $output );
    $output = str_replace( '</span> ', '</a></li> ', $output );
    
    echo apply_filters( 'frameshift_do_link_pages', $output );

}