<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */

get_header(); ?>

	<?php anaglyph_get_post_additional_title(); ?>
	<?php anaglyph_add_breadcrumbs(); ?>
	<section id="blog-post" class="block">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="section-content">
						<?php anaglyph_get_single_content(); ?>
					</div>	
				</div>	
			</div>	
		</div>	
	</section>	
<?php
	get_footer();