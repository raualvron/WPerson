<?php

/**
 * Create shortcodes for
 * post-related stuff
 *
 * @package FrameShift
 */
 
/**
 * Shortcode to output post date
 *
 * @since 1.0
 */
 
add_shortcode( 'post_date', 'frameshift_post_date_shortcode' );
 
function frameshift_post_date_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	$defaults = array(
		'format' => get_option( 'date_format' ),
		'before' => '',
		'after'  => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	$output = sprintf( '<%4$s class="post-date">%1$s%3$s%2$s</%4$s>', $before, $after, get_the_time( $format ), $wrap );

	return apply_filters( 'frameshift_post_date_shortcode', $output, $atts );

}

/**
 * Shortcode to output post categories
 *
 * @since 1.0
 */

add_shortcode( 'post_categories', 'frameshift_post_categories_shortcode' );

function frameshift_post_categories_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	$defaults = array(
		'sep'    => ', ',
		'before' => '',
		'after'  => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	$cats = get_the_category_list( $sep );

	$output = sprintf( '<%4$s class="post-categories">%2$s%1$s%3$s</%4$s> ', $cats, $before, $after, $wrap );

	return apply_filters( 'frameshift_post_categories_shortcode', $output, $atts );

}

/**
 * Shortcode to output post author
 *
 * @since 1.0
 */
 
add_shortcode( 'post_author', 'frameshift_post_author_shortcode' );

function frameshift_post_author_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	$defaults = array(
		'before' => '',
		'after'  => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	$output = sprintf( '<%4$s class="post-author vcard">%2$s<span class="fn">%1$s</span>%3$s</%4$s>', esc_html( get_the_author() ), $before, $after, $wrap );

	return apply_filters( 'frameshift_post_author_shortcode', $output, $atts );

}

/**
 * Shortcode to output post author link (website)
 *
 * @since 1.0
 */
 
add_shortcode( 'post_author_link', 'frameshift_post_author_link_shortcode' );

function frameshift_post_author_link_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	$defaults = array(
		'before'   => '',
		'after'    => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	$author = get_the_author();

	if ( get_the_author_meta( 'url' ) )
		$author = '<a href="' . get_the_author_meta( 'url' ) . '" title="' . esc_attr( sprintf( __( "Visit %s&#8217;s website", 'frameshift' ), $author ) ) . '" rel="external">' . $author . '</a>';

	$output = sprintf( '<%4$s class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</%4$s>', $author, $before, $after, $wrap );

	return apply_filters( 'frameshift_post_author_link_shortcode', $output, $atts );

}

/**
 * Shortcode to output post author link (posts)
 *
 * @since 1.0
 */
 
add_shortcode( 'post_author_posts_link', 'frameshift_post_author_posts_link_shortcode' );

function frameshift_post_author_posts_link_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	$defaults = array(
		'before' => '',
		'after'  => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	global $authordata;
	if ( !is_object( $authordata ) )
		return false;

	$link = sprintf(
	        '<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
	        get_author_posts_url( $authordata->ID, $authordata->user_nicename ),
	        esc_attr( sprintf( __( 'Posts by %s', 'frameshift' ), get_the_author() ) ),
	        get_the_author()
	);
	$link = apply_filters( 'the_author_posts_link', $link );

	$output = sprintf( '<%4$s class="author vcard">%2$s<span class="fn">%1$s</span>%3$s</%4$s>', $link, $before, $after, $wrap );

	return apply_filters( 'frameshift_post_author_posts_link_shortcode', $output, $atts );

}

/**
 * Shortcode to output post
 * comments number with link
 *
 * @since 1.0
 */

add_shortcode( 'post_comments', 'frameshift_post_comments_shortcode' );

