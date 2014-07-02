<?php

/**
 * Built general post output
 *
 * @package FrameShift
 */

/**
 * Add post meta to
 * frameshift_post_title_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_title_after', 'frameshift_do_post_meta' );
add_action( 'frameshift_widget_post_title_after', 'frameshift_do_post_meta' );

function frameshift_do_post_meta() {

	// Don't show meta info on pages
	if ( is_page( get_the_ID() ) || is_attachment( get_the_ID() ) || get_post_type( get_the_ID() ) == 'portfolio' )
		return;

	$post_meta = '[post_date] ' . __( 'by', 'frameshift' ) . ' [post_author_posts_link] ' . __( 'in', 'frameshift' ) . ' [post_categories] [post_edit before="- "]';
	
	// Add badge for sticky posts
	$sticky = ( is_sticky() && ( is_home() || is_page_template( 'page-tpl-blog.php' ) ) ) ? apply_filters( 'frameshift_post_sticky', '<span class="label label-info">' . __( 'Sticky', 'frameshift' ) . '</span> ' ) : '';
	
	printf( '<div class="post-meta">%2$s%1$s</div>', apply_filters( 'frameshift_do_post_meta', $post_meta ), $sticky );

}

// Activate shortcodes in $post_meta
add_filter( 'frameshift_do_post_meta', 'do_shortcode', 20 );

/**
 * Add post format icons to
 * post meta
 *
 * @since 1.0
 */

add_filter( 'frameshift_do_post_meta', 'frameshift_post_format_meta' );

function frameshift_post_format_meta( $meta ) {

	if( ! current_theme_supports( 'post-formats' ) )
		return $meta;
	
	// Get current post format
	$post_format = get_post_format();
	
	if( ! empty( $post_format ) ) {
	
		// Get post format link
		$post_format_link = get_post_format_link( $post_format );
		
		// Return linked format icon with meta
		return '<a href="' . $post_format_link . '" class="post-format-link">' . frameshift_get_post_format_icon( $post_format ) . '</a>' . $meta;
	
	}
	
	return $meta;

}

