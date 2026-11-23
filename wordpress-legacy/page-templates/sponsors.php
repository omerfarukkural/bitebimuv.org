<?php
/**
 * Template Name: Destekçiler Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header(
        get_the_title() ?: __( 'Destekçilerimiz', 'bitebimuv' ),
        get_the_excerpt() ?: __( 'Bize destek olan kurumlar ve bireyler', 'bitebimuv' )
    ); ?>

    <?php
    $levels = [
        'platinum' => [ 'label' => __( '🏅 Platin Destekçiler', 'bitebimuv' ), 'cols' => 3 ],
        'gold'     => [ 'label' => __( '🥇 Altın Destekçiler', 'bitebimuv' ),  'cols' => 4 ],
        'silver'   => [ 'label' => __( '🥈 Gümüş Destekçiler', 'bitebimuv' ), 'cols' => 5 ],
        'bronze'   => [ 'label' => __( '🥉 Bronz Destekçiler', 'bitebimuv' ),  'cols' => 6 ],
        'supporter'=> [ 'label' => __( '💙 Destekçiler', 'bitebimuv' ),         'cols' => 6 ],
    ];

    foreach ( $levels as $level => $info ) :
        $sponsors_q = new WP_Query( [
            'post_type'      => 'bbm_sponsor',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'meta_query'     => [
                [ 'key' => '_bbm_sponsor_level', 'value' => $level, 'compare' => '=' ],
            ],
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ] );
        if ( ! $sponsors_q->have_posts() ) continue;
    ?>
    <section class="bbm-section bbm-sponsors-level">
        <div class="bbm-container">
            <h2 class="bbm-sponsors-level-title"><?php echo $info['label']; ?></h2>
            <div class="bbm-sponsors-grid bbm-sponsors-grid--<?php echo $info['cols']; ?>">
            <?php while ( $sponsors_q->have_posts() ) : $sponsors_q->the_post();
                $website = get_post_meta( get_the_ID(), '_bbm_sponsor_website', true );
                $contact = get_post_meta( get_the_ID(), '_bbm_sponsor_contact', true );
            ?>
            <div class="bbm-sponsor-card" data-aos="fade-up">
                <?php if ( $website ) : ?>
                <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener sponsored" class="bbm-sponsor-card__link" aria-label="<?php echo esc_attr( get_the_title() . ' ' . __( 'web sitesini ziyaret et', 'bitebimuv' ) ); ?>">
                <?php endif; ?>
                    <div class="bbm-sponsor-card__logo">
                        <?php if ( has_post_thumbnail() ) :
                            the_post_thumbnail( 'medium', [ 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'bbm-sponsor-logo-img' ] );
                        else : ?>
                            <span class="bbm-sponsor-name-text"><?php the_title(); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="bbm-sponsor-card__name"><?php the_title(); ?></div>
                <?php if ( $website ) : ?>
                </a>
                <?php endif; ?>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php endforeach; ?>

    <!-- Destekçi Ol CTA -->
    <section class="bbm-section bbm-become-sponsor" style="background: linear-gradient(135deg, var(--bbm-primary), var(--bbm-secondary)); color:#fff; text-align:center;">
        <div class="bbm-container">
            <h2 style="font-size:clamp(1.5rem,3vw,2.5rem); margin-bottom:1rem"><?php _e( 'Siz de Destekçimiz Olun', 'bitebimuv' ); ?></h2>
            <p style="opacity:.9; max-width:600px; margin:0 auto 2rem; font-size:1.1rem"><?php _e( 'Derneğimizin faaliyetlerine destek vererek topluluğumuzu güçlendirin.', 'bitebimuv' ); ?></p>
            <?php
            $contact_email = get_theme_mod( 'bbm_contact_email', '' );
            if ( $contact_email ) :
            ?>
            <a href="mailto:<?php echo esc_attr( $contact_email ); ?>?subject=<?php echo rawurlencode( __( 'Destekçi Olmak İstiyorum', 'bitebimuv' ) ); ?>" class="bbm-btn bbm-btn--white">
                <?php _e( 'İletişime Geçin', 'bitebimuv' ); ?>
            </a>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php get_footer();
