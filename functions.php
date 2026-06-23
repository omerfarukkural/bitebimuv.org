<?php
/**
 * BiteBiMuv Dernek — functions.php v4.0
 */

define( 'BBM_VERSION', '4.0.0' );
define( 'BBM_DIR', get_template_directory() );
define( 'BBM_URI', get_template_directory_uri() );

require_once BBM_DIR . '/inc/custom-post-types.php';
require_once BBM_DIR . '/inc/new-cpts.php';
require_once BBM_DIR . '/inc/customizer.php';
require_once BBM_DIR . '/inc/template-functions.php';
require_once BBM_DIR . '/inc/template-tags.php';
require_once BBM_DIR . '/inc/schema-org.php';
require_once BBM_DIR . '/inc/kvkk.php';
require_once BBM_DIR . '/inc/seo.php';
require_once BBM_DIR . '/inc/security.php';
require_once BBM_DIR . '/inc/admin-dashboard.php';
require_once BBM_DIR . '/inc/admin-export.php';
require_once BBM_DIR . '/inc/admin-columns.php';
require_once BBM_DIR . '/inc/pwa.php';
require_once BBM_DIR . '/inc/payment-turkey.php';
require_once BBM_DIR . '/inc/sms.php';
require_once BBM_DIR . '/inc/email-templates.php';

/* ── Theme Setup ── */
function bbm_setup(): void {
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form','comment-form','comment-list','gallery','caption','style','script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'woocommerce' );

    add_image_size( 'bbm-hero',    1920, 800,  true );
    add_image_size( 'bbm-card',     800, 500,  true );
    add_image_size( 'bbm-member',   400, 400,  true );
    add_image_size( 'bbm-gallery',  800, 600,  true );
    add_image_size( 'bbm-thumb',    400, 300,  true );
    add_image_size( 'bbm-square',   600, 600,  true );
    add_image_size( 'bbm-wide',    1200, 400,  true );
    add_image_size( 'bbm-portrait', 400, 600,  true );

    register_nav_menus( [
        'primary' => __( 'Ana Menü',         'bitebimuv-dernek' ),
        'footer'  => __( 'Alt Bilgi Menüsü', 'bitebimuv-dernek' ),
        'social'  => __( 'Sosyal Medya',     'bitebimuv-dernek' ),
        'topbar'  => __( 'Üst Çubuk',        'bitebimuv-dernek' ),
    ] );

    load_theme_textdomain( 'bitebimuv-dernek', BBM_DIR . '/languages' );
}
add_action( 'after_setup_theme', 'bbm_setup' );

