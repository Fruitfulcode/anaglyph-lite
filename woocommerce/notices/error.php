<?php
/**
 * Show error messages
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version   3.1.1
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! $messages ) return;
?>
<div class="alert alert-danger">
	<ul> <!--class="woocommerce-error"--> 
		<?php foreach ( $messages as $message ) : ?>
			<li><?php echo wp_kses_post( $message ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>	