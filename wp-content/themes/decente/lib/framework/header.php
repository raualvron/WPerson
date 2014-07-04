<?php

/**
 * Built header output with top bar,
 * main header section with logo and header right area
 * and main and sub menu.
 *
 * @package FrameShift
 */
 
/**
 * Built doctype and open html and head tag.
 * Add classes to html tag for ie7 and ie8.
 * Add meta tag for content type with charset.
 *
 * @since 1.0
 */
 
add_action( 'frameshift_head', 'frameshift_do_doctype' );
 
function frameshift_do_doctype() {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if IE 7 ]><html xmlns="http://www.w3.org/1999/xhtml" class="ie ie7" <?php language_attributes( 'xhtml' ); ?>"> <![endif]-->
<!--[if IE 8 ]><html xmlns="http://www.w3.org/1999/xhtml" class="ie ie8" <?php language_attributes( 'xhtml' ); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes( 'xhtml' ); ?>> <!--<![endif]-->
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php

}

/**
 * Built title tag
 *
 * @since 1.0
 */
 
add_action( 'frameshift_head', 'frameshift_do_title' );
 
function frameshift_do_title() {
	
	$args = array(
		'separator' => ' &raquo; ',
		'position'  => 'right'
	);
	
	$args = apply_filters( 'frameshift_do_title_args', $args );

	$output = "\n" . '<title>' . wp_title( $args['separator'], false, $args['position'] ) . get_bloginfo( 'name' ) . '</title>';
	
	echo apply_filters( 'frameshift_do_title', $output );

}

/**
 * Add scripts to header
 *
 * @since 1.0
 */
 
add_action( 'wp_enqueue_scripts', 'frameshift_scripts' );

function frameshift_scripts() {

	wp_enqueue_script( 'jquery' );
	
	wp_enqueue_script( 'scripts', FRAMESHIFT_ASSETS_JS_URL . '/scripts.js', array( 'jquery' ), '1.0', true );
	
	wp_enqueue_script( 'bootstrap', FRAMESHIFT_ASSETS_JS_URL . '/bootstrap.min.js', array( 'jquery' ), '2.0', true );

	if ( is_singular() && get_option( 'thread_comments' ) && ! is_page_template( 'page-tpl-blog.php' ) )
		wp_enqueue_script( 'comment-reply' );
	
	if( current_theme_supports( 'FlexSlider' ) )
		wp_enqueue_script( 'flex', FRAMESHIFT_ASSETS_JS_URL . '/flex/jquery.flexslider-min.js', array( 'jquery' ), '1.8', true );
		
	if( current_theme_supports( 'prettify' ) )
		wp_enqueue_script( 'prettify', FRAMESHIFT_ASSETS_JS_URL . '/prettify/prettify.js', array( 'scripts' ), '1.0', true );

	if( current_theme_supports( 'PhotoSwipe' ) ) {
		wp_enqueue_script( 'klass', FRAMESHIFT_ASSETS_JS_URL . '/photoswipe/klass.min.js', array( 'jquery' ), '3.0.5', true );
		wp_enqueue_script( 'photoswipe', FRAMESHIFT_ASSETS_JS_URL . '/photoswipe/code.photoswipe.jquery-3.0.5.min.js', array( 'jquery' ), '3.0.5', true );
	}
	
	// Localize scripts
	$data = array( 'menu_blank' => __( 'Select a page', 'frameshift' ) );
	wp_localize_script( 'scripts', 'frameshift_localize', $data );
	
}

/**
 * Add stylesheets to header
 *
 * @since 1.0
 */
 
add_action( 'wp_enqueue_scripts', 'frameshift_stylesheets' );

function frameshift_stylesheets() {

	wp_enqueue_style( 'bootstrap', FRAMESHIFT_ASSETS_CSS_URL . '/bootstrap.min.css', false, '2.0', 'all'  );
	wp_enqueue_style( 'layout', FRAMESHIFT_ASSETS_CSS_URL . '/layout.min.css', false, '1.0', 'all'  );
	wp_enqueue_style( 'style', get_bloginfo( 'stylesheet_url' ), false, '1.0', 'screen'  );
		
	wp_enqueue_style( 'bitter', 'http://fonts.googleapis.com/css?family=Bitter', false, '1.0', 'all' );

	if( current_theme_supports( 'PhotoSwipe' ) )
		wp_enqueue_style( 'photoswipe', FRAMESHIFT_ASSETS_JS_URL . '/photoswipe/photoswipe.css', false, '3.0.5', 'all' );
	
	// Localize scripts
	$data = array( 'menu_blank' => __( 'Select a page', 'frameshift' ) );
	wp_localize_script( 'scripts', 'frameshift_localize', $data );
}

/**
 * Add favicon and main stylesheet to
 * frameshift_head hook.
 *
 * @since 1.0
 */
 
