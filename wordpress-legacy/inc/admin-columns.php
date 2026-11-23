<?php
/**
 * Admin Columns – CPT Listelerinde Özel Sütunlar
 * Bite Bi Muv Derneği Teması v4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// ── Event columns ─────────────────────────────────────────────────────────────
add_filter( 'manage_bbm_event_posts_columns',       'bbm_event_columns' );
add_action( 'manage_bbm_event_posts_custom_column', 'bbm_event_column_data', 10, 2 );
add_filter( 'manage_edit-bbm_event_sortable_columns', 'bbm_event_sortable_columns' );

function bbm_event_columns( array $cols ): array {
    $new = [];
    foreach ( $cols as $k => $v ) {
        $new[ $k ] = $v;
        if ( 'title' === $k ) {
            $new['event_date']     = __( 'Tarih', 'bitebimuv' );
            $new['event_location'] = __( 'Konum', 'bitebimuv' );
            $new['event_capacity'] = __( 'Kapasite', 'bitebimuv' );
            $new['event_type']     = __( 'Tür', 'bitebimuv' );
        }
    }
    unset( $new['date'] );
    $new['date'] = __( 'Eklenme', 'bitebimuv' );
    return $new;
}

function bbm_event_column_data( string $col, int $post_id ) {
    switch ( $col ) {
        case 'event_date':
            $d = get_post_meta( $post_id, '_bbm_event_date', true );
            echo $d ? '<strong>' . esc_html( date_i18n( 'j M Y', strtotime( $d ) ) ) . '</strong>' : '—';
            $time = get_post_meta( $post_id, '_bbm_event_time', true );
            if ( $time ) echo '<br><small style="color:#888">' . esc_html( $time ) . '</small>';
            break;
        case 'event_location':
            $loc = get_post_meta( $post_id, '_bbm_event_location', true );
            $type = get_post_meta( $post_id, '_bbm_event_type', true );
            if ( $type === 'online' ) {
                echo '<span style="color:#6366f1">🖥 Online</span>';
            } else {
                echo esc_html( $loc ?: '—' );
            }
            break;
        case 'event_capacity':
            $cap = get_post_meta( $post_id, '_bbm_event_capacity', true );
            echo $cap ? esc_html( $cap ) . ' kişi' : '<span style="color:#888">Sınırsız</span>';
            break;
        case 'event_type':
            $type = get_post_meta( $post_id, '_bbm_event_type', true );
            $map  = [ 'online' => '🖥 Online', 'in-person' => '📍 Yüz yüze', 'hybrid' => '🔀 Hibrit' ];
            echo esc_html( $map[ $type ] ?? $type ?: '—' );
            break;
    }
}

function bbm_event_sortable_columns( array $cols ): array {
    $cols['event_date'] = 'event_date';
    return $cols;
}

// ── Member columns ────────────────────────────────────────────────────────────
add_filter( 'manage_bbm_member_posts_columns',       'bbm_member_columns' );
add_action( 'manage_bbm_member_posts_custom_column', 'bbm_member_column_data', 10, 2 );

function bbm_member_columns( array $cols ): array {
    $new = [ 'cb' => $cols['cb'], 'thumbnail' => __( 'Fotoğraf', 'bitebimuv' ) ];
    foreach ( $cols as $k => $v ) {
        if ( $k === 'cb' ) continue;
        $new[ $k ] = $v;
        if ( 'title' === $k ) {
            $new['member_role']  = __( 'Unvan', 'bitebimuv' );
            $new['member_email'] = __( 'E-posta', 'bitebimuv' );
            $new['member_city']  = __( 'Şehir', 'bitebimuv' );
        }
    }
    return $new;
}

function bbm_member_column_data( string $col, int $post_id ) {
    switch ( $col ) {
        case 'thumbnail':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, [ 40, 40 ], [ 'style' => 'border-radius:50%;width:40px;height:40px;object-fit:cover;' ] );
            } else {
                echo '<div style="width:40px;height:40px;border-radius:50%;background:#e5e7eb;display:flex;align-items:center;justify-content:center;font-size:18px">👤</div>';
            }
            break;
        case 'member_role':
            $role = get_post_meta( $post_id, '_bbm_member_role', true );
            echo $role ? '<em>' . esc_html( $role ) . '</em>' : '—';
            break;
        case 'member_email':
            $email = get_post_meta( $post_id, '_bbm_member_email', true );
            echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '—';
            break;
        case 'member_city':
            echo esc_html( get_post_meta( $post_id, '_bbm_member_city', true ) ?: '—' );
            break;
    }
}

// ── Volunteer columns ─────────────────────────────────────────────────────────
add_filter( 'manage_bbm_volunteer_posts_columns',       'bbm_volunteer_columns' );
add_action( 'manage_bbm_volunteer_posts_custom_column', 'bbm_volunteer_column_data', 10, 2 );

function bbm_volunteer_columns( array $cols ): array {
    $new = [ 'cb' => $cols['cb'] ];
    foreach ( $cols as $k => $v ) {
        if ( $k === 'cb' ) continue;
        $new[ $k ] = $v;
        if ( 'title' === $k ) {
            $new['volunteer_email']  = __( 'E-posta', 'bitebimuv' );
            $new['volunteer_skills'] = __( 'Beceriler', 'bitebimuv' );
            $new['volunteer_status'] = __( 'Durum', 'bitebimuv' );
        }
    }
    return $new;
}

function bbm_volunteer_column_data( string $col, int $post_id ) {
    switch ( $col ) {
        case 'volunteer_email':
            $e = get_post_meta( $post_id, '_bbm_volunteer_email', true );
            echo $e ? '<a href="mailto:' . esc_attr( $e ) . '">' . esc_html( $e ) . '</a>' : '—';
            break;
        case 'volunteer_skills':
            $s = get_post_meta( $post_id, '_bbm_volunteer_skills', true );
            if ( $s ) {
                $skills = array_map( 'trim', explode( ',', $s ) );
                foreach ( array_slice( $skills, 0, 3 ) as $skill ) {
                    echo '<span style="background:#ede9fe;color:#5b21b6;border-radius:4px;padding:1px 6px;font-size:11px;margin-right:3px">' . esc_html( $skill ) . '</span>';
                }
            } else {
                echo '—';
            }
            break;
        case 'volunteer_status':
            $status = get_post_meta( $post_id, '_bbm_volunteer_status', true );
            $badges = [
                'active'   => [ 'Aktif',     '#d1fae5', '#065f46' ],
                'inactive' => [ 'Pasif',      '#fee2e2', '#991b1b' ],
                'pending'  => [ 'Beklemede',  '#fef3c7', '#92400e' ],
            ];
            $b = $badges[ $status ] ?? [ $status, '#f3f4f6', '#374151' ];
            echo '<span style="background:' . esc_attr( $b[1] ) . ';color:' . esc_attr( $b[2] ) . ';border-radius:4px;padding:2px 8px;font-size:11px;font-weight:600">' . esc_html( $b[0] ) . '</span>';
            break;
    }
}

// ── Sponsor columns ────────────────────────────────────────────────────────────
add_filter( 'manage_bbm_sponsor_posts_columns',       'bbm_sponsor_columns' );
add_action( 'manage_bbm_sponsor_posts_custom_column', 'bbm_sponsor_column_data', 10, 2 );

function bbm_sponsor_columns( array $cols ): array {
    $new = [ 'cb' => $cols['cb'], 'sponsor_logo' => __( 'Logo', 'bitebimuv' ) ];
    foreach ( $cols as $k => $v ) {
        if ( $k === 'cb' ) continue;
        $new[ $k ] = $v;
        if ( 'title' === $k ) {
            $new['sponsor_level']   = __( 'Seviye', 'bitebimuv' );
            $new['sponsor_website'] = __( 'Website', 'bitebimuv' );
            $new['sponsor_until']   = __( 'Bitiş', 'bitebimuv' );
        }
    }
    return $new;
}

function bbm_sponsor_column_data( string $col, int $post_id ) {
    switch ( $col ) {
        case 'sponsor_logo':
            if ( has_post_thumbnail( $post_id ) ) {
                echo get_the_post_thumbnail( $post_id, [ 60, 40 ], [ 'style' => 'object-fit:contain;max-height:40px;max-width:60px;' ] );
            }
            break;
        case 'sponsor_level':
            $level = get_post_meta( $post_id, '_bbm_sponsor_level', true );
            $map   = [ 'platinum' => [ 'Platin', '#e5e7eb', '#374151' ], 'gold' => [ 'Altın', '#fef3c7', '#92400e' ], 'silver' => [ 'Gümüş', '#f3f4f6', '#4b5563' ], 'bronze' => [ 'Bronz', '#fed7aa', '#7c2d12' ], 'supporter' => [ 'Destekçi', '#dbeafe', '#1e40af' ] ];
            $b = $map[ $level ] ?? [ $level, '#f3f4f6', '#374151' ];
            echo '<span style="background:' . esc_attr( $b[1] ) . ';color:' . esc_attr( $b[2] ) . ';border-radius:4px;padding:2px 8px;font-size:11px;font-weight:600">' . esc_html( $b[0] ) . '</span>';
            break;
        case 'sponsor_website':
            $url = get_post_meta( $post_id, '_bbm_sponsor_website', true );
            if ( $url ) {
                $domain = parse_url( $url, PHP_URL_HOST );
                echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $domain ) . ' ↗</a>';
            } else {
                echo '—';
            }
            break;
        case 'sponsor_until':
            $until = get_post_meta( $post_id, '_bbm_sponsor_until', true );
            if ( $until ) {
                $expired = strtotime( $until ) < time();
                $color   = $expired ? '#ef4444' : '#10b981';
                echo '<span style="color:' . $color . '">' . esc_html( date_i18n( 'j M Y', strtotime( $until ) ) ) . '</span>';
                if ( $expired ) echo '<br><small style="color:#ef4444">⚠ Süresi Geçmiş</small>';
            } else {
                echo '—';
            }
            break;
    }
}

// ── Document columns ──────────────────────────────────────────────────────────
add_filter( 'manage_bbm_document_posts_columns',       'bbm_document_columns' );
add_action( 'manage_bbm_document_posts_custom_column', 'bbm_document_column_data', 10, 2 );

function bbm_document_columns( array $cols ): array {
    $new = [];
    foreach ( $cols as $k => $v ) {
        $new[ $k ] = $v;
        if ( 'title' === $k ) {
            $new['doc_year']      = __( 'Yıl', 'bitebimuv' );
            $new['doc_size']      = __( 'Boyut', 'bitebimuv' );
            $new['doc_downloads'] = __( 'İndirme', 'bitebimuv' );
        }
    }
    return $new;
}

function bbm_document_column_data( string $col, int $post_id ) {
    switch ( $col ) {
        case 'doc_year':
            echo esc_html( get_post_meta( $post_id, '_bbm_document_year', true ) ?: '—' );
            break;
        case 'doc_size':
            echo esc_html( get_post_meta( $post_id, '_bbm_document_file_size', true ) ?: '—' );
            break;
        case 'doc_downloads':
            $count = (int) get_post_meta( $post_id, '_bbm_document_download_count', true );
            echo '<strong>' . $count . '</strong>';
            break;
    }
}
