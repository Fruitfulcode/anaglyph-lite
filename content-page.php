<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php do_action('anaglyph_before_page_entry_content')?>
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array(
									'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'anaglyph-lite' ) . '</span>',
									'after'       => '</div>',
									'link_before' => '<span>',
									'link_after'  => '</span>',
							) ); ?>
		<?php edit_post_link( '<i title="' . __("Edit", 'anaglyph-lite') . '" class="icon icon_pencil-edit"></i><span class="edit-link-text">'.__("Edit", 'anaglyph-lite') .'</span>', '', '' ); ?>
	</div><!-- .entry-content -->
	<?php do_action('anaglyph_after_page_entry_content')?>
</article><!-- #post-## -->