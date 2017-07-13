<?php
/**
 * Plugin Name: Order Coupon Field for WooCommerce
 * Description: Records each coupon code used for an order in an order meta field for reporting purposes.
 * Version: 1.0.1
 * Author: Potent Plugins
 * Author URI: http://potentplugins.com/?utm_source=order-coupon-field-for-woocommerce&utm_medium=link&utm_campaign=wp-plugin-author-uri
 * License: GNU General Public License version 2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 */

if (!defined('ABSPATH'))
	die();

add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'pp_wccptf_action_links');
function pp_wccptf_action_links($links) {
	array_unshift($links, '<a href="'.esc_url(get_admin_url(null, 'admin.php?page=pp_wccptf')).'">About &amp; Settings</a>');
	return $links;
}

add_action('woocommerce_new_order_item', 'pp_wccptf_new_order_item', 10, 3);
function pp_wccptf_new_order_item($itemId, $item, $orderId) {
	if ($item->get_type() != 'coupon') {
		return;
	}
	global $pp_wccptf_settings;
	if (!isset($pp_wccptf_settings))
		$pp_wccptf_settings = get_option('pp_wccptf_settings', pp_wccptf_default_settings());
	$code = $item->get_code();
	if (!empty($pp_wccptf_settings['code_on'])) {
		add_post_meta($orderId, $pp_wccptf_settings['meta_key'], $code);
	}
	if (!empty($pp_wccptf_settings['desc_on'])) {
		$coupon = new WC_Coupon($code);
		add_post_meta($orderId, $pp_wccptf_settings['desc_key'], $coupon->get_description());
	}
}

add_action('woocommerce_order_add_coupon', 'pp_wccptf_coupon_added', 10, 3);
function pp_wccptf_coupon_added($orderId, $orderItemId, $couponCode) {
	global $woocommerce, $pp_wccptf_settings;
	if (version_compare($woocommerce->version, '3.0') >= 0) {
		return;
	}
	if (!isset($pp_wccptf_settings))
		$pp_wccptf_settings = get_option('pp_wccptf_settings', pp_wccptf_default_settings());
	if (!empty($pp_wccptf_settings['code_on'])) {
		add_post_meta($orderId, $pp_wccptf_settings['meta_key'], $couponCode);
	}
}

add_action('admin_menu', 'pp_wccptf_admin_menu');
function pp_wccptf_admin_menu() {
	add_submenu_page('woocommerce', 'Order Coupon Field', 'Order Coupon Field', 'manage_woocommerce', 'pp_wccptf', 'pp_wccptf_page');
}

function pp_wccptf_default_settings() {
	return array(
		'code_on' => 1,
		'meta_key' => 'order_coupon',
		'desc_on' => 0,
		'desc_key' => 'order_coupon_description'
	);
}

function pp_wccptf_page() {
	
	// Print header
	echo('
		<div class="wrap">
			<h2>Order Coupon Field for WooCommerce</h2>
			<style scoped>
				p {
					max-width: 600px;
				}
				form {
					padding-bottom: 50px;
				}
				label {
					display: block;
					margin-bottom: 10px;
					vertical-align: baseline;
				}
				label em {
					margin-left: 10px;
				}
			</style>
	');
	
	// Check for WooCommerce
	if (!class_exists('WooCommerce')) {
		echo('<div class="error"><p>This plugin requires that WooCommerce is installed and activated.</p></div></div>');
		return;
	} else if (!function_exists('wc_get_order_statuses')) {
		echo('<div class="error"><p>This plugin requires WooCommerce 2.2 or higher. Please update your WooCommerce install.</p></div></div>');
		return;
	}
	
	$settings = pp_wccptf_default_settings();
	
	if (!empty($_POST)) {
		check_admin_referer('pp_wccptf_settings_save');
		$_POST['code_on'] = (empty($_POST['code_on']) ? 0 : 1);
		$_POST['desc_on'] = (empty($_POST['desc_on']) ? 0 : 1);
		if (empty($_POST['meta_key'])) {
			unset($_POST['meta_key']);
		}
		if (empty($_POST['desc_key'])) {
			unset($_POST['desc_key']);
		}
		update_option('pp_wccptf_settings', array_merge($settings, array_intersect_key($_POST, $settings)));
	}
	
	$settings = array_merge($settings, get_option('pp_wccptf_settings', array()));
	global $woocommerce;
	$wcLt3 = (version_compare($woocommerce->version, '3.0') < 0);
	
	echo('
		<p>This plugin records information about each coupon used for an order in order meta fields for reporting purposes. You can change the names of the meta fields below. Note that the data recorded reflects coupons added to the order (after this functionality is enabled) and persists even if the order\'s coupons are later modified or deleted.
			To report on this data, check out
			<a href="https://potentplugins.com/downloads/product-sales-report-pro-wordpress-plugin/?utm_source=order-coupon-field-for-woocommerce&utm_medium=link&utm_campaign=wp-plugin-referral" target="_blank">Product Sales Report Pro</a> (for reporting on product sales totals filtered by coupon code used) or
			<a href="https://potentplugins.com/downloads/export-order-items-pro-wordpress-plugin/?utm_source=order-coupon-field-for-woocommerce&utm_medium=link&utm_campaign=wp-plugin-referral" target="_blank">Export Order Items Pro</a> (for exporting individual order items with the order coupon code as a column).</p>
		
		<form action="" method="post">
			
	');
	wp_nonce_field('pp_wccptf_settings_save');
	echo('
			<label>
				<input type="checkbox" name="code_on"'.($settings['code_on'] ? ' checked="checked"' : '').' />
				Save coupon code in meta field:
				<input type="text" name="meta_key" value="'.esc_html($settings['meta_key']).'" />
			</label>
			<label>
				<input type="checkbox" name="desc_on"'.($settings['desc_on'] && !$wcLt3 ? ' checked="checked"' : '').($wcLt3 ? ' disabled="disabled"' : '').' />
				Save coupon description in meta field:
				<input type="text" name="desc_key" value="'.esc_html($settings['desc_key']).'" />'.($wcLt3 ? '<em>Requires WooCommerce version 3.0 or greater.</em>' : '').'
			</label>
			<button type="submit" class="button-primary">Save Changes</button>
		</form>');
		
	$potent_slug = 'order-coupon-field-for-woocommerce';
	include(__DIR__.'/plugin-credit.php');
	
	echo('</div>');
}

register_activation_hook(__FILE__, 'pp_wccptf_activate');
function pp_wccptf_activate() {
	$settings = get_option('pp_wccptf_settings', false);
	if ($settings !== false) {
		if (isset($settings['_inactive']))
			unset($settings['_inactive']);
		update_option('pp_wccptf_settings', $settings, true);
	}
}

register_deactivation_hook(__FILE__, 'pp_wccptf_deactivate');
function pp_wccptf_deactivate() {
	$settings = get_option('pp_wccptf_settings', false);
	if ($settings !== false) {
		$settings['_inactive'] = 1;
		update_option('pp_wccptf_settings', $settings, false);
	}
}

?>