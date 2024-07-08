<?php
/**
 * Plugin Name: Prixz WooCommerce Product Get Data Bought Together
 * Description: Plugin para obtener información de los productos que se compran conjuntamente al producto actual en single-product en WooCommerce en formato JSON.
 * Version: 1.1
 * Author: Woo Prixz Team
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Hook para crear un endpoint 
add_action('rest_api_init', function () {
    register_rest_route('wc-product-info-bought-together/v1', '/product/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_product_info',
        'permission_callback' => '__return_true', 
    ));
});

function get_product_info($data) {
    $product_id = $data['id'];

    // Intentar obtener el token almacenado en caché
    $token = get_transient('prixz_api_products_get_data_token');

    // Si el token no existe o ha expirado, obtener uno nuevo
    if (!$token) {
        // URL para obtener el token
        $token_url = 'https://prixzapp.uc.r.appspot.com/api/auth/login/service';
        $token_body = array(
            'username' => 'wocommerce',
            'password' => 'AShy83env8C8e8C5AH'
        );

        // Solicitud para obtener el token
        $response = wp_remote_post($token_url, array(
            'body' => wp_json_encode($token_body),
            'headers' => array('Content-Type' => 'application/json')
        ));

        if (is_wp_error($response)) {
            return new WP_Error('token_error', 'Error al obtener el token', array('status' => 500));
        }

        $token_response = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($token_response['payload']['token'])) {
            return new WP_Error('token_error', 'Token no encontrado en la respuesta', array('status' => 500));
        }

        $token = $token_response['payload']['token'];

        // Almacenar el token en caché durante 6 meses (en segundos)
        set_transient('prixz_api_products_get_data_token', $token, 6 * MONTH_IN_SECONDS);
    }

    // URL para obtener la información del producto
    $product_url = 'https://us-central1-microservices-pro-723e4.cloudfunctions.net/products/getAllProductsByIdJust20';
    $product_url = add_query_arg(array(
        'productId' => $product_id,
        'periodicity' => 'YEAR'
    ), $product_url);

    // Solicitud para obtener la información del producto
    $response = wp_remote_get($product_url, array(
        'headers' => array(
            'x-token' => $token
        )
    ));

    if (is_wp_error($response)) {
        return new WP_Error('product_error', 'Error al obtener la información del producto', array('status' => 500));
    }

    $product_info = json_decode(wp_remote_retrieve_body($response), true);

    return rest_ensure_response($product_info);
}
