<?php

global 	$inc_theme_url, $prefix, $posts_count, $row_heading, $time_post_delay, $anaglyph_is_redux_active;
		$inc_theme_url = get_template_directory_uri() . '/includes/theme/';
		
		$row_heading = true;
		$posts_count = 1;
		$time_post_delay = 0;
		$prefix = '_anaglyph_';
		$anaglyph_is_redux_active = class_exists('ReduxFramework');

		add_theme_support( 'woocommerce' );
		
if ( ! isset( $content_width ) ) $content_width = 1170;
		
if ( ! function_exists( 'anaglyph_setup' ) ) :
/**
 * Anaglyph Theme setup.
 * Set up theme defaults and registers support for various WordPress features.
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 * @since Anaglyph Theme 1.0
 */
function anaglyph_setup() {
	/*
	 * Make Anaglyph Theme available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Anaglyph Theme, use a find and
	 * replace to change 'anaglyph-lite' to the name of your theme in all
	 * template files.
	 */
	 
	load_theme_textdomain( 'anaglyph-lite', get_template_directory() . '/languages' );
	add_theme_support( "title-tag" );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 750, 360, true );
	
	register_nav_menus( array(
		'primary'   => __( 'Top primary menu', 'anaglyph-lite' )
	) );

	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery', 'chat'));
}
endif; // anaglyph_setup
add_action( 'after_setup_theme', 'anaglyph_setup' );

/*Walkers*/
class Anaglyph_Submenu_Class extends Walker_Nav_Menu {
	 function start_lvl(&$output, $depth = 0, $args = array()) {
		$classes 	 = array('sub-menu', 'list-unstyled', 'child-navigation');
		$class_names = implode( ' ', $classes );
		$output .= "\n" . '<ul class="' . $class_names . '">' . "\n";
	}
	
	 function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
			
           global $wp_query, $anaglyph_config, $woocommerce;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           
		   
			$attributes .= !empty( $item->url ) ? ' href="' .  $item->url .'"' : '';
           
            $item_output = $args->before;
            $item_output .= '<a'. $attributes .'>';
            $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
     }
}


if ( ! function_exists( 'anaglyph_add_page_parent_class' ) ) {
	function anaglyph_add_page_parent_class( $css_class, $page, $depth, $args ) {
		if ( ! empty( $args['has_children'] ) ) {
			$css_class[] = 'parent';
		}
		return $css_class;
	}
	add_filter( 'page_css_class', 'anaglyph_add_page_parent_class', 10, 4 );
}

class Anaglyph_Page_Walker extends Walker_page {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
			$output .= "\n$indent<ul class='sub-menu list-unstyled child-navigation'>\n";
	}
}
/*End custom walkers*/

/*Customize*/
if ( ! function_exists( 'anaglyph_customize_register' ) ) :
function anaglyph_customize_register( $wp_customize ) {
	class Anaglyph_Theme_Options_Button_Control extends WP_Customize_Control {
		public $type = 'button_link_control';
 
		public function render_content() {
			?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<input class="button button-primary save link_to_options" type="button" value="<?php _e('Anaglyph Options', 'anaglyph-lite'); ?>" onclick="javascript:location.href='<?php echo esc_url(admin_url('admin.php?page=anaglyph_options')); ?>'"/>
				</label>
			<?php
		}
	}
	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	
	$wp_customize->remove_section( 'colors');
	$wp_customize->remove_section( 'header_image');
	$wp_customize->remove_section( 'background_image');
	$wp_customize->add_section('anaglyph_themeoptions_link', array(
							   'title' => __('Anaglyph Options', 'anaglyph-lite'),
							   'priority' => 10,
							));
	
	
	$wp_customize->add_setting( 'themeoptions_button_control', array('sanitize_callback' => 'themeoptions_button_control_sanitize_func')  );
 
	$wp_customize->add_control(
		new Anaglyph_Theme_Options_Button_Control (
        $wp_customize,
        'button_link_control',
        array(
            'label' 	=> 'Advanced theme settings',
			'section' 	=> 'anaglyph_themeoptions_link',
            'settings' 	=> 'themeoptions_button_control'
        )
    )
);
}
add_action( 'customize_register', 'anaglyph_customize_register' );

function themeoptions_button_control_sanitize_func ( $value ) {
	return $value;
}
endif; // anaglyph_customize_register

/**
 * Adjust content_width value for image attachment template.
 * @since Anaglyph Theme 1.0
 * @return void
 */
if ( ! function_exists( 'anaglyph_content_width' ) ) :
function anaglyph_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 1170;
	}
}
add_action( 'template_redirect', 'anaglyph_content_width' );
endif; //anaglyph_content_width

/**
 * Register three Anaglyph Theme widget areas.
 * @since Anaglyph Theme 1.0
 * @return void
 */
