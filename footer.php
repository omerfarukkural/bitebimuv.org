<?php
/**
 * Site Alt Bilgisi
 *
 * @package bitebimuv-dernek
 */
?>
</main><!-- #bbm-main-content -->

<footer id="bbm-footer" class="bbm-footer" role="contentinfo">

    <!-- Bültene Abone Ol şeridi -->
    <div class="bbm-newsletter-bar">
        <div class="container">
            <div class="bbm-newsletter-bar__inner">
                <div class="bbm-newsletter-bar__text">
                    <h3><?php _e( 'Haberdar Olun!', 'bitebimuv-dernek' ); ?></h3>
                    <p><?php _e( 'Etkinlik ve duyurulardan anında haberdar olmak için bültenimize abone olun.', 'bitebimuv-dernek' ); ?></p>
                </div>
                <form class="bbm-newsletter-form" id="bbm-newsletter-form">
                    <input type="email" name="email" placeholder="<?php esc_attr_e( 'E-posta adresiniz', 'bitebimuv-dernek' ); ?>" required class="bbm-newsletter-input">
                    <button type="submit" class="bbm-btn bbm-btn--accent">
                        <?php _e( 'Abone Ol', 'bitebimuv-dernek' ); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Ana footer alanı -->
    <div class="bbm-footer__main">
        <div class="container">
            <div class="bbm-footer__grid">

                <!-- Hakkımızda sütunu -->
                <div class="bbm-footer__col bbm-footer__col--brand">
                    <a href="<?php echo home_url(); ?>" class="bbm-footer__logo">
                        <?php echo bbm_get_smiley( 'small', 'bbm-footer-smiley' ); ?>
                        <span><?php bloginfo( 'name' ); ?></span>
                    </a>
                    <p class="bbm-footer__desc">
                        <?php echo esc_html( get_theme_mod( 'bbm_about_text', 'BiteBiMuv Derneği olarak toplumsal dayanışmayı güçlendirmek için çalışıyoruz.' ) ); ?>
                    </p>
                    <div class="bbm-footer__social">
                        <?php echo bbm_get_social_links(); ?>
                    </div>
                </div>

                <!-- Hızlı Linkler -->
                <div class="bbm-footer__col">
                    <h4 class="bbm-footer__col-title"><?php _e( 'Hızlı Linkler', 'bitebimuv-dernek' ); ?></h4>
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'menu_class'     => 'bbm-footer__menu',
                        'container'      => false,
                        'depth'          => 1,
                        'fallback_cb'    => false,
                    ] );
                    ?>
                </div>

                <!-- Widget Alanları -->
                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                <div class="bbm-footer__col">
                    <?php dynamic_sidebar( 'footer-2' ); ?>
                </div>
                <?php endif; ?>

                <!-- İletişim Bilgileri -->
                <div class="bbm-footer__col bbm-footer__col--contact">
                    <h4 class="bbm-footer__col-title"><?php _e( 'İletişim', 'bitebimuv-dernek' ); ?></h4>
                    <ul class="bbm-footer__contact-list">
                        <?php $address = get_theme_mod( 'bbm_address' ); if ( $address ) : ?>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <?php echo nl2br( esc_html( $address ) ); ?>
                        </li>
                        <?php endif; ?>
                        <?php $phone = get_theme_mod( 'bbm_phone' ); if ( $phone ) : ?>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.16 6.16l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php $email = get_theme_mod( 'bbm_email' ); if ( $email ) : ?>
                        <li>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div><!-- .bbm-footer__grid -->
        </div><!-- .container -->
    </div><!-- .bbm-footer__main -->

    <!-- Footer alt bar -->
    <div class="bbm-footer__bottom">
        <div class="container">
            <p class="bbm-footer__copyright">
                &copy; <?php echo date( 'Y' ); ?>
                <a href="<?php echo home_url(); ?>"><?php bloginfo( 'name' ); ?></a>.
                <?php _e( 'Tüm hakları saklıdır.', 'bitebimuv-dernek' ); ?>
                <?php if ( get_theme_mod( 'bbm_show_credits', true ) ) : ?>
                | <?php _e( 'WordPress ile güçlendirilmiştir.', 'bitebimuv-dernek' ); ?>
                <?php endif; ?>
            </p>
            <nav class="bbm-footer__bottom-nav" aria-label="<?php esc_attr_e( 'Alt navigasyon', 'bitebimuv-dernek' ); ?>">
                <a href="<?php echo get_privacy_policy_url(); ?>"><?php _e( 'Gizlilik', 'bitebimuv-dernek' ); ?></a>
                <a href="<?php echo home_url( '/iletisim' ); ?>"><?php _e( 'İletişim', 'bitebimuv-dernek' ); ?></a>
            </nav>
        </div>
    </div>

</footer><!-- #bbm-footer -->

<!-- Scroll to top butonu -->
<button id="bbm-scroll-top" class="bbm-scroll-top" aria-label="<?php esc_attr_e( 'Yukarı çık', 'bitebimuv-dernek' ); ?>" hidden>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="20" height="20"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<!-- Mini smiley (scroll ile görünür, köşede kalır) -->
<div id="bbm-floating-smiley" class="bbm-floating-smiley" hidden aria-hidden="true">
    <?php echo bbm_get_smiley( 'small', 'bbm-mini-smiley' ); ?>
</div>

</div><!-- #bbm-page -->

<?php wp_footer(); ?>
</body>
</html>
