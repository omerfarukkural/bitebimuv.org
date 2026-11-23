<?php
/**
 * Template Name: İletişim Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header(
        get_the_title() ?: __( 'İletişim', 'bitebimuv' ),
        get_the_excerpt() ?: __( 'Bize ulaşın, size yardımcı olmaktan mutluluk duyarız.', 'bitebimuv' )
    ); ?>

    <section class="bbm-section bbm-contact-page">
        <div class="bbm-container">
            <div class="bbm-contact-layout">

                <!-- Contact Info -->
                <div class="bbm-contact-info">
                    <h2 class="bbm-section-title"><?php _e( 'Bize Ulaşın', 'bitebimuv' ); ?></h2>
                    <p class="bbm-contact-intro"><?php the_content(); ?></p>

                    <div class="bbm-contact-cards">
                        <?php
                        $address = get_theme_mod( 'bbm_contact_address', '' );
                        $phone   = get_theme_mod( 'bbm_contact_phone', '' );
                        $email   = get_theme_mod( 'bbm_contact_email', '' );
                        $hours   = get_theme_mod( 'bbm_contact_hours', 'Pazartesi – Cuma: 09:00 – 18:00' );
                        $map_lat = get_theme_mod( 'bbm_map_lat', '41.0082376' );
                        $map_lng = get_theme_mod( 'bbm_map_lng', '28.9783589' );

                        $contact_items = [
                            [ 'icon' => '📍', 'label' => __( 'Adres', 'bitebimuv' ),     'value' => $address, 'type' => 'text' ],
                            [ 'icon' => '📞', 'label' => __( 'Telefon', 'bitebimuv' ),   'value' => $phone,   'type' => 'tel' ],
                            [ 'icon' => '✉️', 'label' => __( 'E-posta', 'bitebimuv' ),  'value' => $email,   'type' => 'email' ],
                            [ 'icon' => '🕐', 'label' => __( 'Çalışma Saatleri', 'bitebimuv' ), 'value' => $hours, 'type' => 'text' ],
                        ];
                        foreach ( $contact_items as $item ) :
                            if ( ! $item['value'] ) continue;
                        ?>
                        <div class="bbm-contact-card">
                            <div class="bbm-contact-card__icon"><?php echo $item['icon']; ?></div>
                            <div class="bbm-contact-card__body">
                                <h4><?php echo esc_html( $item['label'] ); ?></h4>
                                <?php if ( $item['type'] === 'tel' ) : ?>
                                    <a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $item['value'] ) ); ?>"><?php echo esc_html( $item['value'] ); ?></a>
                                <?php elseif ( $item['type'] === 'email' ) : ?>
                                    <a href="mailto:<?php echo esc_attr( $item['value'] ); ?>"><?php echo esc_html( $item['value'] ); ?></a>
                                <?php else : ?>
                                    <p><?php echo nl2br( esc_html( $item['value'] ) ); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Social Links -->
                    <div class="bbm-contact-social">
                        <h4><?php _e( 'Sosyal Medya', 'bitebimuv' ); ?></h4>
                        <div class="bbm-social-icons bbm-social-icons--lg">
                            <?php echo bbm_get_social_links(); ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bbm-contact-form-wrap">
                    <div class="bbm-contact-form-card">
                        <h3><?php _e( 'Mesaj Gönderin', 'bitebimuv' ); ?></h3>
                        <form id="bbm-contact-form" class="bbm-form" novalidate aria-label="<?php esc_attr_e( 'İletişim formu', 'bitebimuv' ); ?>">
                            <?php wp_nonce_field( 'bbm_contact_nonce', 'bbm_contact_nonce' ); ?>
                            <?php echo bbm_honeypot_field(); ?>
                            <div class="bbm-form-row">
                                <div class="bbm-form-group">
                                    <label for="contact-name"><?php _e( 'Adınız Soyadınız', 'bitebimuv' ); ?> <span class="bbm-req">*</span></label>
                                    <input type="text" id="contact-name" name="name" required autocomplete="name" placeholder="<?php esc_attr_e( 'Adınız Soyadınız', 'bitebimuv' ); ?>">
                                </div>
                                <div class="bbm-form-group">
                                    <label for="contact-email"><?php _e( 'E-posta', 'bitebimuv' ); ?> <span class="bbm-req">*</span></label>
                                    <input type="email" id="contact-email" name="email" required autocomplete="email" placeholder="ornek@email.com">
                                </div>
                            </div>
                            <div class="bbm-form-group">
                                <label for="contact-subject"><?php _e( 'Konu', 'bitebimuv' ); ?></label>
                                <input type="text" id="contact-subject" name="subject" placeholder="<?php esc_attr_e( 'Mesajınızın konusu', 'bitebimuv' ); ?>">
                            </div>
                            <div class="bbm-form-group">
                                <label for="contact-message"><?php _e( 'Mesajınız', 'bitebimuv' ); ?> <span class="bbm-req">*</span></label>
                                <textarea id="contact-message" name="message" rows="6" required placeholder="<?php esc_attr_e( 'Mesajınızı buraya yazın…', 'bitebimuv' ); ?>"></textarea>
                            </div>
                            <div class="bbm-form-group bbm-form-group--check">
                                <label class="bbm-checkbox">
                                    <input type="checkbox" name="kvkk_contact" required>
                                    <span><?php _e( 'KVKK kapsamında kişisel verilerimin işlenmesine onay veriyorum.', 'bitebimuv' ); ?></span>
                                </label>
                            </div>
                            <button type="submit" class="bbm-btn bbm-btn--primary bbm-btn--full">
                                <?php _e( 'Mesajı Gönder', 'bitebimuv' ); ?>
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                            </button>
                            <div class="bbm-form-message" role="alert" aria-live="polite"></div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Leaflet Map -->
            <?php if ( $map_lat && $map_lng ) : ?>
            <div class="bbm-map-section">
                <h3 class="bbm-section-subtitle"><?php _e( 'Konumumuz', 'bitebimuv' ); ?></h3>
                <div id="bbm-leaflet-map"
                     class="bbm-leaflet-map"
                     data-lat="<?php echo esc_attr( $map_lat ); ?>"
                     data-lng="<?php echo esc_attr( $map_lng ); ?>"
                     data-title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>"
                     aria-label="<?php esc_attr_e( 'Dernek konum haritası', 'bitebimuv' ); ?>"
                     role="application">
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', [], '1.9.4', true );
wp_enqueue_style( 'leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', [], '1.9.4' );
wp_enqueue_script( 'bbm-map', get_template_directory_uri() . '/assets/js/map.js', [ 'leaflet' ], BBM_VERSION, true );

get_footer();
