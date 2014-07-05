<?php
/**
 * The default template for displaying portfolio content in single-portfolio.php
 *
 * @package FrameShift
 * @since 1.0
 */
    	
// Set up post data
the_post();

?>
    		
<div <?php post_class( 'clearfix' ); ?>>
        	
    <div class="post-teaser clearfix">
    	<?php the_content(); ?>
    </div>
    
    <?php
    	// Action hook after portfolio content
    	do_action( 'frameshift_portfolio_content_after' );
    ?>
    			
</div><!-- .post-<?php the_ID(); ?> -->