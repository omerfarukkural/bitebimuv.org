<?php
/**
 * Section: Tanıklıklar / Üye Yorumları
 * Bite Bi Muv Derneği Teması v4.0
 */

$testimonials_q = new WP_Query( [
    'post_type'      => 'bbm_testimonial',
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'meta_query'     => [
        [ 'key' => '_bbm_testimonial_approved', 'value' => '1', 'compare' => '=' ],
    ],
    'orderby'        => 'rand',
] );

if ( ! $testimonials_q->have_posts() ) return;
?>

<section class="bbm-section bbm-testimonials-section" style="background: var(--bbm-surface);" aria-label="<?php esc_attr_e( 'Üye yorumları', 'bitebimuv' ); ?>">
    <div class="bbm-container">
        <div class="bbm-section-header" data-aos="fade-up">
            <span class="bbm-section-badge"><?php _e( 'Yorumlar', 'bitebimuv' ); ?></span>
            <h2 class="bbm-section-title"><?php _e( 'Üyelerimiz Ne Diyor?', 'bitebimuv' ); ?></h2>
            <p class="bbm-section-subtitle"><?php _e( 'Topluluğumuzu deneyimleyenlerin gerçek sözleri.', 'bitebimuv' ); ?></p>
        </div>

        <div class="bbm-testimonials-slider" id="bbm-testimonials-slider" role="list">
            <?php
            $slide_index = 0;
            while ( $testimonials_q->have_posts() ) : $testimonials_q->the_post();
                $author  = get_post_meta( get_the_ID(), '_bbm_testimonial_author', true ) ?: get_the_title();
                $role    = get_post_meta( get_the_ID(), '_bbm_testimonial_role', true );
                $company = get_post_meta( get_the_ID(), '_bbm_testimonial_company', true );
                $rating  = (int) get_post_meta( get_the_ID(), '_bbm_testimonial_rating', true );
                $quote   = get_post_meta( get_the_ID(), '_bbm_testimonial_quote', true ) ?: get_the_excerpt();
                $rating  = max( 1, min( 5, $rating ) );
            ?>
            <div class="bbm-testimonial-card <?php echo $slide_index === 0 ? 'is-active' : ''; ?>"
                 role="listitem"
                 data-aos="fade-up"
                 data-aos-delay="<?php echo $slide_index * 80; ?>"
                 aria-label="<?php echo esc_attr( sprintf( __( '%s yorumu', 'bitebimuv' ), $author ) ); ?>">

                <!-- Quote Icon -->
                <div class="bbm-testimonial-quote-icon" aria-hidden="true">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                </div>

                <!-- Stars -->
                <?php if ( $rating ) : ?>
                <div class="bbm-testimonial-stars" aria-label="<?php echo esc_attr( sprintf( __( '%d yıldız', 'bitebimuv' ), $rating ) ); ?>">
                    <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                    <span class="bbm-star <?php echo $s <= $rating ? 'bbm-star--filled' : ''; ?>" aria-hidden="true">★</span>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>

                <!-- Quote Text -->
                <blockquote class="bbm-testimonial-text">
                    <p><?php echo esc_html( $quote ); ?></p>
                </blockquote>

                <!-- Author -->
                <div class="bbm-testimonial-author">
                    <div class="bbm-testimonial-avatar">
                        <?php if ( has_post_thumbnail() ) :
                            the_post_thumbnail( 'thumbnail', [ 'loading' => 'lazy', 'decoding' => 'async' ] );
                        else : ?>
                            <div class="bbm-testimonial-avatar__placeholder">
                                <?php echo mb_substr( $author, 0, 1, 'UTF-8' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="bbm-testimonial-author__info">
                        <strong class="bbm-testimonial-author__name"><?php echo esc_html( $author ); ?></strong>
                        <?php if ( $role || $company ) : ?>
                        <span class="bbm-testimonial-author__role">
                            <?php echo esc_html( implode( ', ', array_filter( [ $role, $company ] ) ) ); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            $slide_index++;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Slider Dots -->
        <?php if ( $testimonials_q->found_posts > 3 ) : ?>
        <div class="bbm-slider-dots" role="tablist" aria-label="<?php esc_attr_e( 'Yorum slayt navigasyonu', 'bitebimuv' ); ?>">
            <?php for ( $d = 0; $d < ceil( $testimonials_q->found_posts / 3 ); $d++ ) : ?>
            <button class="bbm-slider-dot <?php echo $d === 0 ? 'is-active' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $d === 0 ? 'true' : 'false'; ?>"
                    data-slide="<?php echo $d; ?>"
                    aria-label="<?php echo esc_attr( sprintf( __( 'Slayt %d', 'bitebimuv' ), $d + 1 ) ); ?>">
            </button>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
