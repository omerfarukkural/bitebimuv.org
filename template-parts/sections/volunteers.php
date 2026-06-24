<?php
/**
 * Section: Gönüllü Çağrısı (Ana Sayfa)
 * Bite Bi Muv Derneği Teması v4.0
 */

$vol_count = wp_count_posts( 'bbm_volunteer' )->publish;
$areas     = get_terms( [ 'taxonomy' => 'bbm_volunteer_area', 'hide_empty' => true, 'number' => 8 ] );
?>

<section class="bbm-section bbm-volunteers-cta-section" aria-label="<?php esc_attr_e( 'Gönüllü çağrısı', 'bitebimuv' ); ?>">
    <div class="bbm-container">
        <div class="bbm-volunteers-cta-inner">

            <!-- Left: Info -->
            <div class="bbm-volunteers-cta__content" data-aos="fade-right">
                <span class="bbm-section-badge"><?php _e( 'Gönüllülük', 'bitebimuv' ); ?></span>
                <h2 class="bbm-section-title"><?php _e( 'Fark Yaratan Ekibimize Katıl', 'bitebimuv' ); ?></h2>
                <p class="bbm-section-body">
                    <?php printf(
                        __( 'Halihazırda %s gönüllümüzle birlikte anlamlı projeler yürütüyoruz. Sen de bu ekibin parçası olabilirsin.', 'bitebimuv' ),
                        '<strong>' . $vol_count . '</strong>'
                    ); ?>
                </p>

                <?php if ( $areas && ! is_wp_error( $areas ) ) : ?>
                <div class="bbm-vol-areas">
                    <h4><?php _e( 'Gönüllülük Alanları:', 'bitebimuv' ); ?></h4>
                    <div class="bbm-vol-area-tags">
                        <?php foreach ( $areas as $area ) : ?>
                        <span class="bbm-vol-area-tag">
                            <?php echo esc_html( $area->name ); ?>
                            <small><?php echo $area->count; ?> <?php _e( 'kişi', 'bitebimuv' ); ?></small>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="bbm-vol-benefits">
                    <?php
                    $benefits = apply_filters( 'bbm_volunteer_benefits', [
                        [ '🌱', __( 'Kişisel gelişim fırsatları', 'bitebimuv' ) ],
                        [ '🤝', __( 'Güçlü bir ağ ve topluluk', 'bitebimuv' ) ],
                        [ '🎓', __( 'Eğitim ve workshop erişimi', 'bitebimuv' ) ],
                        [ '🏅', __( 'Resmi gönüllü sertifikası', 'bitebimuv' ) ],
                    ] );
                    foreach ( $benefits as $b ) : ?>
                    <div class="bbm-vol-benefit">
                        <span class="bbm-vol-benefit__icon"><?php echo $b[0]; ?></span>
                        <span><?php echo esc_html( $b[1] ); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <a href="<?php echo esc_url( home_url( '/gonulluler/' ) ); ?>" class="bbm-btn bbm-btn--primary">
                    <?php _e( 'Gönüllü Ol', 'bitebimuv' ); ?>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>

            <!-- Right: Visual with active volunteers preview -->
            <div class="bbm-volunteers-cta__visual" data-aos="fade-left">
                <div class="bbm-vol-preview-card">
                    <div class="bbm-vol-preview-header">
                        <div class="bbm-vol-preview-avatars">
                            <?php
                            $preview_vols = get_posts( [ 'post_type' => 'bbm_volunteer', 'numberposts' => 5, 'meta_query' => [ [ 'key' => '_bbm_volunteer_status', 'value' => 'active' ] ] ] );
                            foreach ( $preview_vols as $pv ) :
                            ?>
                            <div class="bbm-vol-preview-avatar" title="<?php echo esc_attr( $pv->post_title ); ?>">
                                <?php if ( has_post_thumbnail( $pv ) ) :
                                    echo get_the_post_thumbnail( $pv, [ 48, 48 ], [ 'loading' => 'lazy', 'decoding' => 'async' ] );
                                else : ?>
                                    <div class="bbm-vol-preview-avatar__placeholder"><?php echo mb_substr( $pv->post_title, 0, 1, 'UTF-8' ); ?></div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                            <?php if ( $vol_count > 5 ) : ?>
                            <div class="bbm-vol-preview-avatar bbm-vol-preview-avatar--count">+<?php echo $vol_count - 5; ?></div>
                            <?php endif; ?>
                        </div>
                        <p><strong><?php echo $vol_count; ?></strong> <?php _e( 'aktif gönüllü', 'bitebimuv' ); ?></p>
                    </div>

                    <div class="bbm-vol-preview-smiley">
                        <?php echo bbm_get_smiley( 'large' ); ?>
                    </div>

                    <div class="bbm-vol-preview-cta">
                        <p class="bbm-vol-preview-tagline"><?php _e( '"Birlikte daha güçlüyüz!"', 'bitebimuv' ); ?></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
