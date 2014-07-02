<?php

/**
 * Create spaces widget. Image upload function
 * was added from image widget (see credit below).
 *
 * @package FrameShift
 * @subpackage Widgets
 * @since 1.0
 * @author Simon Rimkus
 * @author Shane & Peter, Inc. (Peter Chester)
 * @credit http://wordpress.org/extend/plugins/image-widget/
 */
 
/**
 * Register widget
 */
 
add_action( 'widgets_init', 'frameshift_register_widget_spaces' );
 
function frameshift_register_widget_spaces() {

	register_widget( 'FrameShift_Spaces' );

}

/**
 * Widget class
 */

class FrameShift_Spaces extends WP_Widget {
	
	function __construct() {
	
		$widget_ops = array( 'description' => __( 'Content space with text and image', 'frameshift' ) );
		parent::__construct( 'FrameShift_Spaces', FRAMESHIFT_NAME . ' ' . __( 'Spaces', 'frameshift' ), $widget_ops );

		global $pagenow;
		
		if ( is_admin() ) {
		
    		add_action( 'admin_init', array( $this, 'fix_async_upload_image' ) );
    		
			if ( 'widgets.php' == $pagenow ) {
			
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( $this->id_base, FRAMESHIFT_ADMIN_URL . '/js/image-widget.js', array( 'thickbox' ), false, true );
				$data = array( 'frameshift_change_image' => __( 'Change image', 'frameshift' ) );
				wp_localize_script( $this->id_base, 'frameshift_spaces', $data );
				add_action( 'admin_head-widgets.php', array( $this, 'frameshift_spaces_admin_head' ) );
				
			} elseif ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			
				add_filter( 'image_send_to_editor', array( $this,'image_send_to_editor'), 1, 8 );
				add_filter( 'gettext', array( $this, 'replace_text_in_thickbox' ), 1, 3 );
				add_filter( 'media_upload_tabs', array( $this, 'media_upload_tabs' ) );
				
			}
		}
		
	}
	
	function widget( $args, $instance ) {
		
		extract( $args, EXTR_SKIP );
		
		$icon 	  	= isset( $instance['icon'] ) ? strip_tags( $instance['icon'] ) : false;
		$title 		= isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : false;
		$title_icon = isset( $instance['title_icon'] ) ? strip_tags( $instance['title_icon'] ) : false;
		$image 		= isset( $instance['image'] ) ? $instance['image'] : false;
		$imageurl 	= isset( $instance['imageurl'] ) ? $instance['imageurl'] : false;
		$size 		= isset( $instance['size'] ) ? $instance['size'] : 'post-thumbnail';
		$imagewidth	= isset( $instance['imagewidth'] ) ? $instance['imagewidth'] : false;
		$height		= isset( $instance['height'] ) ? $instance['height'] : false;
		$align 		= isset( $instance['align'] ) ? $instance['align'] : 'none';
		$link 		= isset( $instance['link'] ) ? $instance['link'] : false;
		$text		= isset( $instance['text'] ) ? wp_kses_post( $instance['text'] ) : false;
		$center		= isset( $instance['center'] ) ? $instance['center'] : false;
		$link_space	= isset( $instance['link_space'] ) ? strip_tags( $instance['link_space'] ) : false;
		$width 		= isset( $instance['width'] ) ? $instance['width'] : 'full';
		$position	= isset( $instance['position'] ) ? $instance['position'] : false;
		    
		// Check widget width
		    
		$widget_width = $width;
		    	
		// Correct box width if necessary
		    
		if( $id == 'sidebar' || $id == 'sidebar-home' )
		    $widget_width = '';
		    
		if( $id == 'home' && $width == frameshift_get_span( 'full' ) )
		    $widget_width = frameshift_get_span( 'big' );
		    
		// Add class to center widget content
		$center = ( $center == true ) ? ' widget-spaces-center' : false;
		
		// Add width and position classes
		
		$position = ( $position == true ) ? ' first' : false;
		if( ! empty( $position ) )
			$widget_width = $widget_width . $position;
		
		?>
		
		<div id="<?php echo frameshift_dashes( $widget_id ); ?>-wrap" class="widget-wrap widget-spaces-wrap <?php echo $widget_width; ?>">
		
			<div id="<?php echo frameshift_dashes( $widget_id ); ?>" class="widget widget-spaces<?php echo $center; ?> clearfix">
			
				<div class="widget-inner">
        			
			    	<?php
			    		$space = '';
        				
        				if( $size != 'custom' ) {        		
        				
        					// Correct image sizes if necessary
        				
        					if( $width == frameshift_get_span( 'small' ) && ! empty( $size ) && $size != 'post-thumbnail' )
        						$size = 'post-thumbnail';
        						
        					if( $width == frameshift_get_span( 'half' ) && ! empty( $size ) && $size != 'post-thumbnail' && $size != 'half' )
        						$size = 'half';
        						
        					if( $width == frameshift_get_span( 'big' ) && $size == 'full' )
        						$size = 'big';
        						
        					// Correct image aligns if necessary
        					
        					if(
        						( $width == frameshift_get_span( 'small' ) || $id == 'sidebar' || $id == 'sidebar-home' ) ||
        						$width == frameshift_get_span( 'half' ) && $size == 'half' ||
        						$width == frameshift_get_span( 'big' ) && $size == 'big' ||
        						$size == 'full'
        					)
        						$align = 'none';
        					
        				}
        				
        				// Correct image size if empty
        				
        				if( $size == 'custom' && ( empty( $width ) || empty( $height ) ) )        		
        					$size = 'post-thumbnail';
        					
        				// Check if space should be linked
        				
        				if( $link_space ) {        		
        					$url = ( substr( $link_space, 0, 4 ) == 'http' ) ? strip_tags( $link_space ) : get_permalink( (int) $link_space );
        				}
        				
        				// Display space icon
        				
        				if( ! empty( $icon ) ) {
        					
        					$icon = '<i class="' . $icon . '"></i>';
        					
        					if( ! empty( $url ) )
        						$icon = '<a href="' . $url . '">' . $icon . '</a>';
        					
        					$space .= '<div class="space-icon">' . $icon . '</div>' . "\n";        				
        				}
        				
			    		// Display title
			    		
			    		if( ! empty( $title ) ) {
			    		
			    			if( ! empty( $title_icon ) )
			    				$title = '<i class="' . $title_icon . '"></i>' . $title;
			    				
			    			if( ! empty( $url ) )
			    				$title = '<a href="' . $url . '">' . $title . '</a>';
			    				
			    			$space .= '<h3 class="title">' . $title . '</h3>' . "\n";
			    		}
			    			
			    		// Display image
			    		
			    		if( $image ) {
			    		
			    			// Create optional image overlay	    
	    					$overlay = apply_filters( 'frameshift_space_image_overlay', false );
			    		
			    			if( $size == 'custom' ) {
			    				$image_tag = '<img src="' . $imageurl . '" alt="' . strip_tags( $instance['title'] ) . '" class="attachment-' . $size . ' spaces-post-image align' . $align . '" style="max-width:' . $imagewidth . 'px;height:auto" />';
			    			} else {
			    				$image_tag = wp_get_attachment_image( $image, $size, false, array( 'class' => 'attachment-' . $size . ' spaces-post-image align' . $align ) );
			    			}
			    			
			    			if( $link ) {		    		
			    				$full_size = wp_get_attachment_image_src( $image, 'full' );
			    				$image_tag = '<a href="' . $full_size[0] . '" rel="prettyPhoto[gallery]" title="' . strip_tags( $instance['title'] ) . '" class="space-image-zoom">' . $image_tag . $overlay . '</a>';		    		
			    			} elseif( ! empty( $url ) ) {
			    				$image_tag = '<a href="' . $url . '">' . $image_tag . $overlay . '</a>';		    		
			    			}
			    			
			    			$space .= '<div class="space-image">' . $image_tag . '</div>';
			    		}
			    			
			    		// Display text
			    		
			    		if( ! empty( $text ) )
			    			$space .= '<div class="spaces-text">' . apply_filters( 'the_content', $text ) . '</div>';
			    			
			    		echo apply_filters( 'frameshift_widget_spaces', $space );
			    	?>
			    
			    </div><!-- .widget-inner -->
			    
			</div><!-- .widget-spaces -->
		
		</div><!-- .widget-wrap -->
		
		<?php
	}
	
	function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
		
		$instance = array(
			'remove' 	=> 0,
			'center' 	=> 0,
			'link'   	=> 0,
			'position'  => 0
		);
		
		foreach ( $instance as $field => $val ) {
			if ( isset( $new_instance[$field] ) )
				$instance[$field] = 1;
		}
		
		$instance['icon'] 		= strip_tags( $new_instance['icon'] );
		$instance['title'] 		= strip_tags( $new_instance['title'] );
		$instance['title_icon'] = strip_tags( $new_instance['title_icon'] );
		$instance['image'] 		= $new_instance['image'];
		$instance['imageurl'] 	= $this->get_image_url( $new_instance['image'], $new_instance['width'], $new_instance['width'] );
		if( $_SERVER["HTTPS"] == "on" ) {
			$instance['imageurl'] = str_replace( 'http://', 'https://', $instance['imageurl'] );
		}
		$instance['size'] 	    = $new_instance['size'];
		$instance['imagewidth'] = ( ! empty( $new_instance['imagewidth'] ) ) ? (int) $new_instance['imagewidth'] : 0;
		$instance['height']     = $new_instance['imagewidth'];
		$instance['align'] 	    = strip_tags( $new_instance['align'] );
		$instance['text']  	    = wp_kses_post( $new_instance['text'] );
		$instance['link_space'] = strip_tags( $new_instance['link_space'] );
		$instance['width'] 	    = $new_instance['width'];		
		
		$instance['remove'] 	= $new_instance['remove'];
		
		// Empty image values if remove
		
		if( $instance['remove'] ) {
		
			$instance['image'] 	  = '';
			$instance['imageurl'] = '';
			$instance['imagewidth']    = '';
			$instance['height']   = '';
			
		}

		return $instance;
	}
	 
	function form( $instance ) {
	
		$defaults = array(
    		'remove'   => false,
    		'center'   => false,
    		'link' 	   => false,
    		'position' => false
    	);
        
		$instance	= wp_parse_args( (array) $instance, $defaults );
		
		$icon 	  	= isset( $instance['icon'] ) ? strip_tags( $instance['icon'] ) : false;
		$title 	  	= isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : false;
		$image 		= isset( $instance['image'] ) ? $instance['image'] : false;
		$imageurl 	= isset( $instance['imageurl'] ) ? $instance['imageurl'] : false;
		$imagewidth	= isset( $instance['imagewidth'] ) ? $instance['imagewidth'] : false;
		$title_icon = isset( $instance['title_icon'] ) ? strip_tags( $instance['title_icon'] ) : false;
		$image_link = ! empty( $instance['image'] ) ? __('Change image', 'frameshift' ) : __('Add image', 'frameshift' );
		$size 		= isset( $instance['size'] ) ? $instance['size'] : 'post-thumbnail';
		$align 		= isset( $instance['align'] ) ? $instance['align'] : 'none';
		$text		= isset( $instance['text'] ) ? wp_kses_post( $instance['text'] ) : false;
		$link_space = isset( $instance['link_space'] ) ? strip_tags( $instance['link_space'] ) : false;
		$width 		= isset( $instance['width'] ) ? $instance['width'] : 'full';
		
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'icon' ); ?>"><?php _e( 'Icon', 'frameshift' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'icon' ); ?>" name="<?php echo $this->get_field_name( 'icon' ); ?>">
			    <option value=""<?php selected( 'icon', $icon ); ?>><?php _e( 'none', 'frameshift' ); ?></option>
			    <?php
			    	foreach( frameshift_bootstrap_icons() as $k ) {
			    		echo '<option value="' . $k . '"' . selected( $k, $icon, false ) . '>' . $k . '</option>';				
			    	}
			    ?>
			</select><br />
			<span class="description"><?php printf( __( 'See available <a href="%s" target="_blank">icons</a>', 'frameshift' ), 'http://themeshift.com/frameshift/docs/icons/' ); ?></span>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'frameshift' ); ?>:</label><br />
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />	
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title_icon' ); ?>"><?php _e( 'Title Icon', 'frameshift' ); ?>:</label><br />
			<select class="widefat" id="<?php echo $this->get_field_id( 'title_icon' ); ?>" name="<?php echo $this->get_field_name( 'title_icon' ); ?>">
			    <option value=""<?php selected( 'title_icon', $title_icon ); ?>><?php _e( 'none', 'frameshift' ); ?></option>
			    <?php
			    	foreach( frameshift_bootstrap_icons() as $k ) {
			    		echo '<option value="' . $k . '"' . selected( $k, $title_icon, false ) . '>' . $k . '</option>';				
			    	}
			    ?>
			</select><br />
			<span class="description"><?php printf( __( 'See available <a href="%s" target="_blank">icons</a>', 'frameshift' ), 'http://themeshift.com/frameshift/docs/icons/' ); ?></span>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'image' ); ?>"><?php _e( 'Image', 'frameshift' ); ?>:</label>
		</p>
		<p>
			<?php
				$media_upload_iframe_src = 'media-upload.php?type=image&widget_id=' . $this->id;
				$image_upload_iframe_src = apply_filters( 'image_upload_iframe_src', $media_upload_iframe_src );
			?>
			<a href="<?php echo $image_upload_iframe_src; ?>&TB_iframe=true" id="add_image-<?php echo $this->get_field_id( 'image' ); ?>" class="thickbox-image-widget" onClick="set_active_widget('<?php echo $this->id; ?>');return false;"><?php echo $image_link; ?></a>

			<div id="display-<?php echo $this->get_field_id( 'image' ); ?>" class="frameshift-spaces-image<?php if( empty( $instance['imageurl'] ) ) echo ' no-image'; ?>">
				<?php 
					if( $imageurl ) {
					
						if( $instance['size'] == 'custom' ) {
					
							echo "<img src=\"$imageurl\" alt=\"$title\" />";
						
						} else {
						
							echo wp_get_attachment_image( $image, 'post-thumbnail' );
						
						}
					}
				?>
			</div>
			
			<input id="<?php echo $this->get_field_id( 'image' ); ?>" name="<?php echo $this->get_field_name( 'image' ); ?>" type="hidden" value="<?php echo $image; ?>" />
		</p>
		
		<div id="settings-<?php echo $this->get_field_id( 'image' ); ?>" class="<?php if( empty( $imageurl ) ) echo 'no-image'; ?>">
		
			<p>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'remove' ); ?>" name="<?php echo $this->get_field_name( 'remove' ); ?>" <?php checked( $instance['remove'], true ) ?> />
				<label for="<?php echo $this->get_field_id( 'remove' ); ?>"><?php _e( 'Remove image', 'frameshift' ); ?></label>		
			</p>	
		
			<p>
				<label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Image size', 'frameshift' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>">
					<option value="custom"<?php selected( 'custom', $size ); ?>><?php _e( 'custom', 'frameshift' ); ?></option>
					<?php
						foreach( frameshift_image_sizes() as $k => $v ) {
							echo '<option value="' . $k . '"' . selected( $k, $size, false ) . '>' . $v['label'] . ' (' . $v['size']['w'] . 'x' . $v['size']['h'] . ')</option>';				
						}
					?>
				</select><br />
				<span class="description"<?php if( $size == 'custom' ) echo ' style="display:none"'; ?>><?php _e( 'Image size is also limited by the box width you select below', 'frameshift' ); ?></span>
			</p>
			
			<div <?php if( $size != 'custom' ) echo ' style="display:none"'; ?>>
			
				<p>
					<label for="<?php echo $this->get_field_id('imagewidth' ); ?>"><?php _e( 'Image width', 'frameshift' ); ?>:</label><br />
					<input class="widefat" id="<?php echo $this->get_field_id( 'imagewidth' ); ?>" name="<?php echo $this->get_field_name( 'imagewidth' ); ?>" type="text" value="<?php echo esc_attr( strip_tags( $imagewidth ) ); ?>" onchange="changeImgWidth('<?php echo $this->id; ?>')" />
					<input id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="hidden" value="<?php echo esc_attr( strip_tags( $imagewidth ) ); ?>" onchange="changeImgHeight('<?php echo $this->id; ?>')" />
				</p>
			
			</div>
		
			<p>
				<label for="<?php echo $this->get_field_id( 'align' ); ?>"><?php _e( 'Image align', 'frameshift' ); ?>:</label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'align' ); ?>" name="<?php echo $this->get_field_name( 'align' ); ?>">
					<option value="none"<?php selected( 'none', $align ); ?>><?php _e( 'none', 'frameshift' ); ?></option>
					<option value="left"<?php selected( 'left', $align ); ?>><?php _e( 'left', 'frameshift' ); ?></option>
					<option value="right"<?php selected( 'right', $align ); ?>><?php _e( 'right', 'frameshift' ); ?></option>
					<option value="center"<?php selected( 'center', $align ); ?>><?php _e( 'center', 'frameshift' ); ?></option>
				</select>
			</p>
			
			<p>
				<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" <?php checked( $instance['link'], true ) ?> />
				<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link image', 'frameshift' ); ?></label><br />
				<span class="description"><?php _e( 'Click on image opens lightbox', 'frameshift' ); ?></span>
			</p>
		
		</div>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text', 'frameshift' ); ?>:</label>
			<textarea class="widefat" rows="10" cols="10" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea ( $text ); ?></textarea><br />
		</p>
		
		<p>
		    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'center' ); ?>" name="<?php echo $this->get_field_name( 'center' ); ?>" <?php checked( $instance['center'], true ) ?> />
		    <label for="<?php echo $this->get_field_id( 'center' ); ?>"><?php _e( 'Center widget content', 'frameshift' ); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'link_space' ); ?>"><?php _e( 'Link', 'frameshift' ); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link_space' ); ?>" name="<?php echo $this->get_field_name( 'link_space' ); ?>" type="text" value="<?php echo esc_attr( $link_space ); ?>" /><br />
			<span class="description"><?php _e( 'Adds links to title and image (enter a URL or post/page ID)', 'frameshift' ); ?></span>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', 'frameshift' ); ?>:</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>">
				<?php
					foreach( frameshift_widget_widths() as $k => $v ) {
						echo '<option value="' . $k . '"' . selected( $k, $width, false ) . '>' . $v . '</option>';
					}
				?>
			</select>
		</p>
		
		<p>
		    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'position' ); ?>" name="<?php echo $this->get_field_name( 'position' ); ?>" <?php checked( $instance['position'], true ) ?> />
		    <label for="<?php echo $this->get_field_id( 'position' ); ?>"><?php _e( 'First widget', 'frameshift' ); ?></label><br />
		    <span class="description"><?php _e( 'Position of the widget in the row.', 'frameshift' ); ?></span>
		</p>
		
	<?php }
	
	/**
	 * Retrieve resized image URL
	 */
	 
	function get_image_url( $id, $width = false, $height = false ) {
		
		$attachment = wp_get_attachment_metadata( $id );
		$attachment_url = wp_get_attachment_url( $id );
		
		if ( isset( $attachment_url ) ) {
		
			if ( $width && $height ) {
				$uploads = wp_upload_dir();
				$imgpath = $uploads['basedir'] . '/' . $attachment['file'];
				error_log( $imgpath );
				$image = image_resize( $imgpath, $width, $height );
				if ( $image && !is_wp_error( $image ) ) {
					error_log( is_wp_error( $image ) );
					$image = path_join( dirname( $attachment_url ), basename( $image ) );
				} else {
					$image = $attachment_url;
				}
			} else {
				$image = $attachment_url;
			}
			if ( isset( $image ) ) {
				return $image;
			}
		}
	}

	/**
	 * Test context to see if the uploader is being used for the image widget or for other regular uploads
	 */
	 
	function is_frameshift_spaces_widget_context() {
		if ( isset( $_SERVER['HTTP_REFERER'] ) && strpos( $_SERVER['HTTP_REFERER'], $this->id_base ) !== false ) {
			return true;
		} elseif( isset( $_REQUEST['_wp_http_referer'] ) && strpos( $_REQUEST['_wp_http_referer'], $this->id_base ) !== false ) {
			return true;
		} elseif( isset( $_REQUEST['widget_id'] ) && strpos( $_REQUEST['widget_id'], $this->id_base ) !== false ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Replace "Insert into Post" with "Insert into Widget"
	 */
	 
	function replace_text_in_thickbox( $translated_text, $source_text, $domain ) {
		if ( $this->is_frameshift_spaces_widget_context() ) {
			if ( 'Insert into Post' == $source_text ) {
				return __('Insert into Widget', 'frameshift' );
			}
		}
		return $translated_text;
	}
	
	/**
	 * Filter image_end_to_editor results
	 */
	 
	function image_send_to_editor( $html, $id, $caption, $title, $align, $url, $size, $alt = '' ) {
	
		if ( $this->is_frameshift_spaces_widget_context() ) {
			if ( $alt=='' )
				$alt = $title;
			?>
			<script type="text/javascript">
				// send image variables back to opener
				var win = window.dialogArguments || opener || parent || top;
				win.IW_html = '<?php echo addslashes( $html ) ?>';
				win.IW_img_id = '<?php echo $id ?>';
				win.IW_alt = '<?php echo addslashes( $alt ) ?>';
				win.IW_caption = '<?php echo addslashes( $caption ) ?>';
				win.IW_title = '<?php echo addslashes( $title ) ?>';
				win.IW_align = '<?php echo $align ?>';
				win.IW_url = '<?php echo $url ?>';
				win.IW_size = '<?php echo $size ?>';
			</script>
			<?php
		}
		
		return $html;
	}

	/**
	 * Remove from url tab
	 */
	 
	function media_upload_tabs( $tabs ) {
		if ( $this->is_frameshift_spaces_widget_context() ) {
			unset( $tabs['type_url'] );
		}
		return $tabs;
	}
	
	function fix_async_upload_image() {
		if( isset( $_REQUEST['attachment_id'] ) ) {
			$GLOBALS['post'] = get_post( $_REQUEST['attachment_id'] );
		}
	}
	
	/**
	 * Admin header css
	 */
	function frameshift_spaces_admin_head() {
		?>
		<style type="text/css">
			.thickbox-image-widget {
				padding-left: 20px;
				background: url(<?php echo home_url(); ?>/wp-admin/images/media-button-image.gif) no-repeat left center;
				text-decoration: none;
			}
			.frameshift-spaces-image {
				padding: 5px;
				background: url(<?php echo FRAMESHIFT_ADMIN_IMAGES_URL; ?>/bg-logo-img.png);
				overflow: hidden;
			}
			.frameshift-spaces-image img {
				max-width: 100%;
				height: auto;
			}
			.no-image {
				display: none;
			}
		</style>
		<?php
	}
}