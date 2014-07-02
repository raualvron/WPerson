<?php

/**
 * Create lastest posts widget.
 *
 * @package FrameShift
 * @subpackage Widgets
 * @since 1.0
 */
 
/**
 * Register widget
 */
 
add_action( 'widgets_init', 'frameshift_register_widget_latest' );
 
function frameshift_register_widget_latest() {

	register_widget( 'FrameShift_Latest' );

}

class FrameShift_Latest extends WP_Widget {
 
	function __construct() {
		$widget_ops = array( 'description' => __( 'Latest posts teaser', 'frameshift' ) );
		parent::__construct( 'FrameShift_Latest', FRAMESHIFT_NAME . ' ' . __( 'Latest Posts', 'frameshift' ), $widget_ops );
    }
 
    function widget( $args, $instance ) {
    
    	extract( $args, EXTR_SKIP );
        
        $title 			= $instance['title'];
        $cat 			= $instance['cat'];
        if ( !$number = (int) $instance['number'] )
			$number = 1;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 20 )
			$number = 20;
		if ( !$length = (int) $instance['length'] )
			$length = 25;
		else if ( $length < 1 )
			$length = 25;        
        $width 		= ( isset( $instance['width'] ) ) ? $instance['width'] : 'full';
        
        // Correct width
        if( $width == frameshift_get_span( 'big' ) && ( $id == 'home-top' || $id == 'home-bottom' || $id == 'ffooter' ) )
        	$width = frameshift_get_span( 'full' );
        if( ( $width == frameshift_get_span( 'full' ) || $width == frameshift_get_span( 'half' ) ) && $id == 'home' )
        	$width = frameshift_get_span( 'big' );
        
        // Check widget width
        
        if( $width != 'full' ) {
       		$widget_width = $width;
        } else {
        	$widget_width = '';
       		if( $id == 'home-top' || $id == 'home-bottom' || $id == 'ffooter' ) {
       			$clear = ' clearfix';
       		}        	
        }
		    
		if( ( $id == 'sidebar' || $id == 'sidebar-home' ) || ( $id == 'home' && ( $width == 'span12' || $width == 'full' ) ) ) {
		    $widget_width = '';
		    $clear = ' clearfix';
		}
		
		// Create query args
        
        $query_args = array(
        	'post_type'		 	  => array( 'post' ),
        	'cat' 				  => $cat,
        	'posts_per_page' 	  => $number,
        	'post_status' 		  => 'publish',
        	'ignore_sticky_posts' => 1
        );
        
        $query_args = apply_filters( 'frameshift_widget_latest_query_args', $query_args );
        
        $latest = new WP_Query( $query_args );
        
