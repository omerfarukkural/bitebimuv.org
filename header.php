<?php
/**
 * Site Başlığı - v3.0 Şaheser Sürüm
 *
 * @package bitebimuv-dernek
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="<?php echo esc_attr( get_theme_mod( 'bbm_primary_color', '#6366f1' ) ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="bbm-page" class="bbm-site-wrapper">

<!-- Scroll progress -->
<div class="bbm-scroll-progress" id="bbm-scroll-progress" aria-hidden="true"></div>

<!-- Skip link -->
<a class="screen-reader-text" href="#bbm-main-content"><?php esc_html_e( 'İçeriğe geç', 'bitebimuv-dernek' ); ?></a>

<?php
// Topbar duyuru çubuğu
$topbar_enabled = get_theme_mod( 'bbm_topbar_enabled', true );
$topbar_text    = get_theme_mod( 'bbm_topbar_text', '' );
$topbar_url     = get_theme_mod( 'bbm_topbar_url', '' );
if ( $topbar_enabled && $topbar_text ) :
?>
<div class="bbm-topbar" id="bbm-topbar" role="complementary" aria-label="<?php esc_attr_e( 'Duyuru çubuğu', 'bitebimuv-dernek' ); ?>">
    <div class="bbm-container">
        <div class="bbm-topbar__inner">
            <div class="bbm-topbar__contact">
                <?php $phone = get_theme_mod( 'bbm_contact_phone', '' ); if ( $phone ) : ?>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="bbm-topbar__item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <?php echo esc_html( $phone ); ?>
                </a>
                <?php endif; ?>
                <?php $email = get_theme_mod( 'bbm_contact_email', '' ); if ( $email ) : ?>
                <a href="mailto:<?php echo esc_attr( $email ); ?>" class="bbm-topbar__item">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <?php echo esc_html( $email ); ?>
                </a>
                <?php endif; ?>
            </div>
            <div class="bbm-topbar__ticker" aria-live="polite">
                <?php if ( $topbar_url ) : ?>
                <a href="<?php echo esc_url( $topbar_url ); ?>" class="bbm-topbar__ticker-text"><?php echo esc_html( $topbar_text ); ?></a>
                <?php else : ?>
                <span class="bbm-topbar__ticker-text"><?php echo esc_html( $topbar_text ); ?></span>
                <?php endif; ?>
            </div>
            <div class="bbm-topbar__social">
                <?php
                $social_links = bbm_get_social_links();
                $social_topbar = array_slice( $social_links, 0, 4 );
                foreach ( $social_topbar as $link ) :
                    if ( empty( $link['url'] ) ) continue;
                ?>
                <a href="<?php echo esc_url( $link['url'] ); ?>" class="bbm-topbar__social-link"
                   aria-label="<?php echo esc_attr( $link['label'] ); ?>"
                   target="_blank" rel="noopener noreferrer">
                    <?php echo $link['icon']; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<header id="bbm-header" class="bbm-header" role="banner">
    <div class="bbm-header__inner bbm-container">

        <!-- Logo / Site başlığı -->
        <div class="bbm-header__brand">
            <?php if ( has_custom_logo() ) :
                the_custom_logo();
            else : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bbm-header__logo-link" rel="home">
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

        <!-- Ana navigasyon -->
        <nav id="bbm-nav" class="bbm-nav" role="navigation" aria-label="<?php esc_attr_e( 'Ana Navigasyon', 'bitebimuv-dernek' ); ?>">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'menu_id'        => 'bbm-primary-menu',
                'menu_class'     => 'bbm-nav__menu',
                'container'      => false,
                'fallback_cb'    => 'bbm_nav_fallback',
                'walker'         => class_exists( 'BBM_Nav_Walker' ) ? new BBM_Nav_Walker() : null,
            ] );
            ?>
        </nav>

        <!-- Sağ eylemler: arama + dark mode + CTA + hamburger -->
        <div class="bbm-header__actions">

            <!-- Arama butonu -->
            <button class="bbm-header__icon-btn" aria-label="<?php esc_attr_e( 'Aramayı aç', 'bitebimuv-dernek' ); ?>"
                    id="bbm-search-toggle" title="Ctrl+K">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20" height="20" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.35-4.35"/>
                </svg>
            </button>

            <!-- Dark mode toggle -->
            <button class="bbm-header__icon-btn bbm-darkmode-toggle" id="bbm-darkmode-toggle"
                    aria-label="<?php esc_attr_e( 'Karanlık moda geç', 'bitebimuv-dernek' ); ?>"
                    title="<?php esc_attr_e( 'Tema değiştir', 'bitebimuv-dernek' ); ?>">
                <svg class="bbm-dm-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" aria-hidden="true">
                    <circle cx="12" cy="12" r="5"/>
                    <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                </svg>
                <svg class="bbm-dm-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            <!-- Üye ol CTA -->
            <a href="<?php echo esc_url( get_theme_mod( 'bbm_membership_page_url', '#uye-ol' ) ); ?>"
               class="bbm-btn bbm-btn--primary bbm-btn--sm bbm-header__cta">
                <?php echo esc_html( get_theme_mod( 'bbm_hero_cta_text', __( 'Üye Ol', 'bitebimuv-dernek' ) ) ); ?>
            </a>

            <!-- Mobil hamburger -->
            <button class="bbm-hamburger" id="bbm-hamburger"
                    aria-label="<?php esc_attr_e( 'Menüyü aç/kapat', 'bitebimuv-dernek' ); ?>"
                    aria-expanded="false" aria-controls="bbm-primary-menu">
                <span class="bbm-hamburger__line"></span>
                <span class="bbm-hamburger__line"></span>
                <span class="bbm-hamburger__line"></span>
            </button>
        </div>

    </div><!-- .bbm-header__inner -->

    <!-- Gelişmiş canlı arama paneli -->
    <div class="bbm-search-panel" id="bbm-search-panel" hidden aria-modal="true" role="dialog"
         aria-label="<?php esc_attr_e( 'Arama', 'bitebimuv-dernek' ); ?>">
        <div class="bbm-search-panel__backdrop" id="bbm-search-backdrop"></div>
        <div class="bbm-search-panel__inner">
            <div class="bbm-search-panel__box">
                <div class="bbm-search-panel__field">
                    <svg class="bbm-search-panel__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="search" id="bbm-search-input" class="bbm-search-input"
                           placeholder="<?php esc_attr_e( 'Etkinlik, haber, proje ara...', 'bitebimuv-dernek' ); ?>"
                           autocomplete="off" autofocus aria-autocomplete="list"
                           aria-controls="bbm-search-results" aria-label="<?php esc_attr_e( 'Arama terimi', 'bitebimuv-dernek' ); ?>">
                    <div class="bbm-search-panel__kbd" aria-hidden="true">ESC</div>
                </div>
                <div id="bbm-search-results" class="bbm-search-results" role="listbox" aria-label="Arama sonuçları"></div>
                <div class="bbm-search-panel__footer">
                    <span class="bbm-search-panel__hint">
                        <kbd>↑</kbd><kbd>↓</kbd> gezin &nbsp; <kbd>↵</kbd> aç &nbsp; <kbd>ESC</kbd> kapat
                    </span>
                    <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>" class="bbm-search-panel__all" id="bbm-search-all-link">
                        <?php esc_html_e( 'Tüm sonuçlar', 'bitebimuv-dernek' ); ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

</header><!-- #bbm-header -->

<main id="bbm-main-content" class="bbm-main" tabindex="-1">
