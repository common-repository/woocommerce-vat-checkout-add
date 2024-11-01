<?php
/*
Plugin Name: WooCommerce VAT
Plugin URI: http://www.wpdoze.com
Description: WooCommerce VAT plugin
Author: WP Doze
Author URI: http://www.wpdoze.com
Version: 1.0.1

	Copyright: © 2012 WPDoze (email : bitdoze1@gmail.com)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Check if WooCommerce is active
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Check if WooCommerce is active and bail if it's not
if ( ! WoocommerceCustomCheckoutVATField::is_woocommerce_active() )
	return;

/**
 * The WoocommerceCustomCheckoutVATField global object
 * @name $woocommerce_custom_checkout_VAT_field
 * @global WoocommerceCustomCheckoutVATField $GLOBALS['woocommerce_custom_checkout_VAT_field']
 */
$GLOBALS['woocommerce_custom_checkout_VAT_field'] = new WoocommerceCustomCheckoutVATField();

class WoocommerceCustomCheckoutVATField {

	public function __construct() {
		// Installation
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) $this->install();

		add_action( 'woocommerce_init', array( $this, 'init' ) );
		
	}
	public function init() {
	
		add_filter( 'woocommerce_checkout_fields' , array( $this,'VAT_override_checkout_fields' ));
		add_action( 'woocommerce_admin_order_data_after_billing_address',  array( $this,'VAT_custom_checkout_field_order_meta_keys' ));
		
	}
	

 
		// Our hooked in function - $fields is passed via the filter!
	public function VAT_override_checkout_fields( $fields ) {
				$fields['billing']['VAT_cui'] = array(
				'label'     => __('VAT', 'woocommerce'),
				'placeholder'   => _x('VAT', 'placeholder', 'woocommerce'),
				'required'  => true,
				'class'     => array('form-row-wide'),
				'clear'     => true
				);
 
				return $fields;
}

	public function VAT_custom_checkout_field_order_meta_keys( $order ) {
		echo "<p><strong>VAT:</strong>" .
		//$order->order_custom_fields['_VAT_cui'][0] . "</p>";
		 get_post_meta( $order->id, '_VAT_cui', true ) . "</p>";
		
	
}

	public static function is_woocommerce_active() {

		$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() )
			$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );

		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', $active_plugins );
	}




	/** Lifecycle methods ******************************************************/


	/**
	 * Run every time.  Used since the activation hook is not executed when updating a plugin
	 */
	private function install() {

	
	}

}