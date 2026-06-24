<?php
/**
 * Etkinlik Detay Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();

while ( have_posts() ) : the_post();
    $event_id       = get_the_ID();
    $ev_date        = get_post_meta( $event_id, '_bbm_event_date', true );
    $ev_time        = get_post_meta( $event_id, '_bbm_event_time', true );
    $ev_end_date    = get_post_meta( $event_id, '_bbm_event_end_date', true );
    $ev_location    = get_post_meta( $event_id, '_bbm_event_location', true );
    $ev_address     = get_post_meta( $event_id, '_bbm_event_address', true );
    $ev_maps_url    = get_post_meta( $event_id, '_bbm_event_maps_url', true );
    $ev_capacity    = get_post_meta( $event_id, '_bbm_event_capacity', true );
    $ev_price       = get_post_meta( $event_id, '_bbm_event_price', true );
    $ev_organizer   = get_post_meta( $event_id, '_bbm_event_organizer', true );
    $ev_contact     = get_post_meta( $event_id, '_bbm_event_contact', true );
    $ev_is_online   = get_post_meta( $event_id, '_bbm_event_is_online', true );
    $ev_online_url  = get_post_meta( $event_id, '_bbm_event_online_url', true );
    $ev_has_reg     = get_post_meta( $event_id, '_bbm_event_has_registration', true );
    $registrations  = get_post_meta( $event_id, '_bbm_registrations', true );
    $reg_count      = is_array( $registrations ) ? count( $registrations ) : 0;
    $is_past        = $ev_date && strtotime( $ev_date ) < strtotime( 'today' );
    $is_full        = $ev_capacity && $reg_count >= intval( $ev_capacity );
    $cats           = get_the_terms( $event_id, 'bbm_event_category' );
?>

<!-- Breadcrumb -->
<div class="bbm-page-header bbm-page-header--event <?php echo $is_past ? 'bbm-page-header--past' : ''; ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="bbm-page-header__bg-img" style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'bbm-wide' ) ); ?>');" aria-hidden="true"></div>
    <?php else : ?>
    <div class="bbm-page-header__bg" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="bbm-container">
        <?php bbm_breadcrumb(); ?>
        <div class="bbm-page-header__content">
            <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
            <div class="bbm-event-cats">
                <?php foreach ( $cats as $cat ) : ?>
                <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="bbm-tag"><?php echo esc_html( $cat->name ); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <h1 class="bbm-page-header__title"><?php the_title(); ?></h1>
            <?php if ( $ev_date ) : ?>
            <div class="bbm-event-single__meta-hero">
                <span class="bbm-event-single__date-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <?php echo esc_html( bbm_get_event_date_formatted( $ev_date, 'full' ) ); ?>
                    <?php if ( $ev_time ) echo ' ' . esc_html( $ev_time ); ?>
                </span>
                <?php if ( $ev_location ) : ?>
                <span class="bbm-event-single__loc-badge">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo esc_html( $ev_location ); ?>
                </span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<article class="bbm-section bbm-event-single" id="etkinlik-<?php echo $event_id; ?>">
    <div class="bbm-container">
        <div class="bbm-event-single__layout">

            <!-- Ana içerik -->
            <div class="bbm-event-single__main">

                <!-- Etkinlik görseli -->
                <?php if ( has_post_thumbnail() ) : ?>
                <figure class="bbm-event-single__thumbnail">
                    <?php the_post_thumbnail( 'bbm-wide', [ 'loading' => 'eager', 'class' => 'bbm-event-single__img' ] ); ?>
                </figure>
                <?php endif; ?>

                <!-- Etkinlik içeriği -->
                <div class="bbm-prose bbm-event-single__content">
                    <?php the_content(); ?>
                </div>

                <!-- Paylaş butonları -->
                <div class="bbm-event-single__share">
                    <strong><?php esc_html_e( 'Paylaş:', 'bitebimuv-dernek' ); ?></strong>
                    <?php echo bbm_share_buttons(); ?>
                </div>

            </div>

            <!-- Kenar çubuğu: Etkinlik bilgileri -->
            <aside class="bbm-event-single__sidebar" aria-label="<?php esc_attr_e( 'Etkinlik bilgileri', 'bitebimuv-dernek' ); ?>">

                <!-- Kayıt / Durum kartı -->
                <div class="bbm-event-single__card bbm-event-single__card--action">
                    <?php if ( $is_past ) : ?>
                        <div class="bbm-event-single__status bbm-event-single__status--past">
                            <?php echo bbm_get_smiley( 'small', 'bbm-past-event-smiley' ); ?>
                            <p><?php esc_html_e( 'Bu etkinlik sona ermiştir.', 'bitebimuv-dernek' ); ?></p>
                        </div>
                    <?php elseif ( $ev_has_reg ) : ?>
                        <?php if ( $is_full ) : ?>
                        <div class="bbm-badge bbm-badge--warning bbm-event-single__capacity-badge">
                            <?php printf( esc_html__( 'Kontenjan doldu (%d/%d)', 'bitebimuv-dernek' ), $reg_count, intval( $ev_capacity ) ); ?>
                        </div>
                        <?php else : ?>
                        <?php if ( $ev_capacity ) : ?>
                        <div class="bbm-event-single__capacity">
                            <div class="bbm-event-single__capacity-bar">
                                <div class="bbm-event-single__capacity-fill"
                                     style="width:<?php echo min( 100, round( ( $reg_count / intval( $ev_capacity ) ) * 100 ) ); ?>%"></div>
                            </div>
                            <small><?php printf( esc_html__( '%d/%d kişi kayıtlı', 'bitebimuv-dernek' ), $reg_count, intval( $ev_capacity ) ); ?></small>
                        </div>
                        <?php endif; ?>
                        <button class="bbm-btn bbm-btn--primary bbm-btn--full bbm-btn--lg bbm-event-register-btn"
                                data-event-id="<?php echo $event_id; ?>"
                                data-event-title="<?php echo esc_attr( get_the_title() ); ?>">
                            <?php esc_html_e( 'Etkinliğe Kayıt Ol', 'bitebimuv-dernek' ); ?>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                        <?php endif; ?>
                    <?php elseif ( $ev_is_online && $ev_online_url ) : ?>
                    <a href="<?php echo esc_url( $ev_online_url ); ?>"
                       class="bbm-btn bbm-btn--primary bbm-btn--full bbm-btn--lg"
                       target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e( 'Online Katıl', 'bitebimuv-dernek' ); ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M15 3h6v6M10 14L21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                    </a>
                    <?php else : ?>
                    <p class="bbm-event-single__open-text">
                        <?php echo bbm_get_smiley( 'small', 'bbm-open-event-smiley' ); ?>
                        <?php esc_html_e( 'Bu etkinlik herkese açıktır, kayıt gerekmez.', 'bitebimuv-dernek' ); ?>
                    </p>
                    <?php endif; ?>
                </div>

                <!-- Bilgi kartı -->
                <div class="bbm-event-single__card bbm-event-single__card--info">
                    <h2 class="bbm-event-single__card-title"><?php esc_html_e( 'Etkinlik Bilgileri', 'bitebimuv-dernek' ); ?></h2>
                    <dl class="bbm-event-single__info-list">

                        <?php if ( $ev_date ) : ?>
                        <div class="bbm-event-single__info-item">
                            <dt>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                                <?php esc_html_e( 'Tarih', 'bitebimuv-dernek' ); ?>
                            </dt>
                            <dd>
                                <?php echo esc_html( bbm_get_event_date_formatted( $ev_date, 'full' ) ); ?>
                                <?php if ( $ev_end_date && $ev_end_date !== $ev_date ) : ?>
                                <br><small><?php printf( esc_html__( '– %s', 'bitebimuv-dernek' ), esc_html( bbm_get_event_date_formatted( $ev_end_date, 'full' ) ) ); ?></small>
                                <?php endif; ?>
                            </dd>
                        </div>
                        <?php endif; ?>

                        <?php if ( $ev_time ) : ?>
                        <div class="bbm-event-single__info-item">
                            <dt>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <?php esc_html_e( 'Saat', 'bitebimuv-dernek' ); ?>
                            </dt>
                            <dd><?php echo esc_html( $ev_time ); ?></dd>
                        </div>
                        <?php endif; ?>

                        <?php if ( $ev_location ) : ?>
                        <div class="bbm-event-single__info-item">
                            <dt>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <?php esc_html_e( 'Mekan', 'bitebimuv-dernek' ); ?>
                            </dt>
                            <dd>
                                <?php echo esc_html( $ev_location ); ?>
                                <?php if ( $ev_address ) : ?><br><small><?php echo esc_html( $ev_address ); ?></small><?php endif; ?>
                                <?php if ( $ev_maps_url ) : ?>
                                <br><a href="<?php echo esc_url( $ev_maps_url ); ?>" class="bbm-link" target="_blank" rel="noopener noreferrer">
                                    <?php esc_html_e( 'Haritada Gör', 'bitebimuv-dernek' ); ?>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="12" height="12" aria-hidden="true"><path d="M15 3h6v6M10 14L21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                </a>
                                <?php endif; ?>
                            </dd>
                        </div>
                        <?php endif; ?>

                        <?php if ( $ev_is_online ) : ?>
                        <div class="bbm-event-single__info-item">
                            <dt>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><rect x="3" y="3" width="18" height="12" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                                <?php esc_html_e( 'Tür', 'bitebimuv-dernek' ); ?>
                            </dt>
                            <dd><span class="bbm-badge bbm-badge--info"><?php esc_html_e( 'Online', 'bitebimuv-dernek' ); ?></span></dd>
                        </div>
                        <?php endif; ?>

                        <?php if ( $ev_price !== '' ) : ?>
                        <div class="bbm-event-single__info-item">
                            <dt>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                <?php esc_html_e( 'Ücret', 'bitebimuv-dernek' ); ?>
                            </dt>
                            <dd>
                                <?php echo $ev_price == '0' || strtolower($ev_price) === 'ücretsiz' || strtolower($ev_price) === 'free'
                                    ? '<span class="bbm-badge bbm-badge--success">' . esc_html__( 'Ücretsiz', 'bitebimuv-dernek' ) . '</span>'
                                    : esc_html( $ev_price ); ?>
                            </dd>
                        </div>
                        <?php endif; ?>

                        <?php if ( $ev_organizer ) : ?>
                        <div class="bbm-event-single__info-item">
                            <dt>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                <?php esc_html_e( 'Organizatör', 'bitebimuv-dernek' ); ?>
                            </dt>
                            <dd><?php echo esc_html( $ev_organizer ); ?></dd>
                        </div>
                        <?php endif; ?>

                        <?php if ( $ev_contact ) : ?>
                        <div class="bbm-event-single__info-item">
                            <dt>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                <?php esc_html_e( 'İletişim', 'bitebimuv-dernek' ); ?>
                            </dt>
                            <dd><?php echo esc_html( $ev_contact ); ?></dd>
                        </div>
                        <?php endif; ?>

                    </dl>
                </div>

                <!-- Takvime ekle -->
                <?php if ( $ev_date && ! $is_past ) : ?>
                <div class="bbm-event-single__card bbm-event-single__card--cal">
                    <h3 class="bbm-event-single__card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <?php esc_html_e( 'Takvime Ekle', 'bitebimuv-dernek' ); ?>
                    </h3>
                    <?php
                    $gcal_url = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                        . '&text=' . urlencode( get_the_title() )
                        . '&dates=' . urlencode( str_replace( '-', '', $ev_date ) . ($ev_time ? 'T' . str_replace( ':', '', $ev_time ) . '00' : '') )
                        . '/' . urlencode( str_replace( '-', '', $ev_end_date ?: $ev_date ) . ($ev_time ? 'T' . str_replace( ':', '', $ev_time ) . '00' : '') )
                        . '&details=' . urlencode( get_the_excerpt() ?: get_the_title() )
                        . '&location=' . urlencode( $ev_address ?: $ev_location );
                    ?>
                    <a href="<?php echo esc_url( $gcal_url ); ?>" class="bbm-btn bbm-btn--outline bbm-btn--sm bbm-btn--full"
                       target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" fill="#4285f4"/><rect x="3" y="10" width="18" height="2" fill="#34a853"/><path d="M8 2v4M16 2v4" stroke="#fff" stroke-width="2"/></svg>
                        <?php esc_html_e( 'Google Takvim', 'bitebimuv-dernek' ); ?>
                    </a>
                </div>
                <?php endif; ?>

            </aside>
        </div>
    </div>
</article>

<?php endwhile; ?>

<!-- İlgili etkinlikler -->
<?php
$related = new WP_Query( [
    'post_type'      => 'bbm_event',
    'posts_per_page' => 3,
    'post__not_in'   => [ get_the_ID() ],
    'meta_key'       => '_bbm_event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
    'meta_query'     => [ [ 'key' => '_bbm_event_date', 'value' => date( 'Y-m-d' ), 'compare' => '>=', 'type' => 'DATE' ] ],
] );

if ( $related->have_posts() ) :
?>
<section class="bbm-section bbm-section--alt bbm-related-events" aria-labelledby="bbm-related-title">
    <div class="bbm-container">
        <h2 id="bbm-related-title" class="bbm-section-title"><?php esc_html_e( 'Diğer Etkinlikler', 'bitebimuv-dernek' ); ?></h2>
        <div class="bbm-grid bbm-grid--3">
            <?php while ( $related->have_posts() ) : $related->the_post();
                get_template_part( 'template-parts/content/event-card' );
            endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
