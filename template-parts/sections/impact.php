<?php
/**
 * Section: Etki / İstatistikler Görseli
 * Bite Bi Muv Derneği Teması v4.0
 */

$stats = apply_filters( 'bbm_impact_stats', [
    [
        'icon'   => '👥',
        'value'  => (string) wp_count_posts( 'bbm_member' )->publish,
        'label'  => __( 'Aktif Üye', 'bitebimuv' ),
        'suffix' => '+',
        'color'  => 'var(--bbm-primary)',
    ],
    [
        'icon'   => '📅',
        'value'  => (string) wp_count_posts( 'bbm_event' )->publish,
        'label'  => __( 'Etkinlik Düzenlendi', 'bitebimuv' ),
        'suffix' => '+',
        'color'  => 'var(--bbm-secondary)',
    ],
    [
        'icon'   => '🚀',
        'value'  => (string) wp_count_posts( 'bbm_project' )->publish,
        'label'  => __( 'Tamamlanan Proje', 'bitebimuv' ),
        'suffix' => '',
        'color'  => '#10b981',
    ],
    [
        'icon'   => '🤝',
        'value'  => (string) wp_count_posts( 'bbm_volunteer' )->publish,
        'label'  => __( 'Gönüllü', 'bitebimuv' ),
        'suffix' => '+',
        'color'  => '#f59e0b',
    ],
    [
        'icon'   => '🏙',
        'value'  => get_theme_mod( 'bbm_stat_cities', '12' ),
        'label'  => get_theme_mod( 'bbm_stat_cities_label', __( 'Şehirde Etkiniz', 'bitebimuv' ) ),
        'suffix' => '',
        'color'  => '#ef4444',
    ],
    [
        'icon'   => '⭐',
        'value'  => (string) wp_count_posts( 'bbm_sponsor' )->publish,
        'label'  => __( 'Destekçi Kurum', 'bitebimuv' ),
        'suffix' => '+',
        'color'  => '#8b5cf6',
    ],
] );
?>

<section class="bbm-section bbm-impact-section" aria-label="<?php esc_attr_e( 'İstatistikler', 'bitebimuv' ); ?>">

    <!-- Decorative SVG wave top -->
    <div class="bbm-impact-wave" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path d="M0,30 C360,60 1080,0 1440,30 L1440,0 L0,0 Z" fill="var(--bbm-surface)"/>
        </svg>
    </div>

    <div class="bbm-impact-bg" aria-hidden="true"></div>

    <div class="bbm-container">
        <div class="bbm-section-header bbm-section-header--light" data-aos="fade-up">
            <span class="bbm-section-badge bbm-section-badge--light"><?php _e( 'Etkimiz', 'bitebimuv' ); ?></span>
            <h2 class="bbm-section-title"><?php _e( 'Rakamlarla Bitebimuv', 'bitebimuv' ); ?></h2>
            <p class="bbm-section-subtitle"><?php _e( 'Kuruluşumuzdan bu yana yarattığımız değerin bir özeti.', 'bitebimuv' ); ?></p>
        </div>

        <div class="bbm-impact-grid">
            <?php foreach ( $stats as $i => $stat ) : ?>
            <div class="bbm-impact-item" data-aos="fade-up" data-aos-delay="<?php echo $i * 70; ?>">
                <div class="bbm-impact-icon" style="color: <?php echo esc_attr( $stat['color'] ); ?>;" aria-hidden="true">
                    <?php echo $stat['icon']; ?>
                </div>
                <div class="bbm-impact-value-wrap">
                    <span class="bbm-impact-value bbm-counter"
                          data-target="<?php echo esc_attr( preg_replace( '/\D/', '', $stat['value'] ) ?: '0' ); ?>"
                          style="color: <?php echo esc_attr( $stat['color'] ); ?>;"
                          aria-label="<?php echo esc_attr( $stat['value'] . $stat['suffix'] . ' ' . $stat['label'] ); ?>">
                        0
                    </span>
                    <span class="bbm-impact-suffix" style="color: <?php echo esc_attr( $stat['color'] ); ?>;" aria-hidden="true">
                        <?php echo esc_html( $stat['suffix'] ); ?>
                    </span>
                </div>
                <div class="bbm-impact-label"><?php echo esc_html( $stat['label'] ); ?></div>
                <div class="bbm-impact-line" style="background: <?php echo esc_attr( $stat['color'] ); ?>;"></div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php
        $founded = get_theme_mod( 'bbm_founded_year', '2020' );
        if ( $founded ) :
            $years_active = (int) date( 'Y' ) - (int) $founded;
        ?>
        <div class="bbm-impact-footer" data-aos="fade-up">
            <p>
                <?php printf(
                    _n(
                        '%1$s yılından bu yana %2$s yıldır topluma hizmet ediyoruz.',
                        '%1$s yılından bu yana %2$s yıldır topluma hizmet ediyoruz.',
                        $years_active,
                        'bitebimuv'
                    ),
                    esc_html( $founded ),
                    '<strong>' . $years_active . '</strong>'
                ); ?>
            </p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Decorative SVG wave bottom -->
    <div class="bbm-impact-wave bbm-impact-wave--bottom" aria-hidden="true">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path d="M0,30 C360,0 1080,60 1440,30 L1440,60 L0,60 Z" fill="var(--bbm-surface)"/>
        </svg>
    </div>
</section>
