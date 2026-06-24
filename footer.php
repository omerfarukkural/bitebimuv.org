<?php
/**
 * Site Alt Bilgisi - v3.0 Şaheser Sürüm
 *
 * @package bitebimuv-dernek
 */
?>

</main><!-- #bbm-main-content -->

<?php
// Bülten bölümü - footer üstü
$newsletter_title = get_theme_mod( 'bbm_newsletter_title', __( 'Bültenimize Abone Olun', 'bitebimuv-dernek' ) );
?>
<section class="bbm-newsletter" aria-labelledby="bbm-newsletter-title">
    <div class="bbm-container">
        <div class="bbm-newsletter__inner">
            <div class="bbm-newsletter__content">
                <span class="bbm-section-badge"><?php echo bbm_get_smiley( 'small', 'bbm-newsletter-smiley' ); ?> <?php esc_html_e( 'Haberdar Olun', 'bitebimuv-dernek' ); ?></span>
                <h2 id="bbm-newsletter-title" class="bbm-newsletter__title"><?php echo esc_html( $newsletter_title ); ?></h2>
                <p class="bbm-newsletter__subtitle"><?php esc_html_e( 'Etkinlikler, duyurular ve projelerden ilk siz haberdar olun.', 'bitebimuv-dernek' ); ?></p>
            </div>
            <form class="bbm-newsletter__form" id="bbm-newsletter-form" novalidate>
                <?php wp_nonce_field( 'bbm_newsletter_nonce', 'bbm_newsletter_nonce_field' ); ?>
                <div class="bbm-newsletter__field">
                    <input type="email" name="email" id="bbm-newsletter-email"
                           class="bbm-form-control bbm-newsletter__input"
                           placeholder="<?php esc_attr_e( 'E-posta adresiniz', 'bitebimuv-dernek' ); ?>"
                           required autocomplete="email" aria-label="<?php esc_attr_e( 'E-posta adresi', 'bitebimuv-dernek' ); ?>">
                    <button type="submit" class="bbm-btn bbm-btn--primary bbm-newsletter__btn">
                        <span class="bbm-btn__text"><?php esc_html_e( 'Abone Ol', 'bitebimuv-dernek' ); ?></span>
                        <svg class="bbm-btn__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <p class="bbm-newsletter__privacy">
                    <?php printf(
                        esc_html__( 'Abone olarak %s\'ni kabul etmiş olursunuz.', 'bitebimuv-dernek' ),
                        '<a href="' . esc_url( get_privacy_policy_url() ) . '">' . esc_html__( 'Gizlilik Politikası', 'bitebimuv-dernek' ) . '</a>'
                    ); ?>
                </p>
                <div class="bbm-form-msg" id="bbm-newsletter-msg" role="alert" aria-live="polite"></div>
            </form>
        </div>
    </div>
</section>

