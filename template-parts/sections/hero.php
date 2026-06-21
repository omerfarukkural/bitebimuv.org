<?php
/**
 * Hero Bölümü - v3.0 Şaheser Sürüm
 * Particle animasyonu + interaktif gülümseyen yüz
 *
 * @package bitebimuv-dernek
 */

$hero_title    = get_theme_mod( 'bbm_hero_title', __( 'Birlikte Daha Güçlüyüz', 'bitebimuv-dernek' ) );
$hero_subtitle = get_theme_mod( 'bbm_hero_subtitle', __( 'Derneğimiz, toplumun her kesiminden insanı bir araya getirerek ortak değerler etrafında güçlü bir topluluk oluşturmaktadır.', 'bitebimuv-dernek' ) );
$cta_text      = get_theme_mod( 'bbm_hero_cta_text', __( 'Üye Ol', 'bitebimuv-dernek' ) );
$cta_url       = get_theme_mod( 'bbm_hero_cta_url', '#uye-ol' );
$cta2_text     = get_theme_mod( 'bbm_hero_cta2_text', __( 'Daha Fazla Bilgi', 'bitebimuv-dernek' ) );
$cta2_url      = get_theme_mod( 'bbm_hero_cta2_url', '#hakkimizda' );
$founded       = get_theme_mod( 'bbm_founded_year', '2010' );
$member_count  = get_theme_mod( 'bbm_member_count', '500' );

// İstatistikler
$stats = [
    [ 'number' => $member_count . '+', 'label' => __( 'Üye', 'bitebimuv-dernek' ) ],
    [ 'number' => date('Y') - intval($founded), 'label' => __( 'Yıllık Deneyim', 'bitebimuv-dernek' ) ],
    [ 'number' => get_theme_mod( 'bbm_stat1_number', '120' ) . '+', 'label' => __( 'Etkinlik', 'bitebimuv-dernek' ) ],
    [ 'number' => get_theme_mod( 'bbm_stat2_number', '50' ) . '+', 'label' => __( 'Proje', 'bitebimuv-dernek' ) ],
];
?>

<section class="bbm-hero" id="ana-sayfa" aria-labelledby="bbm-hero-title">

    <!-- Particle canvas alanı -->
    <div class="bbm-hero__particles" aria-hidden="true"></div>

    <!-- Arka plan dekoratif daireler -->
    <div class="bbm-hero__bg-decor" aria-hidden="true">
        <div class="bbm-hero__bg-circle bbm-hero__bg-circle--1"></div>
        <div class="bbm-hero__bg-circle bbm-hero__bg-circle--2"></div>
        <div class="bbm-hero__bg-circle bbm-hero__bg-circle--3"></div>
    </div>

    <div class="bbm-container bbm-hero__container">
        <div class="bbm-hero__content">

            <!-- Sol: Metin içeriği -->
            <div class="bbm-hero__text">
                <div class="bbm-hero__badge" data-aos="fade-up">
                    <span class="bbm-hero__badge-icon">✨</span>
                    <?php
                    $tagline = get_theme_mod( 'bbm_tagline', __( 'Gönüllü Derneği', 'bitebimuv-dernek' ) );
                    echo esc_html( $tagline );
                    ?>
                    <span class="bbm-hero__badge-dot"></span>
                    <span class="bbm-hero__badge-year"><?php echo esc_html( $founded ); ?>'den beri</span>
                </div>

                <h1 id="bbm-hero-title" class="bbm-hero__title" data-aos="fade-up" data-aos-delay="100">
                    <?php echo wp_kses_post( $hero_title ); ?>
                </h1>

                <p class="bbm-hero__subtitle" data-aos="fade-up" data-aos-delay="200">
                    <?php echo wp_kses_post( $hero_subtitle ); ?>
                </p>

                <div class="bbm-hero__actions" data-aos="fade-up" data-aos-delay="300">
                    <a href="<?php echo esc_url( $cta_url ); ?>" class="bbm-btn bbm-btn--primary bbm-btn--lg">
                        <?php echo esc_html( $cta_text ); ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <a href="<?php echo esc_url( $cta2_url ); ?>" class="bbm-btn bbm-btn--outline bbm-btn--lg">
                        <?php echo esc_html( $cta2_text ); ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    </a>
                </div>

                <!-- Mini istatistikler -->
                <div class="bbm-hero__stats" data-aos="fade-up" data-aos-delay="400">
                    <?php foreach ( $stats as $i => $stat ) : ?>
                    <div class="bbm-hero__stat">
                        <strong class="bbm-hero__stat-number bbm-counter"
                                data-target="<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $stat['number'] ) ); ?>"
                                data-suffix="<?php echo preg_match('/\+/', $stat['number']) ? '+' : ''; ?>">
                            <?php echo esc_html( $stat['number'] ); ?>
                        </strong>
                        <span class="bbm-hero__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
                    </div>
                    <?php if ( $i < count( $stats ) - 1 ) : ?>
                    <div class="bbm-hero__stat-divider" aria-hidden="true"></div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Sağ: İnteraktif gülümseyen yüz maskot -->
            <div class="bbm-hero__mascot" data-aos="zoom-in" data-aos-delay="150">
                <div class="bbm-hero__mascot-rings" aria-hidden="true">
                    <div class="bbm-hero__ring bbm-hero__ring--1"></div>
                    <div class="bbm-hero__ring bbm-hero__ring--2"></div>
                    <div class="bbm-hero__ring bbm-hero__ring--3"></div>
                </div>
                <div class="bbm-hero__smiley-wrap" id="bbm-hero-smiley-wrap">
                    <?php echo bbm_get_smiley( 'hero', 'bbm-hero-smiley' ); ?>
                </div>
                <!-- Duygusal durum göstergesi -->
                <div class="bbm-hero__emotion-badge" id="bbm-hero-emotion-badge" aria-live="polite">
                    <span class="bbm-hero__emotion-icon">😊</span>
                    <span class="bbm-hero__emotion-label"><?php esc_html_e( 'Mutlu', 'bitebimuv-dernek' ); ?></span>
                </div>
            </div>

        </div>
    </div>

    <!-- Kaydır göstergesi -->
    <div class="bbm-hero__scroll-indicator" aria-hidden="true">
        <div class="bbm-hero__scroll-dot"></div>
    </div>

</section>
