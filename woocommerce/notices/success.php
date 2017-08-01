<?php
/**
 * Show messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version   3.1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
?>

<?php foreach ( $messages as $message ) : ?>
	<!--<div class="woocommerce-message">-->
	<div class="alert alert-success">
		<?php echo wp_kses_post( $message ); ?>
	</div>		
	<!--</div>-->
<?php endforeach; ?>
