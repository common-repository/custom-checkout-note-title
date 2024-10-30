<?php
/*
Plugin Name: Custom Checkout Note Title
Plugin URI: https://atratyx.com/
Description: A simple plugin to change the title of the Additional Note field in WooCommerce checkout, customizable from the admin panel.
Version: 1.0
Author: ATRATYX| info@atratyx.com
Author URI: https://atratyx.com/about/
License: GPL-2.0+
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Function to change the title of the Additional Note field
function customize_order_notes_title($fields) {
    // Get the admin setting values
    $custom_note_title = get_option('custom_checkout_note_title', 'Custom Note');
    $custom_note_placeholder = get_option('custom_checkout_note_placeholder', 'Enter any special instructions here');
    
    // Set the label and placeholder from the settings
    $fields['order']['order_comments']['label'] = $custom_note_title;
    $fields['order']['order_comments']['placeholder'] = $custom_note_placeholder;
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'customize_order_notes_title');

// Add a custom settings section to WooCommerce Checkout settings
function custom_checkout_note_settings($settings) {
    $new_settings = array();

    // Add a section for the custom note title
    $new_settings[] = array(
        'name' => __('Custom Checkout Note Settings', 'custom-checkout-note-title'),
        'type' => 'title',
        'desc' => __('Change the title and placeholder for the Additional Note field.', 'custom-checkout-note-title'),
        'id'   => 'custom_checkout_note_settings'
    );

    // Field for custom note title
    $new_settings[] = array(
        'name'     => __('Custom Note Title', 'custom-checkout-note-title'),
        'desc_tip' => __('This will change the title of the additional note field.', 'custom-checkout-note-title'),
        'id'       => 'custom_checkout_note_title',
        'type'     => 'text',
        'default'  => 'Custom Note',
        'desc'     => __('Enter the title for the additional note field.', 'custom-checkout-note-title'),
    );

    // Field for custom note placeholder
    $new_settings[] = array(
        'name'     => __('Custom Note Placeholder', 'custom-checkout-note-title'),
        'desc_tip' => __('This will change the placeholder of the additional note field.', 'custom-checkout-note-title'),
        'id'       => 'custom_checkout_note_placeholder',
        'type'     => 'text',
        'default'  => 'Enter any special instructions here',
        'desc'     => __('Enter the placeholder for the additional note field.', 'custom-checkout-note-title'),
    );

    // End of section
    $new_settings[] = array(
        'type' => 'sectionend',
        'id'   => 'custom_checkout_note_settings'
    );

    // Merge new settings with existing checkout settings
    return array_merge($settings, $new_settings);
}
add_filter('woocommerce_get_settings_checkout', 'custom_checkout_note_settings');

// Register the new settings so they can be saved
function register_custom_checkout_note_settings() {
    add_option('custom_checkout_note_title', 'Custom Note');
    add_option('custom_checkout_note_placeholder', 'Enter any special instructions here');
    
    register_setting('woocommerce', 'custom_checkout_note_title');
    register_setting('woocommerce', 'custom_checkout_note_placeholder');
}
add_action('admin_init', 'register_custom_checkout_note_settings');

// Add settings link to the plugin on the plugins page
function custom_checkout_note_plugin_action_links($links) {
    $settings_link = '<a href="admin.php?page=wc-settings&tab=checkout">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'custom_checkout_note_plugin_action_links');
