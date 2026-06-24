<?php
/**
 * BiteBiMuv — Custom Post Types (Etkinlik, Üye, Proje, Duyuru, Galeri)
 */

/* ── Event CPT ── */
add_action( 'init', function () {
    register_post_type( 'bbm_event', [
        'labels' => [
            'name'               => __( 'Etkinlikler',       'bitebimuv-dernek' ),
            'singular_name'      => __( 'Etkinlik',          'bitebimuv-dernek' ),
            'add_new_item'       => __( 'Etkinlik Ekle',     'bitebimuv-dernek' ),
            'edit_item'          => __( 'Etkinliği Düzenle', 'bitebimuv-dernek' ),
            'search_items'       => __( 'Etkinlik Ara',      'bitebimuv-dernek' ),
            'not_found'          => __( 'Etkinlik bulunamadı.', 'bitebimuv-dernek' ),
        ],
        'public'            => true,
        'has_archive'       => true,
        'rewrite'           => ['slug' => 'etkinlikler'],
        'menu_icon'         => 'dashicons-calendar-alt',
        'menu_position'     => 5,
        'supports'          => ['title','editor','thumbnail','excerpt','author'],
        'show_in_rest'      => true,
    ] );

    register_taxonomy( 'bbm_event_category', 'bbm_event', [
        'label'        => __( 'Etkinlik Kategorisi', 'bitebimuv-dernek' ),
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'etkinlik-kategorisi'],
        'show_in_rest' => true,
    ] );
} );

