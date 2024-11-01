<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://www.wemiura.com
 * @since      1.0.0
 *
 * @package    Wp_trova_prezzi_free
 * @subpackage Wp_trova_prezzi_free/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php


?>
<div class="wrap wp_trovaprezzi_wrap">
    <?php
    $loop = wp_tp_create_query();
    ?>
    <div class="wp_tp_table_products_count">
    <h3 style="color: #fff;"><?php echo $loop->post_count; ?> PRODOTTI SELEZIONATI</h3>
    </div>
    <table id="wp_trovaprezzi_products" class="widefat fixed" cellspacing="0">
        <thead>
        <tr>
            <th class="column-columnname">Image</th>
            <th class="column-columnname">Name</th>
            <th class="column-columnname">Brand</th>
            <th class="column-columnname">Price</th>
            <th class="column-columnname">Code</th>
            <th class="column-columnname">Stock</th>
            <th class="column-columnname">Categories</th>
            <th class="column-columnname">Shipping Cost</th>
            <th class="column-columnname">Part Number</th>
            <th class="column-columnname">Ean Code</th>
            <th class="wp_tp_table_status">Status</th>

        </tr>
        </thead>
        <tbody>
    <?php
    $check_ok = 0;
    if ($loop->have_posts()) {
        while ($loop->have_posts()) : $loop->the_post();
            $status = '<span class="hidden">0</span><span style="color:#D34F2B;" class="dashicons dashicons-thumbs-down"></span>';

            $name = get_the_title();
            $price =create_price(get_the_ID());
            $code = create_code(get_the_ID());
            $link = get_the_permalink();
            $category_tree = create_category(get_the_ID());
            if(($name != '') && ( $price!= '') && ($code != '') && ($link !='') && ($category_tree != '')){
                $status = '<span class="hidden">1</span><span style="color:#009233;" class="dashicons dashicons-thumbs-up"></span>';
              $check_ok = $check_ok + 1;
            }
            if($name ==''){ $name = '<span class="wp_tp_table_products_required">REQUIRED</span>'; }
            if($price ==''){ $price = '<span class="wp_tp_table_products_required">REQUIRED</span>'; }
            if($code ==''){ $code = '<span class="wp_tp_table_products_required">REQUIRED</span>'; }
            if($link ==''){ $link = '<span class="wp_tp_table_products_required">REQUIRED</span>'; }
            if($category_tree ==''){ $category_tree = '<span class="wp_tp_table_products_required">REQUIRED</span>'; }
           ?>
    <tr>
        <td class="column-columnname"><img src="<?php echo create_image(get_the_ID()); ?>" /></td>
        <td class="column-columnname"><a target="_blank" href="<?php echo get_edit_post_link(get_the_ID()); ?>"><?php echo $name; ?></a></td>
        <td class="column-columnname"><?php echo create_brand(get_the_ID()) ?></td>
        <td class="column-columnname"><?php echo $price; ?></td>
        <td class="column-columnname"><?php echo $code; ?></td>
        <td class="column-columnname"><?php echo create_stock(get_the_ID()); ?></td>
        <td class="column-columnname"><?php echo $category_tree; ?></td>
        <td class="column-columnname"><?php echo create_shipping_cost(get_the_ID()); ?></td>
        <td class="column-columnname"><?php echo create_part_number(get_the_ID()); ?></td>
        <td class="column-columnname"><?php echo create_eancode(get_the_ID()); ?></td>
        <td class="wp_tp_table_status"><?php echo $status; ?></td>
    </tr>
    <?php
        endwhile;
    }
    ?>
        <script type="text/javascript">
            jQuery('.wp_tp_table_products_count h3').append('<?php echo '<br />'.$check_ok.' VERRANNO PUBBLICATI'; ?>');
        </script>
        </tbody>
        </table>


</div>
<div class="wp_tp_table_legend wrap">
    <div class="wp_tp_table_legend_error"><span style="color:#D34F2B;" class="dashicons dashicons-thumbs-down"></span> <?php _e('Questi prodotti non verrano esportati.','wp-trova-prezzi-free'); ?></div>
    <div class="wp_tp_table_legend_ok"><span style="color:#009233;" class="dashicons dashicons-thumbs-up"></span> <?php _e('Questi prodotti verrano esportati.','wp-trova-prezzi-free'); ?></div>
</div>
<div class="wrap">
<?php
echo '<div id="wp_trovaprezzi_option_page_xml_document">';
do_settings_sections( $this->plugin_name.'_xml_document' );
echo '</div>';
?>
</div>