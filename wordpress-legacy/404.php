<?php
/**
 * 404 Sayfası - Şaheser Sürüm
 *
 * @package bitebimuv-dernek
 */

get_header();
?>

<section class="bbm-404" aria-labelledby="bbm-404-title">
    <div class="bbm-404__bg" aria-hidden="true">
        <div class="bbm-404__bubble bbm-404__bubble--1"></div>
        <div class="bbm-404__bubble bbm-404__bubble--2"></div>
        <div class="bbm-404__bubble bbm-404__bubble--3"></div>
    </div>
    <div class="bbm-container">
        <div class="bbm-404__content">

            <!-- Üzgün gülümseyen yüz -->
            <div class="bbm-404__smiley" data-aos="zoom-in" aria-hidden="true">
                <?php echo bbm_get_smiley( 'xlarge', 'bbm-404-smiley' ); ?>
            </div>

            <div class="bbm-404__code" data-aos="fade-up" data-aos-delay="100" aria-hidden="true">404</div>

            <h1 id="bbm-404-title" class="bbm-404__title" data-aos="fade-up" data-aos-delay="150">
                <?php esc_html_e( 'Ups! Sayfa Bulunamadı', 'bitebimuv-dernek' ); ?>
            </h1>

            <p class="bbm-404__subtitle" data-aos="fade-up" data-aos-delay="200">
                <?php esc_html_e( 'Aradığınız sayfa taşınmış, silinmiş ya da hiç var olmamış olabilir. Endişelenmeyin, birlikte doğru yolu bulabiliriz!', 'bitebimuv-dernek' ); ?>
            </p>

            <div class="bbm-404__search" data-aos="fade-up" data-aos-delay="250">
                <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="bbm-404__search-form">
                    <label for="bbm-404-search" class="screen-reader-text"><?php esc_html_e( 'Arama', 'bitebimuv-dernek' ); ?></label>
                    <div class="bbm-404__search-field">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20" aria-hidden="true"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="search" id="bbm-404-search" name="s"
                               class="bbm-form-control"
                               placeholder="<?php esc_attr_e( 'Ne arıyordunuz?', 'bitebimuv-dernek' ); ?>"
                               value="<?php echo get_search_query(); ?>">
                    </div>
                    <button type="submit" class="bbm-btn bbm-btn--primary">
                        <?php esc_html_e( 'Ara', 'bitebimuv-dernek' ); ?>
                    </button>
                </form>
            </div>

            <div class="bbm-404__actions" data-aos="fade-up" data-aos-delay="300">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bbm-btn bbm-btn--primary bbm-btn--lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <?php esc_html_e( 'Ana Sayfaya Dön', 'bitebimuv-dernek' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/etkinlikler/' ) ); ?>" class="bbm-btn bbm-btn--outline bbm-btn--lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <?php esc_html_e( 'Etkinliklere Bak', 'bitebimuv-dernek' ); ?>
                </a>
            </div>

            <!-- Son etkinlikler önerisi -->
            <?php
            $recent_events = new WP_Query( [
                'post_type'      => 'bbm_event',
                'posts_per_page' => 3,
                'meta_key'       => '_bbm_event_date',
                'orderby'        => 'meta_value',
                'order'          => 'ASC',
                'meta_query'     => [ [ 'key' => '_bbm_event_date', 'value' => date( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ] ],
            ] );

            if ( $recent_events->have_posts() ) :
            ?>
            <div class="bbm-404__suggestions" data-aos="fade-up" data-aos-delay="350">
                <h2 class="bbm-404__suggestions-title">
                    <?php esc_html_e( 'Yaklaşan Etkinliklerimiz', 'bitebimuv-dernek' ); ?>
                </h2>
                <div class="bbm-404__suggestions-grid">
                    <?php while ( $recent_events->have_posts() ) : $recent_events->the_post();
                        $ev_date = get_post_meta( get_the_ID(), '_bbm_event_date', true );
                    ?>
                    <a href="<?php the_permalink(); ?>" class="bbm-404__suggestion-item">
                        <span class="bbm-404__suggestion-date">
                            <?php echo $ev_date ? esc_html( bbm_get_event_date_formatted( $ev_date, 'short' ) ) : ''; ?>
                        </span>
                        <span class="bbm-404__suggestion-title"><?php the_title(); ?></span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>
