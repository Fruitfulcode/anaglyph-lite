<?php
/**
 * Anaglyph Theme functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

/**
 * Set up the content width value based on the theme's design.
 *
 * @see anaglyph_content_width()
 *
 * @since Anaglyph Theme 1.0
 */
 
locate_template('/includes/admin/anaglyph-options/admin-config.php', true);

locate_template('/includes/theme/extensions/template-tags.php', true);
locate_template('/includes/theme/theme-comment-form.php', true);
locate_template('/includes/theme/theme-comments.php', true);
locate_template('/includes/theme/theme-widgets.php', true);
locate_template('/includes/admin/libs/execute-libs.php', true);
locate_template('/includes/theme/theme-function.php', true);

if (anaglyph_is_woocommerce_activated()) {
	locate_template('/includes/theme/theme-woocommerce.php', true);
}	

locate_template('/includes/theme/theme-inlinestyles.php', true);