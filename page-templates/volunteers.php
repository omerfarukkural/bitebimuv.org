<?php
/**
 * Template Name: Gönüllüler Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header(
        get_the_title() ?: __( 'Gönüllüler', 'bitebimuv' ),
        get_the_excerpt() ?: __( 'Topluluğumuzu güçlendiren gönüllülerimiz', 'bitebimuv' )
    ); ?>

    <!-- Çağrı Banner -->
    <section class="bbm-section bbm-vol-cta-section" style="background: linear-gradient(135deg, var(--bbm-primary) 0%, var(--bbm-secondary) 100%); padding: 3rem 0;">
        <div class="bbm-container" style="text-align:center; color:#fff;">
            <h2 style="font-size:clamp(1.5rem,3vw,2.5rem); margin-bottom:1rem"><?php _e( 'Sen de Aramıza Katıl!', 'bitebimuv' ); ?></h2>
            <p style="font-size:1.1rem; opacity:.9; max-width:600px; margin:0 auto 2rem"><?php _e( 'Gönüllü olarak topluluğumuza katkı sağla, yeni insanlarla tanış, birlikte güzel işler yap.', 'bitebimuv' ); ?></p>
            <a href="#bbm-vol-form" class="bbm-btn bbm-btn--white"><?php _e( 'Gönüllü Ol', 'bitebimuv' ); ?></a>
        </div>
    </section>

    <!-- Aktif Gönüllüler -->
    <?php
    $vols_q = new WP_Query( [
        'post_type'      => 'bbm_volunteer',
        'post_status'    => 'publish',
        'posts_per_page' => 24,
        'meta_query'     => [
            [
                'key'     => '_bbm_volunteer_status',
                'value'   => 'active',
                'compare' => '=',
            ],
        ],
    ] );
    if ( $vols_q->have_posts() ) :
    ?>
    <section class="bbm-section bbm-volunteers-grid-section">
        <div class="bbm-container">
            <div class="bbm-section-header" data-aos="fade-up">
                <span class="bbm-section-badge"><?php _e( 'Ekibimiz', 'bitebimuv' ); ?></span>
                <h2 class="bbm-section-title"><?php _e( 'Aktif Gönüllülerimiz', 'bitebimuv' ); ?></h2>
            </div>
            <div class="bbm-members-grid bbm-volunteers-grid">
            <?php
            while ( $vols_q->have_posts() ) : $vols_q->the_post();
                $skills = get_post_meta( get_the_ID(), '_bbm_volunteer_skills', true );
                $avail  = get_post_meta( get_the_ID(), '_bbm_volunteer_availability', true );
                $areas  = wp_get_post_terms( get_the_ID(), 'bbm_volunteer_area', [ 'fields' => 'names' ] );
            ?>
            <div class="bbm-member-card bbm-volunteer-card" data-aos="fade-up">
                <div class="bbm-member-card__avatar">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'thumbnail', [ 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
                    <?php else : ?>
                        <div class="bbm-avatar-placeholder"><?php echo bbm_get_smiley( 'small' ); ?></div>
                    <?php endif; ?>
                </div>
                <div class="bbm-member-card__body">
                    <h3 class="bbm-member-card__name"><?php the_title(); ?></h3>
                    <?php if ( $areas && ! is_wp_error( $areas ) ) : ?>
                    <p class="bbm-member-card__role"><?php echo esc_html( implode( ', ', $areas ) ); ?></p>
                    <?php endif; ?>
                    <?php if ( $skills ) : ?>
                    <div class="bbm-vol-skills">
                        <?php foreach ( array_slice( explode( ',', $skills ), 0, 3 ) as $skill ) : ?>
                        <span class="bbm-tag"><?php echo esc_html( trim( $skill ) ); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <?php if ( $avail ) : ?>
                    <p class="bbm-vol-avail">🕐 <?php echo esc_html( $avail ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Gönüllü Kayıt Formu -->
    <section id="bbm-vol-form" class="bbm-section bbm-vol-form-section" style="background: var(--bbm-surface);">
        <div class="bbm-container">
            <div class="bbm-section-header" data-aos="fade-up">
                <span class="bbm-section-badge"><?php _e( 'Katıl', 'bitebimuv' ); ?></span>
                <h2 class="bbm-section-title"><?php _e( 'Gönüllü Başvuru Formu', 'bitebimuv' ); ?></h2>
                <p class="bbm-section-subtitle"><?php _e( 'Formu doldurun, ekibimiz en kısa sürede sizinle iletişime geçsin.', 'bitebimuv' ); ?></p>
            </div>
            <div style="max-width:640px; margin:0 auto">
                <form id="bbm-volunteer-form" class="bbm-form" novalidate>
                    <?php wp_nonce_field( 'bbm_nonce', 'bbm_nonce' ); ?>
                    <?php echo bbm_honeypot_field(); ?>
                    <div class="bbm-form-row">
                        <div class="bbm-form-group">
                            <label for="vol-name"><?php _e( 'Ad Soyad', 'bitebimuv' ); ?> *</label>
                            <input type="text" id="vol-name" name="vol_name" required autocomplete="name">
                        </div>
                        <div class="bbm-form-group">
                            <label for="vol-email"><?php _e( 'E-posta', 'bitebimuv' ); ?> *</label>
                            <input type="email" id="vol-email" name="vol_email" required autocomplete="email">
                        </div>
                    </div>
                    <div class="bbm-form-row">
                        <div class="bbm-form-group">
                            <label for="vol-phone"><?php _e( 'Telefon', 'bitebimuv' ); ?></label>
                            <input type="tel" id="vol-phone" name="vol_phone" autocomplete="tel">
                        </div>
                        <div class="bbm-form-group">
                            <label for="vol-avail"><?php _e( 'Müsaitlik', 'bitebimuv' ); ?></label>
                            <input type="text" id="vol-avail" name="vol_availability" placeholder="<?php esc_attr_e( 'örn: Hafta sonları', 'bitebimuv' ); ?>">
                        </div>
                    </div>
                    <div class="bbm-form-group">
                        <label for="vol-skills"><?php _e( 'Becerileriniz', 'bitebimuv' ); ?></label>
                        <input type="text" id="vol-skills" name="vol_skills" placeholder="<?php esc_attr_e( 'örn: Grafik tasarım, sosyal medya, etkinlik organizasyonu', 'bitebimuv' ); ?>">
                    </div>
                    <div class="bbm-form-group">
                        <label for="vol-motivation"><?php _e( 'Neden gönüllü olmak istiyorsunuz?', 'bitebimuv' ); ?></label>
                        <textarea id="vol-motivation" name="vol_motivation" rows="4"></textarea>
                    </div>
                    <div class="bbm-form-group bbm-form-group--check">
                        <label class="bbm-checkbox">
                            <input type="checkbox" name="kvkk_volunteer" required>
                            <span><?php _e( 'KVKK kapsamında kişisel verilerimin işlenmesine onay veriyorum.', 'bitebimuv' ); ?></span>
                        </label>
                    </div>
                    <input type="hidden" name="action" value="bbm_volunteer_apply">
                    <button type="submit" class="bbm-btn bbm-btn--primary bbm-btn--full"><?php _e( 'Başvuru Gönder', 'bitebimuv' ); ?></button>
                    <div class="bbm-form-message" role="alert" aria-live="polite"></div>
                </form>
            </div>
        </div>
    </section>

</main>

<?php get_footer();
