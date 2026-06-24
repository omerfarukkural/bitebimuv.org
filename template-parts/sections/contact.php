<?php
/**
 * İletişim Bölümü
 *
 * @package bitebimuv-dernek
 */

$address  = get_theme_mod( 'bbm_address', 'İstanbul, Türkiye' );
$phone    = get_theme_mod( 'bbm_phone',   '+90 (212) 000 00 00' );
$email    = get_theme_mod( 'bbm_email',   'info@bitebimuv.org' );
$maps_url = get_theme_mod( 'bbm_maps_url', '' );
?>
<section class="bbm-contact" id="iletisim" aria-label="<?php esc_attr_e( 'İletişim', 'bitebimuv-dernek' ); ?>">
    <div class="container">
        <div class="bbm-section-header">
            <span class="bbm-section-badge"><?php _e( '📧 Bize Yazın', 'bitebimuv-dernek' ); ?></span>
            <h2 class="bbm-section-title"><?php _e( 'İletişime Geçin', 'bitebimuv-dernek' ); ?></h2>
            <p class="bbm-section-subtitle"><?php _e( 'Sorularınız için bize ulaşın. En kısa sürede geri dönüyorız.', 'bitebimuv-dernek' ); ?></p>
        </div>

        <div class="bbm-contact__grid">

            <!-- İletişim bilgileri -->
            <div class="bbm-contact__info" data-aos="fade-right">
                <div class="bbm-contact__info-card">
                    <div class="bbm-contact__smiley-wrap">
                        <?php echo bbm_get_smiley( 'medium', 'bbm-contact-smiley' ); ?>
                        <p class="bbm-contact__smiley-text"><?php _e( 'Sizden haber almayı seviyoruz!', 'bitebimuv-dernek' ); ?></p>
                    </div>
                    <ul class="bbm-contact__list">
                        <?php if ( $address ) : ?>
                        <li>
                            <div class="bbm-contact__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            </div>
                            <div class="bbm-contact__detail">
                                <strong><?php _e( 'Adres', 'bitebimuv-dernek' ); ?></strong>
                                <p><?php echo nl2br( esc_html( $address ) ); ?></p>
                            </div>
                        </li>
                        <?php endif; if ( $phone ) : ?>
                        <li>
                            <div class="bbm-contact__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.16 6.16l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            </div>
                            <div class="bbm-contact__detail">
                                <strong><?php _e( 'Telefon', 'bitebimuv-dernek' ); ?></strong>
                                <a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                            </div>
                        </li>
                        <?php endif; if ( $email ) : ?>
                        <li>
                            <div class="bbm-contact__icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="22" height="22"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </div>
                            <div class="bbm-contact__detail">
                                <strong><?php _e( 'E-posta', 'bitebimuv-dernek' ); ?></strong>
                                <a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
                            </div>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <div class="bbm-contact__social">
                        <strong><?php _e( 'Sosyal Medya', 'bitebimuv-dernek' ); ?></strong>
                        <?php echo bbm_get_social_links( 'bbm-contact__social-link' ); ?>
                    </div>
                </div>
            </div>

            <!-- İletişim Formu -->
            <div class="bbm-contact__form-wrap" data-aos="fade-left">
                <form class="bbm-contact-form" id="bbm-contact-form" novalidate>
                    <?php wp_nonce_field( 'bbm_nonce', 'bbm_nonce' ); ?>
                    <input type="hidden" name="action" value="bbm_contact">

                    <div class="bbm-form-row">
                        <div class="bbm-form-group">
                            <label for="bbm-cf-name"><?php _e( 'Ad Soyad', 'bitebimuv-dernek' ); ?> *</label>
                            <input type="text" id="bbm-cf-name" name="name" class="bbm-input" required placeholder="<?php esc_attr_e( 'Ömer Faruk Kural', 'bitebimuv-dernek' ); ?>">
                        </div>
                        <div class="bbm-form-group">
                            <label for="bbm-cf-email"><?php _e( 'E-posta', 'bitebimuv-dernek' ); ?> *</label>
                            <input type="email" id="bbm-cf-email" name="email" class="bbm-input" required placeholder="ornek@email.com">
                        </div>
                    </div>
                    <div class="bbm-form-group">
                        <label for="bbm-cf-subject"><?php _e( 'Konu', 'bitebimuv-dernek' ); ?></label>
                        <input type="text" id="bbm-cf-subject" name="subject" class="bbm-input" placeholder="<?php esc_attr_e( 'Üyünlük hakkında...', 'bitebimuv-dernek' ); ?>">
                    </div>
                    <div class="bbm-form-group">
                        <label for="bbm-cf-message"><?php _e( 'Mesajınız', 'bitebimuv-dernek' ); ?> *</label>
                        <textarea id="bbm-cf-message" name="message" class="bbm-input bbm-textarea" required rows="5" placeholder="<?php esc_attr_e( 'Mesajınızı...', 'bitebimuv-dernek' ); ?>"></textarea>
                    </div>
                    <div class="bbm-form-group bbm-form-group--checkbox">
                        <label>
                            <input type="checkbox" name="consent" required>
                            <?php printf(
                                __( '<a href="%s">Gizlilik Politikası</a>nı okudum ve kabul ediyorum.', 'bitebimuv-dernek' ),
                                esc_url( get_privacy_policy_url() )
                            ); ?>
                        </label>
                    </div>
                    <button type="submit" class="bbm-btn bbm-btn--primary bbm-btn--lg bbm-btn--full" id="bbm-contact-submit">
                        <span class="bbm-btn__text"><?php _e( 'Gönder', 'bitebimuv-dernek' ); ?></span>
                        <span class="bbm-btn__loading" hidden><?php _e( 'Gönderiliyor...', 'bitebimuv-dernek' ); ?></span>
                    </button>
                    <div class="bbm-form-message" id="bbm-contact-message" role="alert"></div>
                </form>
            </div>

        </div><!-- .bbm-contact__grid -->
    </div>
</section>
