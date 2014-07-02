<?php
/**
 * Built theme head with doctype, title
 * custom head, favicon and meta info.
 * 
 * Built header output with top bar,
 * main header section with logo and header right area
 * and main and sub menu.
 *
 * @package FrameShift
 * @since 1.0
 */
 
do_action( 'frameshift_head' );
wp_head();

?>

</head>

<body <?php body_class(); ?>>

<?php do_action( 'frameshift_before' ); ?>

<div id="outer">

<?php
	// Action hook before header
	do_action( 'frameshift_header_before' );
	
	// Open layout wrap		
    frameshift_layout_wrap( 'header-wrap' ); ?>
        		
    <div id="header" class="clearfix">
    
    	<div id="header-left">
    	
    		<?php
    			// Action hook for logo output
    			do_action( 'frameshift_logo' );
    		?>
    		
    	</div><!-- #header-left -->
    	
    	<div id="header-right">
    	
    		<?php
    			// Action hook for header right section
    			do_action( 'frameshift_header_right' );
    		?>
    		
    	</div><!-- #header-right -->
    	
    </div><!-- #header --><?php
    
    // Close layout wrap		
    frameshift_layout_wrap( 'header-wrap', 'close' );
	
	// Action hook after header
	do_action( 'frameshift_header_after' );
?>