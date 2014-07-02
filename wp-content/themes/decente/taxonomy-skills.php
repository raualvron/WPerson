<?php
/**
 * The template for displaying a
 * skills taxonomy archive.
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
            	// Action hook before post title
                do_action( 'frameshift_post_title_before' );
            ?>		
    
			<h1 class="post-title">
				<?php
					// Action hook portfolio title inside
			   		do_action( 'frameshift_post_title_inside' );
			   		
			   		// Display current term
					$current_term = get_term_by( 'slug', get_query_var( 'term' ), 'skills' );
    				echo $current_term->name;
				?>
			</h1>
			
			<?php
				// Display term description
				$term_description = term_description();
				if( ! empty( $term_description ) )
				    echo '<div class="category-description clearfix">' . apply_filters( 'the_content', $term_description ) . '</div>';
				    
				// Action hook after post title
				do_action( 'frameshift_post_title_after' );
			?>
			
		</div><!-- #main-middle-title -->
	
		<?php
	    	// Set class of #content div
	    	$content_class = frameshift_get_span( 'full' );
		?>
	
	    <div id="content" class="<?php echo $content_class; ?>">
	    
	    	<?php
	    		if ( have_posts() ) { ?>
				
					<div class="row">
	    			
	    			    <?php
	    			    	// Create loop counter
					    	$counter = 0;
	    			    	
	    			    	while ( have_posts() ) {
							
								// Increase loop counter
	    						$counter++;
	    			    	
	    			    		the_post();
	    			    		
	    			    		// Add clear to post class
	    			    		
	    			    		$clear = ( $counter == 1 || $counter%3 - 1 == 0 ) ? ' clear' : ''; ?>
	    			    		
					    		<div <?php post_class( 'span4' . $clear ); ?>>
    
                                    <?php
                                    	// Action hook before post title
                                        do_action( 'frameshift_portfolio_title_before' );
                                    ?>
                                    
                                    <h2 class="post-title portfolio-title">
                                    	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
                                    		<?php
                                    			// Action hook post title inside
                                        		do_action( 'frameshift_portfolio_title_inside' );
                                    			the_title();
                                    		?>
                                    	</a>
                                    </h2>
                                    
                                    <?php
                                        // Action hook after post title
                                        do_action( 'frameshift_portfolio_title_after' );
                                    ?>
                                    			
                                </div><!-- .post-<?php the_ID(); ?> --><?php
					    	
					    	} // endwhile have_posts()
	    			    ?>
	    			
	    			</div><!-- .row --><?php
	    			
	    			frameshift_pagination();
	    			    		
	    		} else { 
	    		
	    			get_template_part( 'loop', 'no' );
	    		
	    		} // endif have_posts() ?>
	    
	    </div><!-- #content -->
	
	</div><!-- #main-middle -->
	
	<?php	    
	    // Close layout wrap
	    frameshift_layout_wrap( 'main-middle-wrap', 'close' );
	    
	    // Action hook to add content after main
	    do_action( 'frameshift_main_after' );	
	?>	

</div><!-- #main-wrap -->

<?php get_footer(); ?>