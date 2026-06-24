<?php
/**
 * Section: Destekçiler Carousel
 * Bite Bi Muv Derneği Teması v4.0
 */

$sponsors_q = new WP_Query( [
    'post_type'      => 'bbm_sponsor',
    'post_status'    => 'publish',
    'posts_per_page' => 20,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
] );

if ( ! $sponsors_q->have_posts() ) return;
?>

<section class="bbm-section bbm-sponsors-section" aria-label="<?php esc_attr_e( 'Destekçilerimiz', 'bitebimuv' ); ?>">
    <div class="bbm-container">
        <div class="bbm-section-header" data-aos="fade-up">
            <span class="bbm-section-badge"><?php _e( 'Destekçiler', 'bitebimuv' ); ?></span>
            <h2 class="bbm-section-title"><?php _e( 'Bize Destek Veren Kurumlar', 'bitebimuv' ); ?></h2>
            <p class="bbm-section-subtitle"><?php _e( 'Değerli destekçilerimiz sayesinde daha güçlü bir topluluğuz.', 'bitebimuv' ); ?></p>
        </div>

        <div class="bbm-sponsors-ticker-wrap" data-aos="fade-up">
            <div class="bbm-sponsors-ticker" id="bbm-sponsors-ticker" role="list" aria-label="<?php esc_attr_e( 'Sponsor logoları', 'bitebimuv' ); ?>">
                <?php
                // First pass - original items
                while ( $sponsors_q->have_posts() ) : $sponsors_q->the_post();
                    $website = get_post_meta( get_the_ID(), '_bbm_sponsor_website', true );
                    $level   = get_post_meta( get_the_ID(), '_bbm_sponsor_level', true );
                ?>
                <div class="bbm-sponsors-item" role="listitem" data-level="<?php echo esc_attr( $level ); ?>">
                    <?php if ( $website ) : ?>
                    <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener sponsored"
                       aria-label="<?php echo esc_attr( get_the_title() ); ?>">
                    <?php endif; ?>
                        <?php if ( has_post_thumbnail() ) :
                            the_post_thumbnail( 'medium', [ 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'bbm-sponsor-ticker-logo' ] );
                        else : ?>
                            <span class="bbm-sponsor-ticker-name"><?php the_title(); ?></span>
                        <?php endif; ?>
                    <?php if ( $website ) : ?></a><?php endif; ?>
                </div>
                <?php endwhile;
                wp_reset_postdata();

                // Second pass - duplicate for seamless loop
                $sponsors_q2 = new WP_Query( [
                    'post_type'      => 'bbm_sponsor',
                    'post_status'    => 'publish',
                    'posts_per_page' => 20,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC',
                ] );
                while ( $sponsors_q2->have_posts() ) : $sponsors_q2->the_post();
                    $website = get_post_meta( get_the_ID(), '_bbm_sponsor_website', true );
                ?>
                <div class="bbm-sponsors-item" aria-hidden="true">
                    <?php if ( $website ) : ?><a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener sponsored" tabindex="-1"><?php endif; ?>
                        <?php if ( has_post_thumbnail() ) :
                            the_post_thumbnail( 'medium', [ 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'bbm-sponsor-ticker-logo' ] );
                        else : ?>
                            <span class="bbm-sponsor-ticker-name"><?php the_title(); ?></span>
                        <?php endif; ?>
                    <?php if ( $website ) : ?></a><?php endif; ?>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>

        <div class="bbm-sponsors-footer" data-aos="fade-up">
            <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'destekçilerimiz' ) ) ?: home_url( '/destekçilerimiz/' ) ); ?>"
               class="bbm-btn bbm-btn--ghost">
                <?php _e( 'Tüm Destekçilerimiz', 'bitebimuv' ); ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
