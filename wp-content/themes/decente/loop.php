<?php
/**
 * The default template for displaying main content.
 *
 * @package FrameShift
 * @since 1.0
 */
 
global $counter, $parent_id;
		
/**
 * Create post classes for different layouts.
 * Find frameshift_archive_post_class() in
 * /lib/functions/general.php
 */

$post_class = frameshift_archive_post_class( $counter, $parent_id );

?>

<div <?php post_class( $post_class ); ?>>
    
    <?php
    	// Action hook before post title
        do_action( 'frameshift_post_title_before' );
    ?>
    
    <h2 class="post-title">
    	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
    		<?php
    			// Action hook post title inside
        		do_action( 'frameshift_post_title_inside' );
    			the_title();
    		?>
    	</a>
    </h2>
    
    <?php
        // Action hook after post title
        do_action( 'frameshift_post_title_after' );
        
        // Action hook before post content
        do_action( 'frameshift_post_content_before' );
    ?>
    	
    <div class="post-teaser clearfix">
    	<?php frameshift_the_excerpt(); ?>
    </div>
    
    <?php
    	// Action hook after post content
    	do_action( 'frameshift_post_content_after' );
    ?>
    			
</div><!-- .post-<?php the_ID(); ?> -->
