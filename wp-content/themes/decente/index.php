<?php
/**
 * The template used for displaying home page content.
 * For actions added to hooks see /lib/framework/main.php.
 *
 * @package FrameShift
 * @since 1.0
 */
 
get_header(); ?>

<div id="main-wrap" class="wrap">

	<?php	
		// Action hook to add content before main
		do_action( 'frameshift_main_before' );		
				
		// Open layout wrap
		frameshift_layout_wrap( 'main-middle-wrap' );    	
    ?>
		
	<div id="main-middle" class="row"><?php
	
		// Display widget area home when active
		if( is_active_sidebar( 'home' ) && is_front_page() ) {	
		    
		    // Set class of #content div depending on active sidebars
		    $content_class = ( is_front_page() && is_active_sidebar( 'home' ) ) ? frameshift_get_span( 'big' ) : frameshift_get_span( 'full' );
		    
		    // Set class of #content div on home depending on active sidebars
		    if( is_home() && ( is_active_sidebar( 'sidebar' ) || is_active_sidebar( 'sidebar-archive' ) ) )
		    	$content_class = frameshift_get_span( 'big' ); ?>
		
		    <div id="content" class="<?php echo $content_class; ?>">
		    	
		    	<?php dynamic_sidebar( 'home' ); ?>				
		    	
		    </div><!-- #content --><?php 
		    
		    get_sidebar();
		    
		} elseif( ! is_active_sidebar( 'home-top' ) && ! is_active_sidebar( 'home-bottom' ) ) {
		
			// Set class of #content div on home depending on active sidebars
    	    $content_class = ( is_home() && ( is_active_sidebar( 'sidebar' ) || is_active_sidebar( 'sidebar-archive' ) ) ) ? frameshift_get_span( 'big' ) : frameshift_get_span( 'full' ); ?>
		
		    <div id="content" class="<?php echo $content_class; ?>">
		    					
		    	<div class="row">
		    	
		    		<?php
		    			// Create loop counter
		    			global $counter;
		    			$counter = 0;
		    			
		    			// Set args for home custom query
						$home_query_args = array(
						    'posts_per_page' => get_option( 'posts_per_page' ),
						    'paged' => get_query_var( 'paged' )
						);
						
						$home_query_args = apply_filters( 'wpcasa_home_query_args', $home_query_args );
						
						$home_query = new WP_Query( $home_query_args );
		    			
		    			while ( $home_query->have_posts() ) {
		    				
		    				// Increase loop counter
		    				$counter++;
		    			
		    				$home_query->the_post();
		    						
		    		    	/* Include the Post-Format-specific template for the content.
		    				 * If you want to overload this in a child theme then include a file
		    				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		    				 */
		    				get_template_part( 'loop', get_post_format() );
		    			
		    			} // endwhile have_posts()
		    		?>
		    	
		    	</div><!-- .row --><?php		    	
		    
		    	frameshift_pagination( $home_query->max_num_pages ); ?>
		    
		    </div><!-- #content --><?php
		    
		    get_sidebar();
		    
		} // endif is_active_sidebar() ?>
    	
	</div><!-- #main-middle -->	
		
	<?php	
		// Close layout wrap
		frameshift_layout_wrap( 'main-middle-wrap', 'close' );
		
	    // Action hook to add content after main
	    do_action( 'frameshift_main_after' );	    
	?>

</div><!-- #main-wrap -->

<?php get_footer(); ?>