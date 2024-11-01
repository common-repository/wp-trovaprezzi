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
<div class="wrap wp_trovaprezzi_wrap">
<form action="options.php" method="post">
    <?php
    $check_status = 'style="display:block;"';
    if(get_option('wp_trovaprezzi_general_settings') == 'manual'){
        $check_status = 'style="display:none;"';
    }
    settings_fields( $this->plugin_name );
    echo '<div id="wp_trovaprezzi_option_page_general">';
    do_settings_sections( $this->plugin_name.'_general' );
    submit_button();
    echo '</div>';
    echo '<div id="wp_trovaprezzi_option_page_select" '.$check_status.'>';
    do_settings_sections( $this->plugin_name.'_select' );
    submit_button();
    echo '</div>';
    echo '<div id="wp_trovaprezzi_option_page_feed">';
    do_settings_sections( $this->plugin_name.'_feed' );
    submit_button();
    echo '</div>';
    echo '<div id="wp_trovaprezzi_option_page_filter">';
    do_settings_sections( $this->plugin_name.'_filter' );
    submit_button();
    echo '</div>';
    echo '<div id="wp_trovaprezzi_option_page_xml_document">';
    do_settings_sections( $this->plugin_name.'_xml_document' );
    echo '</div>';
    ?>
</form>
</div>
