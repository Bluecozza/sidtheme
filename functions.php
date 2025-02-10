<?php
/**
 * Sidtheme - Functions File
 * 
 * @package Sidtheme
 * @author Bluecozza
 * @version 1.0.0
 */

// ========================
// 1. THEME SETUP
// ========================

if (!function_exists('sidtheme_setup')) :
    /**
     * Mengatur fitur dasar tema
     */
    function sidtheme_setup() {
        // Dukungan fitur WordPress
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));
        add_theme_support('custom-logo', array(
            'height'      => 100,
            'width'       => 400,
            'flex-height' => true,
            'flex-width'  => true,
        ));
        add_theme_support('align-wide');
        add_theme_support('responsive-embeds');

        // Ukuran gambar kustom
        add_image_size('sidtheme-featured', 1200, 600, true);

        // Registrasi menu
        register_nav_menus(array(
            'primary' => __('Menu Utama', 'sidtheme'),
            'footer'  => __('Menu Footer', 'sidtheme'),
        ));

        // Muat teks domain untuk terjemahan
        load_theme_textdomain('sidtheme', get_template_directory() . '/languages');
    }
endif;
add_action('after_setup_theme', 'sidtheme_setup');

// ========================
// 2. SCRIPTS & STYLES
// ========================

/**
 * Memuat skrip dan stylesheet
 */
