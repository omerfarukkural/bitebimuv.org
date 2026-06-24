<?php
/**
 * Haberler / Blog Bölümü
 *
 * @package bitebimuv-dernek
 */

$news_query = new WP_Query( [
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'post_status'    => 'publish',
] );
?>
<section class="bbm-news" id="haberler" aria-label="<?php esc_attr_e( 'Haberler', 'bitebimuv-dernek' ); ?>">
    <div class="container">
        <div class="bbm-section-header">
            <span class="bbm-section-badge"><?php _e( '📰 Blog', 'bitebimuv-dernek' ); ?></span>
            <h2 class="bbm-section-title"><?php _e( 'Son Haberler', 'bitebimuv-dernek' ); ?></h2>
            <p class="bbm-section-subtitle"><?php _e( 'Derneğimizden güncel haberler ve duyurular.', 'bitebimuv-dernek' ); ?></p>
        </div>

        <?php if ( $news_query->have_posts() ) : ?>
        <div class="bbm-news__grid">
            <?php $ni = 0; while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
            <article class="bbm-news-card" data-aos="fade-up" data-aos-delay="<?php echo $ni * 120; ?>">
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="bbm-news-card__img">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'bbm-card' ); ?>
                    </a>
                    <div class="bbm-news-card__category"><?php the_category( ', ' ); ?></div>
                </div>
                <?php endif; ?>
                <div class="bbm-news-card__body">
                    <div class="bbm-news-card__meta">
                        <time datetime="<?php the_date( DATE_W3C ); ?>"><?php the_date( 'd M Y' ); ?></time>
                        <span>&bull;</span>
                        <span><?php the_author(); ?></span>
                    </div>
                    <h3 class="bbm-news-card__title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p class="bbm-news-card__excerpt"><?php the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="bbm-news-card__link">
                        <?php _e( 'Devamını Oku', 'bitebimuv-dernek' ); ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            <?php $ni++; endwhile; wp_reset_postdata(); ?>
        </div>
        <div class="bbm-news__cta">
            <a href="<?php echo get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/haberler' ); ?>" class="bbm-btn bbm-btn--outline">
                <?php _e( 'Tüm Haberleri Gör', 'bitebimuv-dernek' ); ?>
            </a>
        </div>
        <?php else : ?>
        <p class="bbm-news__empty"><?php _e( 'Henüz haber yok. Yakında buradayız!', 'bitebimuv-dernek' ); ?></p>
        <?php endif; ?>
    </div>
</section>
