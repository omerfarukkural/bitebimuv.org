<?php
/**
 * BiteBiMuv Dernek - Ana Fonksiyonlar
 *
 * @package bitebimuv-dernek
 * @version 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'BBM_VERSION', '2.0.0' );
define( 'BBM_DIR', get_template_directory() );
define( 'BBM_URI', get_template_directory_uri() );

/**
 * Tema kurulumu
 */
function bbm_setup() {
    load_theme_textdomain( 'bitebimuv-dernek', BBM_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'appearance-tools' );
    add_theme_support( 'custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );

    add_image_size( 'bbm-hero',    1920, 900,  true );
    add_image_size( 'bbm-card',    600,  420,  true );
    add_image_size( 'bbm-member',  400,  400,  true );
    add_image_size( 'bbm-gallery', 800,  600,  true );
    add_image_size( 'bbm-thumb',   300,  200,  true );

    register_nav_menus( [
        'primary' => __( 'Ana Menü',          'bitebimuv-dernek' ),
        'footer'  => __( 'Alt Menü',          'bitebimuv-dernek' ),
        'social'  => __( 'Sosyal Medya',      'bitebimuv-dernek' ),
    ] );
}
add_action( 'after_setup_theme', 'bbm_setup' );

/**
 * İçerik genişliği
 */
function bbm_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'bbm_content_width', 1200 );
}
add_action( 'after_setup_theme', 'bbm_content_width', 0 );

/**
 * Script ve stilleri kaydet
 */
function bbm_scripts() {
    wp_enqueue_style(
        'bbm-fonts',
        'https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'bbm-style',
        BBM_URI . '/assets/css/main.css',
        [ 'bbm-fonts' ],
        BBM_VERSION
    );

    wp_enqueue_style(
        'bbm-animations',
        BBM_URI . '/assets/css/animations.css',
        [ 'bbm-style' ],
        BBM_VERSION
    );

    wp_enqueue_script(
        'bbm-smiley',
        BBM_URI . '/assets/js/smiley.js',
        [],
        BBM_VERSION,
        true
    );

    wp_enqueue_script(
        'bbm-main',
        BBM_URI . '/assets/js/main.js',
        [ 'bbm-smiley' ],
        BBM_VERSION,
        true
    );

    wp_localize_script( 'bbm-main', 'bbmData', [
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'bbm_nonce' ),
        'siteUrl'  => get_site_url(),
        'themeUrl' => BBM_URI,
        'i18n'     => [
            'loading' => __( 'Yükleniyor...', 'bitebimuv-dernek' ),
            'error'   => __( 'Bir hata oluştu.', 'bitebimuv-dernek' ),
            'success' => __( 'Başarılı!', 'bitebimuv-dernek' ),
            'send'    => __( 'Gönder', 'bitebimuv-dernek' ),
        ],
    ] );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'bbm_scripts' );

/**
 * Widget alanlarını kaydet
 */
function bbm_widgets_init() {
    $args_sidebar = [
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ];

    register_sidebar( array_merge( $args_sidebar, [
        'name'        => __( 'Kenar Çubuğu', 'bitebimuv-dernek' ),
        'id'          => 'sidebar-1',
        'description' => __( 'Blog kenar çubuğu.', 'bitebimuv-dernek' ),
    ] ) );

    $args_footer = [
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title footer-widget-title">',
        'after_title'   => '</h4>',
    ];

    for ( $i = 1; $i <= 4; $i++ ) {
        register_sidebar( array_merge( $args_footer, [
            'name' => sprintf( __( 'Alt Bilgi %d', 'bitebimuv-dernek' ), $i ),
            'id'   => 'footer-' . $i,
        ] ) );
    }
}
add_action( 'widgets_init', 'bbm_widgets_init' );

/**
 * Excerpt uzunluğu
 */
add_filter( 'excerpt_length', fn() => 30 );
add_filter( 'excerpt_more', fn() => '...' );

