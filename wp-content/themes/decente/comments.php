<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @subpackage FrameShift
 * @since 1.0
 */
 
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die ( 'Please do not load this page directly. Thanks!' );

if ( post_password_required() ) {
	printf( '<p class="alert">%s</p>', __( 'This post is password protected. Enter the password to view comments.', 'frameshift' ) );
	return;
}

global $post, $wp_query;

/**
 * Create basic comment layout. The actual display of comments is
 * handled by the callback frameshift_list_comments() which is
 * located in /lib/framework/comments.php
 *
 * @since 1.0
 */

// Action hook before comments
do_action( 'frameshift_comments_before' );

if ( have_comments() && ! empty( $wp_query->comments_by_type['comment'] ) ) {
	
?>

<div id="comments">
    <?php
    	echo apply_filters( 'frameshift_comments_title', '<h3>' . __( 'Comments', 'frameshift' ) . '</h3>' );
    	
    	// Action hook before comments list
		do_action( 'frameshift_comments_list_before' );	
    ?>
    <ol class="comment-list">
    	<?php do_action( 'frameshift_list_comments' ); ?>
    </ol>
    
    <?php
    	// Action hook after comments list
		do_action( 'frameshift_comments_list_after' );
	?>
</div><!-- #comments -->

<?php

} else {

    // No comments so far
 
    if ( 'open' == $post->comment_status ) {

    	// Comments are open but there are no comments
    	echo apply_filters( 'frameshift_no_comments_text', '<div id="comments" class="comments-nocomments">' . __( 'No comments so far.', 'frameshift' ) . '</div><!-- #comments -->' );
    
    } else {
    	
    	// Comments are closed
    	echo apply_filters( 'frameshift_comments_closed_text', '<div id="comments" class="comments-closed">' . __( 'Comments are closed.', 'frameshift' ) . '</div><!-- #comments -->' );
    	
    } // endif comments open
    
} // endif have comments

// Action hook after comments list
do_action( 'frameshift_comments_after' );

/**
 * Create basic ping/trackback layout. The actual display of pings is
 * handled by the callback frameshift_list_pings() which is
 * located in /lib/framework/comments.php
 *
 * @since 1.0
 */

// Action hook before pings list
do_action( 'frameshift_pings_before' );

if ( have_comments() && ! empty( $wp_query->comments_by_type['pings'] ) ) {

	if( isset( $wp_query->query['cpage'] ) && $wp_query->query['cpage'] != 1 )
		return;

?>

<div id="pings">
    <?php
    	echo apply_filters( 'frameshift_pings_title', '<h3>' . __( 'Trackbacks', 'frameshift' ) . '</h3>' );
    	
    	// Action hook after pings list
		do_action( 'frameshift_pings_list_before' );	
    ?>    
    <ol class="ping-list">
    	<?php do_action( 'frameshift_list_pings' ); ?>
    </ol>    
    <?php
    	// Action hook after pings list
		do_action( 'frameshift_pings_list_after' );
	?>	
</div><!-- #pings -->

<?php 

} else {

    /** No pings so far */
    
    echo apply_filters( 'frameshift_no_pings_text', false );
    
} // endif have pings

// Action hook after pings list
do_action( 'frameshift_pings_after' );

/**
 * Create basic comment form layout
 *
 * @since 1.0
 */

// Action hook before comment form
do_action( 'frameshift_comment_form_before' );

global $user_identity, $id;

$commenter = wp_get_current_commenter();

// Check if name and email are required
$req = get_option( 'require_name_email' );

// Add support for Accessible Rich Internet Applications
$aria_req = ( $req ? ' aria-required="true"' : '' );

$args = array(

    'fields' => array(
    
    	'author' =>	
    	
    		'<p class="comment-form-section comment-form-author">' .
    		'<label for="author">' . __( 'Name', 'frameshift' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
    		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1"' . $aria_req . ' />' .
    		'</p><!-- .comment-form-author -->',

    	'email' =>	
    	
    		'<p class="comment-form-section comment-form-email">' .
    		'<label for="email">' . __( 'Email', 'frameshift' ) . ( $req ? '<span class="required">*</span>' : '' ) . '</label> ' .
    		'<input id="email" name="email" type="text" value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
    		'</p><!-- .comment-form-email -->',

    	'url' =>		
    	
    		'<p class="comment-form-section comment-form-url">' .
    		'<label for="url">' . __( 'Website', 'frameshift' ) . '</label>' .
    		'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
    		'</p><!-- .comment-form-url -->'
    ),

    'comment_field' =>	
    
        '<p class="comment-form-section comment-form-comment">' .
        '<label for="comment">' . __( 'Comment', 'frameshift' ) . '<span class="required">*</span></label> ' .
        '<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>' .
        '</p><!-- .comment-form-comment -->',
        
    'must_log_in' =>
    
    	'<div class="alert must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'frameshift' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</div>',
    	
    'logged_in_as' =>
    
    	'<p class="alert logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'frameshift' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',

    'title_reply' => __( 'Leave a Comment', 'frameshift' ),
    'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.', 'frameshift' ) . '</p>',
    'comment_notes_after' => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'frameshift' ), ' <pre class="allowed-tags">' . allowed_tags() . '</pre>' ) . '</p>',
    'cancel_reply_link' => __( 'Cancel reply', 'frameshift' ),
    'id_submit' => 'submit',
    'label_submit' => __( 'Post Comment', 'frameshift' )
    
);

$args = apply_filters( 'frameshift_comment_form_args', $args, $user_identity, $id, $commenter, $req, $aria_req );

// Finally output comment form
comment_form( $args, $id );

// Action hook after comment form
do_action( 'frameshift_comment_form_after' );