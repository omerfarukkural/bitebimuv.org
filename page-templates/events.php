<?php
/**
 * Template Name: Etkinlikler Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header( get_the_title(), get_the_excerpt() ?: __( 'Tüm etkinliklerimizi keşfedin', 'bitebimuv' ) ); ?>

    <section class="bbm-section bbm-events-page">
        <div class="bbm-container">

            <!-- Filtre Çubuğu -->
            <div class="bbm-events-filter" role="search" aria-label="<?php esc_attr_e( 'Etkinlik filtrele', 'bitebimuv' ); ?>">
                <div class="bbm-events-filter__tabs">
                    <button class="bbm-filter-tab active" data-filter="upcoming"><?php _e( '📅 Yaklaşan', 'bitebimuv' ); ?></button>
                    <button class="bbm-filter-tab" data-filter="past"><?php _e( '📁 Geçmiş', 'bitebimuv' ); ?></button>
                    <button class="bbm-filter-tab" data-filter="all"><?php _e( '🗂 Tümü', 'bitebimuv' ); ?></button>
                </div>
                <?php
                $cats = get_terms( [ 'taxonomy' => 'bbm_event_category', 'hide_empty' => true ] );
                if ( $cats && ! is_wp_error( $cats ) ) :
                ?>
                <div class="bbm-events-filter__cats">
                    <select id="bbm-event-cat-filter" class="bbm-select" aria-label="<?php esc_attr_e( 'Kategori seçin', 'bitebimuv' ); ?>">
                        <option value=""><?php _e( 'Tüm Kategoriler', 'bitebimuv' ); ?></option>
                        <?php foreach ( $cats as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>

                <!-- Görünüm Switcher -->
                <div class="bbm-view-toggle">
                    <button class="bbm-view-btn active" data-view="grid" aria-label="<?php esc_attr_e( 'Izgara görünümü', 'bitebimuv' ); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </button>
                    <button class="bbm-view-btn" data-view="list" aria-label="<?php esc_attr_e( 'Liste görünümü', 'bitebimuv' ); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="5" width="18" height="2" rx="1"/><rect x="3" y="11" width="18" height="2" rx="1"/><rect x="3" y="17" width="18" height="2" rx="1"/></svg>
                    </button>
                    <button class="bbm-view-btn" data-view="calendar" aria-label="<?php esc_attr_e( 'Takvim görünümü', 'bitebimuv' ); ?>">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </button>
                </div>
            </div>

            <!-- Grid Görünümü -->
            <div id="bbm-events-grid" class="bbm-events-grid" data-view="grid">
                <?php
                $today     = current_time( 'Y-m-d' );
                $events_q  = new WP_Query( [
                    'post_type'      => 'bbm_event',
                    'posts_per_page' => 12,
                    'meta_key'       => '_bbm_event_date',
                    'orderby'        => 'meta_value',
                    'order'          => 'ASC',
                    'paged'          => get_query_var( 'paged' ) ?: 1,
                ] );

                if ( $events_q->have_posts() ) :
                    while ( $events_q->have_posts() ) : $events_q->the_post();
                        $edate     = get_post_meta( get_the_ID(), '_bbm_event_date', true );
                        $is_past   = $edate && strtotime( $edate ) < strtotime( $today );
                        $etype     = get_post_meta( get_the_ID(), '_bbm_event_type', true );
                        $elocation = get_post_meta( get_the_ID(), '_bbm_event_location', true );
                        $eprice    = get_post_meta( get_the_ID(), '_bbm_event_price', true );
                        ?>
                        <article class="bbm-event-card <?php echo $is_past ? 'is-past' : 'is-upcoming'; ?>"
                                 data-date="<?php echo esc_attr( $edate ); ?>"
                                 data-status="<?php echo $is_past ? 'past' : 'upcoming'; ?>">
                            <?php if ( has_post_thumbnail() ) : ?>
                            <div class="bbm-event-card__thumb">
                                <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                                    <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
                                </a>
                                <?php if ( $is_past ) : ?>
                                <span class="bbm-event-badge bbm-event-badge--past"><?php _e( 'Geçmiş', 'bitebimuv' ); ?></span>
                                <?php else : ?>
                                <?php if ( $edate ) :
                                    $days_left = (int) ceil( ( strtotime( $edate ) - time() ) / 86400 );
                                    if ( $days_left <= 7 && $days_left >= 0 ) :
                                ?>
                                <span class="bbm-event-badge bbm-event-badge--soon"><?php echo $days_left === 0 ? __( 'Bugün!', 'bitebimuv' ) : sprintf( _n( '%d gün kaldı', '%d gün kaldı', $days_left, 'bitebimuv' ), $days_left ); ?></span>
                                <?php endif; endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>

                            <div class="bbm-event-card__body">
                                <?php if ( $edate ) : ?>
                                <div class="bbm-event-card__date">
                                    <span class="bbm-event-card__day"><?php echo date_i18n( 'j', strtotime( $edate ) ); ?></span>
                                    <span class="bbm-event-card__month"><?php echo date_i18n( 'M', strtotime( $edate ) ); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="bbm-event-card__content">
                                    <h3 class="bbm-event-card__title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <div class="bbm-event-card__meta">
                                        <?php if ( $elocation ) : ?>
                                        <span><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg> <?php echo esc_html( $elocation ); ?></span>
                                        <?php endif; ?>
                                        <?php if ( $etype === 'online' ) : ?>
                                        <span>🖥 Online</span>
                                        <?php endif; ?>
                                        <?php if ( $eprice ) : ?>
                                        <span>💰 <?php echo esc_html( $eprice === '0' || strtolower( $eprice ) === 'ücretsiz' ? __( 'Ücretsiz', 'bitebimuv' ) : $eprice . ' ₺' ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; wp_reset_postdata();

                    echo '<div class="bbm-pagination">';
                    echo paginate_links( [ 'total' => $events_q->max_num_pages, 'prev_text' => '← ' . __( 'Önceki', 'bitebimuv' ), 'next_text' => __( 'Sonraki', 'bitebimuv' ) . ' →' ] );
                    echo '</div>';
                else : ?>
                    <div class="bbm-empty-state">
                        <div class="bbm-empty-state__icon"><?php echo bbm_get_smiley( 'medium' ); ?></div>
                        <h3><?php _e( 'Henüz etkinlik yok', 'bitebimuv' ); ?></h3>
                        <p><?php _e( 'Yakında etkinlikler eklenecek. Takipte kalın!', 'bitebimuv' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Takvim Görünümü -->
            <div id="bbm-events-calendar" class="bbm-events-calendar" hidden aria-label="<?php esc_attr_e( 'Etkinlik takvimi', 'bitebimuv' ); ?>">
                <div class="bbm-cal-nav">
                    <button class="bbm-cal-btn" id="bbm-cal-prev" aria-label="<?php esc_attr_e( 'Önceki ay', 'bitebimuv' ); ?>">&#8592;</button>
                    <h2 class="bbm-cal-title" id="bbm-cal-title"></h2>
                    <button class="bbm-cal-btn" id="bbm-cal-next" aria-label="<?php esc_attr_e( 'Sonraki ay', 'bitebimuv' ); ?>">&#8594;</button>
                </div>
                <div class="bbm-cal-grid" id="bbm-cal-grid" role="grid"></div>
                <div class="bbm-cal-legend">
                    <span class="bbm-cal-dot bbm-cal-dot--event"></span> <?php _e( 'Etkinlik var', 'bitebimuv' ); ?>
                    <span class="bbm-cal-dot bbm-cal-dot--today"></span> <?php _e( 'Bugün', 'bitebimuv' ); ?>
                </div>
            </div>

        </div>
    </section>

</main>

<?php
// Pass event data to JS
$all_events = get_posts( [ 'post_type' => 'bbm_event', 'posts_per_page' => -1, 'meta_key' => '_bbm_event_date', 'orderby' => 'meta_value', 'order' => 'ASC' ] );
$event_data = [];
foreach ( $all_events as $ev ) {
    $d = get_post_meta( $ev->ID, '_bbm_event_date', true );
    if ( $d ) {
        $event_data[] = [
            'id'    => $ev->ID,
            'title' => $ev->post_title,
            'date'  => $d,
            'url'   => get_permalink( $ev ),
            'type'  => get_post_meta( $ev->ID, '_bbm_event_type', true ),
        ];
    }
}
echo '<script id="bbm-events-data" type="application/json">' . wp_json_encode( $event_data, JSON_UNESCAPED_UNICODE ) . '</script>';

wp_enqueue_script( 'bbm-calendar', get_template_directory_uri() . '/assets/js/calendar.js', [], BBM_VERSION, true );

get_footer();