/**
 * Add post meta to attachment pages using
 * frameshift_post_title_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_title_after', 'frameshift_do_attachment_meta' );
 
function frameshift_do_attachment_meta() {

	// Only show this meta info on attachment pages
	if ( ! is_attachment( get_the_ID() ) )
		return;

	$attachment_meta = '[post_date] [post_parent] [post_edit before="- "]';
	
	printf( '<div class="post-meta">%s</div>', apply_filters( 'frameshift_do_attachment_meta', $attachment_meta ) );

}

// Activate shortcodes in $post_meta
add_filter( 'frameshift_do_attachment_meta', 'do_shortcode', 20 );

/**
 * Add featured image to
 * frameshift_post_content_before hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_content_before', 'frameshift_do_post_image' );
add_action( 'frameshift_widget_post_content_before', 'frameshift_do_post_image', 10, 2 );

function frameshift_do_post_image( $widget_width = '', $widget_location = '' ) {

	// Get post id	
	$post_id = get_the_ID();
	
	if( empty( $post_id ) )
		return;
		
	// Stop here if images are disabled on single posts
	if( is_singular() && ( frameshift_get_layout_image( 'show_on_single' ) == false && ! is_page_template( 'page-tpl-blog.php' ) ) )
		return;
		
	// Only show image on first page if multipage post
	global $page;
	if( $page != 1 && ! is_page_template( 'page-tpl-blog.php' ) )
		return;
		
	// Go on if there is a featured image

	if( has_post_thumbnail( get_the_ID() ) ) {
	
		if( ! empty( $widget_width ) ) {
		
			// In case of a widget (lastest posts)
		
			$image_size = frameshift_get_layout_image( 'size_widget' );
			$image_align = frameshift_get_layout_image( 'align_widget' );
				
			// Limit images to box width and remove align
			
			if( $widget_width == frameshift_get_span( 'half' ) && ( $image_size == 'half' || $image_size == 'big' || $image_size == 'full' ) )
				$image_align = 'none';
				
			if( ( $widget_width == 'full' || $widget_width == frameshift_get_span( 'big' ) ) && ( $image_size == 'full' || $image_size == 'big' ) && $widget_location == 'home' )
				$image_align = 'none';
				
			if( $widget_location == 'sidebar' || $widget_location == 'sidebar-home' || $widget_width == frameshift_get_span( 'small' ) )
				$image_align = 'none';
		
		} elseif( is_singular() && ! is_page_template( 'page-tpl-blog.php' ) ) {
		
			// If this is a single post or a static page
		
			if( get_post_meta( $post_id, '_image_size', true ) ) {			
				// Respect individual post image settings
				$image_size = get_post_meta( $post_id, '_image_size', true );				
			} else {
				$image_size = frameshift_get_layout_image( 'size_single' );
			}
		
			// Set image align depending on layout
			
			if( get_post_meta( $post_id, '_image_align', true ) ) {
				// Respect individual post image settings
			    $image_align = get_post_meta( $post_id, '_image_align', true );			    
			} else {
			    $image_align = frameshift_get_layout_image( 'align_single' );
			}
			
			// Remove alignment in some cases
			
			if( is_single() && $image_size == 'big' && ( is_active_sidebar( 'sidebar-post' ) || is_active_sidebar( 'sidebar' ) ) && get_post_meta( $post_id, '_layout', true ) != 'full-width' )
				$image_align = 'none';
				
			if( is_page() && ! is_page_template( 'page-tpl-blog.php' ) && $image_size == 'big' && ( is_active_sidebar( 'sidebar-page' ) || is_active_sidebar( 'sidebar' ) ) && get_post_meta( $post_id, '_layout', true ) != 'full-width' )
				$image_align = 'none';
				
			if( $image_size == 'full' )
				$image_align = 'none';
	    	
	    } else {
	    
	    	// Not in a widget nor on a single page but on archive pages
	    
	    	$image_size = frameshift_get_layout_image( 'size_archive' );
	    	$image_align = frameshift_get_layout_image( 'align_archive' );
	    
	    	// Set current archive
	    	
	    	if( is_tag() ) {
				$archive = 'tag';
			} elseif( is_author() ) {
				$archive = 'author';
			} elseif( is_date() ) {
				$archive = 'date';
			} elseif( is_search() ) {
				$archive = 'search';
			} else {
				$archive = 'category';
			}
			
			// Get archive layout
			$archive_layout = frameshift_get_archive_layout( $archive );
	    
	    	// Limit images to box width and remove align
	    	
	    	if( $image_size == 'big' || $image_size == 'full' )
	    		$image_align = 'none';
	    		
	    	if( $archive_layout == frameshift_get_span( 'big' ) && $image_size == 'half' )
	    		$image_align = 'none';
	    		
	    	if( $archive_layout == frameshift_get_span( 'small' ) )
	    		$image_align = 'none';
	    	
	    }
	    
	    // Fincally output post image
	    
	    if( is_singular() && ! is_page_template( 'page-tpl-blog.php' ) ) {
	    
	    	// Echo image on single posts and page withouth link tag
	    	
	    	echo '<div class="post-image align' . $image_align . ' size-' . $image_size . '">' . get_the_post_thumbnail( $post_id, $image_size, array( 'alt' => the_title_attribute('echo=0'), 'title' => the_title_attribute('echo=0') ) ) . '</div><!-- .post-image -->' . "\n";
	    
	    } else {
	    
	    	// Create optional image overlay	    
	    	$overlay = apply_filters( 'frameshift_post_image_overlay', false );
	    
	    	echo '<div class="post-image align' . $image_align . ' size-' . $image_size . '"><a href="' . get_permalink( $post_id ) . '">' . get_the_post_thumbnail( $post_id, $image_size, array( 'alt' => the_title_attribute('echo=0'), 'title' => the_title_attribute('echo=0') ) ) . $overlay . '</a></div><!-- .post-image -->' . "\n";
	    
	    }
	    
	}

}

/**
 * Add post paging for <!--nextpage--> quicktag to
 * frameshift_post_content_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_content_after', 'frameshift_do_link_pages', 10 );

function frameshift_do_link_pages() {

	// Stop if not on single posts or pages
	if( ! is_singular() || is_page_template( 'page-tpl-blog.php' ) )
		return;

	$args = array(
    	'before'           => '<div class="pagination post-pagination"><ul>',
    	'after'            => '</ul></div>',
    	'link_before'      => '<span>',
    	'link_after'       => '</span>',
    	'next_or_number'   => 'number',
    	'nextpagelink'     => __( 'Next page', 'frameshift' ),
    	'previouspagelink' => __( 'Previous page', 'frameshift' ),
    	'pagelink'         => '%',
    	'echo'             => 0
    );
    
    $args = apply_filters( 'frameshift_do_link_pages_args', $args );
    
    /**
     * Hacky way to create a list of wp_link_pages()
     * Unfortunately you cannot place anything before and after a tags
     */
    
    $output = str_replace( '<a', '<li><a', wp_link_pages( $args ) );
    $output = str_replace( '</a>', '</a></li>', $output );
    $output = str_replace( ' <span>', ' <li class="active"><a href="#">', $output );
    $output = str_replace( '</span> ', '</a></li> ', $output );
    
    echo apply_filters( 'frameshift_do_link_pages', $output );

}

