<?php

/*Metaboxes activation*/
add_action( 'init', 'cmb_initialize_cmb_meta_boxes', 999 );
function cmb_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) ) require_once dirname(__FILE__) . '/metaboxes/init.php';
}
require dirname(__FILE__) . '/metaboxes/custom-fields-for-metaboxes.php';
require dirname(__FILE__) . '/metaboxes/allobjects-mb.php';

/*Tgm activation*/
require_once dirname(__FILE__) . '/tgm/class-tgm-init.php';
