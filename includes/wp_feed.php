<?php
function create_category($product_id){
    $array_terms = array();
    $terms_product = wp_get_object_terms($product_id,'product_cat',array('orderby'=>'term_order'));
    foreach($terms_product as $term_product){
            array_push($array_terms, strtolower($term_product->name));
    }
    $array_terms = array_unique($array_terms);
    return implode(' ; ',$array_terms);
}

function create_brand($product_id){
    $brand_select = get_option('wp_trovaprezzi_brand_settings');
    if($brand_select['choice'] != 'custom_post_meta'):
    $brands = get_the_terms($product_id,$brand_select['choice']);
    if($brands) {
        $brand = $brands[0];
        return $brand->name;
    }
    else{
        return false;
    }
  else:
    return get_post_meta($product_id,$brand_select['value'], true);
  endif;
    }

function create_code($product_id){
    global $product;
    $code = '';
    $code_settings = get_option('wp_trovaprezzi_code_settings');
    if($code_settings['choice'] == '_sku'){
        $code = $product->get_sku();
    }else if($code_settings['choice'] == 'product_id'){
        $code = $product_id;
    }else if($code_settings['choice'] == 'custom_post_meta'){
       $code = get_post_meta($product_id,$code_settings['value'],true);
    }
    else{
       $code_term = wp_get_post_terms($product_id,$code_settings,array('fields' => 'names'));
       if($code_term && is_array($code_term) && count($code_term)>0){
           $code = $code_term[0];
       }
    }

    return $code;
}

function create_price($product_id){
    global $product;
    return $product->get_price();
}

function create_description($product_id){
    $description = '';

    $description_settings = get_option('wp_trovaprezzi_description_settings');
    if($description_settings == 'short'){
        $description = substr(get_the_excerpt(),0,250);
    }
    else if($description_settings == 'long'){
        $description = substr(wp_strip_all_tags(get_the_content()),0,250);
    }


    return $description;
}

function create_stock($product_id){
    global $product;
    $in_stock = $product->is_in_stock();
    if($in_stock){
        if($product->get_total_stock()){
            return $product->get_total_stock();
        }
        else{
            return 'disponbile';
        }
    }
    else{
        return 0;
    }

}

function create_image($product_id){
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'large' );
    return $image[0];
}

function create_shipping_cost($product_id)
{
    global $product;
    $shipping_method_cost = '';
    $product_price = create_price($product_id);
    $shipping_method_selected = get_option('wp_trovaprezzi_shipping_cost_settings');
    $shipping_method = maybe_unserialize(get_option('woocommerce_' . $shipping_method_selected . '_settings'));


    switch($shipping_method_selected){
        case 'flat_rate':
        case    'international_delivery':
            $shipping_class_id = $product->get_shipping_class_id();
            if ($shipping_class_id) {
                $shipping_method_cost = $shipping_method['class_cost_' . $shipping_class_id];
            }
            break;
        case 'local_delivery':
            $shipping_method_cost = $shipping_method['fee'];
            break;
        case '0':
            $shipping_method_cost = '';
            break;
        case 'custom':
            $custom_shipping_method_selected = get_option('wp_trovaprezzi_custom_shipping_cost_settings');
            $shipping_method_cost = $custom_shipping_method_selected['value'];
            if($custom_shipping_method_selected['filter']!='-1'){
                if($product_price>= $custom_shipping_method_selected['filter']){
                    $shipping_method_cost = 0;
                }
            }

            break;
    }

    /*
    $shipping_method = maybe_unserialize(get_option('woocommerce_' . $shipping_method_selected . '_settings'));
    if ($shipping_method_selected == 'local_delivery') {
        $shipping_method_cost = $shipping_method['fee'];
    }
    else{
        $shipping_class_id = $product->get_shipping_class_id();
    if ($shipping_class_id && $shipping_method_selected!='0') {

    $shipping_method_cost = $shipping_method['class_cost_' . $shipping_class_id];

}
}
    $free_shipping_settings = maybe_unserialize(get_option('woocommerce_free_shipping_settings'));
    $free_shipping_enabled = $free_shipping_settings['enabled'];
    if ($free_shipping_enabled == 'yes') { // spedizione gratuita abilitata
        $free_shipping_requires = $free_shipping_settings['requires']; // min_amount , either , both
        if (in_array($free_shipping_requires, array('min_amount', 'either', 'both'))) {
            $free_shipping_min_amount = $free_shipping_settings['min_amount'];
            if ($product_price > $free_shipping_min_amount) {
                $shipping_method_cost = 0;
            }
        }


    }
    */

    return $shipping_method_cost;

}

function create_eancode($product_id){
    global $product;
    $eancode = '';
    $eancode_settings = get_option('wp_trovaprezzi_eancode_settings');
    if($eancode_settings['choice'] == '_sku'){
        $eancode = $product->get_sku();
      }
      else if($eancode_settings['choice'] == 'product_id'){
          $eancode = $product_id;
      }
      else if($eancode_settings['choice'] == 'custom_post_meta'){
         $eancode = get_post_meta($product_id,$eancode_settings['value'],true);
      }
    else{
        $eancode_term = wp_get_post_terms($product_id,$eancode_settings,array('fields' => 'names'));
        if($eancode_term && is_array($eancode_term) && count($eancode_term)>0){
            $eancode = $eancode_term[0];
        }
    }

    return $eancode;
}

function create_part_number($product_id){
    global $product;
    $part_number = '';
    $part_number_settings = get_option('wp_trovaprezzi_part_number_settings');
    if($part_number_settings['choice'] == '_sku'){
        $part_number = $product->get_sku();
    }else if($part_number_settings['choice'] == 'product_id'){
        $part_number = $product_id;
    }
    else if($part_number_settings['choice'] == 'custom_post_meta'){
       $part_number = get_post_meta($product_id,$part_number_settings['value'],true);
    }
    else{
        $part_number_term = wp_get_post_terms($product_id,$part_number_settings,array('fields' => 'names'));
        if($part_number_term && is_array($part_number_term) && count($part_number_term)>0){
            $part_number = $part_number_term[0];
        }
    }

    return $part_number;
}

function wp_tp_create_query(){

    $taxonomy_selected = get_option('wp_trovaprezzi_select_tax_settings');
    if ($taxonomy_selected && !empty($taxonomy_selected)){
          $args_feed = array(
              'post_type' => 'product',
              'posts_per_page' => -1,
              'orderby' => 'name',
              'order' => 'asc',
              'tax_query' => array(
                  array(
                      'taxonomy' => 'product_cat',
                      'field' => 'id',
                      'terms' => $taxonomy_selected,
                      'operator' => 'IN'
                  )
              )
          );
          }
    $loop = new WP_Query($args_feed);
    return $loop;
}

 ?>
