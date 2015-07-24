<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

get_header(); ?>
	<?php
		// Start the Loop.
		while ( have_posts() ) : the_post();
			get_template_part( 'content', 'page' );
			do_action('anaglyph_comments_template');
		endwhile;
	?>
<?php
   get_footer();
