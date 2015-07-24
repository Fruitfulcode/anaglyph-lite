<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Anaglyph_Theme
 * @since Anaglyph Theme 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta content="width=device-width, initial-scale=1.0" name="viewport">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<div class="navigation-wrapper">
			<div class="navigation">
				<header class="navbar" id="top" role="banner">
					<div class="container">
						<div class="navbar-header">
							<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<?php anaglyph_get_logo(); ?>
						</div>

						<?php 	if ( has_nav_menu( 'primary' ) ) {
									   wp_nav_menu( array( 
														'theme_location' 	=> 'primary', 
														'menu_class' 	 	=> 'nav navbar-nav', 
														'container'		 	=> 'nav', 
														'container_class' 	=> 'collapse navbar-collapse bs-navbar-collapse navbar-right', 
														'walker' 			=> new anaglyph_submenu_class())); 
								} else {
								?>
									<nav class="collapse navbar-collapse bs-navbar-collapse navbar-right">
										<ul class="nav navbar-nav">
											<?php
												wp_list_pages(array('title_li' => '', 'sort_column' => 'ID', 'walker' => new Anaglyph_Page_Walker()));
											?>	
										</ul>
									</nav>	
								<?php	
								}							  
						?>
					</div><!-- /.container -->
				</header><!-- /.navbar -->
			</div><!-- /.navigation -->
		</div><!-- end Header -->	
		<!-- For top anchor -->	
		
		<?php anaglyph_get_hslider(); ?>
	<div id="main" class="site-main">
