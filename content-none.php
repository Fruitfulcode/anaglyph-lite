<?php
/**
 * The template for displaying a "No posts found" message
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */
?>

<article id="post-none" class="no-post-content">

<div class="entry-content">
	<header class="post-header">
		<h1><?php _e( 'Nothing Found', 'anaglyph-lite' ); ?></h1>
	</header>
</div>		

<div class="entry-content">
	<section class="post-content">
	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
		<p><?php printf( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'anaglyph-lite' ), admin_url( 'post-new.php' ) ); ?></p>
	<?php elseif ( is_search() ) : ?>
		<p><?php _e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'anaglyph-lite' ); ?></p>
		<?php get_search_form(); ?>
	<?php else : ?>
		<p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'anaglyph-lite' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div><!-- .page-content -->

</article>