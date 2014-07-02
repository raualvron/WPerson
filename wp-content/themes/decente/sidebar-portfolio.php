<div id="sidebar" class="<?php echo frameshift_get_span( 'small' ); ?>"><?php

	do_action( 'frameshift_sidebar_portfolio_widgets_before' );
		
	dynamic_sidebar( 'sidebar-portfolio' );
	
	do_action( 'frameshift_sidebar_portfolio_widgets_after' ); ?>

</div><!-- #sidebar -->