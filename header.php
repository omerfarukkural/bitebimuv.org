<?php
/**
 * Site Başlığı
 *
 * @package bitebimuv-dernek
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="bbm-page" class="bbm-site-wrapper">

<!-- Scroll to top indicator -->
<div class="bbm-scroll-progress" id="bbm-scroll-progress" aria-hidden="true"></div>

<!-- Skip to content -->
<a class="screen-reader-text" href="#bbm-main-content"><?php _e( 'İçeriğe geç', 'bitebimuv-dernek' ); ?></a>

<header id="bbm-header" class="bbm-header" role="banner">
    <div class="bbm-header__inner container">

        <!-- Logo / Site başlığı -->
        <div class="bbm-header__brand">
            <?php if ( has_custom_logo() ) :
                the_custom_logo();
            else : ?>
            <a href="<?php echo home_url( '/' ); ?>" class="bbm-header__logo-link" rel="home">
                <div class="bbm-header__logo-smiley">
                    <?php echo bbm_get_smiley( 'small', 'bbm-header-smiley' ); ?>
                </div>
                <div class="bbm-header__logo-text">
                    <span class="bbm-header__site-name"><?php bloginfo( 'name' ); ?></span>
                    <span class="bbm-header__tagline"><?php bloginfo( 'description' ); ?></span>
                </div>
            </a>
            <?php endif; ?>
        </div>

        <!-- Navigasyon -->
        <nav id="bbm-nav" class="bbm-nav" role="navigation" aria-label="<?php esc_attr_e( 'Ana Navigasyon', 'bitebimuv-dernek' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location'  => 'primary',
                'menu_id'         => 'bbm-primary-menu',
                'menu_class'      => 'bbm-nav__menu',
                'container'       => false,
                'fallback_cb'     => 'bbm_nav_fallback',
            ] );
            ?>
        </nav>

        <!-- Sağ alan: arama + CTA -->
        <div class="bbm-header__actions">
            <button class="bbm-header__search-toggle" aria-label="<?php esc_attr_e( 'Arama', 'bitebimuv-dernek' ); ?>" id="bbm-search-toggle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20" height="20"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            </button>
            <a href="#uye-ol" class="bbm-btn bbm-btn--primary bbm-btn--sm">
                <?php echo esc_html( get_theme_mod( 'bbm_hero_cta_text', __( 'Üye Ol', 'bitebimuv-dernek' ) ) ); ?>
            </a>
            <!-- Mobil hamburger -->
            <button class="bbm-hamburger" id="bbm-hamburger" aria-label="<?php esc_attr_e( 'Menüyü aç/kapat', 'bitebimuv-dernek' ); ?>" aria-expanded="false" aria-controls="bbm-primary-menu">
                <span class="bbm-hamburger__line"></span>
                <span class="bbm-hamburger__line"></span>
                <span class="bbm-hamburger__line"></span>
            </button>
        </div>

    </div><!-- .bbm-header__inner -->

    <!-- Arama paneli -->
    <div class="bbm-search-panel" id="bbm-search-panel" hidden>
        <div class="container">
            <form role="search" method="get" action="<?php echo home_url( '/' ); ?>" class="bbm-search-form">
                <input type="search" name="s" value="<?php echo get_search_query(); ?>"
                    placeholder="<?php esc_attr_e( 'Anahtar kelime...', 'bitebimuv-dernek' ); ?>"
                    class="bbm-search-input" autocomplete="off" autofocus>
                <button type="submit" class="bbm-btn bbm-btn--primary"><?php _e( 'Ara', 'bitebimuv-dernek' ); ?></button>
                <button type="button" class="bbm-search-close" id="bbm-search-close" aria-label="Kapat">&times;</button>
            </form>
        </div>
    </div>

</header><!-- #bbm-header -->

<main id="bbm-main-content" class="bbm-main" tabindex="-1">
