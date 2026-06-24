<?php
/**
 * Etkinlik Kart İçeriği
 *
 * @package bitebimuv-dernek
 */

$event_id    = get_the_ID();
$ev_date     = get_post_meta( $event_id, '_bbm_event_date', true );
$ev_time     = get_post_meta( $event_id, '_bbm_event_time', true );
$ev_location = get_post_meta( $event_id, '_bbm_event_location', true );
$ev_price    = get_post_meta( $event_id, '_bbm_event_price', true );
$ev_is_online = get_post_meta( $event_id, '_bbm_event_is_online', true );
$ev_has_reg  = get_post_meta( $event_id, '_bbm_event_has_registration', true );
$is_past     = $ev_date && strtotime( $ev_date ) < strtotime( 'today' );
$cats        = get_the_terms( $event_id, 'bbm_event_category' );
?>

<article class="bbm-card bbm-event-card" aria-labelledby="event-title-<?php echo $event_id; ?>">

    <?php if ( has_post_thumbnail() ) : ?>
    <div class="bbm-card__image">
        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php the_post_thumbnail( 'bbm-card', [ 'class' => 'bbm-card__img', 'loading' => 'lazy' ] ); ?>
        </a>
        <?php if ( $ev_date ) : ?>
        <div class="bbm-event-card__date-badge" aria-hidden="true">
            <span class="bbm-event-card__date-day"><?php echo esc_html( bbm_get_event_date_formatted( $ev_date, 'day' ) ); ?></span>
            <span class="bbm-event-card__date-month"><?php echo esc_html( bbm_get_event_date_formatted( $ev_date, 'month' ) ); ?></span>
        </div>
        <?php endif; ?>
        <?php if ( $is_past ) : ?>
        <div class="bbm-event-card__overlay-badge bbm-event-card__overlay-badge--past">
            <?php esc_html_e( 'Geçmiş', 'bitebimuv-dernek' ); ?>
        </div>
        <?php elseif ( $ev_is_online ) : ?>
        <div class="bbm-event-card__overlay-badge bbm-event-card__overlay-badge--online">
            <?php esc_html_e( 'Online', 'bitebimuv-dernek' ); ?>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="bbm-card__body">

        <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
        <div class="bbm-card__cats">
            <?php foreach ( $cats as $cat ) : ?>
            <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="bbm-tag"><?php echo esc_html( $cat->name ); ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h3 class="bbm-card__title" id="event-title-<?php echo $event_id; ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="bbm-event-card__info">
            <?php if ( $ev_date ) : ?>
            <span class="bbm-event-card__info-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <?php echo esc_html( bbm_get_event_date_formatted( $ev_date, 'medium' ) ); ?>
                <?php if ( $ev_time ) echo ' ' . esc_html( $ev_time ); ?>
            </span>
            <?php endif; ?>
            <?php if ( $ev_location ) : ?>
            <span class="bbm-event-card__info-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html( $ev_location ); ?>
            </span>
            <?php endif; ?>
        </div>

        <?php if ( has_excerpt() ) : ?>
        <p class="bbm-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
        <?php endif; ?>

    </div>

    <div class="bbm-card__footer">
        <?php
        $price_label = '';
        if ( $ev_price === '0' || strtolower( (string)$ev_price ) === 'ücretsiz' || strtolower( (string)$ev_price ) === 'free' ) {
            $price_label = '<span class="bbm-badge bbm-badge--success">' . esc_html__( 'Ücretsiz', 'bitebimuv-dernek' ) . '</span>';
        } elseif ( $ev_price ) {
            $price_label = '<span class="bbm-badge bbm-badge--neutral">' . esc_html( $ev_price ) . '</span>';
        }
        echo $price_label;
        ?>

        <a href="<?php the_permalink(); ?>" class="bbm-btn bbm-btn--primary bbm-btn--sm">
            <?php esc_html_e( 'Detaylar', 'bitebimuv-dernek' ); ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>

        <?php if ( $ev_has_reg && ! $is_past ) : ?>
        <button class="bbm-btn bbm-btn--outline bbm-btn--sm bbm-event-register-btn"
                data-event-id="<?php echo $event_id; ?>"
                data-event-title="<?php echo esc_attr( get_the_title() ); ?>">
            <?php esc_html_e( 'Kayıt Ol', 'bitebimuv-dernek' ); ?>
        </button>
        <?php endif; ?>
    </div>

</article>