/**
 * Add tags to single posts using
 * frameshift_post_content_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_content_after', 'frameshift_do_post_tags', 20 );

function frameshift_do_post_tags() {
	
	if( is_single() )
		the_tags( '<p class="post-tags">',', ', '</p>' );

}

/**
 * Add author box to single posts using
 * frameshift_post_content_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_content_after', 'frameshift_do_author_box', 30 );

function frameshift_do_author_box() {

	// Stop if not single post
	if( ! is_single() || is_attachment() )
		return;
		
	// Stop when author bio empty
	if( ! get_the_author_meta( 'description' ) )
		return;
		
	// Set up fiterable args for author box
	$args = array(
	    'name_before' 		 => '<h4>' . __( 'About', 'frameshift' ) . ' ',
	    'name_after'  		 => '</h4>',
	    'name'				 => '[post_author_posts_link]',
	    'avatar_size' 		 => 80,
	    'description_before' => '',
	    'description_after'  => ''
	);
	
	$args = apply_filters( 'frameshift_do_author_box_args', $args );
	
	// Extract $args
	extract( $args, EXTR_SKIP );
	
	global $authordata;
	$authordata = is_object( $authordata ) ? $authordata : get_userdata( get_query_var('author') );
	
	?>
	
	<div class="author-box clearfix">
	
	    <?php
	    	echo get_avatar( get_the_author_meta( 'email' ), apply_filters( 'frameshift_author_box_gravatar_size', $avatar_size ) );
	    	echo apply_filters( 'frameshift_author_box_title', sprintf( '<div class="author-box-title">%1$s%3$s%2$s</div>', $name_before, $name_after, $name ) );
	    	echo apply_filters( 'frameshift_author_box_description', sprintf( '<div class="author-box-description">%1$s%3$s%2$s</div>', $description_before, $description_after, wpautop( get_the_author_meta( 'description' ) ) ) );
	    ?>
	    
	</div><!-- .author-box -->
	
	<?php

}

// Activate shortcodes in author box title
add_filter( 'frameshift_author_box_title', 'do_shortcode' );

// Activate shortcodes in author box description
add_filter( 'frameshift_author_box_description', 'do_shortcode' );

/**
 * Add post navigation to single posts using
 * frameshift_post_content_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_content_after', 'frameshift_do_post_navigation', 40 );

function frameshift_do_post_navigation() {

	if( ! is_single() )
		return;
		
	$args = array(
		'in_same_cat' => false,
		'excluded_categories' => false
	);
	
	$args = apply_filters( 'frameshift_do_post_navigation_args', $args );
	
	// Extract $args
	extract( $args, EXTR_SKIP );

	$previous = get_adjacent_post( $in_same_cat, $excluded_categories, false );
	$next = get_adjacent_post( $in_same_cat, $excluded_categories, true );

	if( ! empty( $previous ) || ! empty( $next ) ) {
	
		$navigation = '<div class="post-navigation clearfix">';
		
		if( ! empty( $previous ) )
			$navigation .= '<div class="previous"><a href="' . get_permalink( $previous->ID ) . '" title="">&larr; ' . get_the_title( $previous->ID ) . '</a></div>';
		
		if( ! empty( $next ) )
			$navigation .= '<div class="next"><a href="' . get_permalink( $next->ID ) . '" title="">' . get_the_title( $next->ID ) . ' &rarr;</a></div>';
		
		$navigation .= '</div><!-- .post-pagination -->';
		
		echo apply_filters( 'frameshift_do_post_navigation', $navigation );
	
	}

}

/**
 * Add image attachment navigation to
 * frameshift_post_content_after hook
 *
 * @since 1.0
 */
 
