<?php
/**
 * PWA – Progressive Web App Desteği
 * Bite Bi Muv Derneği Teması v4.0
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', 'bbm_pwa_head_tags', 4 );
add_action( 'wp_footer', 'bbm_register_service_worker', 5 );
add_action( 'init', 'bbm_pwa_rewrite_rules' );
add_filter( 'query_vars', 'bbm_pwa_query_vars' );
add_action( 'template_redirect', 'bbm_pwa_template_redirect' );

function bbm_pwa_head_tags() {
    $theme_color = get_theme_mod( 'bbm_primary_color', '#6366f1' );
    $site_name   = get_bloginfo( 'name' );
    $icons_uri   = get_template_directory_uri() . '/assets/images/icons';
    ?>
    <!-- PWA -->
    <link rel="manifest" href="<?php echo esc_url( home_url( '/bbm-manifest.json' ) ); ?>">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?php echo esc_attr( $site_name ); ?>">
    <meta name="application-name" content="<?php echo esc_attr( $site_name ); ?>">
    <meta name="msapplication-TileColor" content="<?php echo esc_attr( $theme_color ); ?>">
    <meta name="msapplication-config" content="<?php echo esc_url( $icons_uri . '/browserconfig.xml' ); ?>">

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $icons_uri . '/apple-touch-icon.png' ); ?>">
    <link rel="icon" type="image/png" sizes="32x32"  href="<?php echo esc_url( $icons_uri . '/favicon-32x32.png' ); ?>">
    <link rel="icon" type="image/png" sizes="16x16"  href="<?php echo esc_url( $icons_uri . '/favicon-16x16.png' ); ?>">
    <link rel="mask-icon" href="<?php echo esc_url( $icons_uri . '/safari-pinned-tab.svg' ); ?>" color="<?php echo esc_attr( $theme_color ); ?>">
    <?php
}

function bbm_register_service_worker() {
    $sw_url = get_template_directory_uri() . '/assets/js/sw.js';
    ?>
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('<?php echo esc_url( $sw_url ); ?>', { scope: '/' })
            .then(reg => console.log('[BBM SW] Registered:', reg.scope))
            .catch(err => console.warn('[BBM SW] Error:', err));
    }
    </script>
    <?php
}

function bbm_pwa_rewrite_rules() {
    add_rewrite_rule( 'bbm-manifest\.json$', 'index.php?bbm_manifest=1', 'top' );
    add_rewrite_rule( 'offline/?$', 'index.php?bbm_offline=1', 'top' );
}

function bbm_pwa_query_vars( array $vars ): array {
    $vars[] = 'bbm_manifest';
    $vars[] = 'bbm_offline';
    return $vars;
}

function bbm_pwa_template_redirect() {
    if ( get_query_var( 'bbm_manifest' ) ) {
        bbm_serve_manifest();
        exit;
    }
    if ( get_query_var( 'bbm_offline' ) ) {
        bbm_serve_offline_page();
    }
}

function bbm_serve_manifest() {
    $site_name   = get_bloginfo( 'name' );
    $site_desc   = get_bloginfo( 'description' );
    $theme_color = get_theme_mod( 'bbm_primary_color', '#6366f1' );
    $icons_uri   = get_template_directory_uri() . '/assets/images/icons';

    $manifest = [
        'name'             => $site_name,
        'short_name'       => mb_strimwidth( $site_name, 0, 12, '', 'UTF-8' ),
        'description'      => $site_desc,
        'start_url'        => '/',
        'display'          => 'standalone',
        'background_color' => '#ffffff',
        'theme_color'      => $theme_color,
        'orientation'      => 'portrait-primary',
        'lang'             => 'tr',
        'dir'              => 'ltr',
        'categories'       => [ 'social', 'community', 'nonprofit' ],
        'icons'            => [
            [ 'src' => $icons_uri . '/icon-72x72.png',   'sizes' => '72x72',   'type' => 'image/png' ],
            [ 'src' => $icons_uri . '/icon-96x96.png',   'sizes' => '96x96',   'type' => 'image/png' ],
            [ 'src' => $icons_uri . '/icon-128x128.png', 'sizes' => '128x128', 'type' => 'image/png' ],
            [ 'src' => $icons_uri . '/icon-144x144.png', 'sizes' => '144x144', 'type' => 'image/png' ],
            [ 'src' => $icons_uri . '/icon-152x152.png', 'sizes' => '152x152', 'type' => 'image/png' ],
            [ 'src' => $icons_uri . '/icon-192x192.png', 'sizes' => '192x192', 'type' => 'image/png', 'purpose' => 'any maskable' ],
            [ 'src' => $icons_uri . '/icon-384x384.png', 'sizes' => '384x384', 'type' => 'image/png' ],
            [ 'src' => $icons_uri . '/icon-512x512.png', 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'any maskable' ],
        ],
        'shortcuts' => [
            [
                'name'       => 'Etkinlikler',
                'short_name' => 'Etkinlik',
                'url'        => '/etkinlikler/',
                'icons'      => [ [ 'src' => $icons_uri . '/icon-96x96.png', 'sizes' => '96x96' ] ],
            ],
            [
                'name'       => 'Üyelik',
                'short_name' => 'Üyelik',
                'url'        => '/uyelik/',
                'icons'      => [ [ 'src' => $icons_uri . '/icon-96x96.png', 'sizes' => '96x96' ] ],
            ],
        ],
        'prefer_related_applications' => false,
    ];

    nocache_headers();
    header( 'Content-Type: application/manifest+json; charset=utf-8' );
    echo wp_json_encode( $manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    exit;
}

function bbm_serve_offline_page() {
    // Load the offline template
    $template = get_template_directory() . '/offline.php';
    if ( file_exists( $template ) ) {
        include $template;
        exit;
    }
}

// Flush rewrite rules on theme activation
add_action( 'after_switch_theme', function() {
    bbm_pwa_rewrite_rules();
    flush_rewrite_rules();
} );