/* ── Scripts & Styles ── */
function bbm_scripts(): void {
    /* Google Fonts */
    wp_enqueue_style(
        'bbm-google-fonts',
        'https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap',
        [], null
    );

    /* Core styles */
    wp_enqueue_style( 'bbm-main',          BBM_URI . '/assets/css/main.css',          [],           BBM_VERSION );
    wp_enqueue_style( 'bbm-animations',    BBM_URI . '/assets/css/animations.css',    ['bbm-main'], BBM_VERSION );
    wp_enqueue_style( 'bbm-dark-mode',     BBM_URI . '/assets/css/dark-mode.css',     ['bbm-main'], BBM_VERSION );
    wp_enqueue_style( 'bbm-kvkk',          BBM_URI . '/assets/css/kvkk.css',          ['bbm-main'], BBM_VERSION );
    wp_enqueue_style( 'bbm-components',    BBM_URI . '/assets/css/components.css',    ['bbm-main'], BBM_VERSION );
    wp_enqueue_style( 'bbm-v4-components', BBM_URI . '/assets/css/v4-components.css', ['bbm-main'], BBM_VERSION );

    /* Conditional: events page → calendar */
    if ( bbm_is_template( 'page-templates/events.php' ) ) {
        wp_enqueue_style(  'bbm-calendar-css', BBM_URI . '/assets/css/calendar.css', ['bbm-main'], BBM_VERSION );
        wp_enqueue_script( 'bbm-calendar', BBM_URI . '/assets/js/calendar.js', [], BBM_VERSION, true );
    }

    /* Conditional: contact page → Leaflet + map */
    if ( bbm_is_template( 'page-templates/contact.php' ) ) {
        wp_enqueue_style(  'leaflet-css', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4' );
        wp_enqueue_script( 'leaflet-js',  'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',  [], '1.9.4', true );
        wp_enqueue_script( 'bbm-map',     BBM_URI . '/assets/js/map.js', ['leaflet-js'],        BBM_VERSION, true );
    }

    /* Comments */
    if ( is_singular() && comments_open() ) wp_enqueue_script( 'comment-reply' );

    /* Core scripts */
    wp_enqueue_script( 'bbm-particles',   BBM_URI . '/assets/js/particles.js',   [],                              BBM_VERSION, true );
    wp_enqueue_script( 'bbm-smiley',      BBM_URI . '/assets/js/smiley.js',      [],                              BBM_VERSION, true );
    wp_enqueue_script( 'bbm-darkmode',    BBM_URI . '/assets/js/dark-mode.js',   [],                              BBM_VERSION, true );
    wp_enqueue_script( 'bbm-kvkk-js',     BBM_URI . '/assets/js/kvkk.js',        [],                              BBM_VERSION, true );
    wp_enqueue_script( 'bbm-livesearch',  BBM_URI . '/assets/js/live-search.js', [],                              BBM_VERSION, true );
    wp_enqueue_script( 'bbm-main-js',     BBM_URI . '/assets/js/main.js',        ['bbm-smiley','bbm-particles'],  BBM_VERSION, true );
    wp_enqueue_script( 'bbm-extras',      BBM_URI . '/assets/js/extras.js',      ['bbm-main-js'],                 BBM_VERSION, true );

    wp_localize_script( 'bbm-main-js', 'BBM', [
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'bbm-nonce' ),
        'homeUrl'  => home_url(),
        'themeUri' => BBM_URI,
        'version'  => BBM_VERSION,
        'darkMode' => [ 'enabled' => bbm_dark_mode_is_enabled() ],
        'kvkk'     => [ 'accepted' => bbm_kvkk_is_accepted() ],
        'i18n'     => [
            'sending'      => __( 'Gönderiliyor…',                        'bitebimuv-dernek' ),
            'sent'         => __( 'Mesajınız iletildi! Teşekkürler 😊', 'bitebimuv-dernek' ),
            'error'        => __( 'Hata oluştu, lütfen tekrar deneyin.',  'bitebimuv-dernek' ),
            'copied'       => __( 'Bağlantı kopyalandı!',                'bitebimuv-dernek' ),
            'searching'    => __( 'Aranıyor…',                           'bitebimuv-dernek' ),
            'noResults'    => __( 'Sonuç bulunamadı.',                    'bitebimuv-dernek' ),
            'registering'  => __( 'Kaydediliyor…',                        'bitebimuv-dernek' ),
            'registered'   => __( 'Kayıt tamamlandı! 🎉',               'bitebimuv-dernek' ),
            'subscribing'  => __( 'Abone olunuyor…',                      'bitebimuv-dernek' ),
            'subscribed'   => __( 'Başarıyla abone oldunuz! 🎊',        'bitebimuv-dernek' ),
            'applying'     => __( 'Başvurunuz gönderiliyor…',            'bitebimuv-dernek' ),
            'applied'      => __( 'Başvurunuz alındı! 🤝',              'bitebimuv-dernek' ),
            'planSelected' => __( 'planı seçildi',                         'bitebimuv-dernek' ),
            'registerFor'  => __( 'Kayıt',                                'bitebimuv-dernek' ),
            'ibanCopied'   => __( 'IBAN kopyalandı!',                     'bitebimuv-dernek' ),
            'downloading'  => __( 'İndiriliyor…',                         'bitebimuv-dernek' ),
        ],
    ] );
}
add_action( 'wp_enqueue_scripts', 'bbm_scripts' );