function frameshift_post_comments_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	$defaults = array(
		'zero'   => __( 'Leave a Comment', 'frameshift' ),
		'one'    => __( '1 Comment', 'frameshift' ),
		'more'   => ' ' . __( 'Comments', 'frameshift' ),
		'before' => '',
		'after'  => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	if ( ! comments_open() )
		return;

	$comments_number = get_comments_number();

	if( $comments_number == 0 ){
	    $comments = $zero;
	}
	elseif( $comments_number > 1 ){
	    $comments = $comments_number . $more;
	}
	else {
	     $comments = $one;
	}

	// Replace #comments with #respond (there's no filter yet)
	$comments_link = ( $comments_number == 0 ) ? str_replace( '#comments', '#respond', get_comments_link() ) : get_comments_link();

	$comments = sprintf( '<a href="%1$s">%2$s</a>', $comments_link, $comments );

	$output = sprintf( '<%4$s class="post-comments">%2$s%1$s%3$s</%4$s>', $comments, $before, $after, $wrap );

	return apply_filters( 'frameshift_post_comments_shortcode', $output, $atts );

}

/**
 * Shortcode to output post tags
 *
 * @since 1.0
 */

add_shortcode( 'post_tags', 'frameshift_post_tags_shortcode' );

function frameshift_post_tags_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

$defaults = array(
    'sep'    => ', ',
    'before' => __( 'Tags', 'frameshift' ) . ': ',
    'after'  => '',
    'wrap'	 => 'span'
);

	extract( shortcode_atts( $defaults, $atts ) );

	$tags = get_the_tag_list( $before, $sep, $after );

	if ( ! $tags )
		return;

	$output = sprintf( '<%2$s class="post-tags">%1$s</%2$s> ', $tags, $wrap );

	return apply_filters( 'frameshift_post_tags_shortcode', $output, $atts );

}

/**
 * Shortcode to output post terms
 *
 * @since 1.0
 */

add_shortcode( 'post_terms', 'frameshift_post_terms_shortcode' );

function frameshift_post_terms_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

$defaults = array(
    'sep'      => ', ',
    'before'   => __( 'Terms', 'frameshift' ) . ': ',
    'after'    => '',
    'taxonomy' => 'category',
    'wrap'	   => 'span'
);

	extract( shortcode_atts( $defaults, $atts ) );

	$terms = get_the_term_list( get_the_ID(), $taxonomy, $before, $sep, $after );

	if ( is_wp_error( $terms ) || empty( $terms ) )
		return false;

	$output = sprintf( '<%2$s class="post-terms">%1$s</%2$s>', $terms, $wrap );

	return apply_filters( 'frameshift_post_terms_shortcode', $output, $terms, $atts );

}

/**
 * Shortcode to output the post edit link
 *
 * @since 1.0
 */

add_shortcode( 'post_edit', 'frameshift_post_edit_shortcode' );

