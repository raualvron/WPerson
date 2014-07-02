<?php

/**
 * Create lastest work widget.
 *
 * @package FrameShift
 * @subpackage Widgets
 * @since 1.0
 */
 
/**
 * Register widget
 */
 
add_action( 'widgets_init', 'frameshift_register_widget_latest_work' );
 
function frameshift_register_widget_latest_work() {

	register_widget( 'FrameShift_Latest_Work' );

}

class FrameShift_Latest_Work extends WP_Widget {
 
	function __construct() {
		$widget_ops = array( 'description' => __( 'Latest portfolio teaser', 'frameshift' ) );
		parent::__construct( 'FrameShift_Latest_Work', FRAMESHIFT_NAME . ' ' . __( 'Latest Work', 'frameshift' ), $widget_ops );
    }
 
    function widget( $args, $instance ) {
    
    	extract( $args, EXTR_SKIP );
        
        $title 	    		 = isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : false;
        $filter	    		 = isset( $instance['filter'] ) ? strip_tags( $instance['filter'] ) : false;
        $portfolio_link		 = isset( $instance['portfolio_link'] ) ? $instance['portfolio_link'] : true;
        $project_title 		 = isset( $instance['project_title'] ) ? $instance['project_title'] : true;
        $project_description = isset( $instance['project_description'] ) ? $instance['project_description'] : true;
        if ( !$number = (int) $instance['number'] )
			$number = 1;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 20 )
			$number = 20;       
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

		if( $filter != 'custom' ) {
        	
        	$query_args = array(
        		'post_type' => array( 'portfolio' ),
        		'posts_per_page' => $number,
        		'ignore_sticky_posts' => 1
        	);
        	
        	// Add taxonomy filter to query args if required
        	
        	if( ! empty( $filter ) ) {
        
        		// Get taxonomy and term from filter (comma-separated value)
        		
        		$get_taxonomy = explode( ',', $filter );
				$taxonomy 	  = $get_taxonomy[0];
				$term 		  = $get_taxonomy[1];
				
				// Set tax_query
			
        		$tax_query = array(
					array(
						'taxonomy' => $taxonomy,
						'field' => 'slug',
						'terms' => array( $term )
					)
				);
        		$query_args = array_merge( $query_args, array( 'tax_query' => $tax_query ) );
        	}
        
        } else {
        
        	// If filter is custom, create meta query (featured => 1)
        
        	$query_args = array(
        		'post_type' 	 => array( 'portfolio' ),
        		'posts_per_page' => $number,
        		'ignore_sticky_posts' => 1,
        		'meta_query'     => array(
					array(
						'key' 	=> 'featured',
						'value' => '1'						
					)
				)
        	);
                	
        }
        
        $query_args = apply_filters( 'frameshift_widget_latest_work_query_args', $query_args );
        
        $latest = new WP_Query( $query_args );
        
        if ( $latest->have_posts() ) {
        
        	// Create loop counter
        	$counter = 1;
		
			?>
		
			<div id="<?php echo frameshift_dashes( $widget_id ); ?>-wrap" class="widget-wrap widget-latest-work-wrap">
			
				<div id="<?php echo frameshift_dashes( $widget_id ); ?>" class="widget widget-latest-work row">
				
					<?php
						// Display widget title
						if( ! empty( $title ) ) {
					?>
						
					<div class="title title-widget clearfix">
		
					    <?php
					    	echo apply_filters( 'frameshift_widget_latest_work_title', '<h2>' . $title . '</h2>' );
					    	do_action( 'frameshift_widget_latest_work_title_inside', $instance );
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
						    			// Action hook before portfolio title (widget)
						    		    do_action( 'frameshift_widget_portfolio_title_before', $instance, $widget_width );
						    		?>
						    		
						    		<h3 class="post-title">
    									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark">
    										<?php
    											// Action hook portfolio title inside
    								    		do_action( 'frameshift_widget_portfolio_title_inside', $instance );
    								    		if( $project_title )
    												the_title();
    										?>
    									</a>
    								</h3>
						    		    
						    		<?php
						    			// Action hook after portfolio title (widget)   
						    		    do_action( 'frameshift_widget_portfolio_title_after', $instance );
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

    function update( $new_instance, $old_instance ) {  
    
    	$new_instance = (array) $new_instance;
    	
    	$instance = array(
    		'portfolio_link'	  => 0,
			'project_title'  	  => 0,
			'project_description' => 0
		);
		
		foreach ( $instance as $field => $val ) {
			if ( isset( $new_instance[$field] ) )
				$instance[$field] = 1;
		}
    
    	$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['filter'] = strip_tags( $new_instance['filter'] );
    	$instance['number'] = (int) $new_instance['number'];
        $instance['width']  = $new_instance['width'];
                  
        return $instance;
    }
 
    function form( $instance ) {
        
        $defaults = array(
			'portfolio_link'  	  => true,
			'project_title'  	  => true,
			'project_description' => true
		);
        
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title  = isset( $instance['title'] ) ? strip_tags( $instance['title'] ) : false;
		$filter = isset( $instance['filter'] ) ? $instance['filter'] : false;
        $number = isset( $instance['number'] ) ? (int) $instance['number'] : 3;
        $width  = isset( $instance['width'] ) ? $instance['width'] : 'full'; ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'frameshift' ); ?>:
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e( 'Filter', 'frameshift' ); ?>:</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>">
				<option value="" <?php selected( '', $filter ); ?>><?php _e( 'Latest portfolio items', 'frameshift' ); ?></option>					
				<?php
					$terms = get_terms( array( 'skills' ), array( 'orderby' => 'count', 'order' => 'DESC' ) );
					foreach( $terms as $term ) {
					echo '<option value="' . $term->taxonomy . ',' . $term->slug . '"'.selected( $term->taxonomy . ',' . $term->slug, $filter ) . '>' . $term->name . '</option>';
					}
				?>
				<option value="custom" <?php selected( 'custom', $filter ); ?>><?php _e( 'Custom field', 'frameshift' ); ?> => featured</option>
			</select>
			
		</p>
		
		<p>
		    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'portfolio_link' ); ?>" name="<?php echo $this->get_field_name( 'portfolio_link' ); ?>" <?php checked( $instance['portfolio_link'], true ) ?> />
		    <label for="<?php echo $this->get_field_id( 'portfolio_link' ); ?>"><?php _e( 'Portfolio Archive Link', 'frameshift' ); ?></label>		
		</p>
		
		<p>
		    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'project_title' ); ?>" name="<?php echo $this->get_field_name( 'project_title' ); ?>" <?php checked( $instance['project_title'], true ) ?> />
		    <label for="<?php echo $this->get_field_id( 'project_title' ); ?>"><?php _e( 'Project Title', 'frameshift' ); ?></label>		
		</p>
		
		<p>
		    <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id( 'project_description' ); ?>" name="<?php echo $this->get_field_name( 'project_description' ); ?>" <?php checked( $instance['project_description'], true ) ?> />
		    <label for="<?php echo $this->get_field_id( 'project_description' ); ?>"><?php _e( 'Project Description', 'frameshift' ); ?></label>		
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show', 'frameshift' ); ?>:</label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /><br /><small><?php _e( '(at most 20)', 'frameshift' ); ?></small>
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