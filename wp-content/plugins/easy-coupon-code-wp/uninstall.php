<?php
/**
 * @link              http://nilaypatel.info
 * @since             1.0.0
 * @package           Easy_Coupon_Code_WP
 *
 * Fired when plugin will be uninstall
 * Text Domain:       easy-coupon-code-wp
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('npcc_activated_on');
delete_option('npcc_deactivated_on');