if ( ! function_exists( 'anaglyph_widgets_init' ) ) :
function anaglyph_widgets_init() {
	
	register_sidebar( array(
		'name'          => __( 'Main sidebar', 'anaglyph-lite' ),
		'id'            => 'sidebar-main',
		'description'   => __( 'Main site sidebar.', 'anaglyph-lite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Blog sidebar', 'anaglyph-lite' ),
		'id'            => 'sidebar-blog',
		'description'   => __( 'Blog sidebar.', 'anaglyph-lite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Single Post Sidebar', 'anaglyph-lite' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Additional blog page sidebar.', 'anaglyph-lite' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	if (anaglyph_is_woocommerce_activated()) {
		register_sidebar( array(
			'name'          => __( 'Shop Page Sidebar', 'anaglyph-lite' ),
			'id'            => 'shop',
			'description'   => __( 'WooCommerce shop page sidebar.', 'anaglyph-lite' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
	
}
add_action( 'widgets_init', 'anaglyph_widgets_init' );
endif; //anaglyph_widgets_init


/**
 * Enqueue scripts and styles for the front end.
 * @since Anaglyph Theme 1.0
 * @return void
 */
if ( ! function_exists( 'anaglyph_scripts' ) ) :
function anaglyph_scripts() {
	global 	$inc_theme_url, $anaglyph_config;
	$slider_on = false;
	$slider_parallax_on = false;
	$slideshowSpeed    = 8000;
	$animationSpeed    = 1000;
	$smoothscroll  	   = false;
	$disable_animation_mobile  = false;
	$animationeffectin = $animationeffectout = '';
	$is_mobile         = false;
	if ( wp_is_mobile()) $is_mobile = true;
	
	if (!empty($anaglyph_config['switch-slider'])) {
		$slider_on = $anaglyph_config['switch-slider'];
	}
	$slider_on = ($slider_on) ? true : false;
	
	if (!empty($anaglyph_config['slider-parallax'])) {
		$slider_parallax_on = $anaglyph_config['slider-parallax'];
	}
	$slider_parallax_on = ($slider_parallax_on) ? true : false;
	
	if (!empty($anaglyph_config['pp-animation-mobile'])) {
		if ( $anaglyph_config['pp-animation-mobile'] == '1' )
			$disable_animation_mobile = true;
	}
	
	if (!empty($anaglyph_config['home-slides'])) {
		$slideshowSpeed = $anaglyph_config['slider-showspeed'];
	}
	
	//enhanced scrolling on/off
	if (!empty($anaglyph_config['smoothscroll'])) {
		$smoothscroll = $anaglyph_config['smoothscroll'];
	}
	
	if (!empty($anaglyph_config['slider-animationeffect-in'])) {
		$animationeffectin = $anaglyph_config['slider-animationeffect-in'];
	}
	
	if (!empty($anaglyph_config['slider-animationeffect-out'])) {
		$animationeffectout = $anaglyph_config['slider-animationeffect-out'];
	}
	
	$headerFixedVartiation  = 1;
	if (!empty($anaglyph_config['header-fixed-settings']) && isset($anaglyph_config['header-fixed-settings']))
	$headerFixedVartiation  = esc_attr($anaglyph_config['header-fixed-settings']);

	if ( is_singular()) wp_enqueue_script( 'comment-reply' );
	
	/*Custom Css*/
	wp_enqueue_style( 'anaglyph-elegantFonts', 	$inc_theme_url . 'assets/icons/elegant-font/style.css');
	wp_enqueue_style( 'anaglyph-bootstrap',  	$inc_theme_url . 'assets/bootstrap/css/bootstrap.min.css');
	wp_enqueue_style( 'anaglyph-vanillabox', 	$inc_theme_url . 'assets/css/vanillabox/vanillabox.css');
	wp_enqueue_style( 'anaglyph-flexslider', 	$inc_theme_url . 'assets/css/flexslider.css');
	wp_enqueue_style( 'anaglyph-animate', 	 	$inc_theme_url . 'assets/css/animate.css');
	wp_enqueue_style( 'anaglyph-selectize', 	$inc_theme_url . 'assets/css/selectize.css');
	wp_enqueue_style( 'anaglyph-style', 		get_stylesheet_uri() );
	
	if (anaglyph_is_woocommerce_activated()) {
		wp_enqueue_style( 'anaglyph-woo', 	 	$inc_theme_url . 'assets/css/woo.css');
	}
	
	/*Custom Js*/
	wp_enqueue_script( 'anaglyph-bootstrap', 	$inc_theme_url . 'assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), '20130402', true );
	wp_enqueue_script( 'anaglyph-flexslider', 	$inc_theme_url . 'assets/js/jquery.flexslider-min.js',	 array( 'jquery' ), '20130402', true );
	wp_enqueue_script( 'anaglyph-html5',		$inc_theme_url . 'assets/js/html5.js',	 array( 'jquery' ), '20130402', true );

	wp_enqueue_script( 'anaglyph-respond',		$inc_theme_url . 'assets/js/respond.js',	 array( 'jquery' ), '20130402', true );
	wp_enqueue_script( 'anaglyph-validate',		$inc_theme_url . 'assets/js/jquery.validate.min.js',	 array( 'jquery' ), '20130402', true );
	wp_enqueue_script( 'anaglyph-placeholder',	$inc_theme_url . 'assets/js/jquery.placeholder.js',	 array( 'jquery' ), '20130402', true );
	wp_enqueue_script( 'anaglyph-vanillabox',	$inc_theme_url . 'assets/js/jquery.vanillabox-0.1.6.min.js',	 array( 'jquery' ), '20130402', true );
	
	wp_enqueue_script( 'anaglyph-sReveal',	$inc_theme_url . 'assets/js/scrollReveal.min.js',	 array( 'jquery' ), '20130402', true );
	wp_enqueue_script( 'anaglyph-skrollr',	$inc_theme_url . 'assets/js/skrollr.js',	 array( 'jquery' ), '20130402', true );
	
	wp_enqueue_script( 'anaglyph-rr',		$inc_theme_url . 'assets/js/retina-1.1.0.min.js',	 array( 'jquery' ), '20130402', true );
	wp_enqueue_script( 'anaglyph-st',		$inc_theme_url . 'assets/js/jquery.scrollTo.min.js', array( 'jquery' ), '20130402', true );
	
	wp_enqueue_script( 'anaglyph-mainJs',	$inc_theme_url . 'assets/js/custom.js',	 array( 'jquery' ), '20130402', true );
	
	
	/*Enable*/
	if ($smoothscroll) {
		wp_enqueue_script( 'anaglyph-smoothscroll',	$inc_theme_url . 'assets/js/smoothscroll.js',	 array( 'jquery' ), '20130402', true );
	}	
	

	wp_enqueue_script( 'anaglyph-selectize',	$inc_theme_url . 'assets/js/selectize.min.js',	 array( 'jquery' ), '20130402', true );
	wp_localize_script( 'anaglyph-mainJs', 'AnaglyphGlobal', 	array( 'ajaxurl' => admin_url( 'admin-ajax.php' ),
																	   'is_mobile' => $is_mobile,
																	   'disable_animation_mobile' => $disable_animation_mobile,
																	   'headerFixedVartiation'	=> esc_js($headerFixedVartiation),
																	   'slider_on' => $slider_on,
																	   'sliderParam' => array (
																			'sliderParallaxOn'		=> $slider_parallax_on,
																			'slideshowSpeed' 		=> esc_js($slideshowSpeed),
																			'animationSpeed' 		=> esc_js($animationSpeed),
																			'directionNav' 	 		=> false,
																			'controlNav' 			=> true,	
																			'animationeffectin'		=> esc_js($animationeffectin),
																			'animationeffectout'	=> esc_js($animationeffectout),
																		)
																	 )
	);  
	
}
add_action( 'wp_enqueue_scripts', 'anaglyph_scripts' );
endif; //anaglyph_scripts


/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Anaglyph Theme 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
if ( ! function_exists( 'anaglyph_body_classes' ) ) :
function anaglyph_body_classes( $classes ) {
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_header_image() ) {
		$classes[] = 'header-image';
	} else {
		$classes[] = 'masthead-fixed';
	}

	if ( is_archive() || is_search() || is_home() || is_404() ) {
		$classes[] = 'list-view';
		$classes[] = 'sub-page';
	}

	if (is_page() && !is_front_page()) {
		$classes[] = 'sub-page';
	}
	
	if (anaglyph_is_woocommerce_activated()) {
		if (is_shop() && is_cart()) {
			$classes[] = 'sub-page';
		}
	}	
	
	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page()  && !is_page()) {
		$classes[] = 'singular';
		$classes[] = 'sub-page';
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		$classes[] = 'grid';
	}

	return $classes;
}
add_filter( 'body_class', 'anaglyph_body_classes' );
endif; //anaglyph_body_classes


/**
 * Extend the default WordPress post classes.
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 
 * @since Anaglyph Theme 1.0
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
if ( ! function_exists( 'anaglyph_post_classes' ) ) :
function anaglyph_post_classes( $classes ) {
	if ( ! post_password_required() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}
	return $classes;
}
add_filter( 'post_class', 'anaglyph_post_classes' );
endif; //anaglyph_post_classes


/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Anaglyph Theme 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
if ( ! function_exists( 'anaglyph_wp_title' ) ) :
function anaglyph_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() ) {
		return $title;
	}
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = get_bloginfo( 'name' );
		$title = "$title $sep $site_description";
	}
	
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'anaglyph-lite' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'anaglyph_wp_title', 10, 2 );
endif; //anaglyph_wp_title

/**
 * Getter function for Featured Content Plugin.
 * @since Anaglyph Theme 1.0
 * @return array An array of WP_Post objects.
 */
if ( ! function_exists( 'anaglyph_get_featured_posts' ) ) :
function anaglyph_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Anaglyph Theme.
	 * @since Anaglyph Theme 1.0
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'anaglyph_get_featured_posts', array() );
}
endif; //anaglyph_get_featured_posts

/**
 * A helper conditional function that returns a boolean value.
 * @since Anaglyph Theme 1.0
 * @return bool Whether there are featured posts.
 */
if ( ! function_exists( 'anaglyph_has_featured_posts' ) ) :
function anaglyph_has_featured_posts() {
	return ! is_paged() && (bool) anaglyph_get_featured_posts();
}
endif; //anaglyph_has_featured_posts

/*Custom functions*/
if ( ! function_exists( 'anaglyph_get_logo' ) ) :
function anaglyph_get_logo() {
	global $anaglyph_config;
	
	$original_logo = $retina_logo = '';
	$width 	= $anaglyph_config['logo-dimensions']['width'];
	$height = $anaglyph_config['logo-dimensions']['height'];
	
	if (!empty($anaglyph_config['logo']['url'])) 		{ $original_logo = esc_url($anaglyph_config['logo']['url']);  		 } else {  $original_logo = ''; }
	if (!empty($anaglyph_config['logo-retina']['url'])) { $retina_logo 	 = esc_url($anaglyph_config['logo-retina']['url']);  } else {  $retina_logo   = ''; }
	
	/*Full Backend Options*/
	$description  = $name = '';
	$description  = esc_attr(get_bloginfo('description'));
	$name  		  = esc_attr(get_bloginfo('name'));
							
	if (!empty($original_logo) || !empty($retina_logo)) {
		if ($original_logo) echo '<a class="navbar-brand nav logo" href="' 			. esc_url( home_url( '/' ) ) . '" title="' . $description .'" rel="home"><img width="'.$width.'" height="'.$height.'" src="'. $original_logo  .'" alt="' . $description . '"/></a>';
		if ($retina_logo) 	echo '<a class="navbar-brand nav logo retina" href="' 	. esc_url( home_url( '/' ) ) . '" title="' . $description .'" rel="home"><img width="'.$width.'" height="'.$height.'" src="'. $retina_logo  .'" alt="' . $description . '"/></a>';	
	} else {
		echo  '<a class="navbar-brand nav" href="' . esc_url( home_url( '/' ) ) . '" title="' . $description .'" rel="home"><h1 class="site-title">'. $name .'</h1><h2 class="site-description">'. $description .'</h2></a>';
	}	
}

endif; //anaglyph_get_logo

if ( ! function_exists( 'anaglyph_get_hslider' ) ) :
function anaglyph_get_hslider() {
	$out_ = '';
	global $anaglyph_config, $post;
	
	if ((!is_front_page()) || (!$anaglyph_config['switch-slider'])) { return; }
	
	$animationeffectin = '';
	if (!empty($anaglyph_config['slider-animationeffect-in'])) {
		$animationeffectin = $anaglyph_config['slider-animationeffect-in'];
	}
	
	$run_classes = array();
	$run_classes[] = 'slide-wrapper';
	$run_classes[] = $animationeffectin;
	$run_classes[] = 'animated';
	
	if (!empty($anaglyph_config['home-slides'])) {
		$slides = $anaglyph_config['home-slides'];
		?>
		
		<section id="slider"><!-- Slider -->
			<div class="flexslider">
				<ul class="slides">
				<?php
					foreach ($slides as $item_id) {
					
						$post = get_post($item_id);
						$attachment_id = get_post_thumbnail_id( $item_id );
						$slide_image = wp_get_attachment_image_src( $attachment_id, 'full');
						setup_postdata($post); ?>
						
						<li class="slide">
							<div class="slide-content">
								<div class="<?php echo implode(' ', $run_classes); ?>">
									<?php if (!empty($anaglyph_config['slider-links'])) { ?> 
										<a href="<?php the_permalink(); ?>">
									<?php } ?>
											<h2 class="reset-margin"><?php the_title(); ?></h2>
											<h3 class="description"><?php echo get_the_excerpt(); ?></h3>
									<?php if (!empty($anaglyph_config['slider-links'])) { ?> 
										</a>
									<?php } ?>
								</div>
							</div>
							<img src="<?php echo esc_url($slide_image[0]); ?>" class="slider-bg" alt="<?php the_title(); ?>">
						</li>
						<?php
						wp_reset_postdata();
					} 
				?>
				</ul>
			</div>
		</section><!-- end Slider -->
		<?php
	}	
	echo $out_;
}
endif; //anaglyph_get_hslider

/*Search form*/
if ( ! function_exists( 'anaglyph_search_form' ) ) :
function anaglyph_search_form( $form ) {
	$form = '';
	$form .= '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( '/' ) . '" >';
		$form .= '<div>';
			$form .= '<label for="s" class="screen-reader-text">'.__('Search', 'anaglyph-lite').'</label>';
			$form .= '<input type="search" value="' . get_search_query() . '" name="s" id="s" placeholder="'.__('Search', 'anaglyph-lite').'"/>';
			$form .= '<input type="submit" class="screen-reader-text" value="'.__('Search', 'anaglyph-lite').'" />';
		$form .= '</div>';
    $form .= '</form>';
    return $form;
}
add_filter( 'get_search_form', 'anaglyph_search_form' );
endif; //anaglyph_search_form


/*-----------------------------------------------------------------------------------*/
/* Check if WooCommerce is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'anaglyph_is_woocommerce_activated' ) ) {
function anaglyph_is_woocommerce_activated() {
	if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
}
}

if ( ! function_exists( 'anaglyph_kses_data' ) ) {
function anaglyph_kses_data($text = null) {
	$allowed_tags = wp_kses_allowed_html( 'post' );
	return wp_kses($text, $allowed_tags);
}
}

if ( ! function_exists( 'anaglyph_change_excerpt_more' ) ) {
function anaglyph_change_excerpt_more( $more ) {
	return '&#8230;<span class="screen-reader-text">  '.get_the_title().'</span>';
}
add_filter('excerpt_more', 'anaglyph_change_excerpt_more');
}

if ( ! function_exists( 'anaglyph_send_mail' ) ) {
	add_action('wp_ajax_send_contact_mail', 'anaglyph_send_mail');
	add_action('wp_ajax_nopriv_send_contact_mail', 'anaglyph_send_mail');
	function anaglyph_send_mail() {
		global $anaglyph_config;
		parse_str($_POST['formData'], $data); 
		
		$phone = $name = $email = $message = $mobile;
		
		
		if (!empty($data['cff-name'])) $name = $data['cff-name'];
		if (!empty($data['cff-email'])) $email = $data['cff-email'];
		if (!empty($data['cff-message'])) $message = $data['cff-message'];
		if (!empty($data['cff-phone'])) $phone = $data['cff-phone'];
		if (!empty($data['cff-mobile'])) $mobile = $data['cff-mobile']; 
		
		if (!empty($anaglyph_config['contact-email'])) {
			$to = esc_attr($anaglyph_config['contact-email']);
		} else {
			$to = get_option('admin_email');
		}
		$subject = sprintf(__('Message From %1s Contact Form', 'anaglyph-lite'), get_bloginfo('name'));

		if (!empty($name)) {
			$body = "";
			$body .= __("Name", 'anaglyph-lite') . ': ';
			$body .= $name;
			$body .= "\n\n";
		}

		if (!empty($phone)) {
			$body .= "";
			$body .= __("Phone", 'anaglyph-lite') . ': ';
			$body .= $phone;
			$body .= "\n";
		}	
		
		if (!empty($mobile)) {
			$body .= "";
			$body .= __("Mobile", 'anaglyph-lite') . ': ';
			$body .= $mobile;
			$body .= "\n";
		}	
		
		if (!empty($message)) {
			$body .= "";
			$body .= __("Message", 'anaglyph-lite') . ': ';
			$body .= $message;
			$body .= "\n";
		}	

		$headers = sprintf(__('From: %1s', 'anaglyph-lite'),  $email) . "\r\n";

		if (is_email( $email )) {
			if (mail($to, $subject, $body, $headers)) {
				echo __('<span id="valid"><i class="icon icon-check"></i>Your Email was sent!</span>', 'anaglyph-lite');
			} else {
				echo __('<span id="valid"><i class="icon icon-check"></i>Error! Your Email not sent!</span>', 'anaglyph-lite');
			}
		} else {
			echo __('<span id="invalid">Invalid Email, please provide a correct email.</span>', 'anaglyph-lite');
		}
		
		die('');
	}	
}

add_action('wp_ajax_reload_captcha_image', 'reload_captcha_image_func');
add_action('wp_ajax_nopriv_reload_captcha_image', 'reload_captcha_image_func');
if ( ! function_exists('reload_captcha_image_func')){
	function reload_captcha_image_func(){
		global 	$inc_theme_url, $anaglyph;
		echo $inc_theme_url . 'extensions/captcha/captcha.php?'.current_time('timestamp', 0);
		die();
	}
}	

add_action('wp_ajax_verify_captcha', 'anaglyph_get_verify_captcha');
add_action('wp_ajax_nopriv_verify_captcha', 'anaglyph_get_verify_captcha');
if ( ! function_exists('anaglyph_get_verify_captcha')){
	function anaglyph_get_verify_captcha(){
		session_start();
		if (isset($_POST) && ($_POST['action'] == 'verify_captcha')) {
			$ecaptcha = $_POST['form_captcha'];
			if ($_SESSION["cf_code"] == $ecaptcha) {
				echo 'true';
			} else {
				echo 'false';
			}
		}
		die('');
	}
}

if ( ! function_exists('anaglyph_get_captcha_html')){
	function anaglyph_get_captcha_html(){
		global 	$inc_theme_url, $anaglyph_config;
		$captcha_html = '';
		$captcha_html  = '<div class="captcha-inner"><img id="captcha_img" src="' . $inc_theme_url . 'extensions/captcha/captcha.php?'.current_time('timestamp', 0).'"></div>';	
		return $captcha_html;
	}
}

if ( ! function_exists( 'anaglyph_get_contact_form' ) ) {
	function anaglyph_get_contact_form() {
		function get_control_group_html($field_html) {
			$out_html = '';			
			$out_html .= '<div class="control-group">';
				$out_html .= '<div class="controls">';
					$out_html .= $field_html;
				$out_html .= '</div>';
			$out_html .= '</div>';	
			return $out_html;
		}
		
		global $anaglyph_config, $wpdb;
		
		$is_closed = true;
		$cnt = 1;
		$out_ = $out_fields_ = $is_row_class = $scroll_reveal = '';
		
		if ($anaglyph_config['contact-animations']) {
			$scroll_reveal = 'data-scroll-reveal="enter right and move 50px"';
		}
			
		
		
		if (!empty($anaglyph_config['contact-section-cform']) && ($anaglyph_config['contact-section-cform'] > 0)) {
		   $form_id = $anaglyph_config['contact-section-cform'];
		   $out_ = do_shortcode( '[contact-form-7 id="'.$form_id.'" title="'.get_the_title($form_id).'"]' );
				
		} else {
		
			$fields_html = array();
			$fields = $anaglyph_config['contact-form-fields'];
		
			if (!empty($fields)) {
				foreach ($fields['enabled'] as $key=>$value) {
					switch($key) {
						case 'name': $fields_html[] = array('col' => 'col-md-6',  'html' => get_control_group_html('<label for="cff-name" class="screen-reader-text">'.__('Name', 'anaglyph-lite').'</label><input type="text" name="cff-name" id="cff-name" placeholder="'.__('Name', 'anaglyph-lite').'" required>'));
						break;
						case 'email': $fields_html[] = array('col' => 'col-md-6', 'html' => get_control_group_html('<label for="cff-email" class="screen-reader-text">'.__('E-mail', 'anaglyph-lite').'</label><input type="email" name="cff-email" id="cff-email" placeholder="'.__('E-mail', 'anaglyph-lite').'" required>'));
						break;
						case 'phone': $fields_html[] = array('col' => 'col-md-6', 'html' => get_control_group_html('<label for="cff-phone" class="screen-reader-text">'.__('Phone', 'anaglyph-lite').'</label><input type="tel" name="cff-phone" id="cff-phone" placeholder="'.__('Phone', 'anaglyph-lite').'" required>'));
						break;
						case 'mob': $fields_html[]   = array('col' => 'col-md-6',  'html' => get_control_group_html('<label for="cff-mobile" class="screen-reader-text">'.__('Mobile', 'anaglyph-lite').'</label><input type="tel" name="cff-mobile" id="cff-mobile" placeholder="'.__('Mobile', 'anaglyph-lite').'">'));
						break;
						case 'captcha': {
							$fields_html[] = array('col' => 'col-md-12', 
												  'html' => '<div class="row"><div class="col-md-2 col-md-offset-4">' . anaglyph_get_captcha_html() . '</div><div class="col-md-6">'.get_control_group_html('<label for="form_captcha" class="screen-reader-text">'.__('Please enter Captcha symbols', 'anaglyph-lite').'</label><input type="text" maxlength="6" name="form_captcha" id="form_captcha" placeholder="'.__('Please enter Captcha symbols', 'anaglyph-lite').'" required>') . '</div></div>');
						}
						break;  
						case 'message': $fields_html[] = array('col' => 'col-md-12', 'html' => get_control_group_html('<label for="cff-message" class="screen-reader-text">'.__('Message', 'anaglyph-lite').'</label><textarea name="cff-message" id="cff-message" placeholder="'.__('Message', 'anaglyph-lite').'" required></textarea>'));
						break;  
					}
				}
			}	
			
			$out_ = '<div class="contact-form" '.$scroll_reveal.'>';
				$out_ .= '<form class="footer-form" id="contactform" method="post" action="#">';
					if (!empty($fields_html)) {
						foreach ($fields_html as $key => $value) {
							$col_class =  $value['col'];
							$html_ 	   =  $value['html'];
							
							if ($col_class != $is_row_class) {
								$is_row_class = $col_class;
								if(!$is_closed) { 
									$out_fields_ .= '</div>';
									$is_closed = true;
									$cnt = 1;
								}	
							}
							
							if ($cnt%2 == 1)  {
								$out_fields_ .= '<div class="row">';
								$is_closed = false;
							}
							$out_fields_ .= '<div class="'.$col_class.'">'.$html_.'</div>';
							
							if ($cnt%2 == 0) {
								$out_fields_ .= '</div>';
								$is_closed = true;
							}
							
							$cnt++;
							
						}
						if (!$is_closed) $out_fields_ .= '</div>';
					}	
					
					$out_ .= $out_fields_;
					$out_ .= '<div class="form-actions pull-right">';
						if (!empty($anaglyph_config['contact-submit']))
							$out_ .= '<input type="submit" class="btn btn-color-primary" id="cff-submit" value="'.$anaglyph_config['contact-submit'].'">';
						else
							$out_ .= '<input type="submit" class="btn btn-color-primary" id="cff-submit" value="'.__('Send a Message', 'anaglyph-lite').'">';
					$out_ .= '</div><!-- /.form-actions -->';
					
					if (!empty($anaglyph_config['contact-description'])) $out_ .= '<span class="pull-left form-description">'.$anaglyph_config['contact-description'].'</span>';
					
					$out_ .= '<div id="form-status"></div>';
				$out_ .= '</form><!-- /.footer-form -->';
			$out_ .= '</div>';
			
		}
		
		return $out_;		
	}
}

if ( ! function_exists( 'anaglyph_get_footer_contact_form' ) ) {
add_action('custom_footer_elements', 'anaglyph_get_footer_contact_form', 1);
function anaglyph_get_footer_contact_form() {
	global $anaglyph_config;
	$out_ = '';
	$class_title = $title = $sub_title = $name_company = $location = $skype = $scroll_reveal = '';
	$tel = $fax = '';
	
	if ($anaglyph_config['contact-animations']) {
		$scroll_reveal = 'data-scroll-reveal="enter left and move 50px"';
	}
	
	$email = get_option('admin_email');
	
	if (!empty($anaglyph_config['contact-stitle']))  { $title = esc_attr($anaglyph_config['contact-stitle']); }	
	if (!empty($anaglyph_config['contact-satitle'])) { $sub_title = esc_attr($anaglyph_config['contact-satitle']); }
	if (!empty($anaglyph_config['contact-location'])) { $name_company = esc_attr($anaglyph_config['contact-location']); }	
	if (!empty($anaglyph_config['contact-address'])) { $location = anaglyph_kses_data(stripslashes($anaglyph_config['contact-address'])); }	
	if (!empty($anaglyph_config['contact-tel'])) { $tel = esc_attr($anaglyph_config['contact-tel']); }	
	if (!empty($anaglyph_config['contact-fax'])) { $fax = esc_attr($anaglyph_config['contact-fax']); }	
	if (!empty($anaglyph_config['contact-email'])) { $email = esc_attr($anaglyph_config['contact-email']); }	
	if (!empty($anaglyph_config['contact-skype'])) { $skype = esc_attr($anaglyph_config['contact-skype']); }	
	

	$out_ .= '<section id="nav-contact-us" class="block">';
		$out_ .= '<div class="container">';
			$out_ .= '<div class="row">';
				$out_ .= '<div class="col-md-12">';
					$out_ .='<div class="section-content">';
						
						/*Title*/
						if (!empty($title)) { 
							$out_ .='<div class="center">';
							$out_ .='<div class="section-title">';

							if (!empty($sub_title)) {
								$class_title = 'has-subtitle';
							}
								$out_ .='<h3 class="font-color-light '.$class_title.'">'.$title.'</h2>';
								if ($class_title != '') {
									$out_ .='<h4 class="has-opacity font-color-light additional">'.$sub_title.'</h3>';
								}	
								
							$out_ .='</div><!-- /.section-title -->';
							$out_ .='</div><!-- /.center -->';
						}	

						$out_ .='<div class="row">';
							
							/*Address*/
							$out_ .='<div class="col-md-4 col-sm-4">';
                                $out_ .='<div class="address" '.$scroll_reveal.'>';
                                    $out_ .='<address>';
                                        $out_ .='<dl>';
                                            if (!empty($name_company)) {
												$out_ .='<dt><i class="icon icon_pin"></i></dt>';
												$out_ .='<dd><strong>'.$name_company.'</strong>';
													$out_ .='<p>'.nl2br($location).'</p>';
												$out_ .='</dd>';
											}
                                            
											if (!empty($tel)) {
												$out_ .='<dt><i class="icon icon_mobile"></i></dt>';
												$out_ .='<dd>'.$tel.'</dd>';
											}
											
											if (!empty($fax)) {
												$out_ .='<dt><i class="icon icon_phone"></i></dt>';
												$out_ .='<dd>'.$fax.'</dd>';
											}	
											
											if (!empty($email)) {
												$out_ .='<dt><i class="icon icon_mail"></i></dt>';
												$out_ .='<dd><a href="mailto:'.$email.'">'.$email.'</a></dd>';
											}	
                                            
											if (!empty($skype)) {
												$out_ .='<dt><i class="icon social_skype"></i></dt>';
												$out_ .='<dd><a title="Call to '.$skype.'" href="skype:'.$skype.'?call">'.$skype.'</a></dd>';
											}	
                                        $out_ .='</dl>';
                                    $out_ .='</address>';
                                $out_ .='</div><!-- /.address -->';
                            $out_ .='</div>';
							/*End address*/
							
							/*Contact form*/
							$out_ .= '<div class="col-md-8 col-sm-8">';
								$out_ .= anaglyph_get_contact_form();
							$out_ .= '</div>';
							/*End contact form*/
							
						$out_ .= '</div><!-- /.row -->';
					$out_ .= '</div><!-- /.section-content -->';
				$out_ .= '</div><!-- /.col-md-12 -->';
			$out_ .= '</div><!-- /.row -->';
		$out_ .= '</div><!-- /.container -->';
		
		$out_ .= '<div class="background">';
		if (!empty($anaglyph_config['contact-simage']['url'])) {
			$out_ .= '<div style="background-image:url('.esc_url($anaglyph_config['contact-simage']['url']).')" class="parallax-background '.esc_attr($anaglyph_config['contact-sopacity']).'" data-center="background-position: 50% 0px;" data-top-bottom="background-position: 50% -50px;" data-bottom-top="background-position: 50% 50px;"></div>';
		}
		$out_ .='</div>';
	$out_ .='</section><!-- /#footer-top -->';
	
	if (!empty($anaglyph_config['contact-information'])) {
		echo $out_;
	}	
}
}

if ( ! function_exists( 'anaglyph_get_social' ) ) {
add_action('custom_footer_elements', 'anaglyph_get_social', 2);
function anaglyph_get_social() {
	global $anaglyph_config, $anaglyph_is_redux_active;
	$ftext = $fsocial = $out_ftext = ''; 
	
	if (!empty($anaglyph_config['footer-text'])) {
		$ftext = anaglyph_kses_data(stripslashes($anaglyph_config['footer-text']));
	} elseif (!$anaglyph_is_redux_active) {
		$ftext = '&#169; <a title="WordPress Development" href="https://github.com/fruitfulcode/">Fruitful Code</a>, Powered by <a href="http://wordpress.org/">WordPress</a>';
	}
		
		if (is_home() || is_front_page()) {
			$out_ftext .= $ftext;
		} else {
			$out_ftext .= '<nofollow>';
				$out_ftext .= $ftext;
			$out_ftext .= '</nofollow>';
			
		}
	
	if (!empty($anaglyph_config['footer-issocial'])) {
		if ($anaglyph_config['footer-issocial']) {
			$fsocial .= '<div class="social pull-right">';
                $fsocial .= '<div class="icons">';
					if (!empty($anaglyph_config['facebook-url'])) 	{ $fsocial .= '<a title="Facebook" 	href="'.esc_url($anaglyph_config['facebook-url']).'"><i class="icon social_facebook"></i></a>'; }	
					if (!empty($anaglyph_config['twitter-url'])) 	{ $fsocial .= '<a title="Twitter" 	href="'.esc_url($anaglyph_config['twitter-url']).'"><i class="icon social_twitter"></i></a>'; }	
					if (!empty($anaglyph_config['linkedin-url'])) 	{ $fsocial .= '<a title="Linked In" href="'.esc_url($anaglyph_config['linkedin-url']).'"><i class="icon social_linkedin"></i></a>'; }	
					if (!empty($anaglyph_config['myspace-url'])) 	{ $fsocial .= '<a title="My space" 	href="'.esc_url($anaglyph_config['myspace-url']).'"><i class="icon social_myspace"></i></a>'; }	
					if (!empty($anaglyph_config['gplus-url'])) 		{ $fsocial .= '<a title="Google+" 	href="'.esc_url($anaglyph_config['gplus-url']).'"><i class="icon social_googleplus"></i></a>'; }	
					if (!empty($anaglyph_config['dribbble-url'])) 	{ $fsocial .= '<a title="Dribble" 	href="'.esc_url($anaglyph_config['dribbble-url']).'"><i class="icon social_dribbble"></i></a>';	}						
					if (!empty($anaglyph_config['flickr-url'])) 	{ $fsocial .= '<a title="Flickr" 	href="'.esc_url($anaglyph_config['flickr-url']).'"><i class="icon social_flickr"></i></a>'; }						
					if (!empty($anaglyph_config['youtube-url'])) 	{ $fsocial .= '<a title="YouTube" 	href="'.esc_url($anaglyph_config['youtube-url']).'"><i class="icon social_youtube"></i></a>'; }						
					if (!empty($anaglyph_config['delicious-url'])) 	{ $fsocial .= '<a title="Delicious" href="'.esc_url($anaglyph_config['delicious-url']).'"><i class="icon social_delicious"></i></a>'; }						
					if (!empty($anaglyph_config['deviantart-url']))	{ $fsocial .= '<a title="Deviantart" href="'.esc_url($anaglyph_config['deviantart-url']).'"><i class="icon social_deviantart"></i></a>'; }						
					if (!empty($anaglyph_config['rss-url'])) 		{ $fsocial .= '<a title="RSS" 		href="'.esc_url($anaglyph_config['rss-url']).'"><i class="icon social_rss"></i></a>'; }						
					if (!empty($anaglyph_config['instagram-url']))  { $fsocial .= '<a title="Instagram" href="'.esc_url($anaglyph_config['instagram-url']).'"><i class="icon social_instagram"></i></a>'; }						
					if (!empty($anaglyph_config['pinterest-url']))  { $fsocial .= '<a title="Pinterset" href="'.esc_url($anaglyph_config['pinterest-url']).'"><i class="icon social_pinterest"></i></a>'; }						
					if (!empty($anaglyph_config['vimeo-url'])) 		{ $fsocial .= '<a title="Vimeo" 	href="'.esc_url($anaglyph_config['vimeo-url']).'"><i class="icon social_vimeo"></i></a>'; }						
					if (!empty($anaglyph_config['picassa-url'])) 	{ $fsocial .= '<a title="Picassa" 	href="'.esc_url($anaglyph_config['picassa-url']).'"><i class="icon social_picassa"></i></a>'; }						
					if (!empty($anaglyph_config['social_tumblr']))	{ $fsocial .= '<a title="Tumblr" 	href="'.esc_url($anaglyph_config['social_tumblr']).'"><i class="icon social_tumblr"></i></a>'; }						
					if (!empty($anaglyph_config['email-address']))  { $fsocial .= '<a title="Email" 	href="mailto:'.esc_attr($anaglyph_config['email-address']).'"><i class="icon icon_mail_alt"></i></a>'; }						
					if (!empty($anaglyph_config['skype-username'])) { $fsocial .= '<a title="Call to '.esc_attr($anaglyph_config['skype-username']).'" href="href="skype:'.esc_attr($anaglyph_config['skype-username']).'?call"><i class="icon social_skype"></i></a>'; }						
                $fsocial .= '</div><!-- /.icons -->';
            $fsocial .= '</div><!-- /.social -->';
		}
	}
	
	$out_ = '';
	$out_ = '<section id="footer-bottom">';
        $out_ .= '<div class="container">';
            if (!empty($out_ftext)) {
				$out_ .= '<div class="copyright pull-left">'.$out_ftext.'</div><!-- /.copyright -->';
			} else {
				$out_ .= '<div class="copyright pull-left"><a title="'.get_bloginfo('name').'" href="'.site_url().'">'.$out_ftext.'</a></div><!-- /.copyright -->';
			}
			
			if ($fsocial != '') {
				$out_ .= $fsocial;
			}
        $out_ .= '</div><!-- /.container -->';
        
		$out_ .= '<div class="background">';
		if (!empty($anaglyph_config['footer-image']['url'])) {
			$out_ .= '<div style="background-image:url('.esc_url($anaglyph_config['footer-image']['url']).')" class="parallax-background '.esc_attr($anaglyph_config['opacity-fsection']).'" data-center="background-position: 50% 0px;" data-top-bottom="background-position: 50% -50px;" data-bottom-top="background-position: 50% 50px;"></div>';
		}
		$out_ .='</div>';
    $out_ .= '</section><!-- /.footer-bottom -->';
	
	echo $out_;
}
}

/*Generate page*/
add_action('anaglyph_before_page_content', 'anaglyph_get_page_title',  1);
add_action('anaglyph_before_page_content', 'anaglyph_add_breadcrumbs', 2);
if ( ! function_exists( 'anaglyph_get_page_title' ) ) {
function anaglyph_get_page_title() {
	global $anaglyph_config
?>
	<!-- Page Title -->
	<section id="page-title">
		<?php 
			$title = get_the_title();
			if (!empty($title)) { ?>
			<div class="title">
				<h1 class="reset-margin"><?php the_title();?></h1>
			</div>
		<?php } ?>	
		<?php if ( has_post_thumbnail()) { 
			global $post;
			$title_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full');
			echo '<img src="'.$title_thumbnail[0].'" class="parallax-bg" alt="">';
			} else {
				anaglyph_custom_image('simple-page');
			}
		?>
	</section>
	<!-- end Page Title -->
<?php	
}	
}

if ( ! function_exists( 'anaglyph_get_post_additional_title' ) ) {
function anaglyph_get_post_additional_title() {
	global $anaglyph_config, $prefix;
	$add_title 	= get_post_meta(get_the_ID() , $prefix . 'image_title_text', true);	
	$add_img 	= get_post_meta(get_the_ID() , $prefix . 'image_title_img', true);	
	$def_img 	= esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg');
?>
	<!-- Page Title -->
	<section id="page-title">
		<?php 
			if (trim($add_title) != '') { 
		?>
			<div class="title">
				<h1 class="reset-margin"><?php echo $add_title; ?></h1>
			</div>
		
		<?php } ?>
		<?php if ( !empty($add_img)) { 
				echo '<img src="'.esc_url($add_img).'" class="parallax-bg" alt="">';
			  } else {	
				anaglyph_custom_image('simple-post');
			  }
		?>
	</section>
	<!-- end Page Title -->
<?php	
}	
}

if ( ! function_exists( 'anaglyph_visibilty_comments' ) ) {
add_action('anaglyph_comments_template', 'anaglyph_visibilty_comments');
function anaglyph_visibilty_comments() {
	global $anaglyph_config, $post, $anaglyph_is_redux_active;
	
	if (!empty($anaglyph_config['pp-comments']) || !$anaglyph_is_redux_active) {
		$is_comment = $anaglyph_config['pp-comments'];
		if ( ( $is_comment == 'page' || $is_comment == 'both' || !$anaglyph_is_redux_active ) && is_page() ) { 
			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) { comments_template(); }
		}	
		
		if ( ( $is_comment == 'post' || $is_comment == 'both' || !$anaglyph_is_redux_active ) && is_single() ) { 
			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) { comments_template(); }
		}	
	}	
}
}