/* ── Template helper ── */
function bbm_is_template( string $template ): bool {
    if ( is_page() ) return is_page_template( $template );
    return false;
}

/* ── Widgets ── */
function bbm_widgets_init(): void {
    $sidebars = [
        'sidebar-1'     => __( 'Ana Kenar Çubuğu', 'bitebimuv-dernek' ),
        'footer-1'      => __( 'Alt Bilgi 1',       'bitebimuv-dernek' ),
        'footer-2'      => __( 'Alt Bilgi 2',       'bitebimuv-dernek' ),
        'footer-3'      => __( 'Alt Bilgi 3',       'bitebimuv-dernek' ),
        'footer-4'      => __( 'Alt Bilgi 4',       'bitebimuv-dernek' ),
        'topbar'        => __( 'Üst Çubuk',         'bitebimuv-dernek' ),
        'before-footer' => __( 'Alt Bilgi Öncesi',  'bitebimuv-dernek' ),
    ];
    foreach ( $sidebars as $id => $name ) {
        register_sidebar( [
            'name'          => $name,
            'id'            => $id,
            'before_widget' => '<section id="%1$s" class="bbm-widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="bbm-widget-title"><span>',
            'after_title'   => '</span></h3>',
        ] );
    }
}
add_action( 'widgets_init', 'bbm_widgets_init' );

/* ── Body Classes ── */
function bbm_body_classes( array $classes ): array {
    if ( ! is_singular() )             $classes[] = 'hfeed';
    if ( bbm_dark_mode_is_enabled() )  $classes[] = 'bbm-dark';
    if ( is_front_page() )             $classes[] = 'is-front-page';
    return $classes;
}
add_filter( 'body_class', 'bbm_body_classes' );

/* ── Dark Mode helper ── */
function bbm_dark_mode_is_enabled(): bool {
    return isset( $_COOKIE['bbm_dark_mode'] ) && $_COOKIE['bbm_dark_mode'] === '1';
}

/* ── CSS tokens injected early ── */
function bbm_customizer_inline_css(): void {
    $p  = sanitize_hex_color( get_theme_mod( 'bbm_primary_color',   '#E8435A' ) ) ?: '#E8435A';
    $s  = sanitize_hex_color( get_theme_mod( 'bbm_secondary_color', '#2D3561' ) ) ?: '#2D3561';
    $a  = sanitize_hex_color( get_theme_mod( 'bbm_accent_color',    '#FFD93D' ) ) ?: '#FFD93D';
    $dk = sanitize_hex_color( get_theme_mod( 'bbm_dark_color',      '#1A1A2E' ) ) ?: '#1A1A2E';
    /* Compute primary RGB for CSS rgba() usage */
    list( $pr, $pg, $pb ) = sscanf( ltrim( $p, '#' ), '%02x%02x%02x' ) ?: [232, 67, 90];
    echo "<style id='bbm-tokens'>:root{--bbm-primary:{$p};--bbm-secondary:{$s};--bbm-accent:{$a};--bbm-dark:{$dk};--bbm-primary-rgb:{$pr},{$pg},{$pb};}</style>\n";
}
add_action( 'wp_head', 'bbm_customizer_inline_css', 1 );

