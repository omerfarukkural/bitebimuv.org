<?php
/**
 * Etkinlikler Bölümü
 *
 * @package bitebimuv-dernek
 */

$events_query = bbm_get_upcoming_events( 4 );
?>
<section class="bbm-events" id="etkinlikler" aria-label="<?php esc_attr_e( 'Etkinlikler', 'bitebimuv-dernek' ); ?>">
    <div class="container">
        <div class="bbm-section-header">
            <span class="bbm-section-badge"><?php _e( '📅 Takvim', 'bitebimuv-dernek' ); ?></span>
            <h2 class="bbm-section-title"><?php _e( 'Yakındaşmakta Olan Etkinlikler', 'bitebimuv-dernek' ); ?></h2>
            <p class="bbm-section-subtitle"><?php _e( 'Etkinliklerimize katılarak topluluklunuzla bir arada olun.', 'bitebimuv-dernek' ); ?></p>
        </div>

        <?php if ( $events_query->have_posts() ) : ?>
        <div class="bbm-events__grid">
            <?php
            $event_index = 0;
            while ( $events_query->have_posts() ) :
                $events_query->the_post();
                ?>
            <div class="bbm-events__item" data-aos="fade-up" data-aos-delay="<?php echo $event_index * 100; ?>">
                <?php bbm_event_card( get_the_ID() ); ?>
            </div>
            <?php
                $event_index++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        <div class="bbm-events__cta">
            <a href="<?php echo get_post_type_archive_link( 'bbm_event' ); ?>" class="bbm-btn bbm-btn--outline">
                <?php _e( 'Tüm Etkinlikleri Gör', 'bitebimuv-dernek' ); ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <?php else : ?>
        <div class="bbm-events__empty">
            <?php echo bbm_get_smiley( 'medium', 'bbm-events-smiley' ); ?>
            <p><?php _e( 'Yakında yeni etkinlikler duyurulacak!', 'bitebimuv-dernek' ); ?></p>
            <a href="#bultene-abone-ol" class="bbm-btn bbm-btn--primary"><?php _e( 'Bültene Abone Ol', 'bitebimuv-dernek' ); ?></a>
        </div>
        <?php endif; ?>

        <!-- Etkinlik Kayıt Formu Modal -->
        <div class="bbm-modal" id="bbm-event-register-modal" hidden role="dialog" aria-modal="true" aria-labelledby="bbm-event-modal-title">
            <div class="bbm-modal__backdrop"></div>
            <div class="bbm-modal__inner">
                <button class="bbm-modal__close" aria-label="Kapat">&times;</button>
                <h3 id="bbm-event-modal-title" class="bbm-modal__title"><?php _e( 'Etkinliğe Kayıt Ol', 'bitebimuv-dernek' ); ?></h3>
                <form class="bbm-event-register-form" id="bbm-event-register-form">
                    <?php wp_nonce_field( 'bbm_nonce', 'bbm_nonce' ); ?>
                    <input type="hidden" name="action" value="bbm_event_register">
                    <input type="hidden" name="event_id" id="bbm-event-id" value="">
                    <div class="bbm-form-group">
                        <label for="bbm-event-name"><?php _e( 'Ad Soyad', 'bitebimuv-dernek' ); ?> *</label>
                        <input type="text" id="bbm-event-name" name="name" required class="bbm-input">
                    </div>
                    <div class="bbm-form-group">
                        <label for="bbm-event-email"><?php _e( 'E-posta', 'bitebimuv-dernek' ); ?> *</label>
                        <input type="email" id="bbm-event-email" name="email" required class="bbm-input">
                    </div>
                    <div class="bbm-form-group">
                        <label for="bbm-event-phone"><?php _e( 'Telefon', 'bitebimuv-dernek' ); ?></label>
                        <input type="tel" id="bbm-event-phone" name="phone" class="bbm-input">
                    </div>
                    <button type="submit" class="bbm-btn bbm-btn--primary bbm-btn--full"><?php _e( 'Kayıt Ol', 'bitebimuv-dernek' ); ?></button>
                    <div class="bbm-form-message" id="bbm-event-form-message"></div>
                </form>
            </div>
        </div>

    </div>
</section>
