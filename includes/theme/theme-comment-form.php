<?php
function anaglyph_comment_form( $args = array(), $post_id = null ) {
		global $user_identity, $id;
     
        if ( null === $post_id )
			 $post_id = $id;
        else
        $id = $post_id;
     
		$commenter = wp_get_current_commenter();
 
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		$fields =  array(
				'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'anaglyph-lite' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
							'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
				'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'anaglyph-lite' ) . '</label> ' . ( $req ? '<span class="required">*</span>' : '' ) .
							'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
				'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'anaglyph-lite' ) . '</label>' .
							'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
		);
 
		$required_text = sprintf( ' ' . __('Required fields are marked %s', 'anaglyph-lite'), '<span class="required">*</span>' );
		$defaults = array(
				'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
				'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . __( 'Comment', 'anaglyph-lite' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
				'must_log_in'          => '<p class="must-log-in">' .  sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.', 'anaglyph-lite' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
				'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'anaglyph-lite' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
				'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.', 'anaglyph-lite' ) . ( $req ? $required_text : '' ) . '</p>',
				'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'anaglyph-lite' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
				'id_form'              => 'commentform',
				'id_submit'            => 'submit',
				'class_submit'         => 'submit',
				'title_reply'          => __( 'Leave a Reply', 'anaglyph-lite' ),
				'title_reply_to'       => __( 'Leave a Reply to %s', 'anaglyph-lite' ),
				'cancel_reply_link'    => __( 'Cancel reply', 'anaglyph-lite' ),
				'label_submit'         => __( 'Post Comment', 'anaglyph-lite' ),
		);
 
		$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );
 
		?>
				<?php if ( comments_open() ) : ?>
						<?php do_action( 'comment_form_before' ); ?>
						<div id="respond">
								<h3 id="reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?> <small><?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?></small></h3>
								<?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
										<?php echo $args['must_log_in']; ?>
										<?php do_action( 'comment_form_must_log_in_after' ); ?>
								<?php else : ?>
										<form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
												<?php do_action( 'comment_form_top' ); ?>
												<?php if ( is_user_logged_in() ) : ?>
														<?php echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
														<?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
												<?php else : ?>
														<?php echo $args['comment_notes_before']; ?>
														<?php
														do_action( 'comment_form_before_fields' );
														foreach ( (array) $args['fields'] as $name => $field ) {
																echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
														}
														do_action( 'comment_form_after_fields' );
														?>
												<?php endif; ?>
												<?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
												<?php echo $args['comment_notes_after']; ?>
												<div class="form-submit form-actions pull-right">
														<input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" class="<?php echo esc_attr( $args['class_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
														<?php comment_id_fields(); ?>
												</div>
												<?php do_action( 'comment_form', $post_id ); ?>
										</form>
								<?php endif; ?>
						</div><!-- #respond -->
						<?php do_action( 'comment_form_after' ); ?>
				<?php else : ?>
						<?php do_action( 'comment_form_comments_closed' ); ?>
				<?php endif; ?>
		<?php
}