        if ( $latest->have_posts() ) {
        
        	// Create loop counter
        	$counter = 1;
		
			?>
		
			<div id="<?php echo frameshift_dashes( $widget_id ); ?>-wrap" class="widget-wrap widget-latest-wrap">
			
				<div id="<?php echo frameshift_dashes( $widget_id ); ?>" class="widget widget-latest row">
				
					<?php
						// Display widget title
						if( ! empty( $title ) ) {
					?>
						
					<div class="title title-widget clearfix">
		
					    <?php
					    	echo apply_filters( 'frameshift_widget_latest_title', '<h2>' . $title . '</h2>' );
					    	do_action( 'frameshift_widget_latest_title_actions' );
					    ?>
					
					</div>
		
					<?php
						} // endif $title
									    
						// Begin to loop through posts
						    
						while( $latest->have_posts() ) {
						
							// Set up post data
							$latest->the_post();
						
							$clear = '';
						
							// Add .clear to post class with if madness
							
							if( FRAMESHIFT_LAYOUT == 'four' ) {
							
								if( $id == 'home-top' || $id == 'home-bottom' || $id == 'ffooter' ) {
									if( $width == 'span3' ) {
										if( $counter == 1 || ($counter-1)%4 == 0 ) {								
											$clear = ' clear';									
										} else {								
											$clear = '';							
										}
									} elseif( $width == 'span6' ) {
										if( $counter == 1 || $counter%2 != 0 ) {								
											$clear = ' clear';									
										} else {								
											$clear = '';							
										}								
									}
								} elseif( $id == 'home' ) {
									if( $width == 'span3' ) {
										if( $counter == 1 || ($counter-1)%3 == 0 ) {								
											$clear = ' clear';									
										} else {								
											$clear = '';							
										}
									}						
								} elseif( $id == 'sidebar' || $id == 'sidebar-home' ) {
									$widget_width = 'span3';
									$clear = ' clear';					
								}
							
							} else {
							
								if( $id == 'home-top' || $id == 'home-bottom' || $id == 'ffooter' ) {
									if( $width == 'span4' ) {
										if( $counter == 1 || ($counter-1)%3 == 0 ) {								
											$clear = ' clear';									
										} else {								
											$clear = '';							
										}
									} elseif( $width == 'span6' ) {
										if( $counter == 1 || $counter%2 != 0 ) {								
											$clear = ' clear';									
										} else {								
											$clear = '';							
										}								
									}
								} elseif( $id == 'home' ) {
									if( $width == 'span4' ) {
										if( $counter == 1 || $counter%2 != 0 ) {								
											$clear = ' clear';									
										} else {								
											$clear = '';							
										}
									}						
								} elseif( $id == 'sidebar' || $id == 'sidebar-home' ) {
									$widget_width = 'span4';
									$clear = ' clear';					
								}
							
							}
						
							?>
						    
						    <div <?php post_class( $widget_width . $clear . ' clearfix' ); ?>>
						    
						    	<div class="widget-inner">
						        
						    		<?php
						    			// Action hook before post title (widget)
						    		    do_action( 'frameshift_widget_post_title_before', $width, $id );
						    		?>
						    		
						    		<h3 class="post-title">
    									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
    										<?php
    											// Action hook post title inside
    								    		do_action( 'frameshift_widget_post_title_inside' );
    											the_title();
    										?>
    									</a>
    								</h3>
						    		    
						    		<?php
						    			// Action hook after post title (widget)   
						    		    do_action( 'frameshift_widget_post_title_after', $width, $id );
						    		    
						    		    // Action hook before post content (widget)
						    		    do_action( 'frameshift_widget_post_content_before', $width, $id );
						    		?>
						        		
						    		<div class="post-teaser">
						    			<?php frameshift_the_excerpt( get_the_ID(), false, $length ); ?>
						        	</div>
						        	
						        	<?php
						        		// Action hook after post content (widget)
						        		do_action( 'frameshift_widget_post_content_after', $width, $id );
						        	?>
						        	
						        </div><!-- .widget-inner -->
						        			
						    </div><!-- .post.<?php the_ID(); ?> --><?php
						    
							// Increase loop counter
							$counter++;
						    
						} // endwhile have_posts()						
					?>
				
				</div><!-- .widget -->
			
			</div><!-- .widget-wrap --><?php
		
		} // endif have_posts()
    }

    function update($new_instance, $old_instance) {  
    
    	$instance['title'] 			= strip_tags($new_instance['title']);
    	$instance['cat'] 			= $new_instance['cat'];
    	$instance['number'] 		= (int) $new_instance['number'];
    	$instance['length'] 		= (int) $new_instance['length'];
        $instance['width'] 	    	= $new_instance['width'];
        
        // Remove transient when settings are edited
        delete_transient( $this->id . '_query' );
                  
        return $new_instance;
    }
 
    function form($instance) {
        
        global $options;
        
		$instance		= wp_parse_args( (array) $instance, array( 'title' => '', 'cat' => '', 'number' => '3', 'length' => 25 ) );
		$title 			= $instance['title'];
		$cat	    	= $instance['cat'];
		if ( ! isset($instance['number'] ) || ! $number = (int) $instance['number'] )
			$number 	= 3;
		if ( !isset($instance['length']) || !$length = (int) $instance['length'] )
			$length 	= 25;
        $width 			= ( isset( $instance['width'] ) ) ? $instance['width'] : 'full';
        
        ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'frameshift' ); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _e( 'Category', 'frameshift' ); ?>:</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>">
				<option value=""><?php _e( 'Most recent posts', 'frameshift' ); ?></option>
				
				<?php
					$categories = get_categories( 'orderby=name&hide_empty=0' );
					foreach( $categories as $category ) {
					echo '<option value="' . $category->cat_ID . '" ' . selected( $category->cat_ID, $cat, false ) . '>' . $category->cat_name . '</option>';
				} ?>
				
			</select>
			
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'frameshift' ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br /><small><?php _e( '(at most 20)', 'frameshift' ); ?></small>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'length' ); ?>"><?php _e( 'Number of words in excerpt', 'frameshift' ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'length' ); ?>" name="<?php echo $this->get_field_name( 'length' ); ?>" type="text" value="<?php echo $length; ?>" size="3" />
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
		
<?php
	}

}