function sidtheme_scripts() {
    // Stylesheet utama
    wp_enqueue_style(
        'sidtheme-style',
        get_stylesheet_uri(),
        array(),
        filemtime(get_template_directory() . '/style.css')
    );

    // Skrip kustom
    wp_enqueue_script(
        'sidtheme-main',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),
        filemtime(get_template_directory() . '/assets/js/main.js'),
        true
    );

    // Dukungan Elementor
    if (did_action('elementor/loaded')) {
        wp_enqueue_style(
            'sidtheme-elementor',
            get_template_directory_uri() . '/assets/css/elementor.css',
            array(),
            filemtime(get_template_directory() . '/assets/css/elementor.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'sidtheme_scripts');

// ========================
// 3. POST META
// ========================

/**
 * Menampilkan tanggal posting
 */
function sidtheme_posted_on() {
    printf(
        '<span class="posted-on">%s <a href="%s" rel="bookmark">%s</a></span>',
        esc_html__('Diposting pada', 'sidtheme'),
        esc_url(get_permalink()),
        esc_html(get_the_date())
    );
}

/**
 * Menampilkan penulis posting
 */
function sidtheme_posted_by() {
    printf(
        '<span class="byline">%s <span class="author vcard"><a class="url fn n" href="%s">%s</a></span></span>',
        esc_html__('oleh', 'sidtheme'),
        esc_url(get_author_posts_url(get_the_author_meta('ID'))),
        esc_html(get_the_author())
    );
}

/**
 * Menampilkan footer posting
 */
function sidtheme_entry_footer() {
    // Kategori
    if ('post' === get_post_type()) {
        $categories_list = get_the_category_list(esc_html__(', ', 'sidtheme'));
        if ($categories_list) {
            printf(
                '<span class="cat-links">%s %s</span>',
                esc_html__('Kategori:', 'sidtheme'),
                $categories_list
            );
        }
    }

    // Tag
    $tags_list = get_the_tag_list('', esc_html__(', ', 'sidtheme'));
    if ($tags_list) {
        printf(
            '<span class="tags-links">%s %s</span>',
            esc_html__('Tag:', 'sidtheme'),
            $tags_list
        );
    }
}

// ========================
// 4. WIDGETS
// ========================

/**
 * Registrasi area widget
 */
function sidtheme_widgets_init() {
    // Widget Footer 1
    register_sidebar(array(
        'name'          => __('Area Widget Footer 1', 'sidtheme'),
        'id'            => 'footer-1',
        'description'   => __('Tambahkan widget untuk kolom pertama footer', 'sidtheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    // Widget Footer 2
    register_sidebar(array(
        'name'          => __('Area Widget Footer 2', 'sidtheme'),
        'id'            => 'footer-2',
        'description'   => __('Tambahkan widget untuk kolom kedua footer', 'sidtheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    // Widget Footer 3
    register_sidebar(array(
        'name'          => __('Area Widget Footer 3', 'sidtheme'),
        'id'            => 'footer-3',
        'description'   => __('Tambahkan widget untuk kolom ketiga footer', 'sidtheme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'sidtheme_widgets_init');

// ========================
// 5. ADMIN MENU & UPDATES
// ========================

/**
 * Tambahkan menu admin untuk update
 */
function sidtheme_admin_menu() {
    add_theme_page(
        __('Update Sidtheme', 'sidtheme'),
        __('Update Sidtheme', 'sidtheme'),
        'manage_options',
        'sidtheme-updates',
        'sidtheme_updates_page'
    );
}
add_action('admin_menu', 'sidtheme_admin_menu');

/**
 * Tampilan halaman update
 */
function sidtheme_updates_page() {
    if (isset($_GET['action']) && $_GET['action'] === 'download-update') {
        check_admin_referer('sidtheme_update_nonce');
        sidtheme_download_update();
    }

    $update_data = sidtheme_check_for_updates();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Manajemen Update Sidtheme', 'sidtheme'); ?></h1>
        
        <?php if ($update_data['update_available']) : ?>
            <div class="notice notice-success">
                <p><?php printf(
                    esc_html__('Versi baru tersedia: %s', 'sidtheme'),
                    '<strong>' . esc_html($update_data['new_version']) . '</strong>'
                ); ?></p>
                <p><a href="<?php echo esc_url(wp_nonce_url(
                    admin_url('themes.php?page=sidtheme-updates&action=download-update'),
                    'sidtheme_update_nonce'
                )); ?>" class="button button-primary">
                    <?php esc_html_e('Download & Install Update', 'sidtheme'); ?>
                </a></p>
            </div>
        <?php else : ?>
            <div class="notice notice-info">
                <p><?php esc_html_e('Anda menggunakan versi terbaru.', 'sidtheme'); ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Cek ketersediaan update
 */
function sidtheme_check_for_updates() {
    $theme = wp_get_theme();
    $response = wp_remote_get('https://api.github.com/repos/Bluecozza/sidtheme/releases/latest');

    if (is_wp_error($response)) {
        return array(
            'update_available' => false,
            'error' => $response->get_error_message()
        );
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    return array(
        'update_available' => version_compare(
            ltrim($data['tag_name'], 'v'), 
            $theme->get('Version'), 
            '>'
        ),
        'new_version' => ltrim($data['tag_name'], 'v'),
        'download_url' => $data['zipball_url'],
        'current_version' => $theme->get('Version')
    );
}

/**
 * Proses download dan instal update
 */
function sidtheme_download_update() {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Akses ditolak.', 'sidtheme'));
    }

    WP_Filesystem();
    global $wp_filesystem;

    // Persiapan direktori
    $temp_dir = get_theme_root() . '/sidtheme_temp/';
    $theme_dir = get_theme_root() . '/sidtheme/';
    
    try {
        // Download file
        $zip_file = download_url(sidtheme_check_for_updates()['download_url']);
        
        // Ekstrak ke direktori temp
        unzip_file($zip_file, $temp_dir);
        
        // Cari direktori utama
        $source_dir = '';
        foreach ($wp_filesystem->dirlist($temp_dir) as $item) {
            if ($item['type'] === 'd' && str_contains($item['name'], 'Bluecozza-sidtheme')) {
                $source_dir = $temp_dir . $item['name'] . '/';
                break;
            }
        }

        if (empty($source_dir)) {
            throw new Exception(esc_html__('Struktur direktori tidak valid.', 'sidtheme'));
        }

        // Hapus versi lama
        if (!$wp_filesystem->delete($theme_dir, true)) {
            throw new Exception(esc_html__('Gagal menghapus versi lama.', 'sidtheme'));
        }

        // Pindahkan file baru
        if (!$wp_filesystem->move($source_dir, $theme_dir, true)) {
            throw new Exception(esc_html__('Gagal memindahkan file update.', 'sidtheme'));
        }

        // Tampilkan pesan sukses
        echo '<div class="notice notice-success">';
        echo '<p>' . esc_html__('Update berhasil diinstal!', 'sidtheme') . '</p>';
        echo '</div>';

    } catch (Exception $e) {
        echo '<div class="notice notice-error">';
        echo '<p>' . esc_html($e->getMessage()) . '</p>';
        echo '</div>';
    } finally {
        // Bersihkan file temporary
        $wp_filesystem->delete($temp_dir, true);
        if (file_exists($zip_file)) unlink($zip_file);
    }
}

// ========================
// 6. ADMIN STYLES
// ========================

/**
 * Muat stylesheet admin
 */
function sidtheme_admin_styles() {
    wp_enqueue_style(
        'sidtheme-admin',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/admin.css')
    );
}
add_action('admin_enqueue_scripts', 'sidtheme_admin_styles');