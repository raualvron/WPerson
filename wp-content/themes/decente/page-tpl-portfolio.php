<?php

/**
 * Template Name: Portfolio (grid)
 * This page template shows the portfolio items.
 *
 * @package FrameShift
 * @since 1.0
 */
 
get_header();

// Set up post data
the_post();

// Save parent page ID
$parent_id = get_the_ID(); ?>

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
					echo get_the_title( $parent_id );
				?>
			</h1>
			
			<?php
				// Get post content
				$post = get_post( $parent_id );
				
				// Display post content like category description
				if( ! empty( $post->post_content ) )
				    echo '<div class="category-description clearfix">' . apply_filters( 'the_content', $post->post_content ) . '</div>';
				    
				// Action hook after post title
				do_action( 'frameshift_post_title_after' );
			?>
			
		</div><!-- #main-middle-title -->
	
		<?php
	    	// Set class of #content div depending on active sidebars
	    	$content_class = is_active_sidebar( 'sidebar-portfolio' ) ? frameshift_get_span( 'big' ) : frameshift_get_span( 'full' );
	    	
	    	// Set class depending on individual page layout
	    	if( get_post_meta( $parent_id, '_layout', true ) == 'full-width' )
	    		$content_class = frameshift_get_span( 'full' );
		?>
	
	    <div id="content" class="<?php echo $content_class; ?>">
	    
	    	<?php				
				// Make sure paging works
				
				if ( get_query_var( 'paged' ) ) {
                        $paged = get_query_var( 'paged' );
                } elseif ( get_query_var( 'page' ) ) {
                        $paged = get_query_var( 'page' );
                } else {
                        $paged = 1;
                }
				
				// Set args for portfolio custom query
	    		$args = array(
	    			'cat'			 => -0,
	    			'post_type'		 => 'portfolio',
				    'posts_per_page' => apply_filters( 'frameshift_portfolio_number', 12 ),
				    'paged'			 => $paged
				);
				
				$args = apply_filters( 'frameshift_portfolio_query_args', $args );
				
				$portfolio_query = new WP_Query( $args );
	    	
	    		if ( $portfolio_query->have_posts() ) { ?>
				
					<div class="portfolio-holder row">
	    			
	    			    <?php
	    			    	// Create loop counter
					    	$counter = 0;
	    			    	
	    			    	while ( $portfolio_query->have_posts() ) {
							
								// Increase loop counter
	    						$counter++;
	    			    	
	    			    		$portfolio_query->the_post();
	    			    		
	    			    		// Add clear to post class
	    			    		
	    			    		if( get_post_meta( $parent_id, '_layout', true ) != 'full-width' ) {
	    			    			$clear = ( $counter == 1 || $counter%2 != 0 ) ? ' clear' : '';
	    			    		} else {
		    			    		$clear = ( $counter == 1 || $counter%3 - 1 == 0 ) ? ' clear' : '';
	    			    		}
	    			    		
	    			    		// Get portfolio item terms
	    			    		
	    			    		$terms = get_the_terms( $post->ID, 'skills' );
	    			    		
					    	    if( ! empty( $terms ) ) {
					    	        $term_slugs = array();
					    	        foreach( $terms as $term ) {
					    	        	$term_slugs[] = $term->slug;
					    	        }
					    	        $term_slugs = implode(' ', $term_slugs);
					    	    } ?>
	    			    		
					    		<div data-id="id-<?php echo $counter; ?>" data-type="<?php echo $term_slugs; ?>" <?php post_class( 'span4' . $clear ); ?>>
    
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
	    			
	    			frameshift_pagination( $portfolio_query->max_num_pages );
	    			    		
	    		} else { 
	    		
	    			get_template_part( 'loop', 'no' );
	    		
	    		} // endif have_posts() ?>
	    
	    </div><!-- #content -->
	    
	    <?php get_sidebar( 'portfolio' ); ?>
	
	</div><!-- #main-middle -->
	
	<?php	    
	    // Close layout wrap
	    frameshift_layout_wrap( 'main-middle-wrap', 'close' );
	    
	    // Action hook to add content after main
	    do_action( 'frameshift_main_after' );	
	?>	

</div><!-- #main-wrap -->

<?php get_footer(); ?>