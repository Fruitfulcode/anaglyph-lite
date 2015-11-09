<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

get_header(); ?>
<?php 
	global $anaglyph_config;
?>
	<!-- Page Title -->
	<section id="page-title">
		<div class="title">
			<h1 class="reset-margin"><?php _e( 'Not found', 'anaglyph-lite' ); ?></h1>
		</div>
		<?php anaglyph_custom_image('404-titleimage'); ?>
	</section>
	<!-- end Page Title -->
	<?php anaglyph_add_breadcrumbs(); ?>
	
	<section id="not-found" class="block" role="main">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-content">
						<div class="center">
							<div class="featured-404">
								<?php anaglyph_custom_image('404-image'); ?>
							</div>
						</div>
					
						<?php 
							if (!empty($anaglyph_config['404-text'])) {
								 echo '<div class="entry-content">';
									echo '<p>'.$anaglyph_config['404-text'] .'</p>'; 
								echo '</div>';
							}	
						?>
						<?php get_search_form(); ?>
					</div>	
				</div>	
			</div>	
		</div>	
	</section>

<?php
	get_footer();
