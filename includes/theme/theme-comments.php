<?php 
	if (!function_exists("anaglyph_custom_comments")) {
		function anaglyph_custom_comments( $comment, $args, $depth ) {
			$GLOBALS['comment'] = $comment; 
			?>
			<li id="comment-<?php echo $comment->comment_ID; ?>" <?php comment_class(); ?>>
				<?php 
					$avatar_img = '';
					if (!anaglyph_validate_gravatar($comment->comment_author_email)) {
						 $avatar_img = '<img src="'. get_template_directory_uri() .'/includes/theme/assets/icons/author-comment.png" alt="" />';
					} else {
						 $avatar_img = anaglyph_commenter_avatar($args);
					}
				?>
				<div class="author-image"><?php echo $avatar_img; ?></div>
				
				<div class="comment-content">
					<div class="author"><?php comment_author_link(); ?></div>
                    <div class="meta has-opacity"><?php echo get_comment_date(get_option( 'date_format' )) ?> <?php _e('at', 'anaglyph-lite'); ?> <?php echo get_comment_time(get_option( 'time_format' )); ?></div>
					<?php comment_text() ?>
					<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('Reply', 'anaglyph-lite')))); ?>
					<div class="clear"></div>
					<?php edit_comment_link(__('Edit this comment', 'anaglyph-lite'), '', ''); ?>
					
					<?php if ($comment->comment_approved == '0') { ?>
						<p class='unapproved'><?php _e('Your comment is awaiting moderation.', 'anaglyph-lite'); ?></p>
					<?php } ?>
                </div><!-- /.comment-content -->
			<?php
		}
	}

	if ( ! function_exists( 'anaglyph_commenter_avatar' ) ) {
		function anaglyph_commenter_avatar( $args ) {
			global $comment;
			$avatar = get_avatar( $comment,  68 );
			return $avatar;
		}
	}
	
	if ( ! function_exists( 'anaglyph_validate_gravatar' ) ) {
		function anaglyph_validate_gravatar($email) {
			$hash = md5(strtolower(trim($email)));
			$uri = 'http://www.gravatar.com/avatar/' . $hash . '?d=404';
			$headers = @get_headers(esc_url($uri));
			if (!preg_match("|200|", $headers[0])) {
				$has_valid_avatar = FALSE;
			} else {
				$has_valid_avatar = TRUE;
			}
			return $has_valid_avatar;
		}
	}