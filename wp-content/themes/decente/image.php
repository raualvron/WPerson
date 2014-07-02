<?php
/**
 * The template used for displaying image attachements.
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
	
		<?php			    
	    	// Set class of #content div depending on active sidebars
	    	$content_class = ( is_active_sidebar( 'sidebar-post' ) || is_active_sidebar( 'sidebar' ) ) ? frameshift_get_span( 'big' ) : frameshift_get_span( 'full' );
	    	
	    	$args = array(
	    		'full-width' => apply_filters( 'frameshift_attachment_full_width', false ),
	    		'image_lightbox' => true
	    	);
	    	
	    	$args = apply_filters( 'frameshift_attachment_image_args', $args );
	    	
	    	// Set class depending on individual page layout
	    	if( $args['full-width'] == true )
	    		$content_class = frameshift_get_span( 'full' );
	    		
	    	// Set image max size depending on active sidebars
	    	$image_big = frameshift_get_image_size( 'big' );
	    	$image_full = frameshift_get_image_size( 'full' );
	    	$image_max = ( $content_class == frameshift_get_span( 'full' ) ) ? $image_full['size']['w'] : $image_big['size']['w'];
		?>
	
	    <div id="content" class="<?php echo $content_class; ?>">
	    
	    	<?php
	    		if( wp_attachment_is_image( get_the_ID() ) ) {
	    		
					$att_image = wp_get_attachment_image_src( get_the_ID(), array( $image_max, $image_max ) ); ?>					
				
					<div class="post post-attachment">
						
						<?php
    						// Action hook before post title
    					    do_action( 'frameshift_post_title_before' );
    					?>
    					
    					<h1 class="post-title"><?php the_title(); ?></h1>
    					
    					<?php
    					    // Action hook after post title
    					    do_action( 'frameshift_post_title_after' );
    					    
    					    // Action hook before post content
    					    do_action( 'frameshift_post_content_before' );
    					?>						
						
						<div class="post-attachment-image">
							<?php
								if( $args['image_lightbox'] == true ) {
							?>
						    <a href="<?php echo wp_get_attachment_url( get_the_ID() ); ?>" title="<?php the_title_attribute(); ?>">
						    	<img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>" alt="<?php the_title(); ?>" />
						    </a>
						    <?php
						    	} else {
						    ?>
						    <img src="<?php echo $att_image[0];?>" width="<?php echo $att_image[1];?>" height="<?php echo $att_image[2];?>" alt="<?php the_title(); ?>" />
						    <?php 
						    	} // endif $args['image_lightbox']
						    	
								if( ! empty( $post->post_excerpt ) ) {
									echo '<div class="image-caption">' . wp_kses_post( $post->post_excerpt ) . '</div>' . "\n";
								} 
							?>
						</div><!-- .post-attachment-image -->
						
						<div class="clear"></div>
						
						<?php
							if( ! empty( $post->post_content ) ) {
								echo '<div class="image-description">' .  apply_filters( 'the_content', $post->post_content ) . '</div>' . "\n";
							}
							
							// Action hook after post content
    						do_action( 'frameshift_post_content_after' );
						?>
						
					</div><!-- .post-attachment --><?php
					
				} // endif wp_attachment_is_image()
			?>				
	    
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