add_action( 'frameshift_head', 'frameshift_do_head' );

function frameshift_do_head() {

	echo "\n";
	
	if( frameshift_get_option( 'favicon' ) )
		echo "\n" . '<link rel="Shortcut Icon" href="' . frameshift_get_option( 'favicon' ) . '" type="image/x-icon" />';

}

/**
 * Add meta tags to
 * frameshift_head hook.
 *
 * @since 1.0
 */
 
add_action( 'frameshift_head', 'frameshift_do_meta' );

function frameshift_do_meta() {

	$output = "\n";
	$output .= '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />' . "\n\n";
	
	echo apply_filters( 'frameshift_do_meta', $output );

}

/**
 * Custom RSS feed
 *
 * @since 1.0
 */

add_filter( 'feed_link' , 'frameshift_feed_link', 1, 2 );

function frameshift_feed_link( $output, $feed ) {

	$feed_url = frameshift_get_option( 'custom_rss' );
	
	if( ! empty( $feed_url ) ) {

		$feed_array = array(
			'rss' => $feed_url,
			'rss2' => $feed_url,
			'atom' => $feed_url,
			'rdf' => $feed_url,
			'comments_rss2' => ''
		);
		
		$feed_array = apply_filters( 'frameshift_feed_link_array', $feed_array );
		
		$feed_array[$feed] = $feed_url;
		
		$output = $feed_array[$feed];
	
	}

	return $output;
}

/**
 * Add generator meta tag
 * for theme info
 *
 * @since 1.0
 */

add_filter( 'the_generator', 'frameshift_generator' );

function frameshift_generator( $generator ) {
	$generator .= "\r\n" . '<meta name="generator" content="' . FRAMESHIFT_NAME . ' ' . FRAMESHIFT_VERSION . '" />';
	return $generator;
}

/**
 * Add custom CSS from
 * theme options to header
 *
 * @since 1.0
 */
 
add_action( 'wp_head', 'frameshift_do_custom_css' );

function frameshift_do_custom_css() {

	$css_option = frameshift_get_option( 'custom_css' );
	
	if( ! empty( $css_option ) ) {	
	
		$css  = '<style type="text/css" media="screen">';
		$css .= stripslashes( $css_option );
		$css .= '</style>' . "\n";
		
		echo $css;
		
	}

}
 
/**
 * Built top bar with menu
 * and social icons
 *
 * @since 1.0
 */
 
// add_action( 'frameshift_header_before', 'frameshift_do_top' );
 
function frameshift_do_top() {

	// Open layout wrap
	frameshift_layout_wrap( 'top-wrap' ); ?>

	<div id="top" class="row">
	        		
		<div id="top-left" class="span8">
		
			<?php echo frameshift_menu( 'top-left' ); ?>
		
		</div><!-- #top-left -->
	
		<div id="top-right" class="span4">
		
			<?php
				// Loop through social icons
		
				$nr = apply_filters( 'frameshift_social_icons_nr', 5 );
				
				$social_icons = array();
				
				for( $i = 1; $i <= $nr; $i++ ) {				    
				    $social_icons[] = frameshift_get_social_icon( frameshift_get_option( 'icon_' . $i ) );				    
				}
				
				// Remove empty elements
				$social_icons = array_filter( $social_icons );
				
				$output = '<div class="social-icons">';
				
				if( ! empty( $social_icons ) ) {					
					$i = 1;														
					foreach( $social_icons as $k => $v ) {				
					    $social_link = frameshift_get_option( 'icon_' . $i . '_link' );				    	
				        $output .= '<a href="' . $social_link . '" target="_blank" title="' . $v['title'] . '" class="social-icon social-icon-' . $v['id'] . '"><img src="' . $v['icon'] . '" alt="" /></a>' . "\n";				    		
				        $i++;				    		
				    }				    
				} else {
					$social_icon = frameshift_get_social_icon( 'rss' );
					$output .= '<a href="' . get_bloginfo_rss( 'rss2_url' ) . '" target="_blank" title="' . $social_icon['title'] . '" class="social-icon social-icon-' . $social_icon['id'] . '"><img src="' . $social_icon['icon'] . '" alt="" /></a>' . "\n";
				}
				
				$output .= '</div><!-- .social-icons -->';
				
				echo apply_filters( 'frameshift_social_icons_top', $output );
				
			?>
		
		</div><!-- #top-right -->
	
	</div><!-- #top --><?php
	
	// Close layout wrap	
	frameshift_layout_wrap( 'top-wrap', 'close' );
	        		
}

/**
 * Custom header image
 *
 * @since 1.0
 */
 
/**
 * Add custom header theme support
 *
 * @since 1.0
 */

