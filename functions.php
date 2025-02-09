<?php
// Theme Setup
function sidtheme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('custom-logo');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');

    // Register menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'sidtheme'),
        'footer'  => __('Footer Menu', 'sidtheme'),
    ));

    // Load text domain
    load_theme_textdomain('sidtheme', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'sidtheme_setup');

// Enqueue scripts and styles
function sidtheme_scripts() {
    // Main stylesheet
    wp_enqueue_style('sidtheme-style', get_stylesheet_uri(), array(), filemtime(get_template_directory() . '/style.css'));

    // Custom JS
    wp_enqueue_script('sidtheme-main', get_template_directory_uri() . '/assets/js/main.js', array(), filemtime(get_template_directory() . '/assets/js/main.js'), true);

    // Add Elementor support
    if (did_action('elementor/loaded')) {
        wp_enqueue_style('sidtheme-elementor', get_template_directory_uri() . '/assets/css/elementor.css', array(), filemtime(get_template_directory() . '/assets/css/elementor.css'));
    }
}
add_action('wp_enqueue_scripts', 'sidtheme_scripts');

// Add Sidurl support
function sidtheme_sidurl_support() {
    if (function_exists('sidurl_shortcode_form')) {
        add_shortcode('sidurl_form', 'sidurl_shortcode_form');
    }
}
add_action('init', 'sidtheme_sidurl_support');