<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

get_header(); ?>
		
	<!-- Page Title -->
	<?php $blog_id = get_option( 'page_for_posts' ); ?>
	<?php if (is_home()) { ?>
	<section id="page-title">
		<div class="title">
			<h1 class="reset-margin"><?php echo get_the_title($blog_id);?></h1>
		</div>
		<?php if ( has_post_thumbnail($blog_id)) { 
			  $title_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($blog_id), 'full');
			  echo '<img src="'.$title_thumbnail[0].'" class="parallax-bg" alt="">';
			  }	
		?>
	</section>
	<?php } ?>
	
	<!-- end Page Title -->
	<?php anaglyph_add_breadcrumbs(); ?>
	<?php anaglyph_default_page_content(); ?>

<?php
get_footer();
