<?php
/**
 * BitebiMuv Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'BITEBIMUV_VERSION', '1.0.0' );
define( 'BITEBIMUV_DIR', get_template_directory() );
define( 'BITEBIMUV_URI', get_template_directory_uri() );

// Theme setup
function bitebimuv_setup() {
    load_theme_textdomain( 'bitebimuv', BITEBIMUV_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'responsive-embeds' );

    register_nav_menus( [
        'primary' => __( 'Ana Menü', 'bitebimuv' ),
        'footer'  => __( 'Alt Menü', 'bitebimuv' ),
    ] );
}
add_action( 'after_setup_theme', 'bitebimuv_setup' );

// Enqueue scripts & styles
function bitebimuv_scripts() {
    wp_enqueue_style( 'bitebimuv-style', get_stylesheet_uri(), [], BITEBIMUV_VERSION );
    wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', [], null );
}
add_action( 'wp_enqueue_scripts', 'bitebimuv_scripts' );

// Widget areas
function bitebimuv_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Kenar Çubuğu', 'bitebimuv' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
}
add_action( 'widgets_init', 'bitebimuv_widgets_init' );

// Custom excerpt length
function bitebimuv_excerpt_length( $length ) {
    return 25;
}
add_filter( 'excerpt_length', 'bitebimuv_excerpt_length' );
