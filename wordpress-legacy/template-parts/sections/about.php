<?php
/**
 * Hakkımızda Bölümü
 *
 * @package bitebimuv-dernek
 */

$about_title = get_theme_mod( 'bbm_about_title', 'Hakkımızda' );
$about_text  = get_theme_mod( 'bbm_about_text', 'BiteBiMuv Derneği olarak, toplumsal dayanışmayı güçlendirmek, kültürel etkinlikler düzenlemek ve üyelerimizin sosyal gelişimini desteklemek amacıyla 2016 yılından bu yana çalışmaktayız.' );

$features = [
    [ '🤝', 'Gönüllülük',         'Topluma gönüllü katkı sağlamak temel değerimizdir.' ],
    [ '🌟', 'Birlik & Dayanışma', 'Birlikte daha güçlü, birlikte daha mutluyuz.' ],
    [ '🌱', 'Sürdürülebilirlik',   'Uzun vadeli ve çevreci projeler geliştiriyoruz.' ],
    [ '📚', 'Eğitim & Gelişim',  'Üyelerimizin kişisel gelişimini önemsiyoruz.' ],
];
?>
<section class="bbm-about" id="hakki-mizda" aria-label="<?php esc_attr_e( 'Hakkımızda', 'bitebimuv-dernek' ); ?>">
    <div class="container">
        <div class="bbm-about__grid">

            <!-- Görsel alan -->
            <div class="bbm-about__visual" data-aos="fade-right">
                <div class="bbm-about__img-wrapper">
                    <?php
                    $about_page = get_page_by_path( 'hakkimizda' );
                    if ( $about_page && has_post_thumbnail( $about_page->ID ) ) :
                        echo get_the_post_thumbnail( $about_page->ID, 'bbm-gallery', [ 'class' => 'bbm-about__img' ] );
                    else :
                    ?>
                    <div class="bbm-about__img-placeholder">
                        <?php echo bbm_get_smiley( 'large', 'bbm-about-smiley' ); ?>
                    </div>
                    <?php endif; ?>

                    <!-- Dekoratif unsurlar -->
                    <div class="bbm-about__badge" aria-hidden="true">
                        <span class="bbm-about__badge-year"><?php echo date( 'Y' ) - 2016; ?>+</span>
                        <span class="bbm-about__badge-text"><?php _e( 'Yıldırı Burada', 'bitebimuv-dernek' ); ?></span>
                    </div>
                    <div class="bbm-about__dots" aria-hidden="true"></div>
                </div>
            </div>

            <!-- Metin alanı -->
            <div class="bbm-about__content" data-aos="fade-left">
                <span class="bbm-section-badge"><?php _e( 'Kim Biz?', 'bitebimuv-dernek' ); ?></span>
                <h2 class="bbm-section-title"><?php echo esc_html( $about_title ); ?></h2>
                <p class="bbm-about__text"><?php echo nl2br( esc_html( $about_text ) ); ?></p>

                <div class="bbm-about__features">
                    <?php foreach ( $features as [ $emoji, $title, $desc ] ) : ?>
                    <div class="bbm-about__feature">
                        <span class="bbm-about__feature-icon" aria-hidden="true"><?php echo $emoji; ?></span>
                        <div class="bbm-about__feature-content">
                            <strong><?php echo esc_html( $title ); ?></strong>
                            <p><?php echo esc_html( $desc ); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="bbm-about__actions">
                    <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'hakkimizda' ) ) ?: home_url( '/hakkimizda' ) ); ?>" class="bbm-btn bbm-btn--primary">
                        <?php _e( 'Daha Fazla Bilgi', 'bitebimuv-dernek' ); ?>
                    </a>
                    <a href="#iletisim" class="bbm-btn bbm-btn--outline"><?php _e( 'İletişime Geç', 'bitebimuv-dernek' ); ?></a>
                </div>
            </div>

        </div>
    </div>
</section>
