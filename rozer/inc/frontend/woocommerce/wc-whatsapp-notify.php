<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Send a WhatsApp message to the shop admin via Meta's WhatsApp Cloud API
 * whenever a new order is placed.
 *
 * Requires three constants to be defined in wp-config.php (NOT in this
 * theme file, so the credentials never end up in version control):
 *
 *   define( 'AAA_WHATSAPP_ACCESS_TOKEN', '...' );
 *   define( 'AAA_WHATSAPP_PHONE_NUMBER_ID', '...' );
 *   define( 'AAA_WHATSAPP_ADMIN_NUMBER', '...' ); // e.g. 923189305684, no leading +
 */
add_action( 'woocommerce_thankyou', 'aaa_whatsapp_order_notify', 10, 1 );
function aaa_whatsapp_order_notify( $order_id ) {
	if ( ! $order_id ) {
		return;
	}

	if ( ! defined( 'AAA_WHATSAPP_ACCESS_TOKEN' ) || ! defined( 'AAA_WHATSAPP_PHONE_NUMBER_ID' ) || ! defined( 'AAA_WHATSAPP_ADMIN_NUMBER' ) ) {
		return;
	}

	// Avoid sending twice for the same order (e.g. on page refresh).
	if ( get_post_meta( $order_id, '_aaa_whatsapp_notified', true ) ) {
		return;
	}

	$order = wc_get_order( $order_id );
	if ( ! $order ) {
		return;
	}

	$items_text = '';
	foreach ( $order->get_items() as $item ) {
		$items_text .= "- {$item->get_name()} x{$item->get_quantity()}\n";
	}

	$address = $order->has_shipping_address() ? $order->get_formatted_shipping_address() : $order->get_formatted_billing_address();
	$address = wp_strip_all_tags( str_replace( '<br/>', ', ', $address ) );

	$message = sprintf(
		"🛒 *New Order #%s*\n\n%s\n*Total:* %s\n*Payment:* %s\n\n*Customer:* %s\n*Phone:* %s\n*Address:* %s",
		$order->get_order_number(),
		$items_text,
		wp_strip_all_tags( $order->get_formatted_order_total() ),
		$order->get_payment_method_title(),
		$order->get_formatted_billing_full_name(),
		$order->get_billing_phone(),
		$address
	);

	$response = wp_remote_post( 'https://graph.facebook.com/v20.0/' . AAA_WHATSAPP_PHONE_NUMBER_ID . '/messages', array(
		'headers' => array(
			'Authorization' => 'Bearer ' . AAA_WHATSAPP_ACCESS_TOKEN,
			'Content-Type'  => 'application/json',
		),
		'body'    => wp_json_encode( array(
			'messaging_product' => 'whatsapp',
			'to'                => AAA_WHATSAPP_ADMIN_NUMBER,
			'type'              => 'text',
			'text'              => array( 'body' => $message ),
		) ),
		'timeout' => 15,
	) );

	if ( is_wp_error( $response ) ) {
		error_log( 'AAA WhatsApp notify failed: ' . $response->get_error_message() );
		return;
	}

	$code = wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		error_log( 'AAA WhatsApp notify failed (' . $code . '): ' . wp_remote_retrieve_body( $response ) );
		return;
	}

	update_post_meta( $order_id, '_aaa_whatsapp_notified', 1 );
}
