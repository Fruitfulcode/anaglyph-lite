<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category Anaglyph
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/webdevstudios/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'anaglph_all_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function anaglph_all_metaboxes( array $meta_boxes ) {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_anaglyph_';
	
	$meta_boxes['anaglph_post_title_image'] = array(
		'id'         => 'anaglph_post_title_image',
		'title'      => __( 'Image + Title', 'anaglyph-lite' ),
		'pages'      => array( 'post'), 
		'context'    => 'side',
		'priority'   => 'low',
		'show_names' => true, 
		'fields'     => array(
			array(
				'name'    => __( 'Title', 'anaglyph-lite' ),
				'subname' => __( 'Edit additional title name', 'anaglyph-lite' ),
				'id'      => $prefix . 'image_title_text',
				'type' 	  => 'text_medium',
				'default' => __('Blog Listing', 'anaglyph-lite')
			),
			
			array(
				'name' => __('Image for title', 'anaglyph-lite'),
				'subname' => __('Upload image for title background.', 'anaglyph-lite' ),
				'id'   => $prefix . 'image_title_img',
				'type' => 'file',
				'allow' => array( 'attachment' )
			),
		),
	);
		
	
	
	// Sidebar pages
	$meta_boxes['anaglph_page_general_settings'] = array(
		'id'         => 'anaglph_page_general_settings',
		'title'      => __( 'Layout', 'anaglyph-lite' ),
		'pages'      => array( 'page'), 
		'context'    => 'side',
		'priority'   => 'low',
		'show_names' => true, 
		'fields'     => array(
			array(
				'name'    => __( 'Page layout', 'anaglyph-lite' ),
				'subname'    => __( 'Select a specific layout for this page.', 'anaglyph-lite' ),
				'id'      => $prefix . 'page_layout',
				'type' 	  => 'custom_layout_sidebars',
				'default' => 0
			),
		),
	);
	return $meta_boxes;
}	