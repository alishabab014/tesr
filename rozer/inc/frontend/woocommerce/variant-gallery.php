<?php

// Add template for gallery
function rozer_variant_gallery_template() {
?>
<script type="text/html" id="tmpl-rt-image">
    <li class="image">
        <input type="hidden" name="rt[{{data.product_variation_id}}][]" value="{{data.id}}">
        <img src="{{data.url}}" width="75">
        <a href="#" class="delete rt-remove-image"><span class="dashicons dashicons-dismiss"></span></a>
    </li>
</script>
<?php
}
add_action('admin_footer', 'rozer_variant_gallery_template');
//Display Fields
add_action( 'woocommerce_variation_options', 'rozer_variable_fields', 10, 3 );
//Save variation fields
add_action( 'woocommerce_save_product_variation', 'rozer_save_variable_fields', 10, 2 );
/**
 * Create new fields for variations
 *
*/
function rozer_variable_fields( $loop, $variation_data, $variation) {
    $variation_id = absint($variation->ID);
    $gallery_images = get_post_meta($variation_id, 'rt_images', true);
?>
    <div class="form-row form-row-full rt-gallery-wrapper">
        <h4><?php esc_html_e('Variation Image Gallery', 'rozer') ?></h4>
        <div class="rt-images-container">
            <ul class="rt-images">
                <?php
                if (is_array($gallery_images) && !empty($gallery_images)) {
                    foreach ($gallery_images as $image_id):
                        $image = wp_get_attachment_image_src($image_id);
                        ?>
                        <li class="image">
                            <input type="hidden" name="rt[<?php echo esc_attr($variation_id) ?>][]"
                                   value="<?php echo esc_attr($image_id); ?>">
                            <img src="<?php echo esc_url($image[0]) ?>" width="75">
                            <a href="#" class="delete rt-remove-image"><span
                                        class="dashicons dashicons-dismiss"></span></a>
                        </li>
                    <?php endforeach;
                } ?>
            </ul>
        </div>
        <p class="rt-add-image-wrapper hide-if-no-js">
            <a href="#" data-product_variation_loop="<?php echo absint($loop) ?>"
               data-product_variation_id="<?php echo esc_attr($variation_id) ?>"
               class="button rt-add-image"><?php esc_html_e('Add Gallery Images', 'rozer') ?></a>
        </p>
    </div>
<?php
}
/**
 * Save new fields for variations
 *
*/
function rozer_save_variable_fields( $variation_id, $i) {
    if(isset($_POST['rt'][$variation_id])) {
        // Text Field
        $_start_date = $_POST['rt'][$variation_id];
        update_post_meta( $variation_id, 'rt_images', $_start_date );
    }
}
/**
* Frontend: Add gallery images to variant
*
*/
function rozer_available_variant_gallery($available_variation, $variationProductObject, $variation){
    $available_variation['variation_gallery_images'] = array();
    $product_id = absint($variation->get_parent_id());
    $variation_id = absint($variation->get_id());
    $variation_image_id = absint($variation->get_image_id());
    $has_variation_gallery_images = (bool)get_post_meta($variation_id, 'rt_images', true);
    if ($has_variation_gallery_images) {
        $gallery_images = (array)get_post_meta($variation_id, 'rt_images', true);
    } else {
        $gallery_images = $variationProductObject->get_gallery_image_ids();
    }
    if ($variation_image_id) {
        array_unshift($gallery_images, $variation_image_id);
    } else {
        $parent_product = wc_get_product($product_id);
        $parent_product_image_id = $parent_product->get_image_id();
        if (!empty($parent_product_image_id)) {
            array_unshift($gallery_images, $parent_product_image_id);
        }
    }
    foreach($gallery_images as $image){
        $available_variation['variation_gallery_images'][] = wc_get_product_attachment_props($image);
    }
    return $available_variation;
}
add_filter('woocommerce_available_variation', 'rozer_available_variant_gallery' ,100, 3);