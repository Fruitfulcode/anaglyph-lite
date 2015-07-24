<?php
if ( ! function_exists( 'anaglyph_get_inline_styles' ) ) {				
	function anaglyph_get_inline_styles () {
		global $anaglyph_config;
		$style = $woo_style = '';
		
		/*Logo*/
		if(!empty($anaglyph_config['logo-retina']['url'])) {
			$style .= '
				@media only screen and (-webkit-min-device-pixel-ratio: 2), 
					only screen and (min-device-pixel-ratio: 2),
					only screen and (min-resolution: 2dppx) {
						.navigation-wrapper .navigation .navbar .navbar-brand.logo { display: none; }
						.navigation-wrapper .navigation .navbar .navbar-brand.logo.retina 	{ display: inline-block;}
					}'. "\n";
		} 
		
		
		if (!empty($anaglyph_config['header-fixed-settings'])) {
			$headerSettings = esc_attr($anaglyph_config['header-fixed-settings']);
			
			if ($headerSettings == 2) {
				$style .= '.sub-page .navigation-wrapper {
								position:fixed !important;
							}
				';
							
			} elseif ($headerSettings == 3) {
				$style .= '.navigation-wrapper {
								position:relative !important;
							}
				';
			}
		}
		
		if (!empty($anaglyph_config['menu-link-color'])) {
			$regular = $hover = $active = '';
			
			$regular	= esc_attr($anaglyph_config['menu-link-color']['regular']);
			$hover 		= esc_attr($anaglyph_config['menu-link-color']['hover']);
			$active		= esc_attr($anaglyph_config['menu-link-color']['active']);
			
			$style .= '
					.navigation-wrapper .navigation .nav > li > a { color:'.$regular.'; }
					.navigation-wrapper .navigation .nav > li:hover > a { color:'.$hover.'; }
					
					.navigation-wrapper .navigation .nav > li.current_page_item > a, 
					.navigation-wrapper .navigation .nav > li.current-menu-item > a, 
					.navigation-wrapper .navigation .nav > li.current-menu-parent > a, 
					.navigation-wrapper .navigation .nav > li.current_page_parent > a, 
					.navigation-wrapper .navigation .nav > li.current-menu-ancestor > a, 
					.navigation-wrapper .navigation .nav > li.active a {
						color:'.$active.';
					}
				';
		}
		
		/*Submenu link color*/
		if (!empty($anaglyph_config['submenu-link-color'])) {
			$regular = $hover = $active = '';
			
			$regular	= esc_attr($anaglyph_config['submenu-link-color']['regular']);
			$hover 		= esc_attr($anaglyph_config['submenu-link-color']['hover']);
			$active		= esc_attr($anaglyph_config['submenu-link-color']['active']);
			
			$bg_regular	= esc_attr($anaglyph_config['submenu-itembg-color']['regular']);
			$bg_hover 	= esc_attr($anaglyph_config['submenu-itembg-color']['hover']);
			$bg_active	= esc_attr($anaglyph_config['submenu-itembg-color']['active']);
			
			
			$style .= '
						.navigation-wrapper .navigation .nav li .child-navigation li a, 
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li a {
							color:'.$regular.';
						}
						
						.navigation-wrapper .navigation .nav li .child-navigation li:hover > a, 
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li:hover > a {
							background-color:'.$bg_hover.';
							color:'.$hover.';
						}
						
						.navigation-wrapper .navigation .nav li .child-navigation li.current_page_item a, 
						.navigation-wrapper .navigation .nav li .child-navigation li.current-menu-item a, 
						.navigation-wrapper .navigation .nav li .child-navigation li.current-menu-parent a, 
						.navigation-wrapper .navigation .nav li .child-navigation li.current_page_parent a, 
						.navigation-wrapper .navigation .nav li .child-navigation li.current-menu-ancestor a, 
						.navigation-wrapper .navigation .nav li .child-navigation li a.active,
						
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li.current_page_item a, 
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li.current-menu-item a, 
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li.current-menu-parent a, 
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li.current_page_parent a, 
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li.current-menu-ancestor a, 
						.navigation-wrapper .navigation .nav li .child-navigation li .child-navigation li a.active
						{
							background-color:'.$bg_active.';
							color:'.$active.';
						}
				';
		}

		if (!empty($anaglyph_config['header-background-color'])) {
			$style .= '
				.header-solid, .sub-page .navigation {
					background-color:'.esc_attr($anaglyph_config['header-background-color']).'; 
				}
				
				@media (max-width: 767px) {
					.navigation-wrapper .navigation .navbar .navbar-collapse {
						background-color:'.esc_attr($anaglyph_config['header-background-color']).'; 
					}
				}
			';	
		}

		
		
		if (!empty($anaglyph_config['submenu-color'])) {
			$style .= '
				.navigation-wrapper .navigation .nav li .child-navigation {
					background-color:'.esc_attr($anaglyph_config['submenu-color']).'; 
				}
			';	
		}
		
		if (!empty($anaglyph_config['submenu-itemborder-color'])) {
			$rgba = array();
			$rgba = $anaglyph_config['submenu-itemborder-color'];
			$style .= '
				.navigation-wrapper .navigation .nav li .child-navigation li {
					border-color:rgba(' . redux_Helpers::hex2rgba($rgba['color']) . ',' . $rgba['alpha'] . '); 
				}
			';	
		}
		
		if (!empty($anaglyph_config['before-item-color'])) {
			$style .= '
				.navigation-wrapper .navigation .nav li a:before {
					background-color:'.esc_attr($anaglyph_config['before-item-color']).'; 
				}
			';	
		}
		
		if (!empty($anaglyph_config['after-item-color'])) {
			$style .= '
				.navigation-wrapper .navigation .nav li a:after {
					background-color:'.esc_attr($anaglyph_config['after-item-color']).'; 
				}
			';	
		}
		
		
		/*Slider options*/
		if (!empty($anaglyph_config['slider-title-bg-color'])) {
			$rgba = array();
			$rgba = $anaglyph_config['slider-title-bg-color'];
			$style .= '
				.slides .slide-content .slide-wrapper h1, 
				.slides .slide-content .slide-wrapper h1:before, 
				.slides .slide-content .slide-wrapper h1:after {
					background-color:rgba(' . redux_Helpers::hex2rgba($rgba['color']) . ',' . $rgba['alpha'] . '); 
				}
			';	
		}
		
			
		if (!empty($anaglyph_config['headline-before-sep-color'])) {
			$style .= '
				.section-title h1:before,
				.section-title h2:before,
				.section-title h3:before,
				.section-title h4:before,
				.section-title h5:before,
				.section-title h6:before,
				.background-color-secondary			{
					background-color:'.esc_attr($anaglyph_config['headline-before-sep-color']).'; 
				}
			';	
		}
		
		if (!empty($anaglyph_config['headline-after-sep-color'])) {
			$style .= '
				.section-title h1:after,
				.section-title h2:after,
				.section-title h3:after,
				.section-title h4:after,
				.section-title h5:after,
				.section-title h6:after,
				.background-color-primary			{
					background-color:'.esc_attr($anaglyph_config['headline-after-sep-color']).'; 
				}
			';	
		}
		
		if (!empty($anaglyph_config['top-title-bg-color'])) {
			$rgba = array();
			$rgba = $anaglyph_config['top-title-bg-color'];
			$style .= '
				.sub-page #page-title .title h1 {
					background-color:rgba(' . redux_Helpers::hex2rgba($rgba['color']) . ',' . $rgba['alpha'] . ');
				}
			';	
		}
		
		
		if (!empty($anaglyph_config['blog-date-bg-color'])) {
			$style .= '
				.blog-post .date-circle {
					background-color:'.esc_attr($anaglyph_config['blog-date-bg-color']).';);
				}
			';	
		}
		
		if (!empty($anaglyph_config['navigation-border'])) {
			$border_arr = array();
			$border_arr = $anaglyph_config['navigation-border'];
			$style .= '
				.pagination li a, .pagination li span {
					border-left-width:'.$border_arr['border-left'].';
					border-right-width:'.$border_arr['border-right'].';
					border-top-width:'.$border_arr['border-top'].';
					border-bottom-width:'.$border_arr['border-bottom'].';
					border-style:'.$border_arr['border-style'].';
				}
			';
			
		}
		
		if (!empty($anaglyph_config['navigation-border-color'])) {
			$border_color = array();
			$border_color = $anaglyph_config['navigation-border-color'];
			$style .= '
					.pagination li a, .pagination li span {
						border-color:'.$border_color['regular'].';
					}
				
					.pagination li a:hover {
						border-color:'.$border_color['hover'].';
					}
					
					.pagination li.active a,
					.pagination li.active span,
					.pagination li.active a:hover, 
					.pagination li.active a:focus, 
					.pagination li.active a:active		{ 
						border-color:'.$border_color['active'].';
					}
				';
		}	
		
		if (!empty($anaglyph_config['navigation-link-color'])) {
			$border_color = array();
			$border_color = $anaglyph_config['navigation-link-color'];
			$style .= '
					.pagination li a, .pagination li span {
						color:'.$border_color['regular'].';
					}
				
					.pagination li a:hover {
						color:'.$border_color['hover'].';
					}
					
					.pagination li.active a,
					.pagination li.active span,
					.pagination li.active a:hover, 
					.pagination li.active a:focus, 
					.pagination li.active a:active		{ 
						color:'.$border_color['active'].';
					}
				';
		}	
		
		
		if (!empty($anaglyph_config['navigation-bglink-color'])) {
			$border_color = array();
			$border_color = $anaglyph_config['navigation-bglink-color'];
			$style .= '
					.pagination li a, .pagination li span {
						background-color:'.$border_color['regular'].';
					}
				
					.pagination li a:hover {
						background-color:'.$border_color['hover'].';
					}
					
					.pagination li.active a,
					.pagination li.active span,
					.pagination li.active a:hover, 
					.pagination li.active a:focus, 
					.pagination li.active a:active		{ 
						background-color:'.$border_color['active'].';
					}
				';
		}	
		
		if (!empty($anaglyph_config['p-opacity'])) {
			$opacity = $anaglyph_config['p-opacity'];
			$style .= '
					.blog-posts .blog-post .blog-post-content p, .container p {
						filter: progid:DXImageTransform.Microsoft.Alpha(Opacity='. $opacity*100 .');
						opacity: '.$opacity.';
					}
				
			';
		}
		
		
		if (!empty($anaglyph_config['contact-scolor'])) {
			$bg_color = $anaglyph_config['contact-scolor'];
			$style .= '
					#nav-contact-us .background {
						background-color:'.$bg_color.';
					}
			';
		}
		
		if (!empty($anaglyph_config['contact-address-icon-color'])) {
			$color = $anaglyph_config['contact-address-icon-color'];
			$style .= '
					.address .icon {
						color:'.$color.';
					}
			';
		}
		
		if (!empty($anaglyph_config['contact-form-btncolor'])) {
			$bg_color = $anaglyph_config['contact-form-btncolor'];
			$style .= '
					#page-footer #nav-contact-us #contactform input#submit {
						background-color:'.$bg_color.';
						border-color:'.$bg_color.';
					}
			';
		}
		
		if (!empty($anaglyph_config['footer-color'])) {
			$bg_color = $anaglyph_config['footer-color'];
			$style .= '
					#footer-bottom .background {
						background-color:'.$bg_color.';
					}
			';
		}
		
		if (!empty($anaglyph_config['custom-css'])) {
			$style .= wp_kses_stripslashes($anaglyph_config['custom-css']);
		}
		
		if (!empty($style)) 	
			wp_add_inline_style( 'anaglyph-style', anaglyph_compress_code($style) ); 
		if (!empty($woo_style)) 
			wp_add_inline_style( 'woo-style', 	   anaglyph_compress_code($woo_style) ); 
		
	}
	add_action('wp_enqueue_scripts', 'anaglyph_get_inline_styles', 99);
}	

if ( ! function_exists( 'anaglyph_get_inline_scripts' ) ) {				
	function anaglyph_get_inline_scripts () {
		global $anaglyph_config;
		if (!empty($anaglyph_config['custom-js'])) {
			if ( wp_script_is( 'jquery', 'done' ) ) { 
				if (trim($anaglyph_config['custom-js']) != null) {
				?>
					<script type="text/javascript">
						<?php echo wp_kses_stripslashes($anaglyph_config['custom-js']); ?>
					</script>
				<?php
				}
			}
		}	
	}
	add_action( 'wp_footer', 'anaglyph_get_inline_scripts', 99 );
}	