add_action( 'frameshift_setup', 'frameshift_custom_header_theme_support' );
 
function frameshift_custom_header_theme_support() {
	
	$args = array(
		'width'               => 1060,
		'height'              => 600,
		'flex-width'		  => true,
		'flex-height'		  => true,
		'header-text'		  => false,
		'default-text-color'  => 'ffffff',
		'default-image'       => get_stylesheet_directory_uri() . '/images/header-image.jpg',
		'random-default'	  => false,
		'wp-head-callback'    => 'frameshift_header_style',
		'admin-head-callback' => 'frameshift_admin_header_style',
	);

	$args = apply_filters( 'frameshift_custom_header_args', $args );
	
	/** 
	 * Register support for custom header WordPress 3.4+
	 * with fallback for older versions.
	 */

	add_theme_support( 'custom-header', $args );

}

// gets included in the site header
function frameshift_header_style() {
	$header_image = get_header_image();
	if( empty( $header_image ) )
		return;
	?>
<style type="text/css">
#header {
    background: url(<?php header_image(); ?>) repeat center top;
    height:70px;
}
</style>
<?php }

// gets included in the admin header
function frameshift_admin_header_style() { ?>
<style type="text/css">
#headimg {
    width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
    height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
    background: no-repeat;
}
</style>
<?php }

/**
 * Add custom background theme support
 *
 * @since 1.0
 */
 
add_action( 'frameshift_setup', 'frameshift_custom_background_theme_support' );
 
function frameshift_custom_background_theme_support() {

	$args = array(
		'default-color' => 'dbdbdb',
		'default-image' => ''
	);
	
	$args = apply_filters( 'frameshift_custom_background_args', $args );	
	
	/** 
	 * Register support for custom background WordPress 3.4+
	 * with fallback for older versions.
	 */

    add_theme_support( 'custom-background', $args );

}

/**
 * Built main menu before header
 *
 * @since 1.0
 */
 
add_action( 'frameshift_header_before', 'frameshift_do_menu' );
 
function frameshift_do_menu() {

	// Open layout wrap
	frameshift_layout_wrap( 'menu-wrap' ); ?>

	<div id="menu">	
		<?php echo frameshift_menu( 'main', true ); ?>    
    </div><!-- .menu --><?php
    
    // Close layout wrap	
	frameshift_layout_wrap( 'menu-wrap', 'close' );

}

/**
 * Built logo
 *
 * @since 1.0
 */
 
add_action( 'frameshift_logo', 'frameshift_do_logo' );

function frameshift_do_logo() {

	// Get logo image from options
	$logo_image = frameshift_get_option( 'logo', FRAMESHIFT_IMAGES . '/logo.png' );
	
	if( ! empty( $logo_image ) ) {
	
		// Create logo image
		$logo = '<div id="logo"><a href="' . home_url() . '"><img src="' . frameshift_get_option( 'logo', true ) . '" alt="" /></a></div><!-- #logo -->';		
		
	} else {

		// Get text logo and set to blog name if emtpy
		$logo = '<div id="logo-text"><a href="' . home_url() . '">' . frameshift_get_option( 'logo_text', get_bloginfo( 'name' ) ) . '</a></div><!-- #logo-text -->';

	}
	
	// Get logo slogan	
	$logo_description = get_bloginfo( 'description' );
	
	if( ! empty( $logo_description ) ) {
	
		// Set slogan tag to H1 on front page
		$tag = ( is_front_page() ) ? 'h1' : 'div';
		$logo .= "\n" . '<' . $tag . ' id="logo-description">' . $logo_description . '</' . $tag . '>';
		
	}
		
	echo apply_filters( 'frameshift_do_logo', $logo );

}

/**
 * Built header right area
 *
 * @since 1.0
 */
 
add_action( 'frameshift_header_right', 'frameshift_do_header_right' );

function frameshift_do_header_right() {

	// Get header right content from options
	$header_right = frameshift_get_option( 'header_right', true );
	
	if( ! empty( $header_right ) )
		echo apply_filters( 'frameshift_do_header_right', nl2br( $header_right ) );

}

// Activate shortocdes in header right
add_filter( 'frameshift_do_header_right', 'do_shortcode' );

/**
 * Built sub menu after main menu
 *
 * @since 1.0
 */
 
add_action( 'frameshift_header_after', 'frameshift_do_submenu' );
 
function frameshift_do_submenu() {

	if( ! has_nav_menu( 'menu-sub' ) )
		return;

	// Open layout wrap
	frameshift_layout_wrap( 'submenu-wrap' ); ?>

	<div id="submenu">	
		<?php echo frameshift_menu( 'sub', false ); ?>		
	</div><!-- .submenu --><?php
    
    // Close layout wrap	
	frameshift_layout_wrap( 'submenu-wrap', 'close' );

}