<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */
?>

<?php do_action('anaglyph_before_post_content'); ?>
<?php
	global $anaglyph_config, $time_post_delay, $anaglyph_is_redux_active;
	$post_layout 		= (int) $anaglyph_config['pp-post'];
	$animation_class 	= esc_attr($anaglyph_config['pp-animations']); 
	if (!$anaglyph_is_redux_active) $animation_class = 'bottom'; 
	$delay_interval 	= anaglyph_get_delay_interval($time_post_delay);
	if (!is_single() && ($animation_class != 'none')) {
		$animation_class = 'data-scroll-reveal="enter '.$animation_class.' and move 50px '.$delay_interval.'"';
	} else {
		$animation_class = '';
	}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post postfomrat-quote'); ?> <?php echo $animation_class; ?>>
	<?php if ( is_search() || !is_single()) : ?>
	<?php anaglyph_get_post_date(); ?>	
	<div class="blog-post-content entry-summary">
		<?php do_action('anaglyph_post_meta'); ?>
		<?php anaglyph_blog_post_preview(); ?>
		<?php anaglyph_get_post_readmore(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<header class="post-header">
			<h1><?php the_title(); ?></h1>
		</header>
		<section class="post-info">
			<?php anaglyph_get_post_date(); ?>		
			<?php anaglyph_get_post_info(); ?>
		</section>
		<section class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'anaglyph-lite' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
				) ); ?>
		</section>
		<footer class="post-footer">
			<?php anaglyph_get_post_share(); ?>
			<?php anaglyph_get_post_tags(); ?>
		</footer>
	</div><!-- .entry-content -->
	<hr />
	<?php endif; ?>
	
</article><!-- #post-aside -->
<?php do_action('anaglyph_after_post_content'); ?>