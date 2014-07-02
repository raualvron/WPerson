<?php
/**
 * The template for displaying tag or date-based archive pages.
 * Also used to display archive-type pages if
 * nothing more specific matches a query.
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
	
	<div id="main-middle" class="row">
	
		<div id="main-middle-title" class="<?php echo frameshift_get_span( 'full' ); ?>">	
		
			<?php
            	// Action hook before archive title
                do_action( 'frameshift_archive_title_before' );
                
                if( is_tag() ) {
		        	echo '<h1 class="post-title">' . single_tag_title( '', false ) . '</h1>';
		        } elseif( is_day() ) {
		        	echo '<h1 class="post-title">' . get_the_date() . '</h1>';
		        } elseif( is_month() ) {
		        	echo '<h1 class="post-title">' . get_the_date( _x( 'F Y', 'monthly archives date format', 'frameshift' ) ) . '</h1>';
		        } elseif( is_year() ) {
		        	echo '<h1 class="post-title">' . get_the_date( _x( 'Y', 'yearly archives date format', 'frameshift' ) ) . '</h1>';
		        } elseif( is_tax() ) {
		            $term = get_queried_object();
		            echo '<h1 class="post-title">' . $term->name . '</h1>';
		        } else {
		        	echo '<h1 class="post-title">' . __( 'Archives', 'frameshift' ) . '</h1>';
		        }
		        
		        // Action hook to add content to title
		        do_action( 'frameshift_archive_title_inside' );
		        
				// Display post content like category description
				$term_description = term_description();
				if( is_tag() && ! empty( $term_description ) )
				    echo '<div class="category-description clearfix">' . apply_filters( 'the_content', $term_description ) . '</div>';
				    
				// Action hook after archive title
				do_action( 'frameshift_archive_title_after' );
			?>
			
		</div><!-- #main-middle-title -->
	
		<?php			    
	    	// Set class of #content div depending on active sidebars
	    	$content_class = ( is_active_sidebar( 'sidebar-archive' ) || is_active_sidebar( 'sidebar' ) ) ? frameshift_get_span( 'big' ) : frameshift_get_span( 'full' );
		?>
	
	    <div id="content" class="<?php echo $content_class; ?>">
	    
	    	<?php if ( have_posts() ) { ?>
				
				<div class="row">
	    		
	    			<?php
	    				// Create loop counter
						$counter = 0;
	    				
	    				while ( have_posts() ) {
							
							// Increase loop counter
	    					$counter++;
	    				
	    					the_post();
	    							
	    			    	/* Include the Post-Format-specific template for the content.
							 * If you want to overload this in a child theme then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'loop', get_post_format() );
						
						} // endwhile have_posts()
	    			?>
	    		
	    		</div><!-- .row --><?php
	    		
	    		frameshift_pagination();
	    		
	    	} else {
	    	
	    		get_template_part( 'loop', 'no' );
			    
			} // endif have_posts() ?>
	    
	    </div><!-- #content -->
	    
	    <?php get_sidebar(); ?>
	
	</div><!-- #main-middle -->
	
	<?php	    
	    // Close layout wrap
	    frameshift_layout_wrap( 'main-middle-wrap', 'close' );
	    
	    // Action hook to add content after main
	    do_action( 'frameshift_main_after' );	
	?>	

</div><!-- #main-wrap -->

<?php get_footer(); ?>