/* ── AJAX: Contact ── */
function bbm_handle_contact(): void {
    check_ajax_referer( 'bbm-nonce', 'nonce' );
    if ( function_exists( 'bbm_is_honeypot_triggered' ) && bbm_is_honeypot_triggered() ) {
        wp_send_json_error( ['message' => __( 'Geçersiz istek.', 'bitebimuv-dernek' )] );
    }
    if ( function_exists( 'bbm_check_rate_limit' ) ) {
        $limit = bbm_check_rate_limit( 'contact', 5, 300 );
        if ( ! $limit['allowed'] ) {
            wp_send_json_error( ['message' => __( 'Fazla istek. Lütfen kısa süre sonra tekrar deneyin.', 'bitebimuv-dernek' )] );
        }
    }
    $name    = sanitize_text_field( $_POST['name']    ?? '' );
    $email   = sanitize_email(      $_POST['email']   ?? '' );
    $phone   = sanitize_text_field( $_POST['phone']   ?? '' );
    $subject = sanitize_text_field( $_POST['subject'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );
    if ( ! $name || ! is_email( $email ) || ! $message ) {
        wp_send_json_error( ['message' => __( 'Zorunlu alanları doğru doldurun.', 'bitebimuv-dernek' )] );
    }
    $to      = get_theme_mod( 'bbm_contact_info_email', get_option( 'admin_email' ) );
    $headers = [ 'Content-Type: text/html; charset=UTF-8', "Reply-To: {$name} <{$email}>" ];
    $sent    = wp_mail( $to, '[BiteBiMuv] ' . ( $subject ?: 'İletişim Formu' ),
        bbm_build_email( [ 'title' => 'Yeni İletişim Mesajı', 'fields' => [
            'Ad Soyad' => esc_html($name), 'E-posta' => esc_html($email),
            'Telefon'  => esc_html($phone), 'Konu'   => esc_html($subject),
            'Mesaj'    => nl2br( esc_html($message) ),
        ] ] ), $headers );
    $sent
        ? wp_send_json_success( ['message' => __( 'Mesajınız iletildi! En kısa sürede dönüş yapacağız.', 'bitebimuv-dernek' )] )
        : wp_send_json_error(   ['message' => __( 'Mesaj gönderilemedi, tekrar deneyin.', 'bitebimuv-dernek' )] );
}
add_action( 'wp_ajax_bbm_contact',        'bbm_handle_contact' );
add_action( 'wp_ajax_nopriv_bbm_contact', 'bbm_handle_contact' );

/* ── AJAX: Event Register ── */
function bbm_handle_event_register(): void {
    check_ajax_referer( 'bbm-nonce', 'nonce' );
    $event_id = absint( $_POST['event_id'] ?? 0 );
    $name     = sanitize_text_field( $_POST['name']  ?? '' );
    $email    = sanitize_email(      $_POST['email'] ?? '' );
    $phone    = sanitize_text_field( $_POST['phone'] ?? '' );
    $count    = min( 10, max( 1, absint( $_POST['count'] ?? 1 ) ) );
    $note     = sanitize_textarea_field( $_POST['note'] ?? '' );
    if ( ! $event_id || ! $name || ! is_email($email) ) {
        wp_send_json_error( ['message' => __( 'Zorunlu alanları doldurun.', 'bitebimuv-dernek' )] );
    }
    $event = get_post( $event_id );
    if ( ! $event || $event->post_type !== 'bbm_event' ) {
        wp_send_json_error( ['message' => __( 'Etkinlik bulunamadı.', 'bitebimuv-dernek' )] );
    }
    $regs   = (array) get_post_meta( $event_id, '_bbm_registrations', true );
    $regs[] = compact( 'name','email','phone','count','note' ) + ['date' => current_time('mysql')];
    update_post_meta( $event_id, '_bbm_registrations', $regs );
    /* Send improved confirmation email */
    if ( function_exists( 'bbm_email_event_registration_confirmed' ) ) {
        bbm_email_event_registration_confirmed( $email, $name, $event_id, $count );
    } else {
        wp_mail( $email, sprintf( __( 'Etkinlik Kaydı: %s', 'bitebimuv-dernek' ), $event->post_title ),
            bbm_build_email( [ 'title' => 'Kaydınız Alındı! 🎉',
                'fields' => [ 'Etkinlik' => esc_html($event->post_title), 'Katılımcı' => $count,
                    'Tarih' => bbm_get_event_date_formatted($event_id) ],
            ] ), ['Content-Type: text/html; charset=UTF-8'] );
    }
    /* SMS notification */
    if ( $phone && function_exists( 'bbm_send_sms' ) ) {
        do_action( 'bbm_event_registered', $phone, $event->post_title, bbm_get_event_date_formatted($event_id) );
    }
    wp_send_json_success( ['message' => __( 'Kaydınız tamamlandı! Onay e-postası gönderildi. 🎊', 'bitebimuv-dernek' )] );
}
add_action( 'wp_ajax_bbm_event_register',        'bbm_handle_event_register' );
add_action( 'wp_ajax_nopriv_bbm_event_register', 'bbm_handle_event_register' );

/* ── AJAX: Newsletter ── */
function bbm_handle_newsletter(): void {
    check_ajax_referer( 'bbm-nonce', 'nonce' );
    $email = sanitize_email( $_POST['email'] ?? '' );
    $name  = sanitize_text_field( $_POST['name'] ?? '' );
    if ( ! is_email( $email ) ) {
        wp_send_json_error( ['message' => __( 'Geçerli bir e-posta adresi girin.', 'bitebimuv-dernek' )] );
    }
    $subs = (array) get_option( 'bbm_newsletter_subscribers', [] );
    if ( in_array( $email, array_column( $subs, 'email' ), true ) ) {
        wp_send_json_error( ['message' => __( 'Bu e-posta adresi zaten kayıtlı.', 'bitebimuv-dernek' )] );
    }
    $subs[] = [ 'email' => $email, 'name' => $name, 'date' => current_time('mysql') ];
    update_option( 'bbm_newsletter_subscribers', $subs );
    wp_mail( $email, __( 'BiteBiMuv Bültenine Hoş Geldiniz!', 'bitebimuv-dernek' ),
        bbm_build_email_v4( [ 'icon' => '🎊', 'title' => 'Bültene Hoş Geldiniz!',
            'subtitle' => 'Abone olduğunuz için teşekkürler',
            'greeting' => $name ? sprintf( __( 'Merhaba %s,', 'bitebimuv-dernek' ), esc_html($name) ) : __( 'Merhaba,', 'bitebimuv-dernek' ),
            'body'     => '<p>BiteBiMuv bültenine başarıyla abone oldunuz. En güncel haberler ve etkinlikler için takipte kalın!</p>',
        ] ), ['Content-Type: text/html; charset=UTF-8'] );
    wp_send_json_success( ['message' => __( 'Bültene başarıyla abone oldunuz! 🎊', 'bitebimuv-dernek' )] );
}
add_action( 'wp_ajax_bbm_newsletter',        'bbm_handle_newsletter' );
add_action( 'wp_ajax_nopriv_bbm_newsletter', 'bbm_handle_newsletter' );

/* ── AJAX: Membership Application ── */
function bbm_handle_membership(): void {
    check_ajax_referer( 'bbm-nonce', 'nonce' );
    if ( function_exists( 'bbm_is_honeypot_triggered' ) && bbm_is_honeypot_triggered() ) {
        wp_send_json_error( ['message' => __( 'Geçersiz istek.', 'bitebimuv-dernek' )] );
    }
    $data = [
        'name'       => sanitize_text_field( $_POST['name']           ?? '' ),
        'surname'    => sanitize_text_field( $_POST['surname']         ?? '' ),
        'email'      => sanitize_email(      $_POST['email']           ?? '' ),
        'phone'      => sanitize_text_field( $_POST['phone']           ?? '' ),
        'birthdate'  => sanitize_text_field( $_POST['birthdate']       ?? '' ),
        'profession' => sanitize_text_field( $_POST['profession']      ?? '' ),
        'city'       => sanitize_text_field( $_POST['city']            ?? '' ),
        'address'    => sanitize_textarea_field( $_POST['address']     ?? '' ),
        'motivation' => sanitize_textarea_field( $_POST['motivation']  ?? '' ),
        'type'       => sanitize_text_field( $_POST['membership_type'] ?? 'standard' ),
        'kvkk'       => ! empty( $_POST['kvkk'] ),
    ];
    if ( ! $data['name'] || ! is_email($data['email']) || ! $data['phone'] ) {
        wp_send_json_error( ['message' => __( 'Zorunlu alanları doldurun.', 'bitebimuv-dernek' )] );
    }
    if ( ! $data['kvkk'] ) {
        wp_send_json_error( ['message' => __( 'KVKK metnini onaylamanız gerekiyor.', 'bitebimuv-dernek' )] );
    }
    $post_id = wp_insert_post( [
        'post_type'  => 'bbm_member_application',
        'post_title' => "{$data['name']} {$data['surname']} — " . current_time('Y-m-d H:i'),
        'post_status'=> 'pending',
        'meta_input' => $data,
    ] );
    if ( is_wp_error( $post_id ) ) {
        wp_send_json_error( ['message' => __( 'Başvuru kaydedilemedi.', 'bitebimuv-dernek' )] );
    }
    wp_mail( get_option('admin_email'), '[BiteBiMuv] Yeni Üyelik Başvurusu',
        bbm_build_email( [ 'title' => 'Yeni Üyelik Başvurusu 🤝', 'fields' => [
            'Ad Soyad'    => "{$data['name']} {$data['surname']}",
            'E-posta'     => $data['email'], 'Telefon'  => $data['phone'],
            'Meslek'      => $data['profession'], 'Şehir' => $data['city'],
            'Üyelik Tipi' => $data['type'],
            'Motivasyon'  => nl2br( esc_html( $data['motivation'] ) ),
        ] ] ), ['Content-Type: text/html; charset=UTF-8'] );
    wp_send_json_success( ['message' => __( 'Başvurunuz alındı! En kısa sürede dönüş yapacağız. 🎉', 'bitebimuv-dernek' )] );
}
add_action( 'wp_ajax_bbm_membership',        'bbm_handle_membership' );
add_action( 'wp_ajax_nopriv_bbm_membership', 'bbm_handle_membership' );

/* ── AJAX: Volunteer Application ── */
function bbm_handle_volunteer_apply(): void {
    check_ajax_referer( 'bbm-nonce', 'nonce' );
    if ( function_exists( 'bbm_is_honeypot_triggered' ) && bbm_is_honeypot_triggered() ) {
        wp_send_json_error( ['message' => __( 'Geçersiz istek.', 'bitebimuv-dernek' )] );
    }
    $name     = sanitize_text_field( $_POST['name']       ?? '' );
    $email    = sanitize_email(      $_POST['email']      ?? '' );
    $phone    = sanitize_text_field( $_POST['phone']      ?? '' );
    $skills   = sanitize_textarea_field( $_POST['skills'] ?? '' );
    $area_ids = array_map( 'absint', (array)( $_POST['areas'] ?? [] ) );
    $kvkk     = ! empty( $_POST['kvkk'] );
    if ( ! $name || ! is_email($email) ) {
        wp_send_json_error( ['message' => __( 'Ad ve e-posta zorunludur.', 'bitebimuv-dernek' )] );
    }
    if ( ! $kvkk ) {
        wp_send_json_error( ['message' => __( 'KVKK metnini onaylamanız gerekiyor.', 'bitebimuv-dernek' )] );
    }
    $post_id = wp_insert_post( [
        'post_type'  => 'bbm_volunteer',
        'post_title' => "$name — " . current_time('Y-m-d'),
        'post_status'=> 'pending',
        'meta_input' => [
            '_bbm_volunteer_email'  => $email,
            '_bbm_volunteer_phone'  => $phone,
            '_bbm_volunteer_skills' => $skills,
            '_bbm_volunteer_status' => 'pending',
        ],
    ] );
    if ( is_wp_error($post_id) ) {
        wp_send_json_error( ['message' => __( 'Başvuru kaydedilemedi.', 'bitebimuv-dernek' )] );
    }
    if ( $area_ids ) {
        wp_set_post_terms( $post_id, $area_ids, 'bbm_volunteer_area' );
    }
    wp_mail( get_option('admin_email'), '[BiteBiMuv] Yeni Gönüllü Başvurusu',
        bbm_build_email_v4( [ 'icon' => '🤝', 'title' => 'Yeni Gönüllü Başvurusu',
            'fields' => [ 'Ad Soyad' => esc_html($name), 'E-posta' => esc_html($email),
                'Telefon' => esc_html($phone), 'Beceriler' => nl2br(esc_html($skills)) ],
        ] ), ['Content-Type: text/html; charset=UTF-8'] );
    wp_send_json_success( ['message' => __( 'Gönüllü başvurunuz alındı! En kısa sürede size ulaşacağız. 🎉', 'bitebimuv-dernek' )] );
}
add_action( 'wp_ajax_bbm_volunteer_apply',        'bbm_handle_volunteer_apply' );
add_action( 'wp_ajax_nopriv_bbm_volunteer_apply', 'bbm_handle_volunteer_apply' );

/* ── AJAX: Live Search ── */
function bbm_handle_live_search(): void {
    check_ajax_referer( 'bbm-nonce', 'nonce' );
    $term = sanitize_text_field( $_POST['term'] ?? '' );
    if ( mb_strlen( $term ) < 2 ) { wp_send_json_success( [] ); }
    $query = new WP_Query( [
        's'              => $term,
        'posts_per_page' => 6,
        'post_type'      => ['post','page','bbm_event','bbm_announcement','bbm_project','bbm_gallery','bbm_document'],
        'post_status'    => 'publish',
    ] );
    $labels  = [
        'post'             => 'Haber',
        'page'             => 'Sayfa',
        'bbm_event'        => 'Etkinlik',
        'bbm_announcement' => 'Duyuru',
        'bbm_project'      => 'Proje',
        'bbm_gallery'      => 'Galeri',
        'bbm_document'     => 'Belge',
    ];
    $results = [];
    foreach ( $query->posts as $post ) {
        $results[] = [
            'id'      => $post->ID,
            'title'   => get_the_title($post),
            'excerpt' => wp_trim_words( strip_tags($post->post_content), 12 ),
            'url'     => get_permalink($post),
            'thumb'   => get_the_post_thumbnail_url( $post->ID, 'bbm-thumb' ) ?: '',
            'type'    => $labels[$post->post_type] ?? $post->post_type,
            'date'    => get_the_date( 'd.m.Y', $post ),
        ];
    }
    wp_send_json_success( $results );
}
add_action( 'wp_ajax_bbm_live_search',        'bbm_handle_live_search' );
add_action( 'wp_ajax_nopriv_bbm_live_search', 'bbm_handle_live_search' );

/* ── HTML Email Builder (v3 compat) ── */
function bbm_build_email( array $args ): string {
    $primary = get_theme_mod( 'bbm_primary_color', '#E8435A' );
    $sec     = get_theme_mod( 'bbm_secondary_color', '#2D3561' );
    $title   = $args['title']   ?? '';
    $intro   = $args['intro']   ?? '';
    $msg     = $args['message'] ?? '';
    $fields  = $args['fields']  ?? [];
    $rows    = '';
    foreach ( $fields as $label => $value ) {
        $rows .= "<tr><td style='padding:10px 16px;font-weight:700;color:{$sec};width:38%;border-bottom:1px solid #eee;font-size:13px;'>{$label}</td>"
               . "<td style='padding:10px 16px;color:#444;border-bottom:1px solid #eee;font-size:14px;'>{$value}</td></tr>";
    }
    $table = $rows ? "<table style='width:100%;border-collapse:collapse;margin:24px 0;background:#fafafa;border-radius:12px;overflow:hidden;border:1px solid #eee;'>{$rows}</table>" : '';
    $site  = get_bloginfo('name');
    $url   = home_url();
    $logo  = get_site_icon_url(64);
    $year  = date('Y');
    return "<!DOCTYPE html><html lang='tr'><head><meta charset='UTF-8'></head><body style='margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,sans-serif;background:#f0f4f8;'>"
        . "<div style='max-width:600px;margin:40px auto;background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.1);'>"
        . "<div style='background:linear-gradient(135deg,{$primary} 0%,{$sec} 100%);padding:48px 40px;text-align:center;'>"
        . ( $logo ? "<img src='{$logo}' width='64' height='64' style='border-radius:50%;margin-bottom:20px;border:3px solid rgba(255,255,255,.3);'>" : '' )
        . "<h1 style='color:#fff;margin:0;font-size:26px;font-weight:800;letter-spacing:-.5px;'>{$title}</h1></div>"
        . "<div style='padding:40px;'>"
        . ( $intro ? "<p style='font-size:17px;color:#333;margin:0 0 8px;font-weight:600;'>{$intro}</p>" : '' )
        . ( $msg   ? "<p style='font-size:15px;color:#555;line-height:1.7;margin:0 0 16px;'>{$msg}</p>"  : '' )
        . "{$table}</div>"
        . "<div style='background:#f8fafc;padding:28px 40px;text-align:center;border-top:1px solid #e8ecf0;'>"
        . "<p style='margin:0;font-size:13px;color:#94a3b8;'><a href='{$url}' style='color:{$primary};text-decoration:none;font-weight:700;'>{$site}</a> &middot; &copy; {$year}</p>"
        . "</div></div></body></html>";
}

/* ── Member Application CPT (admin only) ── */
function bbm_register_application_cpt(): void {
    register_post_type( 'bbm_member_application', [
        'labels'  => [
            'name'          => __( 'Üyelik Başvuruları', 'bitebimuv-dernek' ),
            'singular_name' => __( 'Üyelik Başvurusu',  'bitebimuv-dernek' ),
            'all_items'     => __( 'Tüm Başvurular',    'bitebimuv-dernek' ),
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-groups',
        'supports'     => ['title'],
        'capabilities' => ['create_posts' => 'do_not_allow'],
        'map_meta_cap' => true,
    ] );
}
add_action( 'init', 'bbm_register_application_cpt' );

/* ── Application admin columns ── */
add_filter( 'manage_bbm_member_application_posts_columns', function( $cols ) {
    return [
        'cb'   => $cols['cb'],
        'title'=> __('Ad Soyad','bitebimuv-dernek'),
        'email'=> __('E-posta','bitebimuv-dernek'),
        'type' => __('Üyelik Tipi','bitebimuv-dernek'),
        'city' => __('Şehir','bitebimuv-dernek'),
        'payment' => __('Ödeme','bitebimuv-dernek'),
        'date' => __('Tarih','bitebimuv-dernek'),
    ];
} );
add_action( 'manage_bbm_member_application_posts_custom_column', function( $col, $post_id ) {
    if ( $col === 'email'   ) echo esc_html( get_post_meta($post_id,'email',  true) );
    if ( $col === 'type'    ) echo esc_html( get_post_meta($post_id,'type',   true) );
    if ( $col === 'city'    ) echo esc_html( get_post_meta($post_id,'city',   true) );
    if ( $col === 'payment' ) {
        $status = get_post_meta($post_id, '_bbm_payment_status', true);
        if ( $status === 'paid' ) echo '<span style="color:#059669;font-weight:700;">&#10003; Ödendi</span>';
        else echo '<span style="color:#94a3b8">—</span>';
    }
}, 10, 2 );

/* ── Utilities ── */
add_filter( 'excerpt_length', fn() => 22 );
add_filter( 'excerpt_more',   fn() => '&hellip;' );

function bbm_reading_time( int $post_id = 0 ): string {
    $words = str_word_count( strip_tags( get_post_field( 'post_content', $post_id ?: get_the_ID() ) ) );
    return max(1, (int) round( $words / 200 ) ) . ' dk okuma';
}
