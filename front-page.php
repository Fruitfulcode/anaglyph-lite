<?php
/**
 * The template for displaying Front Page
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
		
		if (get_option('page_on_front') != 0) { 
			global $prefix;
			$layout = 0;
			$page_layout = get_post_meta(get_the_ID(), $prefix.'page_layout', true); 
			if (!empty($page_layout)) $layout = $page_layout;
			
			while ( have_posts() ) : the_post(); ?>
			
				<section id="front-page" class="wpb_row block vc_row-fluid">
					<div class="container">
						<div class="row">
							<div class="col-md-12">
								<div id="content" class="section-content" role="main">
									<?php
										if ($layout == 0) {
											the_content();
										} else if ($layout == 1) {
											echo '<div class="row">';
												echo '<div class="col-md-8">';
													the_content();
												echo '</div>';
												echo '<div class="col-md-4">';
													get_sidebar_part();					
												echo '</div>';
											echo '</div>';
										} else if ($layout == 2) {
											echo '<div class="row">';
												echo '<div class="col-md-4">';
													get_sidebar_part();					
												echo '</div>';
												echo '<div class="col-md-8">';
													the_content();
												echo '</div>';
											echo '</div>';
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</section>	
			<?php
			endwhile;
		} else {
			$blog_id = get_option( 'page_for_posts' ); 
			if (is_home()) { 
				if (!is_front_page()) {
				?>
				<section id="page-title">
					<?php 
						$title = get_the_title($blog_id);
						if (!empty($title)) {
					?>	
							<div class="title">
								<h1 class="reset-margin"><?php echo $title;?></h1>
							</div>
					<?php } ?>		
				<?php 
					if ( has_post_thumbnail($blog_id)) { 
						$title_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($blog_id), 'full');
						echo '<img src="'.$title_thumbnail[0].'" class="parallax-bg" alt="">';
					}	
				?>
				</section>
			<?php 
				} 
			} 
			?>
			<?php anaglyph_add_breadcrumbs(); ?>
			<?php anaglyph_default_page_content(); ?>

	<?php
		}		
	?>
<?php 
get_footer();