if ( ! function_exists( 'anaglyph_breadcrumbs_generate' ) ) {
function anaglyph_breadcrumbs_generate($args = array()) {
	global $wp_query, 
		   $wp_rewrite;
	
	$breadcrumb = '';
	$trail = array();
	
	$path = '';
	$defaults = array(
		'separator' 	  => '',
		'before' 		  => false,
		'after'  		  => false,
		'front_page' 	  => true,
		'show_home' 	  => __( 'Home', 'anaglyph-lite' ),
		'echo' 			  => true, 
		'show_posts_page' => true
	);


	if ( is_singular() )$defaults["singular_{$wp_query->post->post_type}_taxonomy"] = false;

	extract( wp_parse_args( $args, $defaults ) );

	if ( !is_front_page() && $show_home )
		$trail[] = '<li><a href="' . esc_url( home_url() ) . '" title="' . esc_attr( get_bloginfo( 'name' ) ) . '" rel="home" class="trail-begin">' . esc_html( $show_home ) . '</a></li>';

	/* If viewing the front page of the site. */
	if ( is_front_page() ) {
		if ( !$front_page )
			$trail = false;
		elseif ( $show_home )
			$trail['trail_end'] = "{$show_home}";
	}

	/* If viewing the "home"/posts page. */
	elseif ( is_home() ) {
		$home_page = get_page( $wp_query->get_queried_object_id() );
		$trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( $home_page->post_parent, '' ) );
		$trail['trail_end'] = get_the_title( $home_page->ID );
	}

	/* If viewing a singular post (page, attachment, etc.). */
	elseif ( is_singular() ) {
		$post = $wp_query->get_queried_object();
		$post_id = absint( $wp_query->get_queried_object_id() );
		$post_type = $post->post_type;
		$parent = $post->post_parent;
		
		if ( 'page' !== $post_type && 'post' !== $post_type ) {

			$post_type_object = get_post_type_object( $post_type );
			if ( 'post' == $post_type || 'attachment' == $post_type || ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front ) ) $path .= trailingslashit( $wp_rewrite->front );
			if ( !empty( $post_type_object->rewrite['slug'] ) ) $path .= $post_type_object->rewrite['slug'];
			if ( !empty( $path ) && '/' != $path ) $trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( '', $path ) );
			if ( !empty( $post_type_object->has_archive ) && function_exists( 'get_post_type_archive_link' ) ) $trail[] = '<li><a href="' . get_post_type_archive_link( $post_type ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '">' . $post_type_object->labels->name . '</a></li>';
		}

		/* If the post type path returns nothing and there is a parent, get its parents. */
		if ( empty( $path ) && 0 !== $parent || 'attachment' == $post_type ) $trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( $parent, '' ) );

		/* Toggle the display of the posts page on single blog posts. */		
		if ( 'post' == $post_type && $show_posts_page == true && 'page' == get_option( 'show_on_front' ) ) {
			$posts_page = get_option( 'page_for_posts' );
			if ( $posts_page != '' && is_numeric( $posts_page ) ) {
				 $trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( $posts_page, '' ) );
			}
		}

		/* Display terms for specific post type taxonomy if requested. */
		if ( isset( $args["singular_{$post_type}_taxonomy"] ) && $terms = get_the_term_list( $post_id, $args["singular_{$post_type}_taxonomy"], '', ', ', '' ) ) $trail[] = $terms;

		/* End with the post title. */
		$post_title = get_the_title( $post_id ); // Force the post_id to make sure we get the correct page title.
		if ( !empty( $post_title ) ) $trail['trail_end'] = $post_title;
	}

	/* If we're viewing any type of archive. */
	elseif ( is_archive() ) {

		/* If viewing a taxonomy term archive. */
		if ( is_tax() || is_category() || is_tag() ) {

			/* Get some taxonomy and term variables. */
			$term = $wp_query->get_queried_object();
			$taxonomy = get_taxonomy( $term->taxonomy );

			/* Get the path to the term archive. Use this to determine if a page is present with it. */
			if ( is_category() )
				$path = get_option( 'category_base' );
			elseif ( is_tag() )
				$path = get_option( 'tag_base' );
			else {
				if ( $taxonomy->rewrite['with_front'] && $wp_rewrite->front )
					$path = trailingslashit( $wp_rewrite->front );
				$path .= $taxonomy->rewrite['slug'];
			}

			/* Get parent pages by path if they exist. */
			if ( $path )
				$trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( '', $path ) );

			/* If the taxonomy is hierarchical, list its parent terms. */
			if ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent )
				$trail = array_merge( $trail, anaglyph_breadcrumbs_get_term_parents( $term->parent, $term->taxonomy ) );

			/* Add the term name to the trail end. */
			$trail['trail_end'] = $term->name;
		}

		/* If viewing a post type archive. */
		elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {

			/* Get the post type object. */
			$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );

			/* If $front has been set, add it to the $path. */
			if ( $post_type_object->rewrite['with_front'] && $wp_rewrite->front )
				$path .= trailingslashit( $wp_rewrite->front );

			/* If there's a slug, add it to the $path. */
			if ( !empty( $post_type_object->rewrite['archive'] ) )
				$path .= $post_type_object->rewrite['archive'];

			/* If there's a path, check for parents. */
			if ( !empty( $path ) && '/' != $path )
				$trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( '', $path ) );

			/* Add the post type [plural] name to the trail end. */
			$trail['trail_end'] = $post_type_object->labels->name;
		}

		/* If viewing an author archive. */
		elseif ( is_author() ) {

			/* If $front has been set, add it to $path. */
			if ( !empty( $wp_rewrite->front ) )
				$path .= trailingslashit( $wp_rewrite->front );

			/* If an $author_base exists, add it to $path. */
			if ( !empty( $wp_rewrite->author_base ) )
				$path .= $wp_rewrite->author_base;

			/* If $path exists, check for parent pages. */
			if ( !empty( $path ) )
				$trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( '', $path ) );

			/* Add the author's display name to the trail end. */
			$trail['trail_end'] = get_the_author_meta( 'display_name', get_query_var( 'author' ) );
		}

		/* If viewing a time-based archive. */
		elseif ( is_time() ) {

			if ( get_query_var( 'minute' ) && get_query_var( 'hour' ) )
				$trail['trail_end'] = get_the_time( __( 'g:i a', 'anaglyph-lite' ) );

			elseif ( get_query_var( 'minute' ) )
				$trail['trail_end'] = sprintf( __( 'Minute %1$s', 'anaglyph-lite' ), get_the_time( __( 'i', 'anaglyph-lite' ) ) );

			elseif ( get_query_var( 'hour' ) )
				$trail['trail_end'] = get_the_time( __( 'g a', 'anaglyph-lite' ) );
		}

		/* If viewing a date-based archive. */
		elseif ( is_date() ) {

			/* If $front has been set, check for parent pages. */
			if ( $wp_rewrite->front )
				$trail = array_merge( $trail, anaglyph_breadcrumbs_get_parents( '', $wp_rewrite->front ) );

			if ( is_day() ) {
				$trail[] = '<li><a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'anaglyph-lite' ) ) . '">' . get_the_time( __( 'Y', 'anaglyph-lite' ) ) . '</a></li>';
				$trail[] = '<li><a href="' . get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) . '" title="' . get_the_time( esc_attr__( 'F', 'anaglyph-lite' ) ) . '">' . get_the_time( __( 'F', 'anaglyph-lite' ) ) . '</a></li>';
				$trail['trail_end'] = get_the_time( __( 'j', 'anaglyph-lite' ) ) ;
			}

			elseif ( get_query_var( 'w' ) ) {
				$trail[] = '<li><a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'anaglyph-lite' ) ) . '">' . get_the_time( __( 'Y', 'anaglyph-lite' ) ) . '</a></li>';
				$trail['trail_end'] = sprintf( __( 'Week %1$s', 'anaglyph-lite' ), get_the_time( esc_attr__( 'W', 'anaglyph-lite' ) ) );
			}

			elseif ( is_month() ) {
				$trail[] = '<li><a href="' . get_year_link( get_the_time( 'Y' ) ) . '" title="' . get_the_time( esc_attr__( 'Y', 'anaglyph-lite' ) ) . '">' . get_the_time( __( 'Y', 'anaglyph-lite' ) ) . '</a></li>';
				$trail['trail_end'] = get_the_time( __( 'F', 'anaglyph-lite' ) );
			}

			elseif ( is_year() ) {
				$trail['trail_end'] = get_the_time( __( 'Y', 'anaglyph-lite' ) ) ;
			}
		}
	}

	/* If viewing search results. */
	elseif ( is_search() )
		$trail['trail_end'] = '<li>' . sprintf( __( 'Search results for &quot;%1$s&quot;', 'anaglyph-lite' ), esc_attr( get_search_query() ) ) . '</li>';

	/* If viewing a 404 error page. */
	elseif ( is_404() )
		$trail['trail_end'] =  __( '404 Not Found', 'anaglyph-lite' );

	/* Connect the breadcrumb trail if there are items in the trail. */
	if ( is_array( $trail ) ) {

		/* If $before was set, wrap it in a container. */
		if ( !empty( $before ) )
			$breadcrumb .= '<span class="trail-before">' . wp_kses_post( $before ) . '</span> ';

		/* Wrap the $trail['trail_end'] value in a container. */
		if ( !empty( $trail['trail_end'] ) && !is_search() )
			$trail['trail_end'] = '<li class="active"><span class="trail-end">' . wp_kses_post( $trail['trail_end'] ) . '</span></li>';

		/* Format the separator. */
		if ( !empty( $separator ) )
			$separator = '<li><span class="sep">' . wp_kses_post( $separator ) . '</span></li>';

		/* Join the individual trail items into a single string. */
		$breadcrumb .= join( " {$separator} ", $trail );

		/* If $after was set, wrap it in a container. */
		if ( !empty( $after ) )
			$breadcrumb .= '<li><span class="trail-after">' . wp_kses_post( $after ) . '</span></li>';

		/* Close the breadcrumb trail containers. */
	}

	
	$breadcrumb = '<!-- Breadcrumb --><section id="breadcrumb"><div class="container"><nav class="nav-breadcrumb" role="navigation" aria-label="'.__( 'Breadcrumb Navigation', 'anaglyph-lite' ).'" ><ol class="breadcrumb">' . $breadcrumb . '</ol></nav></div></section>';

	/* Output the breadcrumb. */
	if ( $echo ) echo $breadcrumb; else return $breadcrumb;
} 
}

