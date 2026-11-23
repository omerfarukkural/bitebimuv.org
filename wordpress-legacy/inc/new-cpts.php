<?php
/**
 * Yeni Custom Post Types: Gönüllü, Sponsor, Belge, Tanıklık
 * Bite Bi Muv Derneği Teması v4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', 'bbm_register_new_cpts' );
add_action( 'add_meta_boxes', 'bbm_register_new_cpt_meta_boxes' );
add_action( 'save_post', 'bbm_save_new_cpt_meta', 10, 2 );

function bbm_register_new_cpts() {

    // ── Gönüllü ──────────────────────────────────────────────────────────────
    register_post_type( 'bbm_volunteer', [
        'labels' => [
            'name'               => __( 'Gönüllüler', 'bitebimuv' ),
            'singular_name'      => __( 'Gönüllü', 'bitebimuv' ),
            'add_new_item'       => __( 'Yeni Gönüllü Ekle', 'bitebimuv' ),
            'edit_item'          => __( 'Gönüllüyü Düzenle', 'bitebimuv' ),
            'search_items'       => __( 'Gönüllü Ara', 'bitebimuv' ),
            'not_found'          => __( 'Gönüllü bulunamadı.', 'bitebimuv' ),
        ],
        'public'             => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-groups',
        'menu_position'      => 26,
        'supports'           => [ 'title', 'thumbnail', 'excerpt' ],
        'has_archive'        => 'gonulluler',
        'rewrite'            => [ 'slug' => 'gonullu' ],
        'show_in_nav_menus'  => true,
    ] );

    register_taxonomy( 'bbm_volunteer_area', 'bbm_volunteer', [
        'labels'       => [
            'name'          => __( 'Gönüllülük Alanları', 'bitebimuv' ),
            'singular_name' => __( 'Alan', 'bitebimuv' ),
            'add_new_item'  => __( 'Yeni Alan Ekle', 'bitebimuv' ),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'gonullu-alani' ],
    ] );

    // ── Sponsor / Destekçi ───────────────────────────────────────────────────
    register_post_type( 'bbm_sponsor', [
        'labels' => [
            'name'          => __( 'Destekçiler', 'bitebimuv' ),
            'singular_name' => __( 'Destekçi', 'bitebimuv' ),
            'add_new_item'  => __( 'Yeni Destekçi Ekle', 'bitebimuv' ),
            'edit_item'     => __( 'Destekçiyi Düzenle', 'bitebimuv' ),
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-star-filled',
        'menu_position'     => 27,
        'supports'          => [ 'title', 'thumbnail', 'page-attributes' ],
        'has_archive'       => false,
        'rewrite'           => [ 'slug' => 'destekci' ],
        'show_in_nav_menus' => false,
    ] );

    // ── Belge / Doküman ───────────────────────────────────────────────────────
    register_post_type( 'bbm_document', [
        'labels' => [
            'name'          => __( 'Belgeler', 'bitebimuv' ),
            'singular_name' => __( 'Belge', 'bitebimuv' ),
            'add_new_item'  => __( 'Yeni Belge Ekle', 'bitebimuv' ),
            'edit_item'     => __( 'Belgeyi Düzenle', 'bitebimuv' ),
        ],
        'public'            => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-media-document',
        'menu_position'     => 28,
        'supports'          => [ 'title', 'excerpt', 'page-attributes' ],
        'has_archive'       => 'belgeler',
        'rewrite'           => [ 'slug' => 'belge' ],
        'show_in_nav_menus' => true,
    ] );

    register_taxonomy( 'bbm_document_type', 'bbm_document', [
        'labels'       => [
            'name'          => __( 'Belge Türleri', 'bitebimuv' ),
            'singular_name' => __( 'Belge Türü', 'bitebimuv' ),
            'add_new_item'  => __( 'Yeni Tür Ekle', 'bitebimuv' ),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'belge-turu' ],
    ] );

    // ── Tanıklık / Yorum ──────────────────────────────────────────────────────
    register_post_type( 'bbm_testimonial', [
        'labels' => [
            'name'          => __( 'Tanıklıklar', 'bitebimuv' ),
            'singular_name' => __( 'Tanıklık', 'bitebimuv' ),
            'add_new_item'  => __( 'Yeni Tanıklık Ekle', 'bitebimuv' ),
            'edit_item'     => __( 'Tanıklığı Düzenle', 'bitebimuv' ),
        ],
        'public'            => false,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'menu_icon'         => 'dashicons-format-quote',
        'menu_position'     => 29,
        'supports'          => [ 'title', 'thumbnail' ],
        'has_archive'       => false,
        'show_in_nav_menus' => false,
    ] );
}

// ─────────────────────────────────────────────────────────────────────────────
// Meta Boxes
// ─────────────────────────────────────────────────────────────────────────────

function bbm_register_new_cpt_meta_boxes() {
    add_meta_box( 'bbm_volunteer_meta', __( 'Gönüllü Bilgileri', 'bitebimuv' ), 'bbm_volunteer_meta_box', 'bbm_volunteer', 'normal', 'high' );
    add_meta_box( 'bbm_sponsor_meta',   __( 'Destekçi Bilgileri', 'bitebimuv' ),  'bbm_sponsor_meta_box',   'bbm_sponsor',   'normal', 'high' );
    add_meta_box( 'bbm_document_meta',  __( 'Belge Bilgileri', 'bitebimuv' ),     'bbm_document_meta_box',  'bbm_document',  'normal', 'high' );
    add_meta_box( 'bbm_testimonial_meta', __( 'Tanıklık Bilgileri', 'bitebimuv' ), 'bbm_testimonial_meta_box', 'bbm_testimonial', 'normal', 'high' );
}

function bbm_volunteer_meta_box( WP_Post $post ) {
    wp_nonce_field( 'bbm_volunteer_meta_nonce', 'bbm_volunteer_nonce' );
    $fields = [
        '_bbm_volunteer_email'        => [ 'label' => 'E-posta', 'type' => 'email' ],
        '_bbm_volunteer_phone'        => [ 'label' => 'Telefon', 'type' => 'text' ],
        '_bbm_volunteer_availability' => [ 'label' => 'Müsaitlik (örn: Hafta sonları)', 'type' => 'text' ],
        '_bbm_volunteer_skills'       => [ 'label' => 'Beceriler (virgülle ayrılmış)', 'type' => 'text' ],
        '_bbm_volunteer_since'        => [ 'label' => 'Gönüllü Başlangıç Tarihi', 'type' => 'date' ],
        '_bbm_volunteer_status'       => [ 'label' => 'Durum', 'type' => 'select', 'options' => [ 'active' => 'Aktif', 'inactive' => 'Pasif', 'pending' => 'Beklemede' ] ],
    ];
    bbm_render_meta_fields( $post, $fields );
}

function bbm_sponsor_meta_box( WP_Post $post ) {
    wp_nonce_field( 'bbm_sponsor_meta_nonce', 'bbm_sponsor_nonce' );
    $fields = [
        '_bbm_sponsor_website'  => [ 'label' => 'Website URL', 'type' => 'url' ],
        '_bbm_sponsor_level'    => [ 'label' => 'Sponsor Seviyesi', 'type' => 'select', 'options' => [ 'platinum' => 'Platin', 'gold' => 'Altın', 'silver' => 'Gümüş', 'bronze' => 'Bronz', 'supporter' => 'Destekçi' ] ],
        '_bbm_sponsor_since'    => [ 'label' => 'Başlangıç Tarihi', 'type' => 'date' ],
        '_bbm_sponsor_until'    => [ 'label' => 'Bitiş Tarihi', 'type' => 'date' ],
        '_bbm_sponsor_contact'  => [ 'label' => 'İletişim Kişisi', 'type' => 'text' ],
        '_bbm_sponsor_featured' => [ 'label' => 'Öne Çıkar (1=evet)', 'type' => 'number' ],
    ];
    bbm_render_meta_fields( $post, $fields );
}

function bbm_document_meta_box( WP_Post $post ) {
    wp_nonce_field( 'bbm_document_meta_nonce', 'bbm_document_nonce' );
    $fields = [
        '_bbm_document_file_url'    => [ 'label' => 'Dosya URL (PDF/DOC)', 'type' => 'url' ],
        '_bbm_document_file_size'   => [ 'label' => 'Dosya Boyutu (örn: 2.4 MB)', 'type' => 'text' ],
        '_bbm_document_year'        => [ 'label' => 'Yıl', 'type' => 'number' ],
        '_bbm_document_language'    => [ 'label' => 'Dil', 'type' => 'select', 'options' => [ 'tr' => 'Türkçe', 'en' => 'İngilizce' ] ],
        '_bbm_document_download_count' => [ 'label' => 'İndirme Sayısı', 'type' => 'number' ],
    ];
    bbm_render_meta_fields( $post, $fields );
}

function bbm_testimonial_meta_box( WP_Post $post ) {
    wp_nonce_field( 'bbm_testimonial_meta_nonce', 'bbm_testimonial_nonce' );
    $fields = [
        '_bbm_testimonial_author'       => [ 'label' => 'Kişi Adı', 'type' => 'text' ],
        '_bbm_testimonial_role'         => [ 'label' => 'Görevi / Ünvanı', 'type' => 'text' ],
        '_bbm_testimonial_company'      => [ 'label' => 'Kurum / Üyelik Tipi', 'type' => 'text' ],
        '_bbm_testimonial_rating'       => [ 'label' => 'Puan (1–5)', 'type' => 'number' ],
        '_bbm_testimonial_quote'        => [ 'label' => 'Yorum Metni', 'type' => 'textarea' ],
        '_bbm_testimonial_approved'     => [ 'label' => 'Onaylandı (1=evet)', 'type' => 'number' ],
    ];
    bbm_render_meta_fields( $post, $fields );
}

// ─────────────────────────────────────────────────────────────────────────────
// Generic meta field renderer
// ─────────────────────────────────────────────────────────────────────────────

function bbm_render_meta_fields( WP_Post $post, array $fields ) {
    echo '<table class="form-table" style="width:100%">';
    foreach ( $fields as $key => $field ) {
        $value = get_post_meta( $post->ID, $key, true );
        echo '<tr><th style="width:180px"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';

        switch ( $field['type'] ) {
            case 'textarea':
                echo '<textarea name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" rows="4" style="width:100%">' . esc_textarea( $value ) . '</textarea>';
                break;
            case 'select':
                echo '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" style="min-width:200px">';
                echo '<option value="">— Seçin —</option>';
                foreach ( $field['options'] as $opt_val => $opt_label ) {
                    echo '<option value="' . esc_attr( $opt_val ) . '"' . selected( $value, $opt_val, false ) . '>' . esc_html( $opt_label ) . '</option>';
                }
                echo '</select>';
                break;
            default:
                echo '<input type="' . esc_attr( $field['type'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" style="width:100%">';
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

// ─────────────────────────────────────────────────────────────────────────────
// Save meta
// ─────────────────────────────────────────────────────────────────────────────

function bbm_save_new_cpt_meta( int $post_id, WP_Post $post ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! in_array( $post->post_type, [ 'bbm_volunteer', 'bbm_sponsor', 'bbm_document', 'bbm_testimonial' ], true ) ) return;

    $nonce_map = [
        'bbm_volunteer'   => 'bbm_volunteer_nonce',
        'bbm_sponsor'     => 'bbm_sponsor_nonce',
        'bbm_document'    => 'bbm_document_nonce',
        'bbm_testimonial' => 'bbm_testimonial_nonce',
    ];

    $nonce_key = $nonce_map[ $post->post_type ] ?? '';
    if ( ! $nonce_key || ! isset( $_POST[ $nonce_key ] ) ) return;

    $action_map = [
        'bbm_volunteer'   => 'bbm_volunteer_meta_nonce',
        'bbm_sponsor'     => 'bbm_sponsor_meta_nonce',
        'bbm_document'    => 'bbm_document_meta_nonce',
        'bbm_testimonial' => 'bbm_testimonial_meta_nonce',
    ];

    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce_key ] ) ), $action_map[ $post->post_type ] ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $prefix_map = [
        'bbm_volunteer'   => '_bbm_volunteer_',
        'bbm_sponsor'     => '_bbm_sponsor_',
        'bbm_document'    => '_bbm_document_',
        'bbm_testimonial' => '_bbm_testimonial_',
    ];

    $prefix = $prefix_map[ $post->post_type ];

    foreach ( $_POST as $key => $value ) {
        if ( strpos( $key, $prefix ) === 0 ) {
            if ( in_array( $key, [ '_bbm_testimonial_quote' ], true ) ) {
                update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $value ) ) );
            } elseif ( in_array( $key, [ '_bbm_sponsor_website', '_bbm_document_file_url' ], true ) ) {
                update_post_meta( $post_id, $key, esc_url_raw( wp_unslash( $value ) ) );
            } else {
                update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $value ) ) );
            }
        }
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// Document download counter
// ─────────────────────────────────────────────────────────────────────────────

add_action( 'wp_ajax_bbm_document_download',        'bbm_handle_document_download' );
add_action( 'wp_ajax_nopriv_bbm_document_download', 'bbm_handle_document_download' );

function bbm_handle_document_download() {
    check_ajax_referer( 'bbm_nonce', 'nonce' );
    $post_id = absint( $_POST['post_id'] ?? 0 );
    if ( ! $post_id ) wp_send_json_error();

    $count = (int) get_post_meta( $post_id, '_bbm_document_download_count', true );
    update_post_meta( $post_id, '_bbm_document_download_count', $count + 1 );

    $url = get_post_meta( $post_id, '_bbm_document_file_url', true );
    wp_send_json_success( [ 'url' => esc_url( $url ), 'count' => $count + 1 ] );
}
