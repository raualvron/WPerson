<div id="sidebar" class="<?php echo frameshift_get_span( 'small' ); ?>"><?php

	// Hook to add content before widgets in sidebar
	do_action( 'frameshift_sidebar_widgets_before' );
		
	if( is_front_page() && is_active_sidebar( 'home' ) ) {
	
		if( is_active_sidebar( 'sidebar-home' ) ) {
		    	dynamic_sidebar( 'sidebar-home' );
		    } elseif( is_active_sidebar( 'sidebar' ) ) {
		    	dynamic_sidebar( 'sidebar' );
		    } else { ?>
	    	
	    	<div class="widget no-widget">
	    		
	    		<div class="widget-inner">
	
	    			<h4 class="title title-widget"><?php _e('Widget Area', 'frameshift'); ?></h4>
	    			
	    			<?php echo '<p>' . sprintf( __( 'This is a widget area. Please drag and drop your preferred widgets to the area %s', 'frameshift' ), '<strong>' . __( 'General Sidebar', 'frameshift' ) . '</strong> ' . __( 'or', 'frameshift' ) . ' <strong>' . __( 'Home Page Sidebar', 'frameshift' ) . '</strong>' ) . ' &rarr; <a href="' . home_url() . '/wp-admin/widgets.php">' . __( 'Edit Widgets', 'frameshift' ) . '</a></p>' . "\n"; ?>
	    		
	    		</div><!-- widget-inner -->
	    	
	    	</div><!-- .widget --><?php
	    	
	    }
	    
	} elseif( ( is_home() || is_archive() || is_search() || is_page_template( 'page-tpl-blog.php' ) ) && is_active_sidebar( 'sidebar-archive' ) ) {
	
		dynamic_sidebar( 'sidebar-archive' );
		
	} elseif( is_single() && is_active_sidebar( 'sidebar-post' ) ) {
	
		dynamic_sidebar( 'sidebar-post' );
		
	} elseif( is_page() && is_active_sidebar( 'sidebar-page' ) && ! is_page_template( 'page-tpl-blog.php' ) ) {
	
		dynamic_sidebar( 'sidebar-page' );
		
	} elseif( is_active_sidebar( 'sidebar' ) ) {
	
		dynamic_sidebar( 'sidebar' );
		
	}
	
	// Hook to add content after widgets in sidebar
	do_action( 'frameshift_sidebar_widgets_after' ); ?>

</div><!-- #sidebar -->