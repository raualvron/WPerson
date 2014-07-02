<?php

/**
 * Create Comment and ping/trackback output
 *
 * @package FrameShift
 */

/**
 * Call comments template and add to
 * frameshift_post_content_after hook.
 *
 * @since 1.0
 */

add_action( 'frameshift_post_content_after', 'frameshift_get_comments_template', 100 ); 
 
function frameshift_get_comments_template() {

	$args = array(
		'comments_posts' 	   => true,
		'comments_pages' 	   => false,
		'comments_attachments' => false
	);
	
	$args = apply_filters( 'frameshift_get_comments_template_args', $args );
	
	// Extract $args
	extract( $args, EXTR_SKIP );

	if ( is_single() && ! is_attachment() && $comments_posts == true ) {
		comments_template( '', true );
	} elseif ( is_page() && $comments_pages == true  && ! is_page_template( 'page-tpl-blog.php' ) ) {
		comments_template( '', true );
	} elseif ( is_attachment() && $comments_attachments == true ) {
		comments_template( '', true );
	}

}

/**
 * Create comments list output and add to
 * frameshift_list_comments hook.
 *
 * @since 1.0
 */
 
add_action( 'frameshift_list_comments', 'frameshift_do_list_comments' );

function frameshift_do_list_comments() {

	$args = array(
		'type'		  => 'comment',
		'avatar_size' => 80,
		'callback'	  => 'frameshift_comment_callback'
	);

	$args = apply_filters( 'frameshift_do_list_comments_args', $args );

	wp_list_comments( $args );
}

/**
 * Create comment callback with
 * custom comment layout structure.
 *
 * @since 1.0
 */
 
function frameshift_comment_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class( 'clearfix' ); ?>>
	
		<div id="comment-<?php comment_ID() ?>" class="comment-inner clearfix">

			<?php do_action( 'frameshift_comment_before' ); ?>
			
			<div class="comment-author vcard">
				<?php
					echo get_avatar( $comment, $size = $args['avatar_size'] );
					printf( __( '<cite class="fn">%s</cite> <span class="says">%s:</span>', 'frameshift' ), get_comment_author_link(), apply_filters( 'comment_author_says_text', __( 'says', 'frameshift' ) ) );
				?>
	 		</div><!-- .comment-author -->
			
			<div class="comment-meta">
			
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'frameshift' ), get_comment_date(), get_comment_time() ); ?></a>
				
				<?php edit_comment_link( __( 'Edit', 'frameshift' ), '[', ']' ); ?>
				
			</div><!-- .comment-meta -->
			
			<div class="comment-content">
			
				<?php if ($comment->comment_approved == '0') { ?>
					<p class="alert"><?php echo apply_filters( 'frameshift_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'frameshift' ) ); ?></p>
				<?php } ?>
			
				<?php comment_text(); ?>
				
			</div><!-- .comment-content -->
			
			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __( 'Reply', 'frameshift' ), 'login_text' => __( 'Log in to reply', 'frameshift' ) ) ) ); ?>
			</div>
			
			<?php do_action( 'frameshift_comment_after' ); ?>
		
		</div><!-- .comment-inner -->

	<?php // no ending </li> tag because of comment threading
}

/**
 * Create pings list output and add to
 * frameshift_list_pings hook.
 *
 * @since 1.0
 */

add_action( 'frameshift_list_pings', 'frameshift_do_list_pings' );
 
function frameshift_do_list_pings() {

	$args = array(
		'type'	   => 'pings',
		'callback' => 'frameshift_ping_callback'
	);

	$args = apply_filters( 'frameshift_ping_list_args', $args );

	wp_list_comments( $args );
}

/**
 * Create ping callback with
 * custom ping layout structure.
 *
 * @since 1.0
 */
 
function frameshift_ping_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class( 'clearfix' ); ?>>
	
		<div id="comment-<?php comment_ID() ?>" class="comment-inner">

			<?php do_action( 'frameshift_ping_before' ); ?>
			
			<div class="comment-author vcard">
				<?php
					echo get_avatar( $comment, $size = $args['avatar_size'] );
					printf( __( '<cite class="fn">%s</cite>', 'frameshift' ), get_comment_author_link() );
				?>
	 		</div><!-- .comment-author -->
			
			<div class="comment-meta">
			
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'frameshift' ), get_comment_date(), get_comment_time() ); ?></a>
				
				<?php edit_comment_link( __( 'Edit', 'frameshift' ), '[', ']' ); ?>
				
			</div><!-- .comment-meta -->
			
			<div class="comment-content">
			
				<?php if ($comment->comment_approved == '0') { ?>
					<p class="alert"><?php echo apply_filters( 'frameshift_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'frameshift' ) ); ?></p>
				<?php } ?>
			
				<?php comment_text(); ?>
				
			</div><!-- .comment-content -->
			
			<?php do_action( 'frameshift_ping_after' ); ?>
		
		</div><!-- .comment-inner -->

	<?php // no ending </li> tag because of comment threading
}

/**
 * Add comment navigation
 *
 * @since 1.0
 */
 
add_action( 'frameshift_comments_list_before', 'frameshift_comment_navigation' );
 
function frameshift_comment_navigation() { ?>

	<div class="comment-navigation clearfix">
	    <div class="alignright">
	    	<?php previous_comments_link() ?> &nbsp;&nbsp; <?php next_comments_link() ?>
	    </div>
	</div><?php

}