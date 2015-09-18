<?php 

/*Adding cutom metabox filed*/
/*Made by anaglyph*/
	
add_action( 'cmb_render_custom_layout_sidebars', 'anaglyph_custom_layout_sidebars', 10, 2 );
function anaglyph_custom_layout_sidebars( $field, $meta ) {
	$layout = 0;
	$layout = $meta ? $meta : $field['default'];
    ?>
		<ul class="list-layouts">
			<li>
				<input type="radio" id="full-width" value="0" name="<?php echo $field['id'];?>"  <?php checked( $layout, '0' ); ?>/>
				<img src="<?php echo CMB_META_BOX_URL . 'images/full.png'; ?>" alt="" />
			</li>
			<li>
				<input type="radio" id="left-sidebar" value="1" name="<?php echo $field['id'];?>"  <?php checked( $layout, '1' ); ?>/>
				<img src="<?php echo CMB_META_BOX_URL . 'images/left.png'; ?>" alt="" />
			</li>
			<li>
				<input type="radio" id="right-sidebar" value="2" name="<?php echo $field['id'];?>"  <?php checked( $layout, '2' ); ?>/>
				<img src="<?php echo CMB_META_BOX_URL . 'images/right.png'; ?>" alt="" />
			</li>
		</ul>
		<p class="cmb_metabox_description"><?php echo esc_attr($field['desc']); ?></p>
	<?php
}


add_action( 'admin_enqueue_scripts', 'anaglyph_custom_layout_sidebars_script' );
function anaglyph_custom_layout_sidebars_script($hook) {
	wp_register_script( 'cmb-layouts', CMB_META_BOX_URL . 'js/layout/layout.js'  );
	wp_register_style ( 'cmb-layouts', CMB_META_BOX_URL . 'js/layout/layout.css' );
	
	if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page-new.php' || $hook == 'page.php' ) {
		wp_enqueue_script( 'cmb-layouts' );
		wp_enqueue_style ( 'cmb-layouts' );
	}
}
	
function anaglyph_get_image($attachment_id) {
	$out = "";
	$image_attributes = wp_get_attachment_image_src( $attachment_id, 'thumbnail');
	$image_full 	  = wp_get_attachment_image_src( $attachment_id, 'full');
	
	$out .= '<li class="img_status">';
		$out .= '<img id="image-'.$attachment_id.'" src="'. $image_attributes[0] .'" alt="" />';
		$out .= '<p class="cmb_remove_wrapper"><a href="#" class="cmb_remove_file_button">'. __( 'Remove Image', 'anaglyph-lite' ) .'</a></p>';
		$out .= '<input type="hidden" value="'.$image_full[0].'" name="_anaglyph_glry_list['.$attachment_id.']" />';
	$out .= '</li>';
	
	return $out;
}


function anaglyph_sort_glr_list($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

add_action( 'save_post',  'anaglyph_save_postdata', 10, 5);
function anaglyph_save_postdata($post_id) {
	if(defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE) return;
	if(!isset ($_POST['anaglyph_gallery_nonce'])) 	  return;
	if(!is_admin() || !wp_verify_nonce( $_POST['anaglyph_gallery_nonce'], 'anaglyph_gallery' ) ) return;
	
	if ( 'page' == $_POST['post_type'] ) { 
		if ( !current_user_can( 'edit_page', $post_id ) )
			return;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;
	}
	$gallery_keys = array();
	$old_gallery_data = get_post_meta($post_id, '_anaglyph_glry_list', true);
	if(isset($_POST['_anaglyph_glry_list'])) {
		$new_data = $_POST['_anaglyph_glry_list'];
		
		if (is_array($new_data)) {
			$gallery_keys = array_keys($new_data);
			usort($gallery_keys, 'anaglyph_sort_glr_list');
		}
		
		anaglyph_save_meta_data($post_id, $gallery_keys, $old_gallery_data, '_anaglyph_glry_list');
	}
}

function anaglyph_save_meta_data($post_id, $new_data, $old_data, $name){
	if ($new_data == $old_data){ 
		add_post_meta($post_id, $name, $new_data, true);
	} else if(!$new_data){
		delete_post_meta($post_id, $name, $old_data);
	} else if($new_data != $old_data){
		update_post_meta($post_id, $name, $new_data, $old_data);
	}
	return;
}
		
add_action( 'wp_ajax_anaglyph_add_new_element_action', 'anaglyph_add_new_element');
function anaglyph_add_new_element() {
	$out = "";
	if(!is_admin() || !wp_verify_nonce( $_POST['anaglyph_ajax_nonce'], 'anaglyph_add_img_ajax_nonce' ) ) {
		return;
	}
	
	$image_url = $_POST['image_url'];
	$image_id  = $_POST['image_id'];
	$image_attributes = wp_get_attachment_image_src( $image_id, 'thumbnail');
	$image_full = wp_get_attachment_image_src( $image_id, 'full');
	
	$out .= '<li class="img_status">';
		$out .= '<img id="image-'.$image_id.'" src="'. $image_attributes[0] .'" alt="" />';
		$out .= '<p class="cmb_remove_wrapper"><a href="#" class="cmb_remove_file_button">'. __( 'Remove Image', 'anaglyph-lite' ) .'</a></p>';
		$out .= '<input type="hidden" value="'.$image_full[0].'" name="_anaglyph_glry_list['.$image_id.']" />';
	$out .= '</li>';
	
	echo $out;
	die();
}

		
add_action( 'cmb_render_custom_gallery_list', 'anaglyph_custom_gallery_list', 10, 2 );
function anaglyph_custom_gallery_list( $field, $meta) {
	$out = $gallery_items = '';
	$gallery_data = array();
	if (!empty($meta) && is_array($meta)) {
		$gallery_data = $meta;
	}
	$j = 0;
	if (!empty($gallery_data)) {
		foreach($gallery_data as $key => $value) {
			$gallery_items .= anaglyph_get_image($key);
			$j++;
		}
	}
	wp_nonce_field('anaglyph_gallery', 'anaglyph_gallery_nonce' );
	$out .= '<input type="hidden" value="" name="_anaglyph_glry_list" />';
	$out .= '<input type="button" class="button add_gallery_items_button" value="'. __('Add Images', 'anaglyph-lite') .'"/>';
	$out .= '<div class="soratble-inner">';
		$out .= '<ul id="sortable" class="sortable-admin-gallery cmb_media_status attach_list">';
			$out .= $gallery_items;
		$out .= '</ul>';
	$out .= '</div>';

	echo $out;

}

add_action( 'admin_enqueue_scripts', 'anaglyph_custom_gallery_list_script' );
function anaglyph_custom_gallery_list_script($hook) {
	if ( $hook == 'post.php' || $hook == 'post-new.php' || $hook == 'page-new.php' || $hook == 'page.php' ) {
		if(function_exists( 'wp_enqueue_media' )){
			wp_enqueue_media();
		} else {
			wp_enqueue_style ('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
		}
						
		wp_enqueue_script	( 'anaglyph-gallery-js',  CMB_META_BOX_URL  . 'js/gallery/gallery-init.js',  array('jquery'));
		wp_enqueue_style	( 'anaglyph-gallery-css', CMB_META_BOX_URL  . 'js/gallery/gallery-admin.css' ); 
		wp_localize_script	( 'anaglyph-gallery-js',  'anaglyph_vars_ajax', array(
															'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
															'ajax_nonce' 	=> wp_create_nonce( 'anaglyph_add_img_ajax_nonce' ),
												));
	}
}