function bbm_add_event_meta_box(): void {
    add_meta_box( 'bbm_event_details', __( 'Etkinlik Bilgileri', 'bitebimuv-dernek' ),
        'bbm_render_event_meta_box', 'bbm_event', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'bbm_add_event_meta_box' );

function bbm_render_event_meta_box( WP_Post $post ): void {
    wp_nonce_field( 'bbm_save_event_meta', 'bbm_event_nonce' );
    $get = fn($key) => esc_attr( (string) get_post_meta($post->ID, $key, true) );
    $fields = [
        ['_bbm_event_date',       'text', 'Tarih (YYYY-AA-GG)', 'date'],
        ['_bbm_event_time',       'text', 'Saat (ÖR: 14:00)',   'time'],
        ['_bbm_event_end_date',   'text', 'Bitiş Tarihi',       'date'],
        ['_bbm_event_location',   'text', 'Mekan Adı',          ''],
        ['_bbm_event_address',    'text', 'Adres',              ''],
        ['_bbm_event_maps_url',   'url',  'Google Maps URL',    ''],
        ['_bbm_event_capacity',   'number','Kapasite (kişi)',   ''],
        ['_bbm_event_price',      'text', 'Ücret (0=Ücretsiz)', ''],
        ['_bbm_event_organizer',  'text', 'Organizatör',        ''],
        ['_bbm_event_contact',    'text', 'İletişim',           ''],
        ['_bbm_event_online_url', 'url',  'Online Katılım URL', ''],
    ];
    echo "<div class='bbm-meta-grid'>";
    foreach ( $fields as [$key, $type, $label, $input_type] ) {
        $it = $input_type ?: $type;
        printf(
            '<p><label for="%1$s"><strong>%2$s</strong></label><br><input class="widefat" id="%1$s" name="%1$s" type="%3$s" value="%4$s"></p>',
            esc_attr($key), esc_html($label), esc_attr($it), $get($key)
        );
    }
    // Online event checkbox
    $online = get_post_meta( $post->ID, '_bbm_event_is_online', true );
    echo '<p><label><input type="checkbox" name="_bbm_event_is_online" value="1" ' . checked(1, $online, false) . '> <strong>' . __('Online Etkinlik','bitebimuv-dernek') . '</strong></label></p>';
    // Registration enabled
    $reg = get_post_meta( $post->ID, '_bbm_event_registration', true );
    echo '<p><label><input type="checkbox" name="_bbm_event_registration" value="1" ' . checked(1, $reg, false) . '> <strong>' . __('Ön Kayıt Açık','bitebimuv-dernek') . '</strong></label></p>';
    echo '</div>';
}

function bbm_save_event_meta( int $post_id ): void {
    if ( ! isset($_POST['bbm_event_nonce']) || ! wp_verify_nonce($_POST['bbm_event_nonce'],'bbm_save_event_meta') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    if ( ! current_user_can('edit_post', $post_id) ) return;

    $text_keys = ['_bbm_event_date','_bbm_event_time','_bbm_event_end_date',
        '_bbm_event_location','_bbm_event_address','_bbm_event_capacity',
        '_bbm_event_price','_bbm_event_organizer','_bbm_event_contact'];
    $url_keys  = ['_bbm_event_maps_url','_bbm_event_online_url'];
    $bool_keys = ['_bbm_event_is_online','_bbm_event_registration'];

    foreach ( $text_keys as $key ) update_post_meta( $post_id, $key, sanitize_text_field( $_POST[$key] ?? '' ) );
    foreach ( $url_keys  as $key ) update_post_meta( $post_id, $key, esc_url_raw( $_POST[$key] ?? '' ) );
    foreach ( $bool_keys as $key ) update_post_meta( $post_id, $key, ! empty($_POST[$key]) ? 1 : 0 );
}
add_action( 'save_post_bbm_event', 'bbm_save_event_meta' );

// Sort events by date ascending, exclude past
add_action( 'pre_get_posts', function( WP_Query $q ) {
    if ( ! is_admin() && $q->is_main_query() && $q->is_post_type_archive('bbm_event') ) {
        $q->set( 'meta_key', '_bbm_event_date' );
        $q->set( 'orderby', 'meta_value' );
        $q->set( 'order', 'ASC' );
        $q->set( 'meta_query', [[
            'key'     => '_bbm_event_date',
            'value'   => date('Y-m-d'),
            'compare' => '>=',
            'type'    => 'DATE',
        ]] );
    }
} );

/* ── Member CPT ── */
add_action( 'init', function () {
    register_post_type( 'bbm_member', [
        'labels' => [
            'name'          => __( 'Yönetim Kurulu',  'bitebimuv-dernek' ),
            'singular_name' => __( 'Üye',             'bitebimuv-dernek' ),
            'add_new_item'  => __( 'Üye Ekle',        'bitebimuv-dernek' ),
        ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'yonetim-kurulu'],
        'menu_icon'     => 'dashicons-groups',
        'menu_position' => 6,
        'supports'      => ['title','editor','thumbnail'],
        'show_in_rest'  => true,
    ] );
} );

function bbm_add_member_meta_box(): void {
    add_meta_box( 'bbm_member_details', __('Üye Bilgileri','bitebimuv-dernek'),
        'bbm_render_member_meta_box', 'bbm_member', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'bbm_add_member_meta_box' );

function bbm_render_member_meta_box( WP_Post $post ): void {
    wp_nonce_field( 'bbm_save_member_meta', 'bbm_member_nonce' );
    $get = fn($key) => esc_attr( (string) get_post_meta($post->ID, $key, true) );
    $fields = [
        '_bbm_member_title'    => 'Unvan / Görev',
        '_bbm_member_phone'    => 'Telefon',
        '_bbm_member_email'    => 'E-posta',
        '_bbm_member_linkedin' => 'LinkedIn URL',
        '_bbm_member_twitter'  => 'Twitter URL',
        '_bbm_member_order'    => 'Sıralama',
    ];
    echo "<div class='bbm-meta-grid'>";
    foreach ( $fields as $key => $label ) {
        printf( '<p><label><strong>%s</strong><br><input class="widefat" name="%s" type="text" value="%s"></label></p>',
            esc_html($label), esc_attr($key), $get($key) );
    }
    echo '</div>';
}

add_action( 'save_post_bbm_member', function( int $id ) {
    if ( ! isset($_POST['bbm_member_nonce']) || ! wp_verify_nonce($_POST['bbm_member_nonce'],'bbm_save_member_meta') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    $keys = ['_bbm_member_title','_bbm_member_phone','_bbm_member_email','_bbm_member_linkedin','_bbm_member_twitter','_bbm_member_order'];
    foreach ( $keys as $k ) update_post_meta( $id, $k, sanitize_text_field($_POST[$k] ?? '') );
} );

/* ── Project CPT ── */
add_action( 'init', function () {
    register_post_type( 'bbm_project', [
        'labels'        => [ 'name' => __('Projeler','bitebimuv-dernek'), 'singular_name' => __('Proje','bitebimuv-dernek') ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'projeler'],
        'menu_icon'     => 'dashicons-portfolio',
        'menu_position' => 7,
        'supports'      => ['title','editor','thumbnail','excerpt'],
        'show_in_rest'  => true,
    ] );
} );

/* ── Announcement CPT ── */
add_action( 'init', function () {
    register_post_type( 'bbm_announcement', [
        'labels'        => [ 'name' => __('Duyurular','bitebimuv-dernek'), 'singular_name' => __('Duyuru','bitebimuv-dernek') ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'duyurular'],
        'menu_icon'     => 'dashicons-megaphone',
        'menu_position' => 8,
        'supports'      => ['title','editor','thumbnail','excerpt'],
        'show_in_rest'  => true,
    ] );
    register_taxonomy( 'bbm_announcement_type', 'bbm_announcement', [
        'label'        => __('Duyuru Türü','bitebimuv-dernek'),
        'hierarchical' => true,
        'show_in_rest' => true,
    ] );
} );

/* ── Gallery CPT ── */
add_action( 'init', function () {
    register_post_type( 'bbm_gallery', [
        'labels' => [
            'name'          => __( 'Galeri',       'bitebimuv-dernek' ),
            'singular_name' => __( 'Galeri Albümü','bitebimuv-dernek' ),
            'add_new_item'  => __( 'Albüm Ekle',  'bitebimuv-dernek' ),
        ],
        'public'        => true,
        'has_archive'   => true,
        'rewrite'       => ['slug' => 'galeri'],
        'menu_icon'     => 'dashicons-format-gallery',
        'menu_position' => 9,
        'supports'      => ['title','editor','thumbnail','excerpt'],
        'show_in_rest'  => true,
    ] );
    register_taxonomy( 'bbm_gallery_category', 'bbm_gallery', [
        'label'        => __('Galeri Kategorisi','bitebimuv-dernek'),
        'hierarchical' => true,
        'show_in_rest' => true,
    ] );
} );

function bbm_add_gallery_meta_box(): void {
    add_meta_box( 'bbm_gallery_images', __('Galeri Görselleri','bitebimuv-dernek'),
        'bbm_render_gallery_meta_box', 'bbm_gallery', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'bbm_add_gallery_meta_box' );

function bbm_render_gallery_meta_box( WP_Post $post ): void {
    wp_nonce_field( 'bbm_save_gallery_meta', 'bbm_gallery_nonce' );
    $ids   = get_post_meta( $post->ID, '_bbm_gallery_images', true ) ?: '';
    $event = get_post_meta( $post->ID, '_bbm_gallery_event_date', true );
    echo '<p><label><strong>' . __('Görsel ID listesi (virgülle ayrılmış)','bitebimuv-dernek') . '</strong><br>';
    echo '<input class="widefat" name="_bbm_gallery_images" value="' . esc_attr($ids) . '"></label></p>';
    echo '<p><label><strong>' . __('Etkinlik Tarihi','bitebimuv-dernek') . '</strong><br>';
    echo '<input class="widefat" name="_bbm_gallery_event_date" type="date" value="' . esc_attr($event) . '"></label></p>';
    echo '<p><em>' . __('İpucu: Medya Kitaplığı ID\'lerini Media Widget ile bulabilirsiniz.','bitebimuv-dernek') . '</em></p>';
}

add_action( 'save_post_bbm_gallery', function( int $id ) {
    if ( ! isset($_POST['bbm_gallery_nonce']) || ! wp_verify_nonce($_POST['bbm_gallery_nonce'],'bbm_save_gallery_meta') ) return;
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
    update_post_meta( $id, '_bbm_gallery_images',     sanitize_text_field($_POST['_bbm_gallery_images'] ?? '') );
    update_post_meta( $id, '_bbm_gallery_event_date', sanitize_text_field($_POST['_bbm_gallery_event_date'] ?? '') );
} );
