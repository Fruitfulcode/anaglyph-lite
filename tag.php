<?php
/**
 * The template for displaying Tag pages
 *
 * Used to display archive-type pages for posts in a tag.
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

get_header(); ?>
	
	<section id="page-title">
		<div class="title">
			<h1 class="reset-margin"><?php printf( __( 'Tag Archives: %s', 'anaglyph-lite' ), single_tag_title( '', false ) ); ?></h1>
		</div>	
		<?php anaglyph_custom_image('tag-image'); ?>
	</section><!-- .archive-header -->

	<?php anaglyph_add_breadcrumbs(); ?>
	<?php anaglyph_default_page_content(); ?>

<?php
	get_footer();