if ( ! function_exists( 'anaglyph_breadcrumbs_get_parents' ) ) {
function anaglyph_breadcrumbs_get_parents( $post_id = '', $path = '' ) {
	$trail = array();

	if ( empty( $post_id ) && empty( $path ) ) return $trail;
	if ( empty( $post_id ) ) {
		$parent_page = get_page_by_path( $path );
		if( empty( $parent_page ) ) $parent_page = get_page_by_title ( $path );
		if( empty( $parent_page ) ) $parent_page = get_page_by_title ( str_replace( array('-', '_'), ' ', $path ) );
		if ( !empty( $parent_page ) ) $post_id = $parent_page->ID;
	}

	if ( $post_id == 0 && !empty( $path ) ) {
		$path = trim( $path, '/' );
		preg_match_all( "/\/.*?\z/", $path, $matches );
		if ( isset( $matches ) ) {
			$matches = array_reverse( $matches );
			foreach ( $matches as $match ) {
				if ( isset( $match[0] ) ) {
					$path = str_replace( $match[0], '', $path );
					$parent_page = get_page_by_path( trim( $path, '/' ) );
					if ( !empty( $parent_page ) && $parent_page->ID > 0 ) {
						$post_id = $parent_page->ID;
						break;
					}
				}
			}
		}
	}
	
	while ( $post_id ) {
			$page = get_page( $post_id );
			$parents[]  = '<li><a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . esc_html( get_the_title( $post_id ) ) . '</a></li>';
			$post_id = $page->post_parent;
	}

	if ( isset( $parents ) ) $trail = array_reverse( $parents );
	return $trail;
} 
}

