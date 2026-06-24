<?php
/**
 * Etkinlikler Arşiv Sayfası
 *
 * @package bitebimuv-dernek
 */

get_header();

// Gelecek / geçmiş filtre
$filter  = isset( $_GET['filtre'] ) ? sanitize_text_field( $_GET['filtre'] ) : 'gelecek';
$is_past = $filter === 'gecmis';

$meta_compare = $is_past ? '<' : '>=';
$order        = $is_past ? 'DESC' : 'ASC';

$paged = max( 1, get_query_var( 'paged' ) );

// Kategori filtresi
$cat_slug = isset( $_GET['kategori'] ) ? sanitize_text_field( $_GET['kategori'] ) : '';
$cats     = get_terms( [ 'taxonomy' => 'bbm_event_category', 'hide_empty' => true ] );

$query_args = [
    'post_type'      => 'bbm_event',
    'posts_per_page' => 9,
    'paged'          => $paged,
    'meta_key'       => '_bbm_event_date',
    'orderby'        => 'meta_value',
    'order'          => $order,
    'meta_query'     => [ [
        'key'     => '_bbm_event_date',
        'value'   => date( 'Y-m-d' ),
        'compare' => $meta_compare,
        'type'    => 'DATE',
    ] ],
];

if ( $cat_slug && ! is_wp_error( $cats ) ) {
    $query_args['tax_query'] = [ [
        'taxonomy' => 'bbm_event_category',
        'field'    => 'slug',
        'terms'    => $cat_slug,
    ] ];
}

$events_query = new WP_Query( $query_args );
?>

<div class="bbm-page-header">
    <div class="bbm-container">
        <?php bbm_breadcrumb(); ?>
        <div class="bbm-page-header__content">
            <span class="bbm-page-header__badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <?php esc_html_e( 'Etkinlikler', 'bitebimuv-dernek' ); ?>
            </span>
            <h1 class="bbm-page-header__title">
                <?php esc_html_e( 'Tüm Etkinlikler', 'bitebimuv-dernek' ); ?>
            </h1>
            <p class="bbm-page-header__subtitle">
                <?php esc_html_e( 'Derneğimizin düzenlediği etkinliklere göz atın ve topluluğumuza katılın.', 'bitebimuv-dernek' ); ?>
            </p>
        </div>
    </div>
</div>

<section class="bbm-section" aria-labelledby="bbm-events-archive-title">
    <div class="bbm-container">

        <!-- Filtreler -->
        <div class="bbm-archive-filters">
            <div class="bbm-filter-bar bbm-filter-bar--time">
                <a href="<?php echo esc_url( add_query_arg( 'filtre', 'gelecek', get_post_type_archive_link( 'bbm_event' ) ) ); ?>"
                   class="bbm-filter-btn <?php echo ! $is_past ? 'is-active' : ''; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    <?php esc_html_e( 'Yaklaşan', 'bitebimuv-dernek' ); ?>
                </a>
                <a href="<?php echo esc_url( add_query_arg( 'filtre', 'gecmis', get_post_type_archive_link( 'bbm_event' ) ) ); ?>"
                   class="bbm-filter-btn <?php echo $is_past ? 'is-active' : ''; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <?php esc_html_e( 'Geçmiş', 'bitebimuv-dernek' ); ?>
                </a>
            </div>

            <?php if ( ! is_wp_error( $cats ) && ! empty( $cats ) ) : ?>
            <div class="bbm-filter-bar bbm-filter-bar--cats">
                <a href="<?php echo esc_url( add_query_arg( [ 'filtre' => $filter, 'kategori' => '' ], get_post_type_archive_link( 'bbm_event' ) ) ); ?>"
                   class="bbm-filter-btn <?php echo ! $cat_slug ? 'is-active' : ''; ?>">
                    <?php esc_html_e( 'Tümü', 'bitebimuv-dernek' ); ?>
                </a>
                <?php foreach ( $cats as $cat ) : ?>
                <a href="<?php echo esc_url( add_query_arg( [ 'filtre' => $filter, 'kategori' => $cat->slug ], get_post_type_archive_link( 'bbm_event' ) ) ); ?>"
                   class="bbm-filter-btn <?php echo $cat_slug === $cat->slug ? 'is-active' : ''; ?>">
                    <?php echo esc_html( $cat->name ); ?>
                    <span class="bbm-filter-btn__count"><?php echo intval( $cat->count ); ?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Etkinlik grid -->
        <?php if ( $events_query->have_posts() ) : ?>
        <div class="bbm-grid bbm-grid--3" id="bbm-events-archive-title">
            <?php while ( $events_query->have_posts() ) : $events_query->the_post();
                get_template_part( 'template-parts/content/event-card' );
            endwhile; wp_reset_postdata(); ?>
        </div>

        <!-- Sayfalama -->
        <div class="bbm-pagination">
            <?php echo paginate_links( [
                'total'     => $events_query->max_num_pages,
                'current'   => $paged,
                'prev_text' => '&larr; ' . __( 'Önceki', 'bitebimuv-dernek' ),
                'next_text' => __( 'Sonraki', 'bitebimuv-dernek' ) . ' &rarr;',
                'type'      => 'list',
            ] ); ?>
        </div>

        <?php else : ?>
        <div class="bbm-empty-state" data-aos="fade-up">
            <?php echo bbm_get_smiley( 'medium', 'bbm-empty-events-smiley' ); ?>
            <h2><?php $is_past ? esc_html_e( 'Geçmiş etkinlik bulunamadı.', 'bitebimuv-dernek' ) : esc_html_e( 'Yaklaşan etkinlik yok.', 'bitebimuv-dernek' ); ?></h2>
            <p><?php esc_html_e( 'Yakında yeni etkinlikler duyurulacak!', 'bitebimuv-dernek' ); ?></p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bbm-btn bbm-btn--primary">
                <?php esc_html_e( 'Ana Sayfaya Dön', 'bitebimuv-dernek' ); ?>
            </a>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
