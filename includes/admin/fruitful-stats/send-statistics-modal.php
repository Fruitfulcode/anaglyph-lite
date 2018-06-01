<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 31/05/18
 * Time: 13:00
 */

if ( class_exists( 'ReduxFramework' ) ) {
	/**
	 * Enqueue scripts for all admin pages
	 */
	add_action( 'admin_enqueue_scripts', 'anaglyph_add_admin_scripts' );
	function anaglyph_add_admin_scripts() {
		wp_enqueue_script( 'fruitful-stats-modal', get_template_directory_uri() . '/includes/admin/fruitful-stats/assets/js/admin_scripts.js', array( 'jquery' ) );
		wp_enqueue_style( 'fruitful-stats-modal-styles', get_template_directory_uri() . '/includes/admin/fruitful-stats/assets/styles/admin_styles.css' );
	}

	function anaglyph_shortcodes_admin_notice() {
		global $anaglyph_config;
		$options = $anaglyph_config;

		if ( $options['ffc_is_hide_subscribe_notification'] === '0' ) {
			require get_template_directory(). '/includes/admin/fruitful-stats/view/send-statistics-modal-view.php';
		}
	}

	add_action( 'admin_footer', 'anaglyph_shortcodes_admin_notice' );


	add_action( 'wp_ajax_anaglyph_submit_modal', 'anaglyph_submit_modal' );
	function anaglyph_submit_modal() {

		global $anaglyph_config;
		$request_data = $_POST['data'];

		$response = array(
			'status'            => 'failed',
			'title'             => __( 'Uh oh!', 'anaglyph-lite' ),
			'error_message'     => __( 'Sorry, something went wrong, and we failed to receive the shared data from you.', 'anaglyph-lite' ),
			'error_description' => __( 'No worries; go to the theme option to enter the required data manually and save changes.', 'anaglyph-lite' ),
			'stat_msg'          => '',
			'subscr_msg'        => ''
		);


		if ( ! empty( $request_data ) ) {
			foreach ( $request_data as $option => $value ) {
				if ( isset( $anaglyph_config[ $option ] ) ) {
					Redux::setOption( 'anaglyph_config', $option, $value );
				}
			}
			Redux::setOption( 'anaglyph_config', 'ffc_is_hide_subscribe_notification', '1' );

			if ( $request_data['ffc_statistic'] === '1' || $request_data['ffc_subscribe'] === '1' ) {
				$response = array(
					'status'            => 'success',
					'title'             => __( 'Thank you!', 'anaglyph-lite' ),
					'error_message'     => '',
					'error_description' => '',
					'stat_msg'          => __( 'Thank you for being supportive, we appreciate your understanding and assistance!', 'anaglyph-lite' ),
					'subscr_msg'        => $request_data['ffc_subscribe'] === '1' ? __( "Don't forget to check your inbox for our latest letter - you’d like that!", 'anaglyph-lite' ) : ''
				);
			} else {
				$response = array(
					'status'            => 'success',
					'title'             => __( 'What a pity!', 'anaglyph-lite' ),
					'error_message'     => '',
					'error_description' => '',
					'stat_msg'          => __( 'We wish you could have shared your site statistic and joined our community.', 'anaglyph-lite' ),
					'subscr_msg'        => __( 'But if you ever change your mind, you can always do that in the theme options.', 'anaglyph-lite' )
				);
			}
		}

		fruitful_send_stats();
		wp_send_json( $response );
	}

	add_action( 'wp_ajax_anaglyph_dismiss_subscribe_notification', 'anaglyph_dismiss_subscribe_notification' );
	function anaglyph_dismiss_subscribe_notification() {
		Redux::setOption( 'anaglyph_config', 'ffc_is_hide_subscribe_notification', '1' );

		wp_send_json( 'success' );
	}
}
