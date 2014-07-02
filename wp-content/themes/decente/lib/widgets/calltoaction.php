<?php

/**
 * Create call to action widget.
 *
 * @package FrameShift
 * @subpackage Widgets
 * @since 1.0
 */
 
/**
 * Register widget
 */
 
add_action( 'widgets_init', 'frameshift_register_widget_calltoaction' );
 
function frameshift_register_widget_calltoaction() {
		register_widget( 'FrameShift_Call_to_Action' );
}

class FrameShift_Call_to_Action extends WP_Widget {
 
	function __construct() {
		$widget_ops = array( 'description' => __( 'Visually highlighted call to action', 'frameshift' ) );
		parent::__construct( 'FrameShift_Call_to_Action', FRAMESHIFT_NAME . ' ' . __( 'Call to Action', 'frameshift' ), $widget_ops );
    }
 
    function widget( $args, $instance ) {
    
    	extract( $args, EXTR_SKIP );
    	
    	$title  = isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : false;
        $text   = isset( $instance['text'] ) ? wp_kses_post( $instance['text'] ) : false;
        $button = isset( $instance['button'] ) ? strip_tags( $instance['button'] ) : false;
        $label  = isset( $instance['label'] ) ? strip_tags( $instance['label'] ) : __( 'Button Label', 'frameshift' );
        
        // Set button link
        
        if( substr( $button, 0, 4 ) == 'http' ) {
        	$url = $button;
        } elseif( is_numeric( $button ) ) {
			$url = get_permalink( $button );
		};
        
        ?>
        
		<div id="<?php echo frameshift_dashes( $widget_id ); ?>-wrap" class="widget-wrap widget-call-to-action-wrap">
		
		    <div id="<?php echo frameshift_dashes( $widget_id ); ?>" class="widget widget-call-to-action clearfix">
		    
		    	<div class="widget-inner"><?php
		    	
		    		// Create call to action text
		    	
		    		$output = '';
		    	
		    		if( ! empty( $title ) )
		    			$output .= '<h2>' . $title . '</h2>';
		    			
		    		if( ! empty( $text ) )
		    			$output .= '<span>' . $text . '</span>';
		    		
		    		if( ! empty( $output ) )
		    			echo '<div class="call-to-action-text">' . $output . '</div>';
		    			
		    		// Create call to action button
		    			
		    		if( ! empty( $url ) )
		    			echo '<span class="call-to-action-button"><a class="btn btn-info btn-large" href="' . $url . '">' . $label . '</a></span>'; ?>
		
		    	</div><!-- .widget-inner -->
		    	
		    </div><!-- .widget -->
		    
		</div><!-- .widget-wrap --><?php
        
    }

    function update( $new_instance, $old_instance ) {
    
    	$instance = $old_instance;
    
    	$instance['title'] 	= strip_tags( $new_instance['title'] );
    	$instance['text']   = wp_kses_post( $new_instance['text'] );
    	$instance['button'] = strip_tags( $new_instance['button'] );
    	$instance['label']  = strip_tags( $new_instance['label'] );
                  
        return $instance;
    }
 
    function form( $instance ) {
        
        $title  = isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : false;
		$text   = isset( $instance['text'] ) ? wp_kses_post( $instance['text'] ) : false;
		$button = isset( $instance['button'] ) ? strip_tags( $instance['button'] ) : false;
		$label  = isset( $instance['label'] ) ? strip_tags( $instance['label'] ) : __( 'Button Label', 'frameshift' ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'frameshift' ); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text', 'frameshift' ); ?>:</label>
			<textarea class="widefat" rows="10" cols="10" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo esc_textarea( $text ); ?></textarea><br />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'button' ); ?>"><?php _e( 'Button', 'frameshift' ); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id( 'button' ); ?>" name="<?php echo $this->get_field_name( 'button' ); ?>" type="text" value="<?php echo esc_attr( $button ); ?>" />
			</label><br />
			<span class="description"><?php _e( 'Enter URL or post/page ID', 'frameshift' ); ?></span>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'label' ); ?>"><?php _e( 'Label', 'frameshift' ); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id( 'label' ); ?>" name="<?php echo $this->get_field_name( 'label' ); ?>" type="text" value="<?php echo esc_attr( $label ); ?>" />
			</label>
		</p><?php
		
	}

}