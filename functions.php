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

/////////////////
// Add to functions.php
function sidtheme_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    printf(
        '<span class="posted-on">%1$s <a href="%2$s" rel="bookmark">%3$s</a></span>',
        esc_html__('Posted on', 'sidtheme'),
        esc_url(get_permalink()),
        esc_html(get_the_date())
    );
}

function sidtheme_posted_by() {
    printf(
        '<span class="byline">%1$s <span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span></span>',
        esc_html__('by', 'sidtheme'),
        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
        esc_html(get_the_author())
    );
}

function sidtheme_entry_footer() {
    // Kategori
    if ('post' === get_post_type()) {
        $categories_list = get_the_category_list(esc_html__(', ', 'sidtheme'));
        if ($categories_list) {
            printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'sidtheme') . '</span>', $categories_list);
        }
    }

    // Tag
    $tags_list = get_the_tag_list('', esc_html__(', ', 'sidtheme'));
    if ($tags_list) {
        printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'sidtheme') . '</span>', $tags_list);
    }
}