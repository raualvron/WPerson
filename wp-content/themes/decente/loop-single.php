<?php
/**
 * The default template for displaying post content in single.php
 *
 * @package FrameShift
 * @since 1.0
 */
    	
// Set up post data
the_post();

?>
    		
<div <?php post_class( 'clearfix' ); ?>>
    
    <?php
    	// Action hook before post title
        do_action( 'frameshift_post_title_before' );
    ?>
    
    <h1 class="post-title">
    	<?php
    		// Action hook post title inside
       		do_action( 'frameshift_post_title_inside' );
    		the_title();
    	?>
    </h1>
    
    <?php
        // Action hook after post title
        do_action( 'frameshift_post_title_after' );
        
        // Action hook before post content
        do_action( 'frameshift_post_content_before' );
    ?>
    	
    <div class="post-teaser clearfix">
    	<?php the_content(); ?>
    </div>
    
    <?php
    	// Action hook after post content
    	do_action( 'frameshift_post_content_after' );
    ?>
    			
</div><!-- .post-<?php the_ID(); ?> -->