add_action( 'frameshift_post_content_after', 'frameshift_do_attachment_navigation' );

function frameshift_do_attachment_navigation() {

	// Show only on attachment pages
	if( ! is_attachment() )
		return;
	
	// Check if parent exists		
	$post = get_post( get_the_ID() );
	if( empty( $post->post_parent ) )
		return;
		
	$previous = get_adjacent_image_link( true, 0, '&larr; ' . __( 'Previous', 'frameshift' ) );
	$next = get_adjacent_image_link( false, 0, __( 'Next', 'frameshift' ) . ' &rarr;' );
	
	if( ! empty( $previous ) || ! empty( $next ) ) {
	
		// Add class when first or last gallery item
	
		$pos = '';	
		if( empty( $previous ) )
			$pos = ' first';
		if( empty( $next ) )
			$pos = ' last';
			
		// Add class when span4 and we need 1/3 boxes
		$layout = ( FRAMESHIFT_LAYOUT == 'three' && ( is_active_sidebar( 'sidebar' ) || is_active_sidebar( 'sidebar-post' ) ) ) ? ' third' : false;
		
		$navigation = '<div class="attachment-navigation' . $pos . $layout . '"><div class="row">';
		
		if( ! empty( $previous ) )
			$navigation .= '<div class="previous ' . frameshift_get_span( 'small' ) . '">' . $previous . '</div>';
			
		$navigation .= '<div class="center ' . frameshift_get_span( 'small' ) . '">' . do_shortcode( '[post_parent before="" label="' . __( 'Back to Article', 'frameshift' ) . '"]' ) . '</div>';
			
		if( ! empty( $next ) )
			$navigation .= '<div class="next ' . frameshift_get_span( 'small' ) . '">' . $next . '</div>';
			
		$navigation .= '</div></div><!-- .post-pagination -->';
		
		echo apply_filters( 'frameshift_do_attachment_navigation', $navigation );
		
	}

}

/**
 * Return next or previous image link
 * that has the same post parent.
 *
 * Copied from echo functions in media.php.
 *
 * @since 1.0
 */
 
if( ! function_exists( 'get_adjacent_image_link' ) ) {
 
	function get_adjacent_image_link( $prev = true, $size = 'thumbnail', $text = false ) {
	
		$post = get_post( get_the_ID() );
		
		$args = array(
			'post_parent' => $post->post_parent,
			'post_status' => 'inherit',
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'order' => 'ASC',
			'orderby' => 'menu_order ID'
		);
		
		$attachments = array_values( get_children( $args ) );
	
		foreach ( $attachments as $k => $attachment )
			if ( $attachment->ID == $post->ID )
				break;
	
		$k = $prev ? $k - 1 : $k + 1;
	
		if ( isset($attachments[$k]) )
			return wp_get_attachment_link($attachments[$k]->ID, $size, true, false, $text);
	}

}

if( ! function_exists( 'get_previous_image_link' ) ) {
	function get_previous_image_link( $size = 'thumbnail', $text = false ) {
		return get_adjacent_image_link( true, $size, $text );
	}
}

if( ! function_exists( 'get_next_image_link' ) ) {
	function get_next_image_link( $size = 'thumbnail', $text = false ) {
		return get_adjacent_image_link( false, $size, $text );
	}
}