/**
 * Body class ekle
 */
function bbm_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'is-singular';
    }
    if ( is_front_page() ) {
        $classes[] = 'is-front-page';
    }
    return $classes;
}
add_filter( 'body_class', 'bbm_body_classes' );

/**
 * AJAX iletişim formu handler
 */
function bbm_handle_contact() {
    check_ajax_referer( 'bbm_nonce', 'nonce' );

    $name    = sanitize_text_field( $_POST['name'] ?? '' );
    $email   = sanitize_email( $_POST['email'] ?? '' );
    $subject = sanitize_text_field( $_POST['subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );

    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( __( 'Lütfen tüm alanları doldurun.', 'bitebimuv-dernek' ) );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( __( 'Geçerli bir e-posta adresi girin.', 'bitebimuv-dernek' ) );
    }

    $admin_email = get_option( 'admin_email' );
    $mail_subject = sprintf( '[BiteBiMuv] %s - %s', $subject ?: 'İletişim Formu', $name );
    $mail_body = sprintf(
        "Ad Soyad: %s\nE-posta: %s\n\nMesaj:\n%s",
        $name, $email, $message
    );

    $headers = [
        'Content-Type: text/plain; charset=UTF-8',
        sprintf( 'Reply-To: %s <%s>', $name, $email ),
    ];

    $sent = wp_mail( $admin_email, $mail_subject, $mail_body, $headers );

    if ( $sent ) {
        wp_send_json_success( __( 'Mesajınız gönderildi. En kısa sürede size dönüş yapacağız!', 'bitebimuv-dernek' ) );
    } else {
        wp_send_json_error( __( 'Mesaj gönderilemedi. Lütfen daha sonra tekrar deneyin.', 'bitebimuv-dernek' ) );
    }
}
add_action( 'wp_ajax_bbm_contact', 'bbm_handle_contact' );
add_action( 'wp_ajax_nopriv_bbm_contact', 'bbm_handle_contact' );

/**
 * Etkinlik kaydı AJAX handler
 */
function bbm_handle_event_register() {
    check_ajax_referer( 'bbm_nonce', 'nonce' );

    $event_id = absint( $_POST['event_id'] ?? 0 );
    $name     = sanitize_text_field( $_POST['name'] ?? '' );
    $email    = sanitize_email( $_POST['email'] ?? '' );
    $phone    = sanitize_text_field( $_POST['phone'] ?? '' );

    if ( ! $event_id || empty( $name ) || empty( $email ) ) {
        wp_send_json_error( __( 'Gerekli alanları doldurun.', 'bitebimuv-dernek' ) );
    }

    $event_title = get_the_title( $event_id );
    $admin_email = get_option( 'admin_email' );
    $subject     = sprintf( '[BiteBiMuv Etkinlik] %s - Yeni Kayıt', $event_title );
    $body        = sprintf( "Etkinlik: %s\nAd: %s\nE-posta: %s\nTelefon: %s", $event_title, $name, $email, $phone );

    wp_mail( $admin_email, $subject, $body );
    wp_mail( $email, sprintf( 'Etkinlik Kaydınız: %s', $event_title ),
        sprintf( 'Merhaba %s,\n\n"%s" etkinliğine kaydınız alınmıştır.\n\nGörüşmek üzere!\nBiteBiMuv Derneği', $name, $event_title )
    );

    wp_send_json_success( __( 'Etkinliğe başarıyla kaydoldunuz!', 'bitebimuv-dernek' ) );
}
add_action( 'wp_ajax_bbm_event_register', 'bbm_handle_event_register' );
add_action( 'wp_ajax_nopriv_bbm_event_register', 'bbm_handle_event_register' );

// Yardımcı dosyaları dahil et
require BBM_DIR . '/inc/custom-post-types.php';
require BBM_DIR . '/inc/customizer.php';
require BBM_DIR . '/inc/template-functions.php';
require BBM_DIR . '/inc/template-tags.php';
