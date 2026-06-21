<?php
/**
 * Hero Bölümü - İnteraktif Gülümseyen Yüz ile
 *
 * @package bitebimuv-dernek
 */

$hero_title    = get_theme_mod( 'bbm_hero_title',    __( 'BiteBiMuv Derneğine Hoş Geldiniz', 'bitebimuv-dernek' ) );
$hero_subtitle = get_theme_mod( 'bbm_hero_subtitle', __( 'Gönüllülerimizle toplumumuza değer katıyoruz.', 'bitebimuv-dernek' ) );
$cta_text      = get_theme_mod( 'bbm_hero_cta_text', __( 'Üye Ol', 'bitebimuv-dernek' ) );
$cta_url       = get_theme_mod( 'bbm_hero_cta_url',  '#uye-ol' );
?>
<section class="bbm-hero" id="bbm-hero" aria-label="<?php esc_attr_e( 'Ana Banner', 'bitebimuv-dernek' ); ?>">
    <div class="bbm-hero__bg" aria-hidden="true">
        <div class="bbm-hero__bg-circle bbm-hero__bg-circle--1"></div>
        <div class="bbm-hero__bg-circle bbm-hero__bg-circle--2"></div>
        <div class="bbm-hero__bg-circle bbm-hero__bg-circle--3"></div>
        <div class="bbm-hero__bg-particles" id="bbm-particles"></div>
    </div>

    <div class="bbm-hero__inner container">

        <!-- Metin bölümü -->
        <div class="bbm-hero__text">
            <span class="bbm-hero__badge"><?php _e( '🌟 Gönüllü Dernek', 'bitebimuv-dernek' ); ?></span>
            <h1 class="bbm-hero-title"><?php echo esc_html( $hero_title ); ?></h1>
            <p class="bbm-hero__subtitle"><?php echo esc_html( $hero_subtitle ); ?></p>
            <div class="bbm-hero__actions">
                <a href="<?php echo esc_url( $cta_url ); ?>" class="bbm-btn bbm-btn--primary bbm-btn--lg">
                    <?php echo esc_html( $cta_text ); ?>
                </a>
                <a href="#hakki-mizda" class="bbm-btn bbm-btn--ghost bbm-btn--lg">
                    <?php _e( 'Daha Fazla', 'bitebimuv-dernek' ); ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="6 9 12 15 18 9"/></svg>
                </a>
            </div>

            <!-- Mini istatistikler -->
            <div class="bbm-hero__mini-stats">
                <?php
                $mini_stats = [
                    [ get_theme_mod( 'bbm_stat1_number', '500+' ), get_theme_mod( 'bbm_stat1_label', 'Üye' ) ],
                    [ get_theme_mod( 'bbm_stat2_number', '120+' ), get_theme_mod( 'bbm_stat2_label', 'Etkinlik' ) ],
                    [ get_theme_mod( 'bbm_stat3_number', '8' ),    get_theme_mod( 'bbm_stat3_label', 'Yıl' ) ],
                ];
                foreach ( $mini_stats as [ $num, $label ] ) : ?>
                <div class="bbm-hero__mini-stat">
                    <span class="bbm-hero__mini-stat-num"><?php echo esc_html( $num ); ?></span>
                    <span class="bbm-hero__mini-stat-label"><?php echo esc_html( $label ); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- İnteraktif Gülümseyen Yüz -->
        <div class="bbm-hero__mascot" id="bbm-hero-mascot">
            <div class="bbm-hero__mascot-glow" aria-hidden="true"></div>
            <div class="bbm-hero__mascot-rings" aria-hidden="true">
                <div class="bbm-hero__ring bbm-hero__ring--1"></div>
                <div class="bbm-hero__ring bbm-hero__ring--2"></div>
                <div class="bbm-hero__ring bbm-hero__ring--3"></div>
            </div>
            <?php echo bbm_get_smiley( 'hero', 'bbm-smiley' ); ?>
            <div class="bbm-hero__mascot-label">
                <?php _e( 'Fare ile üzerime gel!', 'bitebimuv-dernek' ); ?>
            </div>

            <!-- Floating emojis -->
            <div class="bbm-hero__emoji bbm-hero__emoji--1" aria-hidden="true">♥️</div>
            <div class="bbm-hero__emoji bbm-hero__emoji--2" aria-hidden="true">✨</div>
            <div class="bbm-hero__emoji bbm-hero__emoji--3" aria-hidden="true">🌟</div>
            <div class="bbm-hero__emoji bbm-hero__emoji--4" aria-hidden="true">🤝</div>
        </div>

    </div><!-- .bbm-hero__inner -->

    <!-- Dalga scroll göstergesi -->
    <div class="bbm-hero__scroll-indicator" aria-hidden="true">
        <div class="bbm-hero__scroll-mouse">
            <div class="bbm-hero__scroll-wheel"></div>
        </div>
        <span><?php _e( 'Aşağı kayır', 'bitebimuv-dernek' ); ?></span>
    </div>

    <!-- Alt dalga -->
    <div class="bbm-hero__wave" aria-hidden="true">
        <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
            <path d="M0,40 C360,80 720,0 1080,40 C1260,60 1380,30 1440,40 L1440,80 L0,80 Z" fill="var(--bbm-light)"/>
        </svg>
    </div>
</section>
