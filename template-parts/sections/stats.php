<?php
/**
 * İstatistikler Bölümü
 *
 * @package bitebimuv-dernek
 */

$stats = [
    [ get_theme_mod( 'bbm_stat1_number', '500+' ), get_theme_mod( 'bbm_stat1_label', 'Üyemiz' ),           'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75' ],
    [ get_theme_mod( 'bbm_stat2_number', '120+' ), get_theme_mod( 'bbm_stat2_label', 'Etkinlik' ),         'M8 2v4M16 2v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z' ],
    [ get_theme_mod( 'bbm_stat3_number', '8' ),    get_theme_mod( 'bbm_stat3_label', 'Yıllık Deneyim' ),  'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z' ],
    [ get_theme_mod( 'bbm_stat4_number', '50+' ),  get_theme_mod( 'bbm_stat4_label', 'Tamamlanan Proje' ), 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 0 0 1.946-.806 3.42 3.42 0 0 1 4.438 0 3.42 3.42 0 0 0 1.946.806 3.42 3.42 0 0 1 3.138 3.138 3.42 3.42 0 0 0 .806 1.946 3.42 3.42 0 0 1 0 4.438 3.42 3.42 0 0 0-.806 1.946 3.42 3.42 0 0 1-3.138 3.138 3.42 3.42 0 0 0-1.946.806 3.42 3.42 0 0 1-4.438 0 3.42 3.42 0 0 0-1.946-.806 3.42 3.42 0 0 1-3.138-3.138 3.42 3.42 0 0 0-.806-1.946 3.42 3.42 0 0 1 0-4.438 3.42 3.42 0 0 0 .806-1.946 3.42 3.42 0 0 1 3.138-3.138z' ],
];
?>
<section class="bbm-stats" id="bbm-stats" aria-label="<?php esc_attr_e( 'İstatistikler', 'bitebimuv-dernek' ); ?>">
    <div class="container">
        <div class="bbm-stats__grid">
            <?php foreach ( $stats as $i => [ $number, $label, $icon_path ] ) : ?>
            <div class="bbm-stat-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
                <div class="bbm-stat-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" width="28" height="28">
                        <path d="<?php echo esc_attr( $icon_path ); ?>"/>
                    </svg>
                </div>
                <div class="bbm-stat-card__number bbm-counter" data-target="<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $number ) ); ?>">
                    0<?php echo preg_match( '/[^0-9]/', $number ) ? preg_replace( '/[0-9]/', '', $number ) : ''; ?>
                </div>
                <div class="bbm-stat-card__label"><?php echo esc_html( $label ); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
