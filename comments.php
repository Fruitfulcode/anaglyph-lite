<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area comments">
	<?php if ( have_comments() ) : ?>
	<h2 class="comments-title">
		<?php printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'anaglyph-lite' ), number_format_i18n(get_comments_number())); ?>
	</h2>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation" aria-label="<?php _e( 'Comment Above Navigation', 'anaglyph-lite' ); ?>">
		<h3 class="screen-reader-text"><?php _e( 'Comment navigation', 'anaglyph-lite' ); ?></h3>
		<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'anaglyph-lite' ) ); ?></div>
		<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'anaglyph-lite' ) ); ?></div>
	</nav><!-- #comment-nav-above -->
	<?php endif; // Check for comment navigation. ?>
	
	<ol class="comment-list">
		<?php
			wp_list_comments( array(
				'callback' => 'anaglyph_custom_comments', 
				'type' 	   => 'comment', 
				'style'       => 'ol',
				'avatar_size' => 68
			) );
		?>
	</ol><!-- .comment-list -->

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation" aria-label="<?php _e( 'Comment Below Navigation', 'anaglyph-lite' ); ?>">
			<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'anaglyph-lite' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'anaglyph-lite' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

	<?php if ( ! comments_open() ) : ?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'anaglyph-lite' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>
	<hr />

	<?php 
		$commenter = wp_get_current_commenter();
		$req 	   = get_option( 'require_name_email' );
		$aria_req  = ( $req ? " aria-required='true'" : '' );
	  
		$args = array(
					'id_form' => 'reply-form',
					'label_submit' => __( 'Reply', 'anaglyph-lite' ),
					'class_submit' => 'btn btn-color-primary',
					'fields'  => apply_filters( 'comment_form_default_fields', 
						 array(
							'author'  => '<div class="row"><div class="col-md-6"><div class="control-group"><div class="controls"><label for="author" class="screen-reader-text">'.__('Name', 'anaglyph-lite').'</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' placeholder="'.__('Name', 'anaglyph-lite').'" /></div></div></div>',
							'email'   => '<div class="col-md-6"><div class="control-group"><div class="controls"><label for="email" class="screen-reader-text">'.__('Email', 'anaglyph-lite').'</label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' placeholder="'.__('Email', 'anaglyph-lite').'" /></div></div></div></div>',
						)	
					),
					'comment_notes_before' => '',
					'comment_notes_after'  => '',
					'comment_field' =>  '<div class="row"><div class="col-md-12"><div class="control-group"><div class="controls"><label for="comment" class="screen-reader-text">'.__('Message', 'anaglyph-lite').'</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . __('Message', 'anaglyph-lite') . '"></textarea></div></div></div></div>',					
				);	
		anaglyph_comment_form($args); 
	?>

</section><!-- #comments -->
