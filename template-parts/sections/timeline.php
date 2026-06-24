<?php
/**
 * Tarihçe Zaman Tüneli Bölümü
 *
 * @package bitebimuv-dernek
 */

// Zaman tüneli verilerini özel alanlardan veya sabit verilerden al
$timeline_items = apply_filters( 'bbm_timeline_items', [
    [
        'year'        => get_theme_mod( 'bbm_founded_year', '2010' ),
        'title'       => __( 'Kuruluş', 'bitebimuv-dernek' ),
        'description' => __( 'Derneğimiz, toplumun ihtiyaçlarına cevap vermek amacıyla gönüllü bireyler tarafından kuruldu.', 'bitebimuv-dernek' ),
        'icon'        => '🌱',
        'highlight'   => true,
    ],
    [
        'year'        => intval( get_theme_mod( 'bbm_founded_year', '2010' ) ) + 2,
        'title'       => __( 'İlk Büyük Etkinlik', 'bitebimuv-dernek' ),
        'description' => __( 'Derneğimiz, yüzlerce katılımcının bir araya geldiği ilk büyük etkinliğini düzenledi.', 'bitebimuv-dernek' ),
        'icon'        => '🎉',
        'highlight'   => false,
    ],
    [
        'year'        => intval( get_theme_mod( 'bbm_founded_year', '2010' ) ) + 4,
        'title'       => __( 'Büyüme Dönemi', 'bitebimuv-dernek' ),
        'description' => __( '100 üyeyi aştık ve sosyal sorumluluk projelerimiz hayata geçirilmeye başlandı.', 'bitebimuv-dernek' ),
        'icon'        => '📈',
        'highlight'   => false,
    ],
    [
        'year'        => intval( get_theme_mod( 'bbm_founded_year', '2010' ) ) + 6,
        'title'       => __( 'Uluslararası İşbirlikleri', 'bitebimuv-dernek' ),
        'description' => __( 'Uluslararası sivil toplum kuruluşlarıyla ortaklıklar kurarak projelerimizi genişlettik.', 'bitebimuv-dernek' ),
        'icon'        => '🌍',
        'highlight'   => false,
    ],
    [
        'year'        => intval( get_theme_mod( 'bbm_founded_year', '2010' ) ) + 9,
        'title'       => __( 'Dijital Dönüşüm', 'bitebimuv-dernek' ),
        'description' => __( 'Online etkinlikler ve dijital platformlar aracılığıyla topluluğumuzu daha geniş kitlelere ulaştırdık.', 'bitebimuv-dernek' ),
        'icon'        => '💻',
        'highlight'   => false,
    ],
    [
        'year'        => date( 'Y' ),
        'title'       => __( 'Bugün', 'bitebimuv-dernek' ),
        'description' => sprintf(
            __( '%s+ üyemizle büyümeye devam ediyoruz. Topluma katkı verme yolculuğumuz sürmektedir.', 'bitebimuv-dernek' ),
            get_theme_mod( 'bbm_member_count', '500' )
        ),
        'icon'        => '⭐',
        'highlight'   => true,
    ],
] );

if ( empty( $timeline_items ) ) return;
?>

<section class="bbm-section bbm-timeline-section" id="tarihce" aria-labelledby="bbm-timeline-title">
    <div class="bbm-container">

        <header class="bbm-section-header" data-aos="fade-up">
            <span class="bbm-section-badge">
                <?php echo bbm_get_smiley( 'small', 'bbm-timeline-badge-smiley' ); ?>
                <?php esc_html_e( 'Tarihçemiz', 'bitebimuv-dernek' ); ?>
            </span>
            <h2 id="bbm-timeline-title" class="bbm-section-title">
                <?php esc_html_e( 'Yıllar İçinde', 'bitebimuv-dernek' ); ?>
                <span class="bbm-gradient-text"><?php esc_html_e( 'Büyüyen Bir Topluluk', 'bitebimuv-dernek' ); ?></span>
            </h2>
            <p class="bbm-section-subtitle">
                <?php printf(
                    esc_html__( '%s yılından bu yana toplumun her kesiminden insanla büyüyoruz.', 'bitebimuv-dernek' ),
                    esc_html( get_theme_mod( 'bbm_founded_year', '2010' ) )
                ); ?>
            </p>
        </header>

        <div class="bbm-timeline" role="list">
            <div class="bbm-timeline__line" aria-hidden="true"></div>

            <?php foreach ( $timeline_items as $index => $item ) :
                $is_right = ( $index % 2 === 1 );
            ?>
            <div class="bbm-timeline-item <?php echo $is_right ? 'bbm-timeline-item--right' : 'bbm-timeline-item--left'; ?> <?php echo ! empty( $item['highlight'] ) ? 'bbm-timeline-item--highlight' : ''; ?>"
                 role="listitem"
                 data-aos="<?php echo $is_right ? 'fade-left' : 'fade-right'; ?>"
                 data-aos-delay="<?php echo $index * 100; ?>">

                <div class="bbm-timeline-item__dot" aria-hidden="true">
                    <span class="bbm-timeline-item__icon"><?php echo esc_html( $item['icon'] ); ?></span>
                </div>

                <div class="bbm-timeline-item__content">
                    <div class="bbm-timeline-item__year"><?php echo esc_html( $item['year'] ); ?></div>
                    <h3 class="bbm-timeline-item__title"><?php echo esc_html( $item['title'] ); ?></h3>
                    <p class="bbm-timeline-item__desc"><?php echo esc_html( $item['description'] ); ?></p>
                </div>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
