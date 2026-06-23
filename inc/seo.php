<?php
/**
 * SEO – OpenGraph, Twitter Card, Canonical URL, Meta Tags
 * Bite Bi Muv Derneği Teması v4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_head', 'bbm_seo_meta_tags', 1 );
add_action( 'wp_head', 'bbm_canonical_url',  2 );
add_action( 'wp_head', 'bbm_robots_meta',    3 );

function bbm_seo_meta_tags() {
    global $post;

    $site_name   = get_bloginfo( 'name' );
    $site_desc   = get_bloginfo( 'description' );
    $site_url    = get_site_url();
    $theme_color = get_theme_mod( 'bbm_primary_color', '#6366f1' );
    $org_logo    = get_theme_mod( 'bbm_logo_url', '' );

    $title     = $site_name;
    $desc      = $site_desc;
    $url       = $site_url . '/';
    $og_type   = 'website';
    $image     = $org_logo ?: get_template_directory_uri() . '/assets/images/og-default.jpg';
    $image_alt = $site_name;
    $published = $modified = $author = $section = '';

    if ( is_singular() && isset( $post ) ) {
        $og_type   = 'article';
        $title     = get_the_title( $post ) . ' — ' . $site_name;
        $desc      = bbm_get_meta_description( $post );
        $url       = get_permalink( $post );
        $published = get_the_date( 'c', $post );
        $modified  = get_the_modified_date( 'c', $post );
        $author    = get_the_author_meta( 'display_name', $post->post_author );

        if ( has_post_thumbnail( $post ) ) {
            $img = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'large' );
            if ( $img ) {
                $image     = $img[0];
                $image_alt = get_post_meta( get_post_thumbnail_id( $post ), '_wp_attachment_image_alt', true ) ?: $title;
            }
        }

        $section_map = [
            'bbm_event'   => 'Etkinlikler',
            'bbm_member'  => 'Üyeler',
            'bbm_project' => 'Projeler',
            'bbm_sponsor' => 'Destekçiler',
        ];
        $section = $section_map[ $post->post_type ] ?? '';
        if ( ! $section && 'post' === $post->post_type ) {
            $cats    = get_the_category( $post->ID );
            $section = $cats ? $cats[0]->name : 'Blog';
        }

    } elseif ( is_home() || is_front_page() ) {
        $title = $site_name . ' — ' . $site_desc;
        $desc  = $site_desc;
        $url   = $site_url . '/';

    } elseif ( is_archive() ) {
        $title = get_the_archive_title() . ' — ' . $site_name;
        $desc  = get_the_archive_description() ?: $site_desc;
        $url   = get_pagenum_link();

    } elseif ( is_search() ) {
        $title = sprintf( '"%s" Arama Sonuçları — %s', get_search_query(), $site_name );
        $desc  = $site_desc;
        $url   = get_search_link();

    } elseif ( is_404() ) {
        $title = 'Sayfa Bulunamadı — ' . $site_name;
        $desc  = $site_desc;
    }

    $desc        = mb_strimwidth( wp_strip_all_tags( preg_replace( '/\s+/', ' ', trim( $desc ) ) ), 0, 160, '…', 'UTF-8' );
    $title_clean = wp_strip_all_tags( $title );

    ?>
    <!-- BBM SEO -->
    <meta name="description" content="<?php echo esc_attr( $desc ); ?>">
    <meta name="theme-color" content="<?php echo esc_attr( $theme_color ); ?>">

    <!-- Open Graph -->
    <meta property="og:type"        content="<?php echo esc_attr( $og_type ); ?>">
    <meta property="og:site_name"   content="<?php echo esc_attr( $site_name ); ?>">
    <meta property="og:title"       content="<?php echo esc_attr( $title_clean ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $desc ); ?>">
    <meta property="og:url"         content="<?php echo esc_url( $url ); ?>">
    <meta property="og:image"       content="<?php echo esc_url( $image ); ?>">
    <meta property="og:image:alt"   content="<?php echo esc_attr( $image_alt ); ?>">
    <meta property="og:image:width"  content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale"      content="tr_TR">
    <?php if ( $published ) : ?>
    <meta property="article:published_time" content="<?php echo esc_attr( $published ); ?>">
    <meta property="article:modified_time"  content="<?php echo esc_attr( $modified ); ?>">
    <?php endif; ?>
    <?php if ( $author )  : ?><meta property="article:author"  content="<?php echo esc_attr( $author ); ?>"><?php endif; ?>
    <?php if ( $section ) : ?><meta property="article:section" content="<?php echo esc_attr( $section ); ?>"><?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?php echo esc_attr( $title_clean ); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr( $desc ); ?>">
    <meta name="twitter:image"       content="<?php echo esc_url( $image ); ?>">
    <meta name="twitter:image:alt"   content="<?php echo esc_attr( $image_alt ); ?>">
    <?php
    $tw = get_theme_mod( 'bbm_social_twitter', '' );
    if ( $tw ) : ?>
    <meta name="twitter:site"    content="@<?php echo esc_attr( ltrim( $tw, '@' ) ); ?>">
    <meta name="twitter:creator" content="@<?php echo esc_attr( ltrim( $tw, '@' ) ); ?>">
    <?php endif;

    $author_display = $author ?: $site_name;
    echo '<meta name="author" content="' . esc_attr( $author_display ) . '">' . "\n";
}

function bbm_canonical_url() {
    if ( is_singular() ) {
        echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
    } elseif ( is_home() || is_front_page() ) {
        echo '<link rel="canonical" href="' . esc_url( get_site_url() . '/' ) . '">' . "\n";
    } elseif ( is_archive() ) {
        $term = get_queried_object();
        if ( $term && isset( $term->term_id ) ) {
            echo '<link rel="canonical" href="' . esc_url( get_term_link( $term ) ) . '">' . "\n";
        }
    }
}

function bbm_robots_meta() {
    if ( is_search() || is_404() ) {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    } elseif ( is_paged() ) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    } else {
        echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
    }
}

function bbm_get_meta_description( WP_Post $post ): string {
    if ( $post->post_excerpt ) {
        return $post->post_excerpt;
    }
    if ( 'bbm_event' === $post->post_type ) {
        $date     = get_post_meta( $post->ID, '_bbm_event_date', true );
        $location = get_post_meta( $post->ID, '_bbm_event_location', true );
        $d = get_the_title( $post );
        if ( $date )     $d .= ' — ' . date_i18n( 'j F Y', strtotime( $date ) );
        if ( $location ) $d .= ', ' . $location;
        return $d;
    }
    if ( 'bbm_member' === $post->post_type ) {
        $role = get_post_meta( $post->ID, '_bbm_member_role', true );
        return get_the_title( $post ) . ( $role ? ' — ' . $role : '' ) . ' hakkında bilgi edinin.';
    }
    $content = strip_shortcodes( $post->post_content );
    return wp_strip_all_tags( do_blocks( $content ) );
}

// Sitemap hint on homepage
add_action( 'wp_head', function() {
    if ( is_front_page() ) {
        echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . esc_url( home_url( '/sitemap_index.xml' ) ) . '">' . "\n";
    }
}, 5 );

// Security: remove WP generator / RSD / wlwmanifest
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
add_filter( 'xmlrpc_enabled', '__return_false' );
