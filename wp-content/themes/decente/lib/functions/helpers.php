<?php

/**
 * Helper function to convert
 * underscores to dashes for widget classes
 *
 * @since 1.0
 */
 
function frameshift_dashes( $string ) {

	$string = str_replace( '_', '-', $string );
	
	return $string;

}

/**
 * Helper functions to check if a
 * special page template is active
 *
 * @since 1.0
 */

function is_pagetemplate_active( $pagetemplate = '' ) {
    global $wpdb;
    $sql = "select meta_key from $wpdb->postmeta where meta_key like '_wp_page_template' and meta_value like '" . $pagetemplate . "'";
    $result = $wpdb->query($sql);
    if ($result) {
        return TRUE;
    } else {
        return FALSE;
    }
}


/**
 * Helper function to get the
 * permalink of a page template
 *
 * @since 1.0
 */

function get_pagetemplate_permalink( $pagetemplate = '' ) {
    global $wpdb;
    $sql = "select post_id from $wpdb->postmeta where meta_key like '_wp_page_template' and meta_value like '" . $pagetemplate . "'";
    $rows = $wpdb->get_results($sql);

    if ($rows) {
        return get_permalink($rows[0]->post_id);
    }
}

/**
 * Helper function to
 * check multi-dimensional arrays
 *
 * @since 1.0
 */

function array_empty( $mixed ) {
    if ( is_array( $mixed ) ) {
        foreach ( $mixed as $value ) {
            if ( ! array_empty( $value ) ) {
                return false;
            }
        }
    }
    elseif ( ! empty( $mixed ) ) {
        return false;
    }
    return true;
}

function in_multiarray( $elem, $array )
{
    // if the $array is an array or is an object
     if( is_array( $array ) || is_object( $array ) )
     {
         // if $elem is in $array object
         if( is_object( $array ) )
         {
             $temp_array = get_object_vars( $array );
             if( in_array( $elem, $temp_array ) )
                 return TRUE;
         }
       
         // if $elem is in $array return true
         if( is_array( $array ) && in_array( $elem, $array ) )
             return TRUE;
           
       
         // if $elem isn't in $array, then check foreach element
         foreach( $array as $array_element )
         {
             // if $array_element is an array or is an object call the in_multiarray function to this element
             // if in_multiarray returns TRUE, than the element is in array, else check next element
             if( ( is_array( $array_element ) || is_object( $array_element ) ) && in_multiarray( $elem, $array_element ) )
             {
                 return TRUE;
                 exit;
             }
         }
     }
   
     // if isn't in array return FALSE
     return FALSE;
}

/**
 * Remove recent comments inline CSS
 *
 * @since 1.0
 */
 
add_action( 'widgets_init', 'frameshift_remove_recent_comments_style' );

function frameshift_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'  ) );
}

/**
 * If empty post title, display
 * [Untitled].
 *
 * @since 1.0
 */
 
add_filter( 'the_title', 'frameshift_empty_post_title' );

function frameshift_empty_post_title( $title ) {
	
	if( empty( $title ) )
		$title = apply_filters( 'frameshift_empty_post_title', '[' . __( 'Untitled', 'frameshift' ) . ']' );
		
	return $title;
}

/**
 * Enable iframes and videos for slider
 *
 * @since 1.0
 */
 
global $allowedposttags;

$allowedposttags["iframe"] = array(
 	"src" => array(),
 	"height" => array(),
 	"width" => array()
);

$allowedposttags["object"] = array(
 	"height" => array(),
 	"width" => array()
);

$allowedposttags["param"] = array(
 	"name" => array(),
 	"value" => array()
);

$allowedposttags["embed"] = array(
 	"src" => array(),
 	"type" => array(),
 	"allowfullscreen" => array(),
 	"allowscriptaccess" => array(),
 	"height" => array(),
 	"width" => array()
);

/**
 * Exclude pages from search
 *
 * @since 1.0
 */
 
add_filter( 'pre_get_posts', 'frameshift_search_filter' );
 
function frameshift_search_filter( $query ) {

    if ( ! $query->is_admin && $query->is_search ) {
    
    	$post_types = apply_filters( 'frameshift_search_post_types', array( 'post' ) );
    	
    	if( is_array( $post_types ) )
    		$query->set( 'post_type', $post_types );
        
    }
    return $query;
}

/**
 * Remove 10px extra margin from
 * image caption shortcode
 *
 * @since 1.0
 */
 
add_filter( 'img_caption_shortcode', 'ts_fix_caption_shortcode', 10, 3 );

function ts_fix_caption_shortcode( $x = null, $attr, $content ) {

        extract(shortcode_atts(array(
                'id'    => '',
                'align'    => 'alignnone',
                'width'    => '',
                'caption' => ''
            ), $attr));

        if ( 1 > (int) $width || empty( $caption ) ) {
            return $content;
        }

        if ( $id ) $id = 'id="' . $id . '" ';

    return '<div ' . $id . 'class="wp-caption ' . $align . '" style="width: ' . ( (int) $width ) . 'px">'
    . $content . '<p class="wp-caption-text">' . $caption . '</p></div>';
    
}

/**
 * WPMU signup page
 *
 * @since 1.0
 */
 
add_action( 'before_signup_form', 'frameshift_before_signup' );

function frameshift_before_signup() {
	
	// Open layout wrap
	frameshift_layout_wrap( 'main-middle-wrap' ); 
	
	echo '<div id="main-middle" class="row"><div class="span12">';
}

add_action( 'after_signup_form', 'frameshift_after_signup' );

function frameshift_after_signup() {
	
	echo '</div></div><!-- #main-middle -->';

	// Close layout wrap
	frameshift_layout_wrap( 'main-middle-wrap', 'close' );
}

add_action( 'get_header', 'remove_wpmu_style' );

function remove_wpmu_style() {

	remove_action( 'wp_head', 'wpmu_signup_stylesheet' );
	
}