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
		// Aktifkan support untuk featured image
    add_theme_support('post-thumbnails');
	    // Tambahkan ukuran gambar kustom (opsional)
    add_image_size('sidtheme-featured', 1200, 600, true); // 1200x600, crop center

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

// Register Footer Widget Areas
function sidtheme_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'sidtheme'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in the first footer column.', 'sidtheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'sidtheme'),
        'id'            => 'footer-2',
        'description'   => __('Add widgets here to appear in the second footer column.', 'sidtheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'sidtheme'),
        'id'            => 'footer-3',
        'description'   => __('Add widgets here to appear in the third footer column.', 'sidtheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'sidtheme_widgets_init');

// Tambahkan menu admin untuk update tema
function sidtheme_admin_menu() {
    add_theme_page(
        'Sidtheme Updates', // Judul halaman
        'Sidtheme Updates', // Judul menu
        'manage_options',   // Capability
        'sidtheme-updates', // Slug menu
        'sidtheme_updates_page' // Fungsi callback
    );
}
add_action('admin_menu', 'sidtheme_admin_menu');

// Tampilan halaman update
function sidtheme_updates_page() {
    if (isset($_GET['action']) && $_GET['action'] === 'download-update') {
        sidtheme_download_update();
    }

    $update_data = sidtheme_check_for_updates();
    ?>
    <div class="wrap">
        <h1><?php _e('Sidtheme Updates', 'sidtheme'); ?></h1>
        
        <?php
        if ($update_data['update_available']) {
            echo '<div class="notice notice-success">';
            echo '<p>' . sprintf(
                __('Versi baru tersedia: <strong>%s</strong>.', 'sidtheme'),
                $update_data['new_version']
            ) . '</p>';
            echo '<p><a href="' . esc_url(admin_url('themes.php?page=sidtheme-updates&action=download-update')) . '" class="button button-primary">' . __('Download dan Install Update', 'sidtheme') . '</a></p>';
            echo '</div>';
        } else {
            echo '<div class="notice notice-info">';
            echo '<p>' . __('Anda menggunakan versi terbaru.', 'sidtheme') . '</p>';
            echo '</div>';
        }
        ?>
    </div>
    <?php
}

// Fungsi untuk cek update dari GitHub
function sidtheme_check_for_updates() {
    $theme_data = wp_get_theme();
    $current_version = $theme_data->get('Version');
    $github_url = 'https://api.github.com/repos/Bluecozza/sidtheme/releases/latest';

    // Dapatkan data dari GitHub API
    $response = wp_remote_get($github_url);

    if (is_wp_error($response)) {
        return array(
            'update_available' => false,
            'error' => $response->get_error_message()
        );
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data) || !isset($data['tag_name'])) {
        return array(
            'update_available' => false,
            'error' => __('Gagal memeriksa update.', 'sidtheme')
        );
    }

    $new_version = ltrim($data['tag_name'], 'v'); // Hapus 'v' dari versi (jika ada)
    $download_url = $data['zipball_url']; // URL download zip

    return array(
        'update_available' => version_compare($new_version, $current_version, '>'),
        'new_version' => $new_version,
        'download_url' => $download_url,
        'current_version' => $current_version
    );
}

function sidtheme_admin_styles() {
    wp_enqueue_style(
        'sidtheme-admin',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/admin.css')
    );
}
add_action('admin_enqueue_scripts', 'sidtheme_admin_styles');

// Fungsi untuk download update
function sidtheme_download_update() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Anda tidak memiliki izin untuk melakukan ini.', 'sidtheme'));
    }

    $update_data = sidtheme_check_for_updates();

    if (!$update_data['update_available']) {
        wp_die(__('Tidak ada update yang tersedia.', 'sidtheme'));
    }

    $download_url = $update_data['download_url'];
    $theme_slug = 'sidtheme';
    $theme_path = get_theme_root() . '/' . $theme_slug;

    // Download file zip
    $zip_file = download_url($download_url);

    if (is_wp_error($zip_file)) {
        wp_die(__('Gagal mengunduh update.', 'sidtheme'));
    }

    // Ekstrak file zip
    WP_Filesystem();
    $unzip_result = unzip_file($zip_file, $theme_path);

    if (is_wp_error($unzip_result)) {
        wp_die(__('Gagal mengekstrak update.', 'sidtheme'));
    }

    // Hapus file zip
    unlink($zip_file);

    echo '<div class="notice notice-success">';
    echo '<p>' . __('Update berhasil diinstal!', 'sidtheme') . '</p>';
    echo '</div>';
}