if ( ! function_exists( 'anaglyph_breadcrumbs_get_term_parents' ) ) {
function anaglyph_breadcrumbs_get_term_parents( $parent_id = '', $taxonomy = '' ) {
	$trail = array();
	$parents = array();

	if ( empty( $parent_id ) || empty( $taxonomy ) ) return $trail;
	while ( $parent_id ) {
		$parent = get_term( $parent_id, $taxonomy );
		$parents[] = '<li><a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( $parent->name ) . '">' . $parent->name . '</a></li>';
		$parent_id = $parent->parent;
	}

	if ( !empty( $parents ) ) $trail = array_reverse( $parents );
	return $trail;
} 
}

if ( ! function_exists( 'anaglyph_add_breadcrumbs' ) ) {
function anaglyph_add_breadcrumbs() {
	global $anaglyph_config, $anaglyph_is_redux_active;
	if (!empty($anaglyph_config['pp-breadcrumbs']) || !$anaglyph_is_redux_active) {
		if ($anaglyph_config['pp-breadcrumbs'] || !$anaglyph_is_redux_active) {
			anaglyph_breadcrumbs_generate();
		}
	}
}
}

/*Blog posts*/
if ( ! function_exists( 'anaglyph_get_post_date' ) ) {
	function anaglyph_get_post_date() {
		global $anaglyph_config, $post, $anaglyph_is_redux_active;
		$day_ 	= get_the_date('d');
		$month_ = get_the_date('M');
		
		if (!empty($anaglyph_config['pp-date']) || !$anaglyph_is_redux_active) {
			if ($anaglyph_config['pp-date'] || !is_single() || !$anaglyph_is_redux_active) {
				
		
	?>	
		<div class="date-circle">
			<div class="date-circle-content">
				<div class="day"><?php echo $day_;?></div>
				<div class="month"><?php echo $month_; ?></div>
			</div>
		</div>
	<?php	
			}
		}
	}
}

