<?php
/**
 * The template for displaying category archive pages.
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
            ?>		
    
			<h1 class="post-title">
				<?php
                    echo apply_filters( 'frameshift_category_title', single_cat_title( '', false ) );
                    
                    // Action hook to add content to title
                    do_action( 'frameshift_archive_title_inside' );                    
                ?>
			</h1>
			
			<?php
				// Display category description
                if( category_description() )
                    echo '<div class="category-description clearfix">' . apply_filters( 'the_content', category_description() ) . '</div>';
				    
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