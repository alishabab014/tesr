<?php

add_action( 'wp_ajax_rozer_get_ajax_search', 'rozer_get_ajax_search');
add_action( 'wp_ajax_nopriv_rozer_get_ajax_search', 'rozer_get_ajax_search');
function rozer_get_categories_tree($depth) {
        $args = array(
            'taxonomy'     => 'product_cat',
            'child_of'     => 0,
            'parent'       => 0,
            'orderby'      => 'name',
            'show_count'   => 1,
            'pad_counts'   => 0,
            'hierarchical' => 0,
            'title_li'     => '',
            'hide_empty'   => 0
        );
        $product_categories = get_categories( $args );
        ?>
        <select name="product_cat" class="product_categories">
            <option value="" selected=""><?php esc_html_e( 'All Categories', 'rozer' ) ?></option>
            <?php
            foreach ($product_categories as $item) {
                ?>
                <option value="<?php echo esc_attr($item->slug); ?>"><?php echo esc_attr($item->name); ?></option>
                <?php
                rozer_get_search_option($item->term_id, $level = 1, $depth);
            }
            ?>
        </select>
        <?php
}
function rozer_get_search_option($id_cat, $level, $depth) {
    if($level >= $depth) return;
    $args2 = array(
        'taxonomy'     => 'product_cat',
        'child_of'     => 0,
        'parent'       => $id_cat,
        'orderby'      => 'name',
        'show_count'   => 1,
        'pad_counts'   => 0,
        'hierarchical' => 1,
        'title_li'     => '',
        'hide_empty'   => 0
    );
    $sub_cats = get_categories( $args2 );
    if (count($sub_cats) > 0) {
        $level = $level + 1;
        foreach ($sub_cats as $cat) {
            ?>
            <option value="<?php echo esc_attr($cat->slug); ?>"><?php for ($i=0; $i < $level; $i++) {
                    echo '-';
                } echo esc_attr($cat->name); ?></option>
            <?php
            rozer_get_search_option($cat->term_id, $level, $depth);
        }
    }
}
function rozer_get_ajax_search() {
    if(isset($_GET['keyword'])) {
        $suggestions = array();
        $keyword = $_GET['keyword'];
        $product_cat = '';
        if(isset($_GET['product_cat'])) {
            $product_cat = $_GET['product_cat'];
        }
        $key_word_lenght = 3;
        if( strlen($keyword) < $key_word_lenght) {
            echo '<div id="result">'. esc_html__('Please enter at least', 'rozer') . ' ' .$key_word_lenght. ' ' . esc_html__('characters', 'rozer') . '</div>';
        } else {
            $filter = "AND hs.post_title LIKE '%".$keyword."%' ";
            $search_type = rdt_get_option('header_search_resource' , 'product-post');
            $items_show = rdt_get_option('header_search_limit', 10);
            if( $items_show > 0 ) {
                $limit = "LIMIT ".$items_show;
            }
            // product results
            $tax = 'product_cat';
            if($product_cat && $product_cat != 'none'){
                $tax_arr = array(                    
                    array(
                        'taxonomy' => $tax,                
                        'field' => 'slug',                   
                        'terms' => $product_cat,    
                        'operator' => 'IN'                    
                    )
                );
            }else{
                $product_visibility_term_ids = wc_get_product_visibility_term_ids();
                $tax_arr = array( 
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_taxonomy_id',
                        'terms'    => $product_visibility_term_ids['exclude-from-search'],
                        'operator' => 'NOT IN',
                    )
                );
            }   
            $args = array( 
                'post_type' => 'product',
                'post_status'    => 'publish',
                'posts_per_page' => $items_show,
                's' => $keyword,
                'tax_query' => $tax_arr
            );
            $results = new WP_Query( $args );
            if( $results->have_posts() ) {
                $factory = new WC_Product_Factory();
                while( $results->have_posts() ) {
                    $results->the_post();
                    $product = $factory->get_product( get_the_ID() );
                    $suggestions[] = array(
                        'value' => get_the_title(),
                        'permalink' => get_the_permalink(),
                        'price' => $product->get_price_html(),
                        'thumbnail' => $product->get_image('thumbnail'),
                    );
                }
                wp_reset_postdata();
            }else{
                $suggestions[] = array(
                    'value' => esc_html__('No product found' , 'rozer'),
                    'not_found' => true,
                    'permalink' => '',
                );
            }
            //post results
            if($search_type == 'product-post'){
                $devider = array(
                    'value' => '',
                    'divider' => esc_html__('Results from blog', 'rozer'),
                );
                array_push($suggestions, $devider);
                $args = array( 
                    'post_type' => 'post',
                    'post_status'    => 'publish',
                    'posts_per_page' => $items_show,
                    's' => $keyword,
                    'tax_query' => $tax_arr
                );
                $results = new WP_Query( $args );
                if( $results->have_posts() ) {
                    while( $results->have_posts() ) {
                        $results->the_post();
                        $suggestions[] = array(
                            'value' => get_the_title(),
                            'permalink' => get_the_permalink(),
                            'thumbnail' => get_the_post_thumbnail( null, 'thumbnail', '' ),
                        );
                    }
                    wp_reset_postdata();
                }else{
                    $suggestions[] = array(
                        'value' => esc_html__('No post found' , 'rozer'),
                        'not_found' => true,
                        'permalink' => '',
                    );
                }
            }
            wp_send_json($suggestions);
        }
        wp_die();
    }
    die();
}
