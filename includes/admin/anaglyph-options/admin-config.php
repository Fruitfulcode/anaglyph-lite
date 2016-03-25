<?php
if (!class_exists('anaglyph_config')) {
	
    class anaglyph_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {
            if (!class_exists('ReduxFramework')) return;
            if (  true == redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }
		
		function disable_redux_notice() {
			echo '<style>.redux-notice, .rAds, .rAds span, #redux_rAds { display: none;}</style>';
		}
		
        public function initSettings() {
            add_action('admin_head', array($this,'disable_redux_notice'));
            $this->theme = wp_get_theme();
			$this->setArguments();
            $this->setHelpTabs();
            $this->setSections();
            if (!isset($this->args['opt_name'])) return;
            add_action( 'anaglyph/loaded', array( $this, 'remove_demo' ) );
			
            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {}

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'anaglyph-lite'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'anaglyph-lite'),
                'icon' => 'el-icon-paper-clip',
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
          //$args['dev_mode'] = true;
            return $args;
        }

        // Remove the demo link and the notice of integrated demo from the Redux-framework plugin
        function remove_demo() {
			// Used to hide the demo mode link from the plugin page. Only used when Anaglyph is a plugin.
			if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
					remove_filter( 'plugin_row_meta', array(
						ReduxFrameworkPlugin::instance(),
						'plugin_metalinks'
					), null, 2 );

					// Used to hide the activation notice informing users of the demo panel. Only used when Anaglyph is a plugin.
					remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
			}
        }
		

        public function setSections() {
            // Background Patterns Reader
            $sample_patterns_url    = get_template_directory_uri() . '/includes/admin/anaglyph-options/patterns/';
            
            ob_start();

            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';
			
			

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'anaglyph-lite'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'anaglyph-lite'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview', 'anaglyph-lite'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'anaglyph-lite'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'anaglyph-lite'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'anaglyph-lite') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'anaglyph-lite') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'anaglyph-lite'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }
			
			$arr_section = array();
			$arr_slider_effects = array(
						"bounce"=>'bounce',
						"flash"=>'flash',
						"pulse"=>'pulse',
						"rubberBand"=>'rubberBand',
						"shake"=>'shake',
						"swing"=>'swing',
						"tada"=>'tada',
						"wobble"=>'wobble',
						"bounceIn"=>'bounceIn',
						"bounceInDown"=>'bounceInDown',
						"bounceInLeft"=>'bounceInLeft',
						"bounceInRight"=>'bounceInRight',
						"bounceInUp"=>'bounceInUp',
						"bounceOut"=>'bounceOut',
						"bounceOutDown"=>'bounceOutDown',
						"bounceOutLeft"=>'bounceOutLeft',
						"bounceOutRight"=>'bounceOutRight',
						"bounceOutUp"=>'bounceOutUp',
						"fadeIn"=>'fadeIn',
						"fadeInDown"=>'fadeInDown',
						"fadeInDownBig"=>'fadeInDownBig',
						"fadeInLeft"=>'fadeInLeft',
						"fadeInLeftBig"=>'fadeInLeftBig',
						"fadeInRight"=>'fadeInRight',
						"fadeInRightBig"=>'fadeInRightBig',
						"fadeInUp"=>'fadeInUp',
						"fadeInUpBig"=>'fadeInUpBig',
						"fadeOut"=>'fadeOut',
						"fadeOutDown"=>'fadeOutDown',
						"fadeOutDownBig"=>'fadeOutDownBig',
						"fadeOutLeft"=>'fadeOutLeft',
						"fadeOutLeftBig"=>'fadeOutLeftBig',
						"fadeOutRight"=>'fadeOutRight',
						"fadeOutRightBig"=>'fadeOutRightBig',
						"fadeOutUp"=>'fadeOutUp',
						"fadeOutUpBig"=>'fadeOutUpBig',
						"flip"=>'flip',
						"flipInX"=>'flipInX',
						"flipInY"=>'flipInY',
						"flipOutX"=>'flipOutX',
						"flipOutY"=>'flipOutY',
						"lightSpeedIn"=>'lightSpeedIn',
						"lightSpeedOut"=>'lightSpeedOut',
						"rotateIn"=>'rotateIn',
						"rotateInDownLeft"=>'rotateInDownLeft',
						"rotateInDownRight"=>'rotateInDownRight',
						"rotateInUpLeft"=>'rotateInUpLeft',
						"rotateInUpRight"=>'rotateInUpRight',
						"rotateOut"=>'rotateOut',
						"rotateOutDownLeft"=>'rotateOutDownLeft',
						"rotateOutDownRight"=>'rotateOutDownRight',
						"rotateOutUpLeft"=>'rotateOutUpLeft',
						"rotateOutUpRight"=>'rotateOutUpRight',
						"hinge"=>'hinge',
						"rollIn"=>'rollIn',
						"rollOut"=>'rollOut',"zoomIn"=>'zoomIn',
						"zoomInDown"=>'zoomInDown',
						"zoomInLeft"=>'zoomInLeft',
						"zoomInRight"=>'zoomInRight',
						"zoomInUp"=>'zoomInUp',
						"zoomOut"=>'zoomOut',
						"zoomOutDown"=>'zoomOutDown',
						"zoomOutLeft"=>'zoomOutLeft',
						"zoomOutRight"=>'zoomOutRight',
						"zoomOutUp"=>'zoomOutUp'
				);
			
			/*General Section*/
			
			$arr_section['general'] = array(
                'title'     => __('General', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/general.png',
				'icon_type'	=> 'image',
                'fields'    => array (
						array(
							'id'        => 'logo',
							'type'      => 'media',
							'url'       => false,
							'title'     => __('Logo', 'anaglyph-lite'),
							'subtitle'  => __('Change your Logo here, upload or enter the URL to your logo image.', 'anaglyph-lite'),
							'default'   => array('url' => $sample_patterns_url . 'images/logo.png'),
							
						),
						
						array(
							'id'        => 'logo-retina',
							'type'      => 'media',
							'url'       => false,
							'title'     => __('Logo Retina ', 'anaglyph-lite'),
							'subtitle'  => __('Upload your Retina Logo. This should be your Logo in double size (If your logo is 100 x 20px, it should be 200 x 40px)', 'anaglyph-lite'),
							'default'   => array ('url' => $sample_patterns_url . 'images/logo@2x.png'),
						),

						 array(
							'id'                => 'logo-dimensions',
							'type'              => 'dimensions',
							'units'    			=> false,
							'title'             => __('Original Logo (Width/Height)', 'anaglyph-lite'),
							'subtitle'          => __("If Retina Logo uploaded, please enter the (width/height) of the Standard Logo you've uploaded (not the Retina Logo)", 'anaglyph-lite'),
							'default'           => array(
								'width'     => 129, 
								'height'    => 18,
							)
						),
						
						array(
							'id'        => 'favicon',
							'type'      => 'media',
							'url'       => false,
							'title'     => __('Favicon', 'anaglyph-lite'),
							'subtitle'  => __('A favicon is a 16x16 pixel icon that represents your site; upload your custom Favicon here.', 'anaglyph-lite'),
							'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-16x16.png'),
						),
						
						array(
							'id'        => 'favicon-iphone',
							'type'      => 'media',
							'url'       => false,
							'title'     => __('Favicon iPhone', 'anaglyph-lite'),
							'subtitle'  => __('Upload a custom favicon for iPhone (57x57 pixel png).', 'anaglyph-lite'),
							'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-57x57.png'),
						),
						
						array(
							'id'        => 'favicon-iphone-retina',
							'type'      => 'media',
							'url'       => false,
							'title'     => __('Favicon iPhone Retina', 'anaglyph-lite'),
							'subtitle'  => __('Upload a custom favicon for iPhone retina (114x114 pixel png).', 'anaglyph-lite'),
							'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-114x114.png'),
						),
						
						array(
							'id'        => 'favicon-ipad',
							'type'      => 'media',
							'url'       => false,
							'title'     => __('Favicon iPad', 'anaglyph-lite'),
							'subtitle'  => __('Upload a custom favicon for iPad (72x72 pixel png).', 'anaglyph-lite'),
							'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-72x72.png'),
						),
						
						array(
							'id'        => 'favicon-ipad-retina',
							'type'      => 'media',
							'url'       => false,
							'title'     => __('Favicon iPad Retina', 'anaglyph-lite'),
							'subtitle'  => __('Upload a custom favicon for iPhone retina (144x144 pixel png).', 'anaglyph-lite'),
							'default'   => array('url' => $sample_patterns_url . 'favicon/favicon-144x144.png'),
						),
						 
				)
			);
			
			/*Display options Section*/
			$arr_section['display'] = array(
                'title'     => __('Display options', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/display-options.png',
				'icon_type'	=> 'image',
                'fields'    => array (
					
					array(
                        'id'        => 'smoothscroll',
                        'type'      => 'checkbox',
                        'title'     => __('Enhanced scrolling', 'anaglyph-lite'),
                        'subtitle'  => __('Select to enable scrolling library.', 'anaglyph-lite'),
                        'desc'      => __('Yes', 'anaglyph-lite'),
						'class'		=> 'icheck',
                        'default'   => '1',
						'data'		=> null
                    ),		
					
					array(
                        'id'        => 'excerpt',
                        'type'      => 'select',
                        'title'     => __('Select Post Preview', 'anaglyph-lite'),
                        'subtitle'  => __('Select showing full post, excerpt or title only', 'anaglyph-lite'),
                        'options'   =>  array(
                            '1'     => __("Full post (before <-more->)", "anaglyph-lite"),
                            '2'     => __("Excerpt", "anaglyph-lite"),
                            '3'     => __("Only Title", "anaglyph-lite"),
                        ),
                        'default'   => '2'
                    ),
					
					array(
                        'id'        => 'pp-comments',
                        'type'      => 'select',
                        'title'     => __('Display Comments', 'anaglyph-lite'),
                        'subtitle'  => __('Choose where users are allowed to post comment in your website.', 'anaglyph-lite'),
						'std'		=> 'post',
                        
                        'options'   => array(
                            'post'  => __('Posts Only', 'anaglyph-lite'), 
                            'page'  => __('Pages Only', 'anaglyph-lite'), 
                            'both'  => __('Posts/Pages show', 'anaglyph-lite'), 
							'none'	=> __('Hide all', 'anaglyph-lite'), 
                        ),
                        'default'   => 'post'
                    ),
					
					array(
                        'id'        => 'pp-breadcrumbs',
                        'type'      => 'checkbox',
                        'title'     => __('Display Breadcrumbs', 'anaglyph-lite'),
                        'subtitle'  => __('Display dynamic breadcrumbs on each page of your website.', 'anaglyph-lite'),
                        'desc'      => __('Yes', 'anaglyph-lite'),
						'class'		=> 'icheck',
                        'default'   => '1'
                    ),		
					
					array(
                        'id'        => 'pp-blog',
                        'type'      => 'image_select',
                        'title'     => __('Blog page layout', 'anaglyph-lite'),
                        'subtitle'  => __('Select main content and sidebar alignment.', 'anaglyph-lite'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                        ),
                        'default'   => '1'
                    ),
					
					array(
                        'id'        => 'pp-post',
                        'type'      => 'image_select',
                        'title'     => __('Single post layout', 'anaglyph-lite'),
                        'subtitle'  => __('Select main content and sidebar alignment.', 'anaglyph-lite'),
                        'options'   => array(
                            '1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
                            '2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
                            '3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
                        ),
                        'default'   => '3'
                    ),
					
					array(
                        'id'        => 'pp-columns',
                        'type'      => 'select',
                        'title'     => __('Select Columns', 'anaglyph-lite'),
                        'subtitle'  => __('Select the number of columns for post showing.', 'anaglyph-lite'),
                        'options'   => array(
                            '1' => __("Full width", "anaglyph-lite"), 
                            '2' => __("2 columns",  "anaglyph-lite"), 
                            '3' => __("3 columns",  "anaglyph-lite"),
							'4' => __("4 columns",  "anaglyph-lite"),
                        ),
                        'default'   => '2'
                    ),
					
					array(
                        'id'        => 'pp-animations',
                        'type'      => 'select',
                        'title'     => __('Select animation for posts.', 'anaglyph-lite'),
                        'options'   => array(
                            'top' => __("Top", "anaglyph-lite"), 
                            'left' => __("Left", "anaglyph-lite"), 
                            'right' => __("Right", "anaglyph-lite"),
							'bottom' => __("Bottom", "anaglyph-lite"),
							'none' => __("None", "anaglyph-lite"),
                        ),
                        'default'   => 'bottom'
                    ),
					array(
                        'id'        => 'pp-date',
                        'type'      => 'checkbox',
                        'title'     => __('Display date for posts', 'anaglyph-lite'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'anaglyph-lite'),
                    ),	
					array(
                        'id'        => 'pp-num-comments',
                        'type'      => 'checkbox',
                        'title'     => __('Display comments count for posts', 'anaglyph-lite'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'anaglyph-lite'),
                    ),	
					array(
                        'id'        => 'pp-thumbnail',
                        'type'      => 'checkbox',
                        'title'     => __('Display thumbnails for posts', 'anaglyph-lite'),
                        'default'   => false,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'anaglyph-lite'),
                    ),	
					array(
                        'id'        => 'pp-thumbnail-single',
                        'type'      => 'checkbox',
                        'title'     => __('Display thumbnails for single post page', 'anaglyph-lite'),
                        'default'   => false,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'anaglyph-lite'),
                    ),	
					array(
                        'id'        => 'pp-tags',
                        'type'      => 'checkbox',
                        'title'     => __('Display tags for posts', 'anaglyph-lite'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'anaglyph-lite'),
                    ),	
					
					array(
                        'id'        => 'pp-authors',
                        'type'      => 'checkbox',
                        'title'     => __('Display authors for posts', 'anaglyph-lite'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'anaglyph-lite'),
                    ),	
					
					array(
                        'id'        => 'pp-share',
                        'type'      => 'checkbox',
                        'title'     => __('Display share for post', 'anaglyph-lite'),
                        'default'   => true,
						'class'		=> 'icheck',
						'desc'      => __('Yes', 'anaglyph-lite'),
                    ),	
					
					array(
                        'id'        => 'pp-animation-mobile',
                        'type'      => 'checkbox',
                        'title'     => __('Disable all animations on all mobile devices', 'anaglyph-lite'),
                        'desc'      => __('Yes', 'anaglyph-lite'),
						'class'		=> 'icheck',
                        'default'   => '0'
                    ),
					
				)	
			);	
				
			/*Styling options Section*/
			$arr_section['styling'] = array(
                'title'     => __('Styling', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/styling.png',
				'icon_type'	=> 'image',
                'fields'    => array (
					array(
                        'id'        => 'body-background',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => __('Body Background', 'anaglyph-lite'),
                        'subtitle'  => __('Body background with image, color, etc.', 'anaglyph-lite'),
						'transparent'	=> false,
						'default'   => array(
							'background-color' => '#ffffff',
							'background-repeat'	=> 'inherit',
							'background-attachment'	=> 'inherit',
							'background-position'	=> 'top center',
							'background-size'		=> 'inherit',
						)
                    ),
					
					array(
						'id' 	=> 'headline-section',
						'type' 	=> 'section',
						'title' => __('Headline options', 'anaglyph-lite'),
						'indent' => true 
					),
					
					array(
                        'id'        => 'headline-before-sep-color',
                        'type'      => 'color',
                        'title'     => __('Headline before color separator.', 'anaglyph-lite'),
                        'default'   => '#FACE00',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
					
					array(
                        'id'        => 'headline-after-sep-color',
                        'type'      => 'color',
                        'title'     => __('Headline after color separator.', 'anaglyph-lite'),
                        'default'   => '#E23A00',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
					
					array(
						'id' 	=> 'top-title-section',
						'type' 	=> 'section',
						'title' => __('Page title options', 'anaglyph-lite'),
						'indent' => true 
					),
					
					array(
                        'id'        => 'top-title-text',
						'type'      => 'typography', 
						'title'       => __('Page title text options.', 'anaglyph-lite'),
						'google'      => true, 
						'subsets'	  => false,
						'font-backup' => false,
						'line-height' => false,	
						'text-align'  => false,	
						'text-transform' => true,
						'subsets'	  => true,
						'output'      => array('.sub-page #page-title .title h1'),
						'units'       =>'px',
						'subtitle'	  => __('Select typography for title text.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#ffffff', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '48px',
														'text-transform' => 'uppercase'														
												),
								'preview' => array('text' => 'Page Title')				
					),
					
					array(
                        'id'        => 'top-title-bg-color',
						'type'      => 'color_rgba',
                        'title'     => __('Page title text background color.', 'anaglyph-lite'),
                        'default'   => array('color' => '#000000', 'alpha' => '0.8'),
                        'mode'      => 'color',
                        'validate'  => 'colorrgba',
						'transparent'	=> false
                    ),
					
					array(
						'id' 	=> 'blog-section',
						'type' 	=> 'section',
						'title' => __('Blog options', 'anaglyph-lite'),
						'indent' => true 
					),
					
					array(
                        'id'        => 'blog-date-text',
						'type'      => 'typography', 
						'title'       => __('Blog date text options.', 'anaglyph-lite'),
						'google'      => true, 
						'subsets'	  => false,
						'font-backup' => false,
						'line-height' => false,	
						'text-align'  => false,	
						'subsets'		=> true,
						'output'      => array('.blog-post .date-circle', '.blog-post .date-circle'),
						'units'       =>'px',
						'subtitle'	  => __('Select typography for title text.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#ffffff', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px' 
												),
								'preview' => array('text' => 'Blog date')				
					),
					
					array(
                        'id'        	=> 'blog-date-bg-color',
						'type'      	=> 'color',
                        'title'     	=> __('Blog date background color.', 'anaglyph-lite'),
                        'default'   	=> '#292422',
                        'mode'      	=> 'color',
						'transparent'	=> false
                    ),
					
					array(
						'id' 	=> 'navigation-section',
						'type' 	=> 'section',
						'title' => __('Post navigation options', 'anaglyph-lite'),
						'indent' => true 
					),
					
					array(
                        'id'        => 'navigation-text',
						'type'      => 'typography', 
						'title'       => __('Navigation text options.', 'anaglyph-lite'),
						'google'      => true, 
						'subsets'	  => false,
						'font-backup' => false,
						'line-height' => false,	
						'text-align'  => false,	
						'color'		  => false,	
						'preview'	  => false,	
						'subsets'		=> true,
						'output'	  => array('.pagination li a, .pagination li span'),
						'units'       =>'px',
						'subtitle'	  => __('Select typography for page navigation text.', 'anaglyph-lite'),
								'default'     => array(
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px' 
												),
					),
					
					array(
                        'id'        => 'navigation-border',
                        'type'      => 'border',
                        'title'     => __('Navigation border.', 'anaglyph-lite'),
						'color'		=> false,
                        'default'   => array(
                            'border-style'  => 'solid', 
                            'border-top'    => '2px', 
                            'border-right'  => '2px', 
                            'border-bottom' => '2px', 
                            'border-left'   => '2px'
                        )
                    ),
					
					array(
                        'id'        => 'navigation-border-color',
                        'type'      => 'link_color',
                        'title'     => __('Navigation border color.', 'anaglyph-lite'),
                        'default'   => array(
                            'regular'   => '#999999',
                            'hover'     => '#000000',
                            'active'    => '#E23A00',
                        )
                    ),
					
					array(
                        'id'        => 'navigation-link-color',
                        'type'      => 'link_color',
                        'title'     => __('Navigation link color.', 'anaglyph-lite'),
                        'default'   => array(
                            'regular'   => '#292422',
                            'hover'     => '#292422',
                            'active'    => '#ffffff',
                        )
                    ),
					
					
					array(
                        'id'        => 'navigation-bglink-color',
                        'type'      => 'link_color',
                        'title'     => __('Navigation background link color.', 'anaglyph-lite'),
                        'default'   => array(
                            'regular'   => '#ffffff',
                            'hover'     => '#ffffff',
                            'active'    => '#E23A00',
                        )
                    ),
					
					
				)
			);
			
				
			$pages_list = array();
			
			$args_query_pages = array(
				'post_status' => 'publish',
				'post_type' => array('post', 'page'),
				'posts_per_page' => -1
			);
			$pages_array = get_posts( $args_query_pages );
			if (!empty($pages_array)){
				foreach ( $pages_array as $page_item ) {
					$pages_list[$page_item->ID] = $page_item->post_title;
				}
			}
			
			/*Slider Section*/
			$arr_section['slider'] = array(
                'title'     => __('Slider', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/slider.png',
				'icon_type'	=> 'image',
                'fields'    => array (
				
					array(
                        'id'        => 'switch-slider',
                        'type'      => 'switch',
                        'title'     => __('Slider on "Home" page', 'anaglyph-lite'),
                        'default'   =>  0,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					
					array(
						'id'       		=> 'home-slides',
						'type'     		=> 'select',
						'required'  	=> array('switch-slider', '=', '1'),
						'multi'    		=> true,
						'title'   		=> __('Slides', 'anaglyph-lite'), 
						'subtitle' 		=> __('Select pages or posts', 'anaglyph-lite'),
						'desc'     		=> __('You can sort it.', 'anaglyph-lite'),
						'placeholder'	=> __('Page or post name.', 'anaglyph-lite'),
						'sortable' 		=> true,
						'options'  		=> $pages_list
					),
					
					array(
						'id'        => 'slider-links',
						'type'      => 'checkbox',
						'required'  => array('switch-slider', '=', '1'),
						'title'     => __('Link to page', 'anaglyph-lite'),
						'desc'      => __('Yes', 'anaglyph-lite'),
						'default'   => '1'
					),
					
					array(
                        'id'        => 'slider-parallax',
                        'type'      => 'checkbox',
						'required'  => array('switch-slider', '=', '1'),
                        'title'     => __('Enable parallax', 'anaglyph-lite'),
                        'subtitle'  => __('Select to enable parallax effect.', 'anaglyph-lite'),
                        'desc'      => __('Yes', 'anaglyph-lite'),
						'class'		=> 'icheck',
                        'default'   => '1',
						'data'		=> null
                    ),		
					
					array(
                        'id'            => 'slider-showspeed',
                        'type'          => 'slider',
						'required'  => array('switch-slider', '=', '1'),
                        'title'         => __('Slideshow Speed', 'anaglyph-lite'),
                        'desc'          => __('Min: 1000, max: 28000, step: 500, default value: 8000', 'anaglyph-lite'),
                        'default'       => 8000,
                        'min'           => 1000,
                        'step'          => 500,
                        'max'           => 28000,
                        'display_value' => 'text'
                    ),
					
					array(
                        'id'        => 'slider-animationeffect-in',
                        'type'      => 'select',
                        'title'     => __('Select animation in', 'anaglyph-lite'),
                        'options'   => $arr_slider_effects,
                        'default'   => 'flipInX',
						'required'  => array('switch-slider', '=', '1'),
                    ),
					
					array(
                        'id'        => 'slider-animationeffect-out',
                        'type'      => 'select',
                        'title'     => __('Select animation out', 'anaglyph-lite'),
                        'options'   => $arr_slider_effects,
                        'default'   => 'fadeOutUp',
						'required'  => array('switch-slider', '=', '1'),
                    ),
					
					array(
								'id'          => 'slider-typography',
								'type'        => 'typography', 
								'title'       => __('Slider title', 'anaglyph-lite'),
								'required'  => array('switch-slider', '=', '1'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'text-transform' => true,
								'subsets'		=> true,
								'output'    => array('#slider .flexslider .slides .slide .slide-content .slide-wrapper h1'),
								'units'       =>'px',
								'subtitle'	  => __('Select typography for general text.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#fff', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '72px',
														'text-transform' => 'uppercase'														
												),
								'preview' => array('text' => 'sample text')				
					),

					array(
 								'id'          => 'slider-sub-typography',
 								'type'        => 'typography', 
 								'title'       => __('Slider subtitle', 'anaglyph-lite'),
 								'required'  => array('switch-slider', '=', '1'),
 								'google'      => true, 
 								'subsets'	  => false,
 								'font-backup' => false,
 								'line-height' => false,	
 								'text-align'  => false,	
 								'text-transform' => true,
 								'subsets'		=> true,
 								'output'    => array('#slider .flexslider .slides .slide .slide-content .slide-wrapper h3'),
 								'units'       =>'px',
 								'subtitle'	  => __('Select typography for subtitle.', 'anaglyph-lite'),
 								'default'     => array(
 														'color'       => '#fff', 
 														'font-style'  => '400', 
 														'font-family' => 'Montserrat', 
 														'google'      => true,
 														'font-size'   => '36px',
 														'text-transform' => 'uppercase'														
 												),
 								'preview' => array('text' => 'sample text')				
 					),
					
					array(
                        'id'        	=> 'slider-title-bg-color',
						'type'      	=> 'color_rgba',
                        'title'     	=> __('Slider title background color.', 'anaglyph-lite'),
                        'default'  => array(
							'color' => '#000000', 
							'alpha' => '0.9'
						),
						'transparent'	=> false,
						'mode'     => 'background',
						'required'  => array('switch-slider', '=', '1'),
                    ),
					
				)
				
			);
				
			/*Header Section*/
			$arr_section['header'] = array(
                'title'     => __('Header', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/header.png',
				'icon_type' => 'image',
                'fields'    => array(
					 array(
                        'id'        => 'header-background-color',
                        'type'      => 'color',
                        'title'     => __('Header background color', 'anaglyph-lite'),
                        'default'   => '#FFFFFF',
                        'validate'  => 'color',
						'transparent'	=> true
                    ),
					
					array(
                        'id'        => 'header-fixed-settings',
                        'type'      => 'select',
                        'title'     => __('Select animation in', 'anaglyph-lite'),
                        'options'   => array(
									'1' => __('Fixed header on home page', 'anaglyph-lite'),
									'2' => __('Fixed header for all pages', 'anaglyph-lite'),
									'3' => __('Disable fixed header', 'anaglyph-lite'),
							),
                        'default'   => '1',
						'std'		=> '1'
                    ),
					
					array(
						'id'            => 'header-spacing',
						'type'          => 'spacing',
                        'output'        => array('.navigation-wrapper .navigation'), 
                        'mode'          => 'padding',   
                        'all'           => false,       
                        'left'          => false,     	
                        'right'        	=> false,     	
                        'units'    			=> array('em','px','%'),
						'units_extended'    => 'true',  
                        'title'         => __('Padding Option', 'anaglyph-lite'),
                        'subtitle'      => __('Allow your users to choose the spacing or margin they want.', 'anaglyph-lite'),
                        'default'       => array(
                            'padding-top'    => 10, 
                            'padding-bottom' => 10 
                        )
                    ),
				)
			);
			
			
			/*Menu Section*/
			$arr_section['menu'] = array(
                'title'     => __('Menu', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/menu.png',
				'icon_type' => 'image',
                'fields'    => array(
					
					array(
								'id'          => 'menu-font-settings',
								'type'        => 'typography', 
								'title'       => __('Menu font settings', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'color'		  => false,	
								'text-transform' => true,
								'output'      => array('.navigation-wrapper .navigation .nav li a'),
								'units'       =>'px',
								'subsets'		=> true,
								'subtitle'	  => __('Select typography for general text.', 'anaglyph-lite'),
								'default'     => array(
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px',
														'text-transform' => 'uppercase'
												),
								'preview' => array('text' => 'sample text')				
					),
							
					array(
                        'id'        => 'menu-link-color',
                        'type'      => 'link_color',
                        'title'     => __('Menu item color', 'anaglyph-lite'),
                        'default'   => array(
                            'regular'   => '#292422',
                            'hover'     => '#292422',
                            'active'    => '#292422',
                        )
                    ),
					array(
                        'id'        => 'submenu-link-color',
                        'type'      => 'link_color',
                        'title'     => __('Submenu item color', 'anaglyph-lite'),
                        'default'   => array(
                            'regular'   => '#292422',
                            'hover'     => '#E23A00',
                            'active'    => '#E23A00',
                        )
                    ),
					
					array(
                        'id'        => 'submenu-itembg-color',
                        'type'      => 'link_color',
                        'title'     => __('Submenu item background color', 'anaglyph-lite'),
                        'default'   => array(
                            'regular'   => '#EDEAE6',
                            'hover'     => '#FFFFFF',
                            'active'    => '#FFFFFF',
                        )
                    ),
					
					array(
                        'id'        => 'submenu-color',
                        'type'      => 'color',
                        'title'     => __('Submenu background color', 'anaglyph-lite'),
                        'default'   => '#EDEAE6',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
					
					array(
                        'id'        => 'submenu-itemborder-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Submenu item border color', 'anaglyph-lite'),
                        'default'   => array('color' => '#000000', 'alpha' => '0.05'),
                        'mode'      => 'color',
                        'validate'  => 'colorrgba',
						'transparent'	=> false
                    ),
					
					array(
                        'id'        => 'before-item-color',
                        'type'      => 'color',
                        'title'     => __('Menu before item color.', 'anaglyph-lite'),
                        'default'   => '#E23A00',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
					
					array(
                        'id'        => 'after-item-color',
                        'type'      => 'color',
                        'title'     => __('Menu after item color.', 'anaglyph-lite'),
                        'default'   => '#FACE00',
                        'validate'  => 'color',
						'transparent'	=> false
                    ),
				)
			);
				
			/*Font Styles Section*/
			$arr_section['font-style'] = array(
                'title'     => __('Font styles', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/fonts.png',
				'icon_type' => 'image',
                'fields'    => array(
						array(
								'id'          => 'general-typography',
								'type'        => 'typography', 
								'title'       => __('General Text Font Style', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'output'      => array('body'),
								'units'       =>'px',
								'subsets'		=> true,
								'subtitle'	  => __('Select typography for general text.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#333', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px'
												),
								'preview' => array('text' => 'sample text')				
							 ),
							 
							 array(
								'id'            => 'p-opacity',
								'type'          => 'slider',
								'title'         => __('Transparency for content', 'anaglyph-lite'),
								'subtitle'      => __('Set the opacity for the content part', 'anaglyph-lite'),
								'default'       => .65,
								'min'           => 0,
								'step'          => .1,
								'max'           => 1,
								'resolution'    => 0.1,
								'display_value' => 'label'
							),
							
							array(
								'id'          => 'hone-typography',
								'type'        => 'typography', 
								'title'       => __('H1 Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'text-transform' => true,
								'output'      => array('h1'),
								'subsets'		=> true,
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H1.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#333', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '72px',
														'text-transform' => 'uppercase'
												),
								'preview' => array('text' => 'sample text')								
							 ),
							array(
								'id'          => 'htwo-typography',
								'type'        => 'typography', 
								'title'       => __('H2 Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'text-transform' => true,
								'subsets'		=> true,
								'output'      => array('h2'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H2.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#333', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '36px',
														'text-transform' => 'uppercase'
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 array(
								'id'          => 'hthree-typography',
								'type'        => 'typography', 
								'title'       => __('H3 Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'text-transform' => true,
								'subsets'		=> true,
								'output'      => array('h3'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H3.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#333', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '24px', 
														'text-transform' => 'none'
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 array(
								'id'          => 'hfour-typography',
								'type'        => 'typography', 
								'title'       => __('H4 Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'text-transform' => true,
								'subsets'		=> true,
								'output'      => array('h4'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H4.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#333', 
														'font-style'  => '700', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '18px',
														'text-transform' => 'uppercase'
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 array(
								'id'          => 'hfive-typography',
								'type'        => 'typography', 
								'title'       => __('H5 Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'text-transform' => true,
								'subsets'		=> true,
								'output'      => array('h5'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H5.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#333', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px',
														'text-transform' => 'uppercase'
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 array(
								'id'          => 'hsix-typography',
								'type'        => 'typography', 
								'title'       => __('H6 Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'text-transform' => true,
								'subsets'		=> true,
								'output'      => array('h6'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for header H6.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#333', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px',
														'text-transform' => 'uppercase'
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 array(
								'id'          => 'p-typography',
								'type'        => 'typography', 
								'title'       => __('"p" Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => true,	
								'text-align'  => true,	
								'subsets'		=> true,
								'output'      => array('p'),
								'units'       =>'px',
								'subtitle'	  => __('Select the typography you want for tag "p".', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#292422', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px',
														'line-height' => '24px',
														'text-align'  => 'inherit'
														
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
							 
							 array(
									'id'        => 'content-link-color',
									'type'      => 'link_color',
									'title'     => __('Link style.', 'anaglyph-lite'),
									'subtitle'  => __('Select the typography you want for tag "a".', 'anaglyph-lite'),
									'output'      => array('a'),
									'default'   => array(
										'regular'   => '#292422',
										'hover'     => '#e23a00',
										'active'    => '#e23a00',
									)
                    ),

					)
			);
			
			/*Social Section*/
			$arr_section['social'] = array(
                'title'     => __('Social Links', 'anaglyph-lite'),
                'desc'      => __('Add link to your social media profiles. Icons with link will be display in header or footer.', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/social-links.png',
				'icon_type' => 'image',
				'fields'    => array(
					array(
                        'id'        => 'facebook-url',
                        'type'      => 'text',
                        'title'     => __('Facebook', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'twitter-url',
                        'type'      => 'text',
                        'title'     => __('Twitter', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'linkedin-url',
                        'type'      => 'text',
                        'title'     => __('LinkedIn', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'myspace-url',
                        'type'      => 'text',
                        'title'     => __('MySpace', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'gplus-url',
                        'type'      => 'text',
                        'title'     => __('Google Plus+', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'dribbble-url',
                        'type'      => 'text',
                        'title'     => __('Dribbble', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'flickr-url',
                        'type'      => 'text',
                        'title'     => __('Flickr', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'youtube-url',
                        'type'      => 'text',
                        'title'     => __('You Tube', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'delicious-url',
                        'type'      => 'text',
                        'title'     => __('Delicious', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'deviantart-url',
                        'type'      => 'text',
                        'title'     => __('Deviantart', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'rss-url',
                        'type'      => 'text',
                        'title'     => __('RSS', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'instagram-url',
                        'type'      => 'text',
                        'title'     => __('Instagram', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'pinterest-url',
                        'type'      => 'text',
                        'title'     => __('Pinterest', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'vimeo-url',
                        'type'      => 'text',
                        'title'     => __('Vimeo', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'picassa-url',
                        'type'      => 'text',
                        'title'     => __('Picassa', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'tumblr-url',
                        'type'      => 'text',
                        'title'     => __('Tumblr', 'anaglyph-lite'),
                        'validate'  => 'url',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'email-address',
                        'type'      => 'text',
                        'title'     => __('E-mail', 'anaglyph-lite'),
                        'validate'  => 'email',
                        'msg'       => 'custom error message',
                        'default'   => ''
                    ),
					array(
                        'id'        => 'skype-username',
                        'type'      => 'text',
                        'title'     => __('Skype', 'anaglyph-lite'),
                        'default'   => ''
                    ),
				)	
			);	
			
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 
			global $wpdb;
			$contact_form_array = array();
			$contact_form_array[-1] = __('Anaglyph contact form', 'anaglyph-lite');
			if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
				
				$cf7_forms = $wpdb->get_results("SELECT ID, post_title FROM $wpdb->posts WHERE post_type = 'wpcf7_contact_form' order by post_title");
				if ( !empty($cf7_forms)) {
					foreach ( $cf7_forms as $cform ) {
						$contact_form_array[$cform->ID] = $cform->post_title;
					}
				} 
			}
			

			/*Contact Section*/
			$arr_section['contact'] = array(
                'title'     => __('Contact', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/contact.png',
				'icon_type' => 'image',
				'fields'    => array(
					array(
                        'id'        => 'contact-stitle',
                        'type'      => 'text',
                        'title'     => __('Contact section title', 'anaglyph-lite'),
                        'subtitle'  => __('Edit title for contact section', 'anaglyph-lite'),
                        'default'   => 'Contact us'
                    ),
					array(
                        'id'        => 'contact-satitle',
                        'type'      => 'text',
                        'title'     => __('Contact section additional title', 'anaglyph-lite'),
                        'subtitle'  => __('Edit additional title for contact section', 'anaglyph-lite'),
                        'default'   => 'Questions or just wanna say hello? We&#8217;re waiting!'
                    ),
					
					array(
                        'id'        => 'contact-scolor',
                        'type'      => 'color',
                        'title'     => __('Contact section Background Color', 'anaglyph-lite'),
                        'subtitle'  => __('Pick a background color for the contact section.', 'anaglyph-lite'),
                        'default'   => '#181818',
                        'validate'  => 'color',
						'transparent' => false
                    ),
					array(
                        'id'        => 'contact-simage',
                        'type'      => 'media',
                        'title'     => __('Contact section background image', 'anaglyph-lite'),
                    ),
					
					array(
                        'id'        => 'contact-address-icon-color',
                        'type'      => 'color',
                        'title'     => __('Contact section address icon color.', 'anaglyph-lite'),
                        'default'   => '#E23A00',
                        'validate'  => 'color',
						'transparent' => false
                    ),

					array(
                        'id'        => 'contact-sopacity',
                        'type'      => 'select',
                        'title'     => __('Select section opacity', 'anaglyph-lite'),
                        'options'   => array(
                            __("opacity-1", "anaglyph-lite") => "opacity-1", 
							__("opacity-2", "anaglyph-lite") => "opacity-2", 
							__("opacity-3", "anaglyph-lite") => "opacity-3", 
							__("opacity-4", "anaglyph-lite") => "opacity-4", 
							__("opacity-5", "anaglyph-lite") => "opacity-5", 
							__("opacity-6", "anaglyph-lite") => "opacity-6", 
							__("opacity-7", "anaglyph-lite") => "opacity-7", 
							__("opacity-8", "anaglyph-lite") => "opacity-8", 
							__("opacity-9", "anaglyph-lite") => "opacity-9", 
							__("opacity-10", "anaglyph-lite") => "opacity-10", 
							__("opacity-20", "anaglyph-lite") => "opacity-20", 
							__("opacity-30", "anaglyph-lite") => "opacity-30", 
							__("opacity-40", "anaglyph-lite") => "opacity-40", 
							__("opacity-50", "anaglyph-lite") => "opacity-50", 
							__("opacity-60", "anaglyph-lite") => "opacity-60", 
							__("opacity-70", "anaglyph-lite") => "opacity-70", 
							__("opacity-80", "anaglyph-lite") => "opacity-80", 
							__("opacity-90", "anaglyph-lite") => "opacity-90", 
                        ),
                        'default'   => 'opacity-7'
                    ),
					array(
                        'id'        => 'contact-information',
                        'type'      => 'switch',
                        'title'     => __('Contact information panel', 'anaglyph-lite'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',
                    ),
					
					array(
                        'id'        => 'contact-location',
                        'type'      => 'text',
                        'title'     => __('Location Name', 'anaglyph-lite'),
                        'subtitle'  => __('Enter the location name.', 'anaglyph-lite'),
                        'default'   => '',
						'required'  => array('contact-information', '=', '1'),
                    ),
					
					array(
                        'id'        => 'contact-address',
                        'type'      => 'textarea',
                        'title'     => __('Location Address', 'anaglyph-lite'),
                        'subtitle'  => __("Enter your company's address", 'anaglyph-lite'),
                        'validate'  => 'no_html',
                        'default'   => '',
						'required'  => array('contact-information', '=', '1'),
                    ),
					
					array(
                        'id'        => 'contact-tel',
                        'type'      => 'text',
                        'title'     => __('Telephone', 'anaglyph-lite'),
                        'subtitle'  => __('Enter your telephone number', 'anaglyph-lite'),
                        'default'   => '',
						'required'  => array('contact-information', '=', '1'),
                    ),
					
					array(
                        'id'        => 'contact-fax',
                        'type'      => 'text',
                        'title'     => __('Fax', 'anaglyph-lite'),
                        'subtitle'  => __('Enter your fax number', 'anaglyph-lite'),
                        'default'   => '',
						'required'  => array('contact-information', '=', '1'),
                    ),
					
					array(
                        'id'        => 'contact-skype',
                        'type'      => 'text',
                        'title'     => __('Skype', 'anaglyph-lite'),
                        'subtitle'  => __('Enter your skype number', 'anaglyph-lite'),
                        'default'   => '',
						'required'  => array('contact-information', '=', '1'),
                    ),
					
					
					array(
						'id' 	=> 'contact-section',
						'type' 	=> 'section',
						'title' => __('Contact form settings', 'anaglyph-lite'),
						'indent' => true,
						'required'  => array('contact-information', '=', '1'),						
					),
					
					array(
						'id'       => 'contact-section-cform',
						'type'     => 'select',
						'title'    => __('Select contact form', 'anaglyph-lite'), 
						'subtitle' => __('select a form from the list to be displayed.', 'anaglyph-lite'),
						'options'  => $contact_form_array,
						'default'  => '-1',
						'required'  => array('contact-information', '=', '1'),						
					),
					
					array(
                        'id'        => 'contact-email',
                        'type'      => 'text',
                        'title'     => __('Contact Form E-Mail', 'anaglyph-lite'),
                        'subtitle'  => __('Enter your E-mail address to use on the "Contact Form".', 'anaglyph-lite'),
                        'validate'  => 'email',
                        'default'   => get_option('admin_email'),
						'required'  => array(
										array('contact-information', '=', '1'),
										array('contact-section-cform', '<', '0'),
									),	
                    ),
					
					array(
                        'id'        => 'contact-description',
                        'type'      => 'text',
                        'title'     => __('Contact Form description', 'anaglyph-lite'),
                        'subtitle'  => __('Enter description for "Contact Form".', 'anaglyph-lite'),
                        'default'   => __('Worried about a SPAM? We are first to hate it!', 'anaglyph-lite'),
						'required'  => array(
										array('contact-information', '=', '1'),
										array('contact-section-cform', '<', '0'),
									),	
                    ),
					
					array(
                        'id'        => 'contact-submit',
                        'type'      => 'text',
                        'title'     => __('Contact Form submit', 'anaglyph-lite'),
                        'subtitle'  => __('Enter submit button text for "Contact Form".', 'anaglyph-lite'),
                        'default'   => __('Send a message', 'anaglyph-lite'),
						'required'  => array(
										array('contact-information', '=', '1'),
										array('contact-section-cform', '<', '0'),
									),	
                    ),
					
					array(
                        'id'        => 'contact-form-fields',
                        'type'      => 'sorter',
                        'title'     => __('Contact Form Fields', 'anaglyph-lite'),
                        'subtitle'  => __('Select the order and fields for the contact form.', 'anaglyph-lite'),
                        'options' => array(
								'enabled'  => array(
									'name' 	=> __('Name','anaglyph-lite'),
									'email' => __('Email','anaglyph-lite'),
									'message' => __('Message', 'anaglyph-lite'),
									),
									'disabled' => array(
										'mob'	=> __('Mobile', 'anaglyph-lite'),
										'phone'	=> __('Phone','anaglyph-lite'),
										'captcha' => __('Captcha','anaglyph-lite'),
									),
								),	
								
						'required'  => array(
										array('contact-information', '=', '1'),
										array('contact-section-cform', '<', '0'),
									),	
                    ),
					array(
                        'id'        => 'contact-animations',
                        'type'      => 'checkbox',
                        'title'     => __('Animation', 'anaglyph-lite'),
                        'desc'      => __('On', 'anaglyph-lite'),
						'class'		=> 'icheck',
                        'default'   => '1'
                    ),		
					
				)
			);
			
			
			/*Default Page*/
			$arr_section['default-pages'] = array(
                'title'     => __('Default pages', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/default-pages.png',
				'icon_type' => 'image',
				'fields'    => array(
					array(
                        'id'        => 'simple-post',
                        'type'      => 'media',
                        'title'     => __('Single Post header image.', 'anaglyph-lite'),
                        'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					
					array(
                        'id'        => 'simple-page',
                        'type'      => 'media',
                        'title'     => __('Page header image.', 'anaglyph-lite'),
                        'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					
					array(
                        'id'        => 'search-image',
                        'type'      => 'media',
                        'title'     => __('Search header image.', 'anaglyph-lite'),
                        'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					
					array(
                        'id'        => '404-titleimage',
                        'type'      => 'media',
                        'title'     => __('404 header image.', 'anaglyph-lite'),
                        'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					array(
                        'id'        => '404-image',
                        'type'      => 'media',
                        'title'     => __('404 logo.', 'anaglyph-lite'),
                        'subtitle'  => __('Upload a banner for your 404 page.', 'anaglyph-lite'),
						//'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					array(
                        'id'        => '404-text',
                        'type'      => 'textarea',
                        'title'     => __('404 page text.', 'anaglyph-lite'),
                        'subtitle'  => __('Enter the text you want to show in your 404 page here.', 'anaglyph-lite'),
                        'default'   => __('It looks like nothing was found at this location. Maybe try a search?', 'anaglyph-lite')
                    ),
					
					array(
                        'id'        => 'category-image',
                        'type'      => 'media',
                        'title'     => __('Category header image.', 'anaglyph-lite'),
						'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					
					array(
                        'id'        => 'archive-image',
                        'type'      => 'media',
                        'title'     => __('Archive header image.', 'anaglyph-lite'),
						'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					
					array(
                        'id'        => 'author-image',
                        'type'      => 'media',
                        'title'     => __('Author archive header image.', 'anaglyph-lite'),
                        'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					
					array(
                        'id'        => 'tag-image',
                        'type'      => 'media',
                        'title'     => __('Tag archive header image.', 'anaglyph-lite'),
                        'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
                    ),
					
				)
			);	
			
				
			/*Footer Section*/
			$arr_section['footer'] = array(
                'title'     => __('Footer', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/footer.png',
				'icon_type' => 'image',
				'fields'    => array(
					array(
                        'id'        => 'footer-text',
                        'type'      => 'editor',
                        'title'     => __('Copyright section', 'anaglyph-lite'),
                        'subtitle'  => __('Replace default theme copyright information and links', 'anaglyph-lite'),
                        'default'   => '&#169; <a title="WordPress Development" href="https://github.com/fruitfulcode/">Fruitful Code</a>, Powered by <a href="http://wordpress.org/">WordPress</a>',
                    ),
					array(
                        'id'        => 'footer-color',
                        'type'      => 'color',
                        'title'     => __('Footer Background Color', 'anaglyph-lite'),
                        'subtitle'  => __('Pick a background color for the footer.', 'anaglyph-lite'),
                        'default'   => '#181818',
                        'validate'  => 'color',
						'transparent' => false
                    ),
					array(
                        'id'        => 'footer-image',
                        'type'      => 'media',
                        'title'     => __('Footer background image', 'anaglyph-lite'),
                    ),
					array(
                        'id'        => 'opacity-fsection',
                        'type'      => 'select',
                        'title'     => __('Select section opacity', 'anaglyph-lite'),
                        'options'   => array(
                            __("opacity-1", "anaglyph-lite") => "opacity-1", 
							__("opacity-2", "anaglyph-lite") => "opacity-2", 
							__("opacity-3", "anaglyph-lite") => "opacity-3", 
							__("opacity-4", "anaglyph-lite") => "opacity-4", 
							__("opacity-5", "anaglyph-lite") => "opacity-5", 
							__("opacity-6", "anaglyph-lite") => "opacity-6", 
							__("opacity-7", "anaglyph-lite") => "opacity-7", 
							__("opacity-8", "anaglyph-lite") => "opacity-8", 
							__("opacity-9", "anaglyph-lite") => "opacity-9", 
							__("opacity-10", "anaglyph-lite") => "opacity-10", 
							__("opacity-20", "anaglyph-lite") => "opacity-20", 
							__("opacity-30", "anaglyph-lite") => "opacity-30", 
							__("opacity-40", "anaglyph-lite") => "opacity-40", 
							__("opacity-50", "anaglyph-lite") => "opacity-50", 
							__("opacity-60", "anaglyph-lite") => "opacity-60", 
							__("opacity-70", "anaglyph-lite") => "opacity-70", 
							__("opacity-80", "anaglyph-lite") => "opacity-80", 
							__("opacity-90", "anaglyph-lite") => "opacity-90", 
                        ),
                        'default'   => 'opacity-7'
                    ),
					array(
                        'id'        => 'footer-issocial',
                        'type'      => 'checkbox',
                        'title'     => __('Social icons', 'anaglyph-lite'),
                        'desc'      => __('Enable social icons.', 'anaglyph-lite'),
                        'default'   => '1',
						'class'		=> 'icheck',
                    ),
					array(
                        'id'        => 'footer-iscontact',
                        'type'      => 'checkbox',
                        'title'     => __('See the contact information in the footer', 'anaglyph-lite'),
                        'desc'      => __('Enable contact information', 'anaglyph-lite'),
                        'default'   => '1',
						'class'		=> 'icheck',
                    ),
					array(
								'id'          => 'footer-typography',
								'type'        => 'typography', 
								'title'       => __('Footer Font Style.', 'anaglyph-lite'),
								'google'      => true, 
								'subsets'	  => false,
								'font-backup' => false,
								'line-height' => false,	
								'text-align'  => false,	
								'subsets'		=> true,
								'output'      => array('footer#page-footer, footer#page-footer p'),
								'units'       =>'px',
								'subtitle'	  => __('Select typography for footer.', 'anaglyph-lite'),
								'default'     => array(
														'color'       => '#fff', 
														'font-style'  => '400', 
														'font-family' => 'Montserrat', 
														'google'      => true,
														'font-size'   => '14px',
												),
								'preview' => array('text' => 'sample text'),				
							 ), 
					
					
				)
			);
				
			
			/*Custom Section*/
			$arr_section['custom'] = array(
                'title'     => __('Custom Code', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/custom-code.png',
				'icon_type' => 'image',
                'fields'    => array (
					array(
                        'id'        => 'custom-css',
                        'type'      => 'ace_editor',
                        'title'     => __('CSS Code', 'anaglyph-lite'),
                        'subtitle'  => __('Paste your CSS code here.', 'anaglyph-lite'),
                        'mode'      => 'css',
                        'theme'     => 'chrome',
                        'desc'      => '',
                        'default'   => ""
                    ),
					array(
                        'id'        => 'custom-js',
                        'type'      => 'ace_editor',
                        'title'     => __('JS Code', 'anaglyph-lite'),
                        'subtitle'  => __('Paste your JS code here.', 'anaglyph-lite'),
                        'mode'      => 'javascript',
                        'theme'     => 'chrome',
                        'desc'      => '',
                        'default'   => ""
                    ),
				)
			);
			
			if ( class_exists( 'woocommerce' ) ) {
				/*WooCommerce options*/
				$arr_section['woocommerce'] = array(
					'title'     => __('WooCommerce', 'anaglyph-lite'),
					'icon'      => $sample_patterns_url . 'images/icons/woo.png',
					'icon_type' => 'image',
					'fields'    => array (
						array(
							'id'        => 'shopheader-image',
							'type'      => 'media',
							'title'     => __('Shop header image.', 'anaglyph-lite'),
							'default'   => array('url' =>  esc_url(get_template_directory_uri() . '/includes/theme/assets/bgs/title-background.jpg')),
						),
						
						array(
							'id'        => 'shop-layout',
							'type'      => 'image_select',
							'title'     => __('Shop layout', 'anaglyph-lite'),
							'subtitle'  => __('Select main content and sidebar alignment.', 'anaglyph-lite'),
							'options'   => array(
								'1' => array('alt' => '1 Column',       'img' => ReduxFramework::$_url . 'assets/img/1col.png'),
								'2' => array('alt' => '2 Column Left',  'img' => ReduxFramework::$_url . 'assets/img/2cl.png'),
								'3' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . 'assets/img/2cr.png'),
							),
							'default'   => '1'
						),
						
						array(
							'id'        => 'shop-product-perpage',
							'type'      => 'text',
							'title'     => __('Products per page', 'anaglyph-lite'),
							'subtitle'      => __('Change number of products displayed per page.', 'anaglyph-lite'),
							'validate'  => 'numeric',
							'default'   => '12',
						),
						
						
						array(
							'id'        => 'shop-product-perrow',
							'type'      => 'select',
							'title'     => __('Products per row', 'anaglyph-lite'),
							'subtitle'  => __('Change number of products per row.', 'anaglyph-lite'),
							'options'   => array(
								'2' => __('2 Columns', 'anaglyph-lite'),
								'3' => __('3 Columns', 'anaglyph-lite'),
								'4' => __('4 Columns', 'anaglyph-lite'),
							),
							'default'   => '4'
						),
						
						array(
							'id'        => 'shop-animations',
							'type'      => 'select',
							'title'     => __('Select animation for products.', 'anaglyph-lite'),
							'options'   => array(
								'top' => __("Top", "anaglyph-lite"), 
								'left' => __("Left", "anaglyph-lite"), 
								'right' => __("Right", "anaglyph-lite"),
								'bottom' => __("Bottom", "anaglyph-lite"),
								'none' => __("None", "anaglyph-lite"),
							),
							'default'   => 'bottom'
						),
						
					)
				);
			}	
            
            $arr_section['import-export'] = array(
                'title'     => __('Import / Export', 'anaglyph-lite'),
                'desc'      => __('Import and Export your anaglyph Framework settings from file, text or URL.', 'anaglyph-lite'),
                'icon'      => $sample_patterns_url . 'images/icons/import-export.png',
				'icon_type' => 'image',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your anaglyph options',
                        'full_width'    => false,
                    ),
                ),
            );  
			
			$fields = apply_filters( 'anaglyph_admin_fields', $arr_section);
			$this->sections = $fields;
        }

        public function setHelpTabs() {}


        public function setArguments() {
			$source_path = get_template_directory_uri() . '/includes/admin/anaglyph-options/patterns/';
			
            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'anaglyph_config',        // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $this->theme->get('Name'),      // Name that appears at the top of your panel
                'display_version'   => $this->theme->get('Version'),   // Version that appears at the top of your panel
                'menu_type'         => 'menu',                   // Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => false,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Anaglyph options', 'anaglyph-lite'),
                'page_title'        => __('Anaglyph options', 'anaglyph-lite'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '861074126314', // Must be defined to add google fonts to the typography module
				'google_update_weekly' => false,
                
                'async_typography'  => false,                   // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => 'anaglyph_config',       // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                   // Show the time the page took to load, etc
                'customizer'        => false,                   // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => $source_path . 'anaglyph-icon.png',  // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'anaglyph-icon',         // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => 'anaglyph_options',      // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '*',                     // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => false,                  // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                   // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                   // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit'     => '<span id="footer-thankyou">' . __( 'Anaglyph Options panel created using "Reduxe Framework".', 'anaglyph-lite' ). '</span>',                     // Disable the footer credit of anaglyph. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', 	  // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE
				'page_type'				=> 'submenu',
				
                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'slide',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'slide',
                            'duration'  => '500',
                            'event'     => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/Fruitfulcode',
                'title' => 'Visit us on GitHub',
                'img'   => esc_url($source_path . 'images/icons/github.png'), 
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/fruitfulc0de',
                'title' => 'Like us on Facebook',
                'img'   => esc_url($source_path . 'images/icons/facebook.png'), 
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://twitter.com/fruitfulcode',
                'title' => 'Follow us on Twitter',
                'img'   => esc_url($source_path . 'images/icons/twitter.png'), 
            );
            

            // Panel Intro text -> before the form
            if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v   = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                 $this->args['intro_text'] = '';
			   //sprintf(__('', 'anaglyph-lite'), $v);
            } else {
                $this->args['intro_text']  = '';
            }

            // Add content after the form.
            $this->args['footer_text'] = '';
		}
	}
}

global $anaglyphConfig;
   	   $anaglyphConfig = new anaglyph_config();