if ( ! function_exists( 'anaglyph_get_post_info' ) ) {
	function anaglyph_get_post_info() {
		global $post, $anaglyph_config, $anaglyph_is_redux_active;
		$comments_class = array();
		$comments_class[] = 'post-comments';
		
	?>
			<?php if (!empty($anaglyph_config['pp-authors']) || !$anaglyph_is_redux_active) { ?>
				<div class="post-author">
					<i class="icon icon_profile"></i>
					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo get_the_author_link(); ?></a>	
				</div>
			<?php } else {
					$comments_class[] = 'with-margin';
				  }
			?>	
		
			<?php 
				if (!empty($anaglyph_config['pp-num-comments']) || !$anaglyph_is_redux_active) { 
					$comments = 0;
					$comments_count  = wp_count_comments($post->ID);
					$comments = (int) $comments_count->total_comments;
			?>
				<div class="<?php echo implode(' ', $comments_class); ?>">
					<i class="icon icon_comment_alt"></i>
					<span><?php echo $comments; ?></span>
				</div>
			<?php } ?>	
	
		<?php
	}
}

if ( ! function_exists( 'anaglyph_get_post_share' ) ) {
	function anaglyph_get_post_share() {
		global $anaglyph_config, $post, $anaglyph_is_redux_active;
		if (!empty($anaglyph_config['pp-share']) || !$anaglyph_is_redux_active) {
			if ($anaglyph_config['pp-share'] || !$anaglyph_is_redux_active) {
			$src = '';
			$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), false, '' );	
			if (!empty($src)) {
				$src = $src[0];
			}	
	?>
			<div class="social">
				<div class="icons">
					<span><?php _e('Share this post:', 'anaglyph-lite'); ?></span>
					<a title="Twitter" 		href="https://twitter.com/share?url=<?php the_permalink(); ?>"><i class="icon social_twitter"></i></a>
					<a title="Facebook" 	href="http://www.facebook.com/sharer.php?u<?php the_permalink(); ?>"><i class="icon social_facebook"></i></a>
					<a title="Pinterest" 	href="//pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php echo $src[0]; ?>&description=<?php the_title(); ?>"><i class="icon social_pinterest"></i></a>
					<a title="Google +" 	href="https://plus.google.com/share?url={URL}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="icon social_googleplus"></i></a>
				</div>
			</div>
	<?php
			}
		}	
	}
}