function frameshift_post_edit_shortcode( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	$link = get_edit_post_link();

	if( empty( $link ) )
		return;

	$defaults = array(
		'label'  => __( '[Edit]', 'frameshift' ),
		'before' => '',
		'after'  => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	$output = sprintf( '<%5$s class="post-edit">%3$s<a href="%1$s">%2$s</a>%4$s</%5$s> ', $link, $label, $before, $after, $wrap );

	return apply_filters( 'frameshift_post_edit_shortcode', $output, $atts );

}

/**
 * Shortcode to output the post parent link
 *
 * @since 1.0
 */

add_shortcode( 'post_parent', 'frameshift_post_parent' );

function frameshift_post_parent( $atts ) {

	$post_id = get_the_ID();	
	if( empty( $post_id ) )
		return;

	// Check if parent exists		
	$post = get_post( get_the_ID() );
	if( empty( $post->post_parent ) )
		return;

	// Get parent post
	$parent = get_post( $post->post_parent );

	$defaults = array(
		'label'   => get_the_title( $parent->ID ),
		'before' => '- ' . __( 'Attached to', 'frameshift' ) . ': ',
		'after'  => '',
		'wrap'	 => 'span'
	);

	extract( shortcode_atts( $defaults, $atts ) );

	$output = sprintf( '<%5$s class="post-parent">%3$s<a href="%1$s">%2$s</a>%4$s</%5$s> ', get_permalink( $parent->ID ), $label, $before, $after, $wrap );

	return apply_filters( 'frameshift_post_parent_shortcode', $output, $atts );

}

/**
 * Custom post gallery shortcode
 *
 * @since 1.0
 */

add_shortcode( 'image_gallery', 'frameshift_post_gallery' );

function frameshift_post_gallery( $atts ) {

	$defaults = array(
		'link'	  => '',
		'order'   => 'ASC',
        'orderby' => 'menu_order ID',
        'id'      => get_the_ID(),
        'size'    => 'post-thumbnail',
        'include' => '',
        'exclude' => ''
	);

	extract( shortcode_atts( $defaults, $atts ) );

    if ( ! empty( $include ) ) {
    
    	$include = preg_replace( '/[^0-9,]+/', '', $include );
    
    	$args = array(
    		'include' 		 => $include,
    		'post_status' 	 => 'inherit',
    		'post_type' 	 => 'attachment',
    		'post_mime_type' => 'image',
    		'order' 		 => $order,
    		'orderby' 		 => $orderby
    	);
    	
    	$args = apply_filters( 'frameshift_post_gallery_args', $args );
    
        $_attachments = get_posts( $args );

        $attachments = array();
        
        foreach ( $_attachments as $k => $v ) {
            $attachments[$v->ID] = $_attachments[$k];
        }
        
    } else {
    
    	if( $exclude == 'featured' )
    		$exclude = get_post_thumbnail_id( get_the_ID() );
    
    	$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
    	
    	$args = array(
    		'post_parent' 	 => $id,
    		'exclude' 		 => $exclude,
    		'post_status' 	 => 'inherit',
    		'post_type' 	 => 'attachment',
    		'post_mime_type' => 'image',
    		'order' 		 => $order,
    		'orderby' 		 => $orderby
    	);
    	
    	$args = apply_filters( 'frameshift_post_gallery_args', $args );
    	
        $attachments = get_children( $args );
        
    }

	// Stop if no attachments

    if ( empty( $attachments ) )
        return;
        
    // Return attachment link list in feeds

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
        return $output;
    }
    
    // Get layout
    
    $layout    = get_post_meta( get_the_ID(), '_layout', true );
    $post_type = get_post_type();
    
    // Check full size
    
    $full_size = ( $layout == 'full-width' || ( ! is_active_sidebar( 'sidebar' ) && ! is_active_sidebar( 'sidebar-' . $post_type ) ) ) ? true : false;
    
    // Set clear
    
    if( $size == 'post-thumbnail' || $size == 'small' ) {    
    	if( $full_size == true ) {
		    $n = ( FRAMESHIFT_LAYOUT == 'four' ) ? 4 : 3;
		} else {
		    $n = ( FRAMESHIFT_LAYOUT == 'four' ) ? 3 : 2;
		}		
	} elseif( $size == 'half' ) {
		$n = 2;    
    } elseif( $size == 'big' ) {
    	$n = 1;    
    } elseif( $size == 'full' ) {
    	// Correct size if necessary
    	$size = ( $full_size == true ) ? $size : 'big';
    	$n = 1;
    }
    
    // Correct image size
    $size = ( $size == 'small' ) ? 'post-thumbnail' : $size;
    
    // Correct span depending on image size
    $span = ( $size == 'post-thumbnail' ) ? 'small' : $size;
    
    // Create optional image overlay
	$overlay = apply_filters( 'frameshift_portfolio_image_overlay', false );
    
    // Begin image gallery output
    
    $output  = "\n\n" . '<div class="image-gallery">' . "\n";
    $output .= "\n\t" . '<div class="row">' . "\n\n";
    
    // Set counter
    $i = 0;
    
    	// Loop through attachments

		foreach ( $attachments as $attachment_id => $attachment ) {

			$clear = ( $i%$n == 0 ) ? ' clear' : false;

		    $output .= "\t\t" . '<div class="image-gallery-item ' . frameshift_get_span( $span ) . $clear . '">';

		    // Link to attachment page

		    if( $link == 'attachment' ) {
		    	$output .= wp_get_attachment_link( $attachment_id, $size, true, false );
		    } else {

		    	// Unlinked image

		    	$image = wp_get_attachment_image( $attachment_id , $size );

		    	// Link image if desired

		    	if( $link != 'false' ) {
		    		$src 	 = wp_get_attachment_image_src( $attachment_id, apply_filters( 'frameshift_post_gallery_lightbox_size', 'original' ) );
		    		$output .= '<a href="' . $src[0] . '" title="' . $attachment->post_title . '" class="gallery-link" rel="prettyPhoto[gallery]">' . $image . '</a>';
		    	} else {
		    		$output .= $image;		    	
		    	}    
		    }

		    if( ! empty( $attachment->post_excerpt ) )
		    	$output .= '<div class="image-gallery-caption">' . $attachment->post_excerpt . '</div>';

		    $output .= '</div>' . "\n\n";

			// Increase counter
			$i++;

		} // endforeach

	$output .= "\t" . '</div><!-- .row -->' . "\n";
	$output .= "\n" . '</div><!-- .image-gallery -->' . "\n\n";

    return apply_filters( 'frameshift_post_gallery_shortcode', $output );
}

