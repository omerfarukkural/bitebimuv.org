<?php
/**
 * Tema Özelleştirici Ayarları
 *
 * @package bitebimuv-dernek
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function bbm_customize_register( $wp_customize ) {

    // ===========================================
    // BÖLÜM: Genel Ayarlar
    // ===========================================
    $wp_customize->add_section( 'bbm_general', [
        'title'    => __( 'BiteBiMuv - Genel Ayarlar', 'bitebimuv-dernek' ),
        'priority' => 30,
    ] );

    // Slogan
    $wp_customize->add_setting( 'bbm_tagline', [
        'default'           => 'Birlikte daha güçlü, birlikte daha mutlu!',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'bbm_tagline', [
        'label'   => __( 'Ana Slogan', 'bitebimuv-dernek' ),
        'section' => 'bbm_general',
        'type'    => 'text',
    ] );

    // Hero Başlık
    $wp_customize->add_setting( 'bbm_hero_title', [
        'default'           => 'BiteBiMuv Derneğine Hoş Geldiniz',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'bbm_hero_title', [
        'label'   => __( 'Hero Başlık', 'bitebimuv-dernek' ),
        'section' => 'bbm_general',
        'type'    => 'text',
    ] );

    // Hero Alt Başlık
    $wp_customize->add_setting( 'bbm_hero_subtitle', [
        'default'           => 'Gönüllülerimizle toplumumuza değer katıyoruz.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'bbm_hero_subtitle', [
        'label'   => __( 'Hero Alt Başlık', 'bitebimuv-dernek' ),
        'section' => 'bbm_general',
        'type'    => 'textarea',
    ] );

    // Hero CTA Butonu
    $wp_customize->add_setting( 'bbm_hero_cta_text', [
        'default'           => 'Üye Ol',
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'bbm_hero_cta_text', [
        'label'   => __( 'Hero Buton Metni', 'bitebimuv-dernek' ),
        'section' => 'bbm_general',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'bbm_hero_cta_url', [
        'default'           => '#uye-ol',
        'sanitize_callback' => 'esc_url_raw',
    ] );
    $wp_customize->add_control( 'bbm_hero_cta_url', [
        'label'   => __( 'Hero Buton URL', 'bitebimuv-dernek' ),
        'section' => 'bbm_general',
        'type'    => 'url',
    ] );

    // ===========================================
    // BÖLÜM: Renkler
    // ===========================================
    $wp_customize->add_section( 'bbm_colors', [
        'title'    => __( 'BiteBiMuv - Renkler', 'bitebimuv-dernek' ),
        'priority' => 40,
    ] );

    $color_settings = [
        'bbm_color_primary'   => [ '#E8435A', 'Ana Renk (Kırmızı)' ],
        'bbm_color_secondary' => [ '#2D3561', 'İkincil Renk (Lacivert)' ],
        'bbm_color_accent'    => [ '#FFD93D', 'Vurgu Rengi (Sarı)' ],
        'bbm_color_dark'      => [ '#1A1A2E', 'Koyu Renk' ],
    ];

    foreach ( $color_settings as $id => [ $default, $label ] ) {
        $wp_customize->add_setting( $id, [
            'default'           => $default,
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ] );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, [
            'label'   => __( $label, 'bitebimuv-dernek' ),
            'section' => 'bbm_colors',
        ] ) );
    }

    // ===========================================
    // BÖLÜM: İstatistikler
    // ===========================================
    $wp_customize->add_section( 'bbm_stats', [
        'title'    => __( 'BiteBiMuv - İstatistikler', 'bitebimuv-dernek' ),
        'priority' => 50,
    ] );

    $stats = [
        [ 'bbm_stat1_number', 'bbm_stat1_label', '500+', 'Üyemiz' ],
        [ 'bbm_stat2_number', 'bbm_stat2_label', '120+', 'Etkinlik' ],
        [ 'bbm_stat3_number', 'bbm_stat3_label', '8',    'Yıllık Deneyim' ],
        [ 'bbm_stat4_number', 'bbm_stat4_label', '50+',  'Tamamlanan Proje' ],
    ];

    foreach ( $stats as [ $num_id, $label_id, $num_default, $label_default ] ) {
        $wp_customize->add_setting( $num_id, [ 'default' => $num_default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ] );
        $wp_customize->add_control( $num_id, [ 'label' => __( 'Rakam', 'bitebimuv-dernek' ), 'section' => 'bbm_stats', 'type' => 'text' ] );
        $wp_customize->add_setting( $label_id, [ 'default' => $label_default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ] );
        $wp_customize->add_control( $label_id, [ 'label' => __( 'Etiket', 'bitebimuv-dernek' ), 'section' => 'bbm_stats', 'type' => 'text' ] );
    }

    // ===========================================
    // BÖLÜM: İletişim Bilgileri
    // ===========================================
    $wp_customize->add_section( 'bbm_contact_info', [
        'title'    => __( 'BiteBiMuv - İletişim Bilgileri', 'bitebimuv-dernek' ),
        'priority' => 60,
    ] );

    $contact_fields = [
        'bbm_address'    => [ 'Adres', 'İstanbul, Türkiye' ],
        'bbm_phone'      => [ 'Telefon', '+90 (212) 000 00 00' ],
        'bbm_email'      => [ 'E-posta', 'info@bitebimuv.org' ],
        'bbm_maps_url'   => [ 'Google Maps URL', '' ],
    ];

    foreach ( $contact_fields as $id => [ $label, $default ] ) {
        $wp_customize->add_setting( $id, [ 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $id, [ 'label' => __( $label, 'bitebimuv-dernek' ), 'section' => 'bbm_contact_info', 'type' => 'text' ] );
    }

    // ===========================================
    // BÖLÜM: Sosyal Medya
    // ===========================================
    $wp_customize->add_section( 'bbm_social', [
        'title'    => __( 'BiteBiMuv - Sosyal Medya', 'bitebimuv-dernek' ),
        'priority' => 70,
    ] );

    $social_fields = [
        'bbm_facebook'  => 'Facebook URL',
        'bbm_instagram' => 'Instagram URL',
        'bbm_twitter'   => 'X (Twitter) URL',
        'bbm_youtube'   => 'YouTube URL',
        'bbm_linkedin'  => 'LinkedIn URL',
        'bbm_whatsapp'  => 'WhatsApp Numarası',
    ];

    foreach ( $social_fields as $id => $label ) {
        $wp_customize->add_setting( $id, [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( $id, [ 'label' => __( $label, 'bitebimuv-dernek' ), 'section' => 'bbm_social', 'type' => 'url' ] );
    }

    // ===========================================
    // BÖLÜM: Hakkımızda Metni
    // ===========================================
    $wp_customize->add_section( 'bbm_about', [
        'title'    => __( 'BiteBiMuv - Hakkımızda', 'bitebimuv-dernek' ),
        'priority' => 45,
    ] );

    $wp_customize->add_setting( 'bbm_about_title', [
        'default'           => 'Hakkımızda',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'bbm_about_title', [
        'label'   => __( 'Hakkımızda Başlık', 'bitebimuv-dernek' ),
        'section' => 'bbm_about',
        'type'    => 'text',
    ] );

    $wp_customize->add_setting( 'bbm_about_text', [
        'default'           => 'BiteBiMuv Derneği olarak, toplumsal dayanışmayı güçlendirmek, kültürel etkinlikler düzenlemek ve üyelerimizin sosyal gelişimini desteklemek amacıyla 2016 yılından bu yana çalışmaktayız. Gönüllülük ruhuyla ve birlikte üretme anlayışıyla daha güzel bir toplum inşa ediyoruz.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'postMessage',
    ] );
    $wp_customize->add_control( 'bbm_about_text', [
        'label'   => __( 'Hakkımızda Metin', 'bitebimuv-dernek' ),
        'section' => 'bbm_about',
        'type'    => 'textarea',
    ] );

    // Postmessage ile anlık önizleme
    $wp_customize->selective_refresh->add_partial( 'bbm_hero_title', [
        'selector'        => '.bbm-hero-title',
        'render_callback' => fn() => get_theme_mod( 'bbm_hero_title' ),
    ] );
}
add_action( 'customize_register', 'bbm_customize_register' );

/**
 * Özelleştirici CSS'ini üret
 */
function bbm_customizer_css() {
    $primary   = get_theme_mod( 'bbm_color_primary',   '#E8435A' );
    $secondary = get_theme_mod( 'bbm_color_secondary', '#2D3561' );
    $accent    = get_theme_mod( 'bbm_color_accent',    '#FFD93D' );
    $dark      = get_theme_mod( 'bbm_color_dark',      '#1A1A2E' );
    ?>
    <style id="bbm-customizer-css">
        :root {
            --bbm-primary:   <?php echo sanitize_hex_color( $primary ); ?>;
            --bbm-secondary: <?php echo sanitize_hex_color( $secondary ); ?>;
            --bbm-accent:    <?php echo sanitize_hex_color( $accent ); ?>;
            --bbm-dark:      <?php echo sanitize_hex_color( $dark ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'bbm_customizer_css' );