<footer id="bbm-footer" class="bbm-footer" role="contentinfo">
    <div class="bbm-footer__main">
        <div class="bbm-container">
            <div class="bbm-footer__grid">

                <!-- Kolon 1: Dernek hakkında -->
                <div class="bbm-footer__col bbm-footer__col--about">
                    <div class="bbm-footer__brand">
                        <?php if ( has_custom_logo() ) :
                            the_custom_logo();
                        else : ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bbm-footer__logo-link">
                            <?php echo bbm_get_smiley( 'small', 'bbm-footer-smiley' ); ?>
                            <span class="bbm-footer__site-name"><?php bloginfo( 'name' ); ?></span>
                        </a>
                        <?php endif; ?>
                    </div>
                    <p class="bbm-footer__desc">
                        <?php echo esc_html( get_theme_mod( 'bbm_about_text', get_bloginfo( 'description' ) ) ); ?>
                    </p>
                    <!-- Sosyal medya -->
                    <div class="bbm-footer__social" aria-label="<?php esc_attr_e( 'Sosyal medya bağlantıları', 'bitebimuv-dernek' ); ?>">
                        <?php
                        $social_links = bbm_get_social_links();
                        foreach ( $social_links as $link ) :
                            if ( empty( $link['url'] ) ) continue;
                        ?>
                        <a href="<?php echo esc_url( $link['url'] ); ?>"
                           class="bbm-footer__social-link"
                           aria-label="<?php echo esc_attr( $link['label'] ); ?>"
                           target="_blank" rel="noopener noreferrer">
                            <?php echo $link['icon']; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Kolon 2: Hızlı bağlantılar -->
                <div class="bbm-footer__col">
                    <h3 class="bbm-footer__heading"><?php esc_html_e( 'Hızlı Bağlantılar', 'bitebimuv-dernek' ); ?></h3>
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

                <!-- Kolon 3: Son etkinlikler -->
                <div class="bbm-footer__col">
                    <h3 class="bbm-footer__heading"><?php esc_html_e( 'Yaklaşan Etkinlikler', 'bitebimuv-dernek' ); ?></h3>
                    <?php
                    $footer_events = new WP_Query( [
                        'post_type'      => 'bbm_event',
                        'posts_per_page' => 3,
                        'meta_key'       => '_bbm_event_date',
                        'orderby'        => 'meta_value',
                        'order'          => 'ASC',
                        'meta_query'     => [ [ 'key' => '_bbm_event_date', 'value' => date( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ] ],
                    ] );
                    if ( $footer_events->have_posts() ) :
                        echo '<ul class="bbm-footer__events">';
                        while ( $footer_events->have_posts() ) : $footer_events->the_post();
                            $ev_date = get_post_meta( get_the_ID(), '_bbm_event_date', true );
                            $ev_loc  = get_post_meta( get_the_ID(), '_bbm_event_location', true );
                    ?>
                        <li class="bbm-footer__event-item">
                            <a href="<?php the_permalink(); ?>" class="bbm-footer__event-link">
                                <span class="bbm-footer__event-date">
                                    <?php echo $ev_date ? esc_html( bbm_get_event_date_formatted( get_the_ID(), 'short' ) ) : ''; ?>
                                </span>
                                <span class="bbm-footer__event-name"><?php the_title(); ?></span>
                            </a>
                        </li>
                    <?php
                        endwhile;
                        echo '</ul>';
                        wp_reset_postdata();
                    else :
                    ?>
                        <p class="bbm-footer__empty"><?php esc_html_e( 'Yaklaşan etkinlik yok.', 'bitebimuv-dernek' ); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Kolon 4: İletişim -->
                <div class="bbm-footer__col">
                    <h3 class="bbm-footer__heading"><?php esc_html_e( 'İletişim', 'bitebimuv-dernek' ); ?></h3>
                    <address class="bbm-footer__contact">
                        <?php $addr = get_theme_mod( 'bbm_contact_address', '' ); if ( $addr ) : ?>
                        <div class="bbm-footer__contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <span><?php echo nl2br( esc_html( $addr ) ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php $phone = get_theme_mod( 'bbm_contact_phone', '' ); if ( $phone ) : ?>
                        <div class="bbm-footer__contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                        </div>
                        <?php endif; ?>
                        <?php $email = get_theme_mod( 'bbm_contact_email', '' ); if ( $email ) : ?>
                        <div class="bbm-footer__contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                        </div>
                        <?php endif; ?>
                        <?php $hours = get_theme_mod( 'bbm_contact_working_hours', '' ); if ( $hours ) : ?>
                        <div class="bbm-footer__contact-item">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            <span><?php echo esc_html( $hours ); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php $maps_url = get_theme_mod( 'bbm_contact_maps_url', '' ); if ( $maps_url ) : ?>
                        <a href="<?php echo esc_url( $maps_url ); ?>" class="bbm-btn bbm-btn--outline bbm-btn--sm bbm-footer__map-btn"
                           target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><polygon points="3 11 22 2 13 21 11 13 3 11"/></svg>
                            <?php esc_html_e( 'Haritada Göster', 'bitebimuv-dernek' ); ?>
                        </a>
                        <?php endif; ?>
                    </address>
                </div>

            </div><!-- .bbm-footer__grid -->
        </div>
    </div><!-- .bbm-footer__main -->

    <!-- Footer alt çubuğu -->
    <div class="bbm-footer__bottom">
        <div class="bbm-container">
            <div class="bbm-footer__bottom-inner">
                <p class="bbm-footer__copyright">
                    &copy; <?php echo date( 'Y' ); ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
                    &mdash; <?php esc_html_e( 'Tüm hakları saklıdır.', 'bitebimuv-dernek' ); ?>
                </p>
                <nav class="bbm-footer__legal-nav" aria-label="<?php esc_attr_e( 'Yasal bağlantılar', 'bitebimuv-dernek' ); ?>">
                    <?php if ( get_privacy_policy_url() ) : ?>
                    <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Gizlilik Politikası', 'bitebimuv-dernek' ); ?></a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( home_url( '/kvkk-aydinlatma-metni/' ) ); ?>"><?php esc_html_e( 'KVKK Aydınlatma', 'bitebimuv-dernek' ); ?></a>
                    <a href="<?php echo esc_url( home_url( '/kullanim-kosullari/' ) ); ?>"><?php esc_html_e( 'Kullanım Koşulları', 'bitebimuv-dernek' ); ?></a>
                </nav>
            </div>
        </div>
    </div>

</footer><!-- #bbm-footer -->

</div><!-- #bbm-page -->

<!-- Scroll to top -->
<button class="bbm-scroll-top" id="bbm-scroll-top"
        aria-label="<?php esc_attr_e( 'Yukarı çık', 'bitebimuv-dernek' ); ?>"
        title="<?php esc_attr_e( 'Yukarı çık', 'bitebimuv-dernek' ); ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="22" height="22" aria-hidden="true">
        <path d="M18 15l-6-6-6 6"/>
    </svg>
</button>

<!-- Yüzen gülümseyen yüz -->
<div class="bbm-floating-smiley" id="bbm-floating-smiley" aria-hidden="true">
    <?php echo bbm_get_smiley( 'medium', 'bbm-float-smiley' ); ?>
</div>

<!-- Lightbox -->
<div class="bbm-lightbox" id="bbm-lightbox" hidden role="dialog" aria-modal="true"
     aria-label="<?php esc_attr_e( 'Fotoğraf görüntüleyici', 'bitebimuv-dernek' ); ?>">
    <div class="bbm-lightbox__overlay" id="bbm-lightbox-overlay"></div>
    <div class="bbm-lightbox__content">
        <button class="bbm-lightbox__close" id="bbm-lightbox-close"
                aria-label="<?php esc_attr_e( 'Kapat', 'bitebimuv-dernek' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="28" height="28" aria-hidden="true">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
        <button class="bbm-lightbox__prev" id="bbm-lightbox-prev" aria-label="<?php esc_attr_e( 'Önceki', 'bitebimuv-dernek' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="28" height="28" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <img id="bbm-lightbox-img" src="" alt="" class="bbm-lightbox__img" loading="lazy">
        <button class="bbm-lightbox__next" id="bbm-lightbox-next" aria-label="<?php esc_attr_e( 'Sonraki', 'bitebimuv-dernek' ); ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="28" height="28" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
        </button>
        <div class="bbm-lightbox__caption" id="bbm-lightbox-caption"></div>
    </div>
</div>

<!-- Etkinlik kayıt modal'ı -->
<div class="bbm-modal" id="bbm-event-modal" hidden role="dialog" aria-modal="true"
     aria-labelledby="bbm-event-modal-title">
    <div class="bbm-modal__overlay" id="bbm-event-modal-overlay"></div>
    <div class="bbm-modal__content" role="document">
        <div class="bbm-modal__header">
            <h2 class="bbm-modal__title" id="bbm-event-modal-title">
                <?php esc_html_e( 'Etkinliğe Kayıt Ol', 'bitebimuv-dernek' ); ?>
            </h2>
            <button class="bbm-modal__close" id="bbm-event-modal-close"
                    aria-label="<?php esc_attr_e( 'Modalı kapat', 'bitebimuv-dernek' ); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="24" height="24" aria-hidden="true">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="bbm-modal__body">
            <form class="bbm-form" id="bbm-event-register-form" novalidate>
                <?php wp_nonce_field( 'bbm_event_register_nonce', 'bbm_event_nonce_field' ); ?>
                <input type="hidden" name="event_id" id="bbm-event-register-id" value="">
                <div class="bbm-form__row">
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-er-name"><?php esc_html_e( 'Ad Soyad *', 'bitebimuv-dernek' ); ?></label>
                        <input type="text" id="bbm-er-name" name="name" class="bbm-form-control" required
                               autocomplete="name" placeholder="<?php esc_attr_e( 'Adınız ve soyadınız', 'bitebimuv-dernek' ); ?>">
                    </div>
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-er-email"><?php esc_html_e( 'E-posta *', 'bitebimuv-dernek' ); ?></label>
                        <input type="email" id="bbm-er-email" name="email" class="bbm-form-control" required
                               autocomplete="email" placeholder="<?php esc_attr_e( 'ornek@eposta.com', 'bitebimuv-dernek' ); ?>">
                    </div>
                </div>
                <div class="bbm-form__row">
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-er-phone"><?php esc_html_e( 'Telefon', 'bitebimuv-dernek' ); ?></label>
                        <input type="tel" id="bbm-er-phone" name="phone" class="bbm-form-control"
                               autocomplete="tel" placeholder="<?php esc_attr_e( '0555 000 00 00', 'bitebimuv-dernek' ); ?>">
                    </div>
                    <div class="bbm-form__group">
                        <label class="bbm-form__label" for="bbm-er-guests"><?php esc_html_e( 'Misafir sayısı', 'bitebimuv-dernek' ); ?></label>
                        <select id="bbm-er-guests" name="guests" class="bbm-form-control bbm-form-select">
                            <option value="1">1 kişi (sadece ben)</option>
                            <option value="2">2 kişi</option>
                            <option value="3">3 kişi</option>
                            <option value="4">4 kişi</option>
                            <option value="5+">5 ve üzeri</option>
                        </select>
                    </div>
                </div>
                <div class="bbm-form__group">
                    <label class="bbm-form__label" for="bbm-er-notes"><?php esc_html_e( 'Notlar / Özel talepler', 'bitebimuv-dernek' ); ?></label>
                    <textarea id="bbm-er-notes" name="notes" class="bbm-form-control" rows="3"
                              placeholder="<?php esc_attr_e( 'Varsa özel taleplerinizi belirtebilirsiniz', 'bitebimuv-dernek' ); ?>"></textarea>
                </div>
                <label class="bbm-form__checkbox">
                    <input type="checkbox" name="kvkk_consent" required>
                    <span><?php printf(
                        esc_html__( '%s kapsamında kişisel verilerimin işlenmesini kabul ediyorum.', 'bitebimuv-dernek' ),
                        '<a href="' . esc_url( get_privacy_policy_url() ) . '" target="_blank">' . esc_html__( 'KVKK Aydınlatma Metni', 'bitebimuv-dernek' ) . '</a>'
                    ); ?></span>
                </label>
                <div class="bbm-form-msg" id="bbm-event-register-msg" role="alert" aria-live="polite"></div>
                <div class="bbm-modal__footer">
                    <button type="button" class="bbm-btn bbm-btn--ghost" id="bbm-event-modal-cancel">
                        <?php esc_html_e( 'İptal', 'bitebimuv-dernek' ); ?>
                    </button>
                    <button type="submit" class="bbm-btn bbm-btn--primary" id="bbm-event-register-submit">
                        <span class="bbm-btn__text"><?php esc_html_e( 'Kayıt Ol', 'bitebimuv-dernek' ); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast bildirimleri -->
<div class="bbm-toast-container" id="bbm-toast-container" aria-live="polite" aria-atomic="true"></div>

<?php wp_footer(); ?>

</body>
</html>
