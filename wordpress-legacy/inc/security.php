<?php
/**
 * Security – Rate Limiting, Honeypot, CSP Headers, Input Sanitization
 * Bite Bi Muv Derneği Teması v4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'send_headers', 'bbm_security_headers' );

function bbm_security_headers() {
    if ( is_admin() ) return;

    $csp = implode( '; ', [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com https://www.googletagmanager.com https://www.google-analytics.com",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://unpkg.com",
        "font-src 'self' https://fonts.gstatic.com data:",
        "img-src 'self' data: https: blob:",
        "connect-src 'self' https://www.google-analytics.com",
        "frame-src 'self' https://www.youtube.com https://player.vimeo.com https://www.google.com https://www.openstreetmap.org",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self'",
        "upgrade-insecure-requests",
    ] );

    header( 'Content-Security-Policy: ' . $csp );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'X-XSS-Protection: 1; mode=block' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    header( 'Permissions-Policy: camera=(), microphone=(), geolocation=(self), payment=()' );
}

/**
 * Rate limiting via transients (IP-based)
 * Returns false if rate-limited (too many requests).
 */
function bbm_check_rate_limit( string $action, int $max = 5, int $window = 60 ): bool {
    $ip  = bbm_get_client_ip();
    $key = 'bbm_rl_' . md5( $action . $ip );
    $count = (int) get_transient( $key );
    if ( $count >= $max ) return false;
    set_transient( $key, $count + 1, $window );
    return true;
}

function bbm_get_client_ip(): string {
    foreach ( [ 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR' ] as $h ) {
        if ( ! empty( $_SERVER[ $h ] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER[ $h ] ) );
            if ( strpos( $ip, ',' ) !== false ) $ip = trim( explode( ',', $ip )[0] );
            if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) return $ip;
        }
    }
    return '0.0.0.0';
}

/**
 * Honeypot field HTML (hidden from humans, bots fill it)
 */
function bbm_honeypot_field(): string {
    $name = 'bbm_' . md5( 'hp_' . get_site_url() . gmdate( 'Ymd' ) );
    return '<div class="bbm-hp-field" aria-hidden="true" style="opacity:0;position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;">
        <label for="' . esc_attr( $name ) . '">' . esc_html__( 'Bu alanı boş bırakın', 'bitebimuv' ) . '</label>
        <input type="text" name="' . esc_attr( $name ) . '" id="' . esc_attr( $name ) . '" value="" tabindex="-1" autocomplete="nope">
    </div>';
}

function bbm_is_honeypot_triggered(): bool {
    $name = 'bbm_' . md5( 'hp_' . get_site_url() . gmdate( 'Ymd' ) );
    return ! empty( $_POST[ $name ] );
}

/**
 * Sanitize form submission array
 */
function bbm_sanitize_form_data( array $raw ): array {
    $clean = [];
    $textarea_keys = [ 'message', 'motivation', 'bio', 'notes' ];
    $email_keys    = [ 'email' ];
    $url_keys      = [ 'website', 'url', 'linkedin' ];

    foreach ( $raw as $k => $v ) {
        $k = sanitize_key( $k );
        if ( is_array( $v ) ) {
            $clean[ $k ] = array_map( 'sanitize_text_field', $v );
        } elseif ( in_array( $k, $textarea_keys, true ) ) {
            $clean[ $k ] = sanitize_textarea_field( wp_unslash( $v ) );
        } elseif ( in_array( $k, $email_keys, true ) ) {
            $clean[ $k ] = sanitize_email( wp_unslash( $v ) );
        } elseif ( in_array( $k, $url_keys, true ) ) {
            $clean[ $k ] = esc_url_raw( wp_unslash( $v ) );
        } else {
            $clean[ $k ] = sanitize_text_field( wp_unslash( $v ) );
        }
    }
    return $clean;
}

// Block known bad crawlers
add_action( 'init', function() {
    if ( is_admin() ) return;
    $bad = [ 'AhrefsBot', 'MJ12bot', 'DotBot', 'SEMrushBot', 'petalbot', 'Bytespider', 'GPTBot' ];
    $ua  = $_SERVER['HTTP_USER_AGENT'] ?? '';
    foreach ( $bad as $bot ) {
        if ( stripos( $ua, $bot ) !== false ) {
            status_header( 403 );
            exit( 'Forbidden' );
        }
    }
}, 1 );

// Prevent user enumeration via ?author=N
add_action( 'template_redirect', function() {
    if ( ! is_admin() && is_author() && get_query_var( 'author' ) ) {
        wp_safe_redirect( home_url( '/' ), 301 );
        exit;
    }
} );

// Obfuscate login error messages
add_filter( 'login_errors', fn() => __( 'Giriş bilgileri hatalı. Lütfen tekrar deneyin.', 'bitebimuv' ) );

// Disable REST API user listing for unauthenticated users
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( ! is_user_logged_in() ) {
        unset( $endpoints['/wp/v2/users'] );
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
} );
