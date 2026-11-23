<?php
/**
 * Şablon Etiketleri
 *
 * @package bitebimuv-dernek
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * İleri / Geri navigasyon
 */
function bbm_post_navigation() {
    the_post_navigation( [
        'prev_text' => '<span class="bbm-nav-label">' . __( 'Önceki', 'bitebimuv-dernek' ) . '</span><span class="bbm-nav-title">%title</span>',
        'next_text' => '<span class="bbm-nav-label">' . __( 'Sonraki', 'bitebimuv-dernek' ) . '</span><span class="bbm-nav-title">%title</span>',
    ] );
}

/**
 * Yazar ve meta bilgileri
 */
function bbm_posted_on() {
    $time = sprintf(
        '<time class="bbm-entry-date" datetime="%1$s">%2$s</time>',
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date( 'd M Y' ) )
    );
    echo '<span class="bbm-posted-on">' . $time . '</span>';
}

function bbm_posted_by() {
    printf(
        '<span class="bbm-byline"><a href="%s" rel="author">%s</a></span>',
        esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
        esc_html( get_the_author() )
    );
}

function bbm_entry_meta() {
    ?>
    <div class="bbm-entry-meta">
        <?php bbm_posted_on(); ?>
        <?php bbm_posted_by(); ?>
        <?php if ( has_category() ) : ?>
        <span class="bbm-category">
            <?php the_category( ', ' ); ?>
        </span>
        <?php endif; ?>
        <?php if ( has_tag() ) : ?>
        <span class="bbm-tags">
            <?php the_tags( '', ', ', '' ); ?>
        </span>
        <?php endif; ?>
        <?php if ( comments_open() ) : ?>
        <span class="bbm-comment-count">
            <?php comments_popup_link( __( '0 Yorum', 'bitebimuv-dernek' ), __( '1 Yorum', 'bitebimuv-dernek' ), __( '% Yorum', 'bitebimuv-dernek' ) ); ?>
        </span>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Sayfalama
 */
function bbm_pagination() {
    the_posts_pagination( [
        'prev_text' => '&larr; ' . __( 'Önceki', 'bitebimuv-dernek' ),
        'next_text' => __( 'Sonraki', 'bitebimuv-dernek' ) . ' &rarr;',
        'class'     => 'bbm-pagination',
    ] );
}

/**
 * Kategori veya arşiv başlığı
 */
function bbm_archive_title() {
    if ( is_category() ) {
        printf( '<h1 class="bbm-archive-title">%s</h1>', single_cat_title( '', false ) );
    } elseif ( is_tag() ) {
        printf( '<h1 class="bbm-archive-title">' . __( 'Etiket: %s', 'bitebimuv-dernek' ) . '</h1>', single_tag_title( '', false ) );
    } elseif ( is_post_type_archive() ) {
        printf( '<h1 class="bbm-archive-title">%s</h1>', post_type_archive_title( '', false ) );
    } elseif ( is_date() ) {
        printf( '<h1 class="bbm-archive-title">%s</h1>', get_the_date( 'F Y' ) );
    } elseif ( is_author() ) {
        printf( '<h1 class="bbm-archive-title">' . __( 'Yazar: %s', 'bitebimuv-dernek' ) . '</h1>', get_the_author() );
    } else {
        _e( '<h1 class="bbm-archive-title">Arşiv</h1>', 'bitebimuv-dernek' );
    }
}

/**
 * Etkinlik kartı
 */
function bbm_event_card( $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $date     = get_post_meta( $post_id, 'bbm_event_date', true );
    $time     = get_post_meta( $post_id, 'bbm_event_time', true );
    $location = get_post_meta( $post_id, 'bbm_event_location', true );
    $price    = get_post_meta( $post_id, 'bbm_event_price', true );
    $is_free  = ( $price === '0' || strtolower( $price ) === 'ücretsiz' || empty( $price ) );
    ?>
    <article class="bbm-event-card">
        <?php if ( has_post_thumbnail( $post_id ) ) : ?>
        <div class="bbm-event-card__image">
            <a href="<?php echo get_permalink( $post_id ); ?>">
                <?php echo get_the_post_thumbnail( $post_id, 'bbm-card' ); ?>
            </a>
            <span class="bbm-event-card__badge bbm-badge--<?php echo $is_free ? 'free' : 'paid'; ?>">
                <?php echo $is_free ? __( 'Ücretsiz', 'bitebimuv-dernek' ) : esc_html( $price ); ?>
            </span>
        </div>
        <?php endif; ?>
        <div class="bbm-event-card__body">
            <?php if ( $date ) : ?>
            <div class="bbm-event-card__date">
                <span class="bbm-event-card__day"><?php echo date( 'd', strtotime( $date ) ); ?></span>
                <span class="bbm-event-card__month"><?php echo bbm_get_event_date_formatted( $post_id, 'M Y' ); ?></span>
            </div>
            <?php endif; ?>
            <div class="bbm-event-card__content">
                <h3 class="bbm-event-card__title">
                    <a href="<?php echo get_permalink( $post_id ); ?>"><?php echo get_the_title( $post_id ); ?></a>
                </h3>
                <?php if ( $location ) : ?>
                <p class="bbm-event-card__location">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?php echo esc_html( $location ); ?>
                </p>
                <?php endif; ?>
                <?php if ( $time ) : ?>
                <p class="bbm-event-card__time">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?php echo esc_html( $time ); ?>
                </p>
                <?php endif; ?>
                <a href="<?php echo get_permalink( $post_id ); ?>" class="bbm-btn bbm-btn--sm bbm-btn--outline">
                    <?php _e( 'Detaylar', 'bitebimuv-dernek' ); ?>
                </a>
            </div>
        </div>
    </article>
    <?php
}
