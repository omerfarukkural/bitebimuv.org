<?php
/**
 * Admin Export – Üye ve Etkinlik CSV Dışa Aktarma
 * Bite Bi Muv Derneği Teması v4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'admin_menu',  'bbm_register_export_page' );
add_action( 'admin_init',  'bbm_handle_export_request' );
add_action( 'admin_notices', 'bbm_export_notices' );

function bbm_register_export_page() {
    add_submenu_page(
        'edit.php?post_type=bbm_member',
        __( 'Dışa Aktar', 'bitebimuv' ),
        __( '📤 Dışa Aktar', 'bitebimuv' ),
        'manage_options',
        'bbm-export',
        'bbm_export_page_html'
    );
}

function bbm_export_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) wp_die( __( 'Yetkisiz erişim.', 'bitebimuv' ) );
    ?>
    <div class="wrap">
        <h1><?php _e( 'Dışa Aktar (CSV)', 'bitebimuv' ); ?></h1>
        <div class="card" style="max-width:600px;padding:20px;margin-top:20px">
            <form method="post">
                <?php wp_nonce_field( 'bbm_export_action', 'bbm_export_nonce' ); ?>

                <h3><?php _e( 'Üyeleri Dışa Aktar', 'bitebimuv' ); ?></h3>
                <p><?php _e( 'Tüm üye listesini (isim, unvan, e-posta, telefon, şehir) CSV olarak indir.', 'bitebimuv' ); ?></p>
                <button type="submit" name="bbm_export_type" value="members" class="button button-primary">
                    <?php _e( '📥 Üyeleri İndir', 'bitebimuv' ); ?>
                </button>

                <hr style="margin:20px 0">

                <h3><?php _e( 'Etkinlikleri Dışa Aktar', 'bitebimuv' ); ?></h3>
                <p><?php _e( 'Tüm etkinlikleri (başlık, tarih, konum, kapasite, kayıt sayısı) CSV olarak indir.', 'bitebimuv' ); ?></p>
                <button type="submit" name="bbm_export_type" value="events" class="button button-primary">
                    <?php _e( '📥 Etkinlikleri İndir', 'bitebimuv' ); ?>
                </button>

                <hr style="margin:20px 0">

                <h3><?php _e( 'Gönüllüleri Dışa Aktar', 'bitebimuv' ); ?></h3>
                <button type="submit" name="bbm_export_type" value="volunteers" class="button button-primary">
                    <?php _e( '📥 Gönüllüleri İndir', 'bitebimuv' ); ?>
                </button>

                <hr style="margin:20px 0">

                <h3><?php _e( 'Üyelik Başvurularını Dışa Aktar', 'bitebimuv' ); ?></h3>
                <button type="submit" name="bbm_export_type" value="applications" class="button button-primary">
                    <?php _e( '📥 Başvuruları İndir', 'bitebimuv' ); ?>
                </button>
            </form>
        </div>
    </div>
    <?php
}

function bbm_handle_export_request() {
    if ( ! isset( $_POST['bbm_export_type'] ) ) return;
    if ( ! isset( $_POST['bbm_export_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['bbm_export_nonce'] ) ), 'bbm_export_action' ) ) {
        wp_die( __( 'Güvenlik doğrulaması başarısız.', 'bitebimuv' ) );
    }
    if ( ! current_user_can( 'manage_options' ) ) wp_die();

    $type = sanitize_key( $_POST['bbm_export_type'] );

    switch ( $type ) {
        case 'members':
            bbm_export_members_csv();
            break;
        case 'events':
            bbm_export_events_csv();
            break;
        case 'volunteers':
            bbm_export_volunteers_csv();
            break;
        case 'applications':
            bbm_export_applications_csv();
            break;
    }
}

function bbm_export_members_csv() {
    $filename = 'uyeler-' . date( 'Ymd-His' ) . '.csv';
    header( 'Content-Type: text/csv; charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Pragma: no-cache' );

    $out = fopen( 'php://output', 'w' );
    // UTF-8 BOM for Excel
    fputs( $out, "\xEF\xBB\xBF" );
    fputcsv( $out, [ 'ID', 'Ad Soyad', 'Unvan', 'E-posta', 'Telefon', 'Şehir', 'Meslek', 'Üyelik Tarihi' ] );

    $members = get_posts( [
        'post_type'   => 'bbm_member',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby'     => 'title',
        'order'       => 'ASC',
    ] );

    foreach ( $members as $m ) {
        fputcsv( $out, [
            $m->ID,
            $m->post_title,
            get_post_meta( $m->ID, '_bbm_member_role', true ),
            get_post_meta( $m->ID, '_bbm_member_email', true ),
            get_post_meta( $m->ID, '_bbm_member_phone', true ),
            get_post_meta( $m->ID, '_bbm_member_city', true ),
            get_post_meta( $m->ID, '_bbm_member_occupation', true ),
            get_the_date( 'd.m.Y', $m ),
        ] );
    }
    fclose( $out );
    exit;
}

function bbm_export_events_csv() {
    $filename = 'etkinlikler-' . date( 'Ymd-His' ) . '.csv';
    header( 'Content-Type: text/csv; charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Pragma: no-cache' );

    $out = fopen( 'php://output', 'w' );
    fputs( $out, "\xEF\xBB\xBF" );
    fputcsv( $out, [ 'ID', 'Başlık', 'Tarih', 'Saat', 'Bitiş', 'Konum', 'Kapasite', 'Tür', 'Fiyat', 'Oluşturulma' ] );

    $events = get_posts( [
        'post_type'   => 'bbm_event',
        'post_status' => 'publish',
        'numberposts' => -1,
        'meta_key'    => '_bbm_event_date',
        'orderby'     => 'meta_value',
        'order'       => 'DESC',
    ] );

    foreach ( $events as $e ) {
        fputcsv( $out, [
            $e->ID,
            $e->post_title,
            get_post_meta( $e->ID, '_bbm_event_date', true ),
            get_post_meta( $e->ID, '_bbm_event_time', true ),
            get_post_meta( $e->ID, '_bbm_event_end_date', true ),
            get_post_meta( $e->ID, '_bbm_event_location', true ),
            get_post_meta( $e->ID, '_bbm_event_capacity', true ),
            get_post_meta( $e->ID, '_bbm_event_type', true ),
            get_post_meta( $e->ID, '_bbm_event_price', true ),
            get_the_date( 'd.m.Y H:i', $e ),
        ] );
    }
    fclose( $out );
    exit;
}

function bbm_export_volunteers_csv() {
    $filename = 'gonulluler-' . date( 'Ymd-His' ) . '.csv';
    header( 'Content-Type: text/csv; charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Pragma: no-cache' );

    $out = fopen( 'php://output', 'w' );
    fputs( $out, "\xEF\xBB\xBF" );
    fputcsv( $out, [ 'ID', 'Ad Soyad', 'E-posta', 'Telefon', 'Müsaitlik', 'Beceriler', 'Durum', 'Başlangıç' ] );

    $volunteers = get_posts( [
        'post_type'   => 'bbm_volunteer',
        'post_status' => 'publish',
        'numberposts' => -1,
        'orderby'     => 'title',
        'order'       => 'ASC',
    ] );

    foreach ( $volunteers as $v ) {
        fputcsv( $out, [
            $v->ID,
            $v->post_title,
            get_post_meta( $v->ID, '_bbm_volunteer_email', true ),
            get_post_meta( $v->ID, '_bbm_volunteer_phone', true ),
            get_post_meta( $v->ID, '_bbm_volunteer_availability', true ),
            get_post_meta( $v->ID, '_bbm_volunteer_skills', true ),
            get_post_meta( $v->ID, '_bbm_volunteer_status', true ),
            get_post_meta( $v->ID, '_bbm_volunteer_since', true ),
        ] );
    }
    fclose( $out );
    exit;
}

function bbm_export_applications_csv() {
    $filename = 'basvurular-' . date( 'Ymd-His' ) . '.csv';
    header( 'Content-Type: text/csv; charset=UTF-8' );
    header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
    header( 'Pragma: no-cache' );

    $out = fopen( 'php://output', 'w' );
    fputs( $out, "\xEF\xBB\xBF" );
    fputcsv( $out, [ 'ID', 'Ad Soyad', 'E-posta', 'Telefon', 'Şehir', 'Meslek', 'Üyelik Tipi', 'Başvuru Tarihi', 'Durum' ] );

    $apps = get_posts( [
        'post_type'   => 'bbm_member_application',
        'post_status' => [ 'publish', 'pending', 'draft' ],
        'numberposts' => -1,
        'orderby'     => 'date',
        'order'       => 'DESC',
    ] );

    foreach ( $apps as $a ) {
        fputcsv( $out, [
            $a->ID,
            get_post_meta( $a->ID, '_bbm_app_name', true ),
            get_post_meta( $a->ID, '_bbm_app_email', true ),
            get_post_meta( $a->ID, '_bbm_app_phone', true ),
            get_post_meta( $a->ID, '_bbm_app_city', true ),
            get_post_meta( $a->ID, '_bbm_app_occupation', true ),
            get_post_meta( $a->ID, '_bbm_app_membership_type', true ),
            get_the_date( 'd.m.Y H:i', $a ),
            get_post_status( $a ),
        ] );
    }
    fclose( $out );
    exit;
}

function bbm_export_notices() {}
