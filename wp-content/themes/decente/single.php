<?php
/**
 * The template used for displaying single post content.
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
	
	<div id="main-middle" class="row">
	
		<?php			    
	    	// Set class of #content div depending on active sidebars
	    	$content_class = ( is_active_sidebar( 'sidebar-post' ) || is_active_sidebar( 'sidebar' ) ) ? frameshift_get_span( 'big' ) : frameshift_get_span( 'full' );
	    	
	    	// Set class depending on individual page layout
	    	if( get_post_meta( get_the_ID(), '_layout', true ) == 'full-width' )
	    		$content_class = frameshift_get_span( 'full' );	    	
		?>
	
	    <div id="content" class="<?php echo $content_class; ?>">			
	    
	    	<?php				
	    	    // Get page content from content-page.php
	    	    get_template_part( 'loop', 'single' );				
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