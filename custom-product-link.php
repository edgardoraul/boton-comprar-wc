<?php
/**
 * Plugin Name: Custom Product Link for WooCommerce
 * Description: Añade un campo personalizado en productos WooCommerce para un enlace y lo muestra en el frontend.
 * Version: 1.0
 * Author: ...:: WebModerna | Estudio Contable y Agencia Web ::...
 * Author URI:  https://webmoderna.com.ar/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Agregar el campo personalizado en el backoffice
add_action('woocommerce_product_options_general_product_data', 'cpl_add_custom_field');
function cpl_add_custom_field() {
    woocommerce_wp_text_input([
        'id'          => '_custom_product_link',
        'label'       => __('Custom Product Link', 'custom-product-link'),
        'placeholder' => 'https://example.com',
        'desc_tip'    => true,
        'description' => __('Añade un enlace personalizado para este producto.', 'custom-product-link'),
        'type'        => 'url',
    ]);
}

// Guardar el valor del campo
add_action('woocommerce_process_product_meta', 'cpl_save_custom_field');
function cpl_save_custom_field($post_id) {
    $custom_link = isset($_POST['_custom_product_link']) ? esc_url_raw($_POST['_custom_product_link']) : '';
    update_post_meta($post_id, '_custom_product_link', $custom_link);
}

// Mostrar el enlace como botón en el frontend al final de la descripción corta
add_action('woocommerce_single_product_summary', 'cpl_display_custom_link', 25);
function cpl_display_custom_link() {
    global $post;
    $custom_link = get_post_meta($post->ID, '_custom_product_link', true);

    if (!empty($custom_link)) {
        ?>
        <div class="product_meta">
            <button type="button" 
                class="single_add_to_cart_button button alt btn" 
                onclick="window.open('<?php echo esc_url($custom_link); ?>', '_blank')">
                <?php _e('Comprarlo', 'custom-product-link'); ?>
            </button>
        </div>
        <?php
    }
}