/**
 * Custom post slider shortcode
 *
 * @since 1.0
 */

add_shortcode( 'image_slider', 'frameshift_post_slider' );

function frameshift_post_slider( $atts ) {

	$defaults = array(
		'link'	  	=> '',
		'order'   	=> 'ASC',
        'orderby' 	=> 'menu_order ID',
        'id'      	=> get_the_ID(),
        'size'    	=> 'full',
        'include' 	=> '',
        'exclude' 	=> '',
        'prevnext'	=> 'true',
        'keynav' 	=> 'false',
        'mousenav' 	=> 'false',
        'effect'  	=> 'fade',
        'direction' => 'horizontal',
        'timer'   	=> 0
	);

	extract( shortcode_atts( $defaults, $atts ) );

    if ( ! empty( $include ) ) {
    
    	$include = preg_replace( '/[^0-9,]+/', '', $include );
    
		$args = array(
		    'include' 		 => $include,
		    'post_status' 	 => 'inherit',
		    'post_type' 	 => 'attachment',
		    'post_mime_type' => 'image',
		    'order' 		 => $order,
		    'orderby' 		 => $orderby
		);
    	
    	$args = apply_filters( 'frameshift_post_slider_args', $args );
    
        $_attachments = get_posts( $args );

        $attachments = array();
        
        foreach ( $_attachments as $k => $v ) {
            $attachments[$v->ID] = $_attachments[$k];
        }
        
    } else {
    
    	if( $exclude == 'featured' )
    		$exclude = get_post_thumbnail_id( get_the_ID() );
    
    	$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
    	
    	$args = array(
    		'post_parent' 	 => $id,
    		'exclude' 		 => $exclude,
    		'post_status' 	 => 'inherit',
    		'post_type' 	 => 'attachment',
    		'post_mime_type' => 'image',
    		'order' 		 => $order,
    		'orderby' 		 => $orderby
    	);
    	
    	$args = apply_filters( 'frameshift_post_slider_args', $args );
    	
        $attachments = get_children( $args );
        
    }

	// Stop if no attachments

    if ( empty( $attachments ) )
        return;
        
    // Return attachment link list in feeds

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
        return $output;
    }
    
    // Get layout
    
    $layout    = get_post_meta( get_the_ID(), '_layout', true );
    $post_type = get_post_type();
    
    // Check full size
    
    $full_size = ( $layout == 'full-width' || ( ! is_active_sidebar( 'sidebar' ) && ! is_active_sidebar( 'sidebar-' . $post_type ) ) ) ? true : false;
    
    // Correct image size
    $size = ( $full_size != true && $size == 'full' ) ? 'big' : $size;
    
    // Correct image size
    $size = ( $size == 'small' ) ? 'post-thumbnail' : $size;
    
    // Correct span depending on image size
    $span = ( $size == 'post-thumbnail' ) ? 'small' : $size;

	// Correct timer and slideshow = true
	$slideshow = ( $timer == 0 ) ? 'false' : 'true';

	// Correct timer
	$timer = ( $timer != 0 ) ? $timer . '000' : 0;

	$slider_args = array(
	    'animation' 		=> '"' . $effect . '",',
	    'slideDirection' 	=> '"' . $direction . '",',
	    'slideshow' 		=> "$slideshow,",
	    'slideshowSpeed' 	=> "{$timer},",
	    'animationDuration' => '300,',
	    'directionNav' 		=> "$prevnext,",
	    'controlNav' 		=> 'false,',
	    'keyboardNav' 		=> "$keynav,",
	    'mousewheel' 		=> "$mousenav,",
	    'prevText' 			=> '"' . __( 'Previous', 'frameshift' ) . '",',
	    'nextText'			=> '"' . __( 'Next', 'frameshift' ) . '",',
	    'pausePlay' 		=> 'false,',
	    'pauseText' 		=> '"' . __( 'Pause', 'frameshift' ) . '",',
	    'playText' 			=> '"' . __( 'Play', 'frameshift' ) . '",',
	    'animationLoop' 	=> 'true,',
	    'pauseOnAction' 	=> 'true,',
	    'pauseOnHover' 		=> 'true'
	);

	$slider_args = apply_filters( 'frameshift_post_slider_options_args', $slider_args );

	// Create inline slider Javascript

	$output  = "\n" . '<script type="text/javascript">' . "\n";
	$output .= 'jQuery(document).ready(function($){' . "\n";
	$output .= "\t" . '$(function(){' . "\n";
	$output .= "\t\t" . '$(".flexslider").flexslider({' . "\n";
	foreach( $slider_args as $k => $v )
		$output .= "\t\t\t" . $k . ': ' . $v . "\n";
	$output .= "\t\t" . '});' . "\n";
	$output .= "\t" . '});' . "\n";
	$output .= '});' . "\n";
	$output .= '</script>' . "\n\n";

	/**
	 * Set fixed height on slider container
	 * to avoid layout jump on load
	 */						
	$img = frameshift_get_image_size( $size );
	$height = $img['size']['h'];
        		
    $output .= '<div class="row"><div class="image-slider ' . frameshift_get_span( $span ) . '">' . "\n";	
	$output .= "\n\t" . '<div class="flexslider height-' . $height . '"><ul class="slides">' . "\n";

	// Loop through attachments

	foreach ( $attachments as $attachment_id => $attachment ) {

		$output .= "\n\t\t" . '<li>';

		// Link to attachment page

		if( $link == 'attachment' ) {

		    $output .= wp_get_attachment_link( $attachment_id, $size, true, false );

		} else {

		    // Unlinked image

		    $image = wp_get_attachment_image( $attachment_id , $size );	    

		    // Link image if desired

		    if( $link != 'false' ) {
		    	$src 	 = wp_get_attachment_image_src( $attachment_id, apply_filters( 'frameshift_post_slider_lightbox_size', 'original' ) );
		    	$output .= '<a href="' . $src[0] . '" title="' . $attachment->post_title . '" class="gallery-link" rel="prettyPhoto[gallery]">' . $image . '</a>';
		    } else {
		    	$output .= $image;		    	
		    }    
		}

		$output .= '</li>' . "\n";

	}

	$output .= "\n\t" . '</ul></div><!-- .flexslider -->' . "\n";
	$output .= "\n" . '</div><!-- .image-slider --></div>' . "\n";

	return apply_filters( 'frameshift_post_slider_shortcode', $output );
}