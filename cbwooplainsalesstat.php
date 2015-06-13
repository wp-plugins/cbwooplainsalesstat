<?php
/**
 * Plugin Name:       CBX Woocommerce Plain Sales Stat
 * Plugin URI:        http://wpboxr.com/product/woocommerce-plain-sales-report-for-wordpress
 * Description:       Plain Sales Stat For Woocommerce
 * Version:           1.0.1
 * Author:            codeboxr
 * Author URI:        http://codeboxr.com
 * Text Domain:       cbwooplainsalesstat
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-cbwooplainsalesstat.php' );

register_activation_hook( __FILE__, array( 'Cbwooplainsalesstat', 'cbwooplainsalesstat_activate' ) );
register_deactivation_hook( __FILE__, array( 'Cbwooplainsalesstat', 'cbwooplainsalesstat_deactivate' ) );


add_action( 'plugins_loaded', array( 'Cbwooplainsalesstat', 'get_instance' ) );


if ( is_admin()  ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-cbwooplainsalesstat-admin.php' );
	add_action( 'plugins_loaded', array( 'CbwooplainsalesstatAdmin', 'get_instance' ) );

}
