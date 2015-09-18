<?php
/**
 * The template for displaying Author archive pages
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

get_header(); 
?>
	<!-- Page Title -->
	<section id="page-title">
		<div class="title">
			<h1 class="reset-margin"><?php printf( __( 'All posts by %s', 'anaglyph-lite' ), get_the_author()); ?></h1>
		</div>
		<?php anaglyph_custom_image('author-image'); ?>
	</section>
	<!-- end Page Title -->
			
	<?php anaglyph_add_breadcrumbs(); ?>
	<?php anaglyph_default_page_content(); ?>

<?php
  get_footer();
