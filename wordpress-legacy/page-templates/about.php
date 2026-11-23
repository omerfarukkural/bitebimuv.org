<?php
/**
 * Template Name: Hakkımızda Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header(
        get_the_title() ?: __( 'Hakkımızda', 'bitebimuv' ),
        get_the_excerpt() ?: __( 'Biz kimiz, ne yapıyoruz, nereye gidiyoruz.', 'bitebimuv' )
    ); ?>

    <!-- Hikaye Section -->
    <section class="bbm-section bbm-about-story">
        <div class="bbm-container">
            <div class="bbm-about-split">
                <div class="bbm-about-content" data-aos="fade-right">
                    <span class="bbm-section-badge"><?php _e( 'Hikayemiz', 'bitebimuv' ); ?></span>
                    <h2 class="bbm-section-title"><?php _e( 'Biz Kimiz?', 'bitebimuv' ); ?></h2>
                    <div class="bbm-prose">
                        <?php the_content(); ?>
                    </div>
                </div>
                <div class="bbm-about-visual" data-aos="fade-left">
                    <?php if ( has_post_thumbnail() ) :
                        the_post_thumbnail( 'large', [ 'class' => 'bbm-about-img', 'loading' => 'lazy', 'decoding' => 'async' ] );
                    else : ?>
                        <div class="bbm-about-smiley-wrap"><?php echo bbm_get_smiley( 'xlarge' ); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Değerler -->
    <?php
    $values = apply_filters( 'bbm_about_values', [
        [ 'icon' => '🤝', 'title' => 'Dayanışma',     'desc' => 'Birbirimize destek olur, birlikte güçleniriz.' ],
        [ 'icon' => '🌱', 'title' => 'Sürdürülebilirlik', 'desc' => 'Bugünü değil, geleceği düşünerek hareket ederiz.' ],
        [ 'icon' => '💡', 'title' => 'İnovasyon',     'desc' => 'Yeni fikirler üretir, değişimi lideriz.' ],
        [ 'icon' => '🎯', 'title' => 'Odak',          'desc' => 'Belirlediğimiz hedeflere azimle ilerleriz.' ],
        [ 'icon' => '🌍', 'title' => 'Kapsayıcılık',  'desc' => 'Herkese açık, ayrımcılığa kapalı bir yapıyız.' ],
        [ 'icon' => '🔍', 'title' => 'Şeffaflık',     'desc' => 'Her adımımızı açık ve dürüst bir şekilde paylaşırız.' ],
    ] );
    ?>
    <section class="bbm-section bbm-about-values" style="background: var(--bbm-surface);">
        <div class="bbm-container">
            <div class="bbm-section-header" data-aos="fade-up">
                <span class="bbm-section-badge"><?php _e( 'Değerlerimiz', 'bitebimuv' ); ?></span>
                <h2 class="bbm-section-title"><?php _e( 'Neye İnanıyoruz?', 'bitebimuv' ); ?></h2>
            </div>
            <div class="bbm-values-grid">
                <?php foreach ( $values as $i => $val ) : ?>
                <div class="bbm-value-card" data-aos="fade-up" data-aos-delay="<?php echo $i * 60; ?>">
                    <div class="bbm-value-icon"><?php echo $val['icon']; ?></div>
                    <h3><?php echo esc_html( $val['title'] ); ?></h3>
                    <p><?php echo esc_html( $val['desc'] ); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- İstatistikler -->
    <?php get_template_part( 'template-parts/sections/impact' ); ?>

    <!-- Zaman Tüneli -->
    <?php get_template_part( 'template-parts/sections/timeline' ); ?>

    <!-- Yönetim Kurulu (üyelerden) -->
    <?php
    $board = new WP_Query( [
        'post_type'      => 'bbm_member',
        'posts_per_page' => 6,
        'meta_query'     => [
            [ 'key' => '_bbm_member_board', 'value' => '1', 'compare' => '=' ],
        ],
        'meta_key'  => '_bbm_member_order',
        'orderby'   => 'meta_value_num',
        'order'     => 'ASC',
    ] );
    if ( $board->have_posts() ) :
    ?>
    <section class="bbm-section bbm-about-board">
        <div class="bbm-container">
            <div class="bbm-section-header" data-aos="fade-up">
                <span class="bbm-section-badge"><?php _e( 'Ekibimiz', 'bitebimuv' ); ?></span>
                <h2 class="bbm-section-title"><?php _e( 'Yönetim Kurulu', 'bitebimuv' ); ?></h2>
            </div>
            <div class="bbm-members-grid">
                <?php while ( $board->have_posts() ) : $board->the_post();
                    get_template_part( 'template-parts/content/member-card' );
                endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Tanıklıklar -->
    <?php get_template_part( 'template-parts/sections/testimonials' ); ?>

</main>

<?php get_footer();