if ( ! function_exists( 'get_sidebar_part' ) ) {
	function get_sidebar_part($sidebar_template = '') {
		?>
		<div class="sidebar">
			<?php get_sidebar($sidebar_template); ?>
		</div>
		<?php
	}
}

/*Single template layouts*/
if ( ! function_exists( 'anaglyph_get_single_content' ) ) {
	function anaglyph_get_single_content() {
		global $anaglyph_config, $post, $anaglyph_is_redux_active;
			   $post_layout = (int) $anaglyph_config['pp-post'];
			
			if (!$anaglyph_is_redux_active)
				$post_layout = 3;
		
		function get_content_part() {
			?>
			<div id="content" role="main">
				<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();
						get_template_part( 'content', get_post_format() );
						// If comments are open or we have at least one comment, load up the comment template.
						do_action('anaglyph_comments_template');
					endwhile;
				?>
			</div>
			<?php						
		}

		if ($post_layout == 1) {
			get_content_part();
		} else if ($post_layout == 2) {
			echo '<div class="row">';
				echo '<div class="col-md-4">';
					get_sidebar_part('single');					
				echo '</div>';
				echo '<div class="col-md-8">';
					get_content_part();					
				echo '</div>';
			echo '</div>';
		} else if ($post_layout == 3) {
			echo '<div class="row">';
				echo '<div class="col-md-8">';
					get_content_part();					
				echo '</div>';
				echo '<div class="col-md-4">';
					get_sidebar_part('single');					
				echo '</div>';
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'anaglyph_get_blog_content_part' ) ) {
	function anaglyph_get_blog_content_part() {
		global $post, $posts_count, $time_post_delay, $row_heading;
		
		?>
			<div class="col-md-12">
				<div class="section-content">
					<div class="row">
						<div class="blog-posts">
						<?php
							if ( have_posts() ) {
								if (is_author()) rewind_posts();
								while ( have_posts() ) : the_post();
									get_template_part( 'content', get_post_format() );
									$time_post_delay += 0.2;
									$posts_count++;
								endwhile;
								if (!$row_heading) echo '</div>';
							} else {
								get_template_part( 'content', 'none' );
							}
						?>
						</div>
					</div>	
					<?php anaglyph_paging_nav(); ?>
				</div>	
			</div>	
		<?php
	}
}

/*Default page conent parts (seacrh, category, etc)*/
if ( ! function_exists( 'anaglyph_default_page_content' ) ) {
	function anaglyph_default_page_content() {
		global $anaglyph_config, $post, $anaglyph_is_redux_active, $posts_count, $time_post_delay, $row_heading;
		
		$blog_layout = (int) $anaglyph_config['pp-blog'];
			
		if (!$anaglyph_is_redux_active)
			$blog_layout = 1;
		
	?>	
		<section id="content" class="block" role="main">
			<div class="container">
				<?php 
					if ($blog_layout == 1) {
						anaglyph_get_blog_content_part();
					} else if ($blog_layout == 2) {
						echo '<div class="row">';
							echo '<div class="col-md-4">';
								get_sidebar_part('blog');					
							echo '</div>';
							echo '<div class="col-md-8">';
								anaglyph_get_blog_content_part();					
							echo '</div>';
						echo '</div>';
					} else if ($blog_layout == 3) {
						echo '<div class="row">';
							echo '<div class="col-md-8">';
								anaglyph_get_blog_content_part();					
							echo '</div>';
							echo '<div class="col-md-4">';
								get_sidebar_part('blog');					
							echo '</div>';
						echo '</div>';
					}
				?>
			</div>	
		</section>	
	<?php 
	}	
}			

add_action('anaglyph_post_meta', 'anaglyph_get_post_thumbnail', 1);
add_action('anaglyph_post_meta', 'anaglyph_get_post_title', 	2);
add_action('anaglyph_post_meta', 'anaglyph_get_post_author', 	3);
add_action('anaglyph_post_meta', 'anaglyph_get_post_tags', 		4);

if ( ! function_exists( 'anaglyph_get_post_thumbnail' ) ) {
	function anaglyph_get_post_thumbnail() {
		global $anaglyph_config, $post;
		if (!empty($anaglyph_config['pp-thumbnail'])) {
			if ($anaglyph_config['pp-thumbnail'] || !is_single()) {
				if ( has_post_thumbnail()) {
						$thumb_id  = get_post_thumbnail_id($post->ID); 
						$alt_text  = get_post_meta($thumb_id , '_wp_attachment_image_alt', true);
					?>
				
					<a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php echo get_the_post_thumbnail(); ?></a>
			<?php
				}	
			}
		}	
	}
}	
	
if ( ! function_exists( 'anaglyph_get_post_title' ) ) {	
	function anaglyph_get_post_title() {
		global $anaglyph_config;
	?>
		<h2><a title="<?php the_title(); ?>" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	<?php	
	}
}

if ( ! function_exists( 'anaglyph_get_post_author' ) ) {	
	function anaglyph_get_post_author() {
		global $anaglyph_config, $anaglyph_is_redux_active;
		if (!empty($anaglyph_config['pp-authors']) || !$anaglyph_is_redux_active) {
			if ($anaglyph_config['pp-authors'] || !$anaglyph_is_redux_active) {
	?>
			<div class="author"><?php _e('By:', 'anaglyph-lite'); ?>&nbsp;<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>"><?php echo get_the_author_link(); ?></a></div>
	<?php	
			} else {
	?>		
				<div class="no-author"></div>
	<?php
			}
		} 
	}
}

if ( ! function_exists( 'anaglyph_get_post_tags' ) ) {	
	function anaglyph_get_post_tags() {
		global $anaglyph_config, $anaglyph_is_redux_active;
		$out_ = '';
		if (!empty($anaglyph_config['pp-tags']) || !$anaglyph_is_redux_active) {
			if ($anaglyph_config['pp-tags'] || !$anaglyph_is_redux_active) {
				$posttags = get_the_tags();
				if ($posttags) {
					$out_ .= '<div class="tags">';
						if (is_single()) {
							$out_ .= '<span>' . __('Tags:', 'anaglyph-lite') . '</span>';
						}
						foreach($posttags as $tag) {
							$out_ .= '<a href="'.get_tag_link($tag->term_id).'"><div class="tag">'.$tag->name.'</div></a>';
						}
					$out_ .= '</div>';	
					echo $out_;
				} else {
					echo '<div class="no-tags"></div>';
				}
			}
		}
	}
}

if ( ! function_exists( 'anaglyph_get_post_readmore' ) ) {	
	function anaglyph_get_post_readmore() {
	?>
		<div class="divider background-color-secondary"></div>
		<div class="read-more">
			<a title="<?php echo __('Read', 'anaglyph-lite') .' '.esc_html(get_the_title()); ?>" href="<?php the_permalink(); ?>"><?php _e('Read More', 'anaglyph-lite'); ?><span class="screen-reader-text"> <?php echo get_the_title(); ?></span></a>
		</div>
	<?php	
	}										
}

if ( ! function_exists( 'anaglyph_custom_image' ) ) {											
	function anaglyph_custom_image($filed = null) {
		global $anaglyph_config;
		if ( !empty($anaglyph_config[$filed])) { 
			$simg = $anaglyph_config[$filed];
			echo '<img src="'.esc_url($simg['url']).'" class="parallax-bg" alt="">';
		}	
	}
}

if ( ! function_exists( 'anaglyph_post_column_before_classes' ) ) {												
	function anaglyph_post_column_before_classes() {
		global $anaglyph_config, $posts_count, $row_heading, $anaglyph_is_redux_active;			
			   $post_before = null;
		if (!is_single()) {
			if (!empty($anaglyph_config['pp-columns'])) {
				$col = (int) esc_attr($anaglyph_config['pp-columns']);
			} elseif (!$anaglyph_is_redux_active) {
				$col = 2;
			}
				
			if ($col > 1) {
				if ($row_heading) {
					echo '<div class="row">'; 
					$row_heading = false;
				}
			}
			
			if ($col == 2) {
				$post_before = '<div class="col-md-6">';
			} else if ($col == 3) {
				$post_before = '<div class="col-md-4">';
			} else if ($col == 4) {
				$post_before = '<div class="col-md-3">';
			} 
		}	
		echo $post_before;
	}
	add_action('anaglyph_before_post_content', 'anaglyph_post_column_before_classes', 1);
}

if ( ! function_exists( 'anaglyph_post_column_after_classes' ) ) {			
	function anaglyph_post_column_after_classes() {
		global $anaglyph_config, $posts_count, $row_heading, $time_post_delay, $anaglyph_is_redux_active;			
		$postsperpage = get_option('posts_per_page');
		$post_after = null;
		if (!is_single()) {
			if (!empty($anaglyph_config['pp-columns'])) {
				$col = (int) esc_attr($anaglyph_config['pp-columns']);
			} elseif (!$anaglyph_is_redux_active) {
				$col = 2;	
			}
			if ($col > 1) {
				$post_after .= '</div>';
				if (($posts_count % $col == 0) || ($posts_count == $postsperpage)) {
					$post_after 	.= '</div>'; 
					$row_heading 	 = true;
					$time_post_delay = -.2;
				}
			} else {
				$time_post_delay = 0.2;
			}
		}
		
		echo $post_after;
	}	
	add_action('anaglyph_after_post_content', 'anaglyph_post_column_after_classes', 99);
}	


if ( ! function_exists( 'anaglyph_add_custom_query' ) ) {			
	function anaglyph_add_custom_query($query) {
		if ($query->is_main_query() && is_home()) {
			$postsperpage = get_option('posts_per_page');
			$sticky_posts = get_option( 'sticky_posts' );
			if (is_array($sticky_posts) && !$query->is_paged()) {
				$sticky_count = count($sticky_posts);
				if ($sticky_count < $postsperpage) {
					$query->set('posts_per_page', $postsperpage - $sticky_count);
				} else {
					$query->set('posts_per_page', 1);
				}
			} else {
				$query->set('posts_per_page', $postsperpage);
			}
		}
	}
	add_action('pre_get_posts', 'anaglyph_add_custom_query');
}


if ( ! function_exists( 'anaglyph_set_excerpt_length' ) ) {				
	function anaglyph_set_excerpt_length( $length ) {
		return 30;
	}
	add_filter( 'excerpt_length', 'anaglyph_set_excerpt_length', 999 );	
}

/*Post password protected*/
if ( ! function_exists( 'anaglyph_password_protect_form' ) ) {				
	function anaglyph_password_protect_form() {
		global $post;
		$out = '';
		$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
		
		$out .= '<form class="protected-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">';
			$out .= '<div class="title">' . __('This is password protected post', 'anaglyph-lite') . '</div>';
			$out .=  '<div class="info">' . __( "This content is password protected. To view it please enter your password below:", 'anaglyph-lite' ) . '</div>';
			
			$out .= '<div class="element-box">';
				$out .= '<label for="post_password" class="screen-reader-text">'.__('Password', 'anaglyph-lite').'</label>';
				$out .= '<input name="post_password" id="'. $label .'" type="password" size="20" maxlength="20" placeholder="'.__('Password', 'anaglyph-lite').'"/>';
				$out .= '<div class="pull-right">';
					$out .= '<input type="submit" name="Submit" class="btn btn-color-primary" value="' . esc_attr__( "Submit", 'anaglyph-lite' ) . '" />';
				$out .= '</div>';
			$out .= '</div>';
		$out .= '</form>';
		
		return $out;
	}
	add_filter( 'the_password_form', 'anaglyph_password_protect_form' );
}

add_filter( "the_content", "anaglyph_post_chat", 99);
if ( ! function_exists( 'anaglyph_post_chat' ) ) {				
	function anaglyph_post_chat($content = null) {
		global $post;
		$format = null;
		if (isset($post)) $format = get_post_format( $post->ID );
		$cnt = 0;
		
		if ($format == 'chat') {
			if (($post->post_type == 'post') && ($format == 'chat')) {
					remove_filter ('the_content',  'wpautop');
					$chatoutput = "<dl class=\"chat\">\n";
					$split = preg_split("/(\r?\n)+|(<br\s*\/?>\s*)+/", $content);
						foreach($split as $haystack) {
							if (strpos($haystack, ":")) {
								$string 	= explode(":", trim($haystack), 2);
								$who 		= strip_tags(trim($string[0]));
								$what 		= strip_tags(trim($string[1]));
								$chatoutput = $chatoutput . "<dt><i class='icon icon_profile'></i><span class='chat-author'><strong>$who:</strong></span></dt><dd>$what</dd>\n";
							}
							else {
								$chatoutput = $chatoutput . $haystack . "\n";
							}
							$cnt++;
							
							if (!is_single()) {
								if ($cnt > 2) break;
							}	
						}
						$content = $chatoutput . "</dl>\n";
						return $content;
			}
		} else {
			return $content;
		}
	}
}


if ( ! function_exists( 'anaglyph_get_delay_interval' ) ) {				
	function anaglyph_get_delay_interval($interval = 0) {
		$time_class = '';		
		if ($interval > 0) {
			$time_class = 'after '.$interval.'s';
		}
		return $time_class;		
	}
}

if ( ! function_exists( 'anaglyph_add_favicon' ) ) {				
	function anaglyph_add_favicon() {
		global $anaglyph_config, $prefix;
		
		if( !empty($anaglyph_config['favicon'])) 				echo '<link rel="shortcut icon" href="' .  	esc_url($anaglyph_config['favicon']['url'])  . '"/>' . "\n";
		if( !empty($anaglyph_config['favicon-iphone'])) 		echo '<link rel="apple-touch-icon" href="'. esc_url($anaglyph_config['favicon-iphone']['url']) .'"> '. "\n"; 
		if( !empty($anaglyph_config['favicon-iphone-retina'])) 	echo '<link rel="apple-touch-icon" sizes="114x114" 	href="'.  esc_url($anaglyph_config['favicon-iphone-retina']['url']) .' "> '. "\n"; 
		if( !empty($anaglyph_config['favicon-ipad'])) 			echo '<link rel="apple-touch-icon" sizes="72x72" 	href="'. esc_url($anaglyph_config['favicon-ipad']['url']) .'"> '. "\n"; 
		if( !empty($anaglyph_config['favicon-ipad-retina']))	echo '<link rel="apple-touch-icon" sizes="144x144" 	href="'. esc_url($anaglyph_config['favicon-ipad-retina']['url'])  .'"> '. "\n";  
	 
	}
	add_action('wp_head', 'anaglyph_add_favicon', 100);
}

if ( ! function_exists( 'anaglyph_img_caption' ) ) {				
	function anaglyph_img_caption( $empty_string, $attributes, $content ) {
		extract(shortcode_atts(array(
			'id' 		=> '',
			'align' 	=> 'alignnone',
			'width' 	=> '',
			'caption' 	=> ''
		), $attributes));
  
		if ( empty($caption) ) return $content;
		if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
		return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" style="width:'.$width.'px;">' . do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
	}
	add_filter( 'img_caption_shortcode', 'anaglyph_img_caption', 10, 3 );
}



/*Compress code*/
if ( ! function_exists( 'anaglyph_compress_code' ) ) {				
	function anaglyph_compress_code($code) {
		$code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $code);
		$code = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $code);
    
		return $code;
	}
}  

if ( ! function_exists( 'anaglyph_blog_post_preview' ) ) {
	function anaglyph_blog_post_preview() {
		global $anaglyph_config;
		if (!empty($anaglyph_config['excerpt'])) {
			if ($anaglyph_config['excerpt'] == 1 ) {
				echo '<div class="blog-post-preview clearfix">';
					the_content( '&#8230;<span class="screen-reader-text">  '.get_the_title().'</span>' );
				echo '</div>';
			}
			if ($anaglyph_config['excerpt'] == 2 ) {
				the_excerpt();
			}
		} else {
			the_excerpt();
		}
	}
}