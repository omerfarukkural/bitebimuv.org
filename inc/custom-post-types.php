<?php
/**
 * Özel İçerik Türleri - Etkinlikler, Üyeler, Projeler, Galeri
 *
 * @package bitebimuv-dernek
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Tüm özel içerik türlerini kaydet
 */
function bbm_register_post_types() {

    // ETKİNLİKLER
    register_post_type( 'bbm_event', [
        'labels' => [
            'name'               => __( 'Etkinlikler', 'bitebimuv-dernek' ),
            'singular_name'      => __( 'Etkinlik', 'bitebimuv-dernek' ),
            'add_new_item'       => __( 'Yeni Etkinlik Ekle', 'bitebimuv-dernek' ),
            'edit_item'          => __( 'Etkinliği Düzenle', 'bitebimuv-dernek' ),
            'view_item'          => __( 'Etkinliği Görüntüle', 'bitebimuv-dernek' ),
            'search_items'       => __( 'Etkinlik Ara', 'bitebimuv-dernek' ),
            'not_found'          => __( 'Etkinlik bulunamadı.', 'bitebimuv-dernek' ),
        ],
        'public'             => true,
        'show_in_rest'       => true,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'          => 'dashicons-calendar-alt',
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'etkinlikler' ],
        'menu_position'      => 5,
    ] );

    // YÖNETİM KURULU / ÜYELER
    register_post_type( 'bbm_member', [
        'labels' => [
            'name'          => __( 'Yönetim Kurulu', 'bitebimuv-dernek' ),
            'singular_name' => __( 'Üye', 'bitebimuv-dernek' ),
            'add_new_item'  => __( 'Yeni Üye Ekle', 'bitebimuv-dernek' ),
            'edit_item'     => __( 'Üyeyi Düzenle', 'bitebimuv-dernek' ),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
        'menu_icon'    => 'dashicons-groups',
        'has_archive'  => false,
        'rewrite'      => [ 'slug' => 'yonetim-kurulu' ],
        'menu_position' => 6,
    ] );

    // PROJELER
    register_post_type( 'bbm_project', [
        'labels' => [
            'name'          => __( 'Projeler', 'bitebimuv-dernek' ),
            'singular_name' => __( 'Proje', 'bitebimuv-dernek' ),
            'add_new_item'  => __( 'Yeni Proje Ekle', 'bitebimuv-dernek' ),
            'edit_item'     => __( 'Projeyi Düzenle', 'bitebimuv-dernek' ),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'menu_icon'    => 'dashicons-portfolio',
        'has_archive'  => true,
        'rewrite'      => [ 'slug' => 'projeler' ],
        'menu_position' => 7,
    ] );

    // DUYURULAR
    register_post_type( 'bbm_announcement', [
        'labels' => [
            'name'          => __( 'Duyurular', 'bitebimuv-dernek' ),
            'singular_name' => __( 'Duyuru', 'bitebimuv-dernek' ),
            'add_new_item'  => __( 'Yeni Duyuru Ekle', 'bitebimuv-dernek' ),
        ],
        'public'       => true,
        'show_in_rest' => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'menu_icon'    => 'dashicons-megaphone',
        'has_archive'  => true,
        'rewrite'      => [ 'slug' => 'duyurular' ],
        'menu_position' => 8,
    ] );
}
add_action( 'init', 'bbm_register_post_types' );

/**
 * Etkinlik meta box'larını kaydet
 */
function bbm_register_meta_boxes() {
    add_meta_box(
        'bbm_event_details',
        __( 'Etkinlik Detayları', 'bitebimuv-dernek' ),
        'bbm_event_meta_box_cb',
        'bbm_event',
        'normal',
        'high'
    );

    add_meta_box(
        'bbm_member_details',
        __( 'Üye Detayları', 'bitebimuv-dernek' ),
        'bbm_member_meta_box_cb',
        'bbm_member',
        'normal',
        'high'
    );

    add_meta_box(
        'bbm_project_details',
        __( 'Proje Detayları', 'bitebimuv-dernek' ),
        'bbm_project_meta_box_cb',
        'bbm_project',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'bbm_register_meta_boxes' );

function bbm_event_meta_box_cb( $post ) {
    wp_nonce_field( 'bbm_event_meta', 'bbm_event_nonce' );
    $fields = [
        'bbm_event_date'       => [ 'label' => 'Etkinlik Tarihi',  'type' => 'date' ],
        'bbm_event_time'       => [ 'label' => 'Başlangıç Saati',  'type' => 'time' ],
        'bbm_event_end_time'   => [ 'label' => 'Bitiş Saati',      'type' => 'time' ],
        'bbm_event_location'   => [ 'label' => 'Konum / Mekan',    'type' => 'text' ],
        'bbm_event_address'    => [ 'label' => 'Adres',            'type' => 'textarea' ],
        'bbm_event_capacity'   => [ 'label' => 'Kapasite',         'type' => 'number' ],
        'bbm_event_price'      => [ 'label' => 'Ücret (0=Ücretsiz)', 'type' => 'text' ],
        'bbm_event_organizer'  => [ 'label' => 'Organizatör',      'type' => 'text' ],
        'bbm_event_contact'    => [ 'label' => 'İletişim',         'type' => 'text' ],
        'bbm_event_register'   => [ 'label' => 'Kayıt Linki',      'type' => 'url' ],
    ];
    echo '<div class="bbm-meta-grid">';
    foreach ( $fields as $key => $field ) {
        $val = esc_attr( get_post_meta( $post->ID, $key, true ) );
        echo '<p><label for="' . $key . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><br>';
        if ( $field['type'] === 'textarea' ) {
            echo '<textarea id="' . $key . '" name="' . $key . '" rows="3" style="width:100%">' . esc_textarea( get_post_meta( $post->ID, $key, true ) ) . '</textarea>';
        } else {
            echo '<input type="' . $field['type'] . '" id="' . $key . '" name="' . $key . '" value="' . $val . '" style="width:100%">';
        }
        echo '</p>';
    }
    echo '</div>';
}

function bbm_member_meta_box_cb( $post ) {
    wp_nonce_field( 'bbm_member_meta', 'bbm_member_nonce' );
    $fields = [
        'bbm_member_title'    => 'Unvan / Görevi',
        'bbm_member_email'    => 'E-posta',
        'bbm_member_phone'    => 'Telefon',
        'bbm_member_linkedin' => 'LinkedIn URL',
        'bbm_member_order'    => 'Sıralama (0=en üst)',
    ];
    foreach ( $fields as $key => $label ) {
        $val = esc_attr( get_post_meta( $post->ID, $key, true ) );
        echo '<p><label for="' . $key . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
        echo '<input type="text" id="' . $key . '" name="' . $key . '" value="' . $val . '" style="width:100%"></p>';
    }
}

function bbm_project_meta_box_cb( $post ) {
    wp_nonce_field( 'bbm_project_meta', 'bbm_project_nonce' );
    $fields = [
        'bbm_project_status'    => 'Durum (aktif/tamamlandi/planlandi)',
        'bbm_project_start'     => 'Başlangıç Tarihi',
        'bbm_project_end'       => 'Bitiş Tarihi',
        'bbm_project_budget'    => 'Bütçe',
        'bbm_project_partner'   => 'Proje Ortağı',
        'bbm_project_beneficiary' => 'Hedef Kitle',
    ];
    foreach ( $fields as $key => $label ) {
        $val = esc_attr( get_post_meta( $post->ID, $key, true ) );
        echo '<p><label for="' . $key . '"><strong>' . esc_html( $label ) . '</strong></label><br>';
        echo '<input type="text" id="' . $key . '" name="' . $key . '" value="' . $val . '" style="width:100%"></p>';
    }
}

/**
 * Meta verileri kaydet
 */
function bbm_save_post_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $meta_keys = [
        'bbm_event_date', 'bbm_event_time', 'bbm_event_end_time', 'bbm_event_location',
        'bbm_event_address', 'bbm_event_capacity', 'bbm_event_price', 'bbm_event_organizer',
        'bbm_event_contact', 'bbm_event_register',
        'bbm_member_title', 'bbm_member_email', 'bbm_member_phone', 'bbm_member_linkedin', 'bbm_member_order',
        'bbm_project_status', 'bbm_project_start', 'bbm_project_end', 'bbm_project_budget',
        'bbm_project_partner', 'bbm_project_beneficiary',
    ];

    foreach ( $meta_keys as $key ) {
        if ( isset( $_POST[ $key ] ) ) {
            update_post_meta( $post_id, $key, sanitize_text_field( $_POST[ $key ] ) );
        }
    }
}
add_action( 'save_post', 'bbm_save_post_meta' );

/**
 * Etkinlik sorgusunu tarihe göre sırala
 */
function bbm_event_query_order( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'bbm_event' ) ) {
        $query->set( 'meta_key', 'bbm_event_date' );
        $query->set( 'orderby', 'meta_value' );
        $query->set( 'order', 'ASC' );
        $query->set( 'meta_query', [
            [
                'key'     => 'bbm_event_date',
                'value'   => date( 'Y-m-d' ),
                'compare' => '>=',
                'type'    => 'DATE',
            ],
        ] );
    }
}
add_action( 'pre_get_posts', 'bbm_event_query_order' );
