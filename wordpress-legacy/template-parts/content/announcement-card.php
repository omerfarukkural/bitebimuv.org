<?php
/**
 * Duyuru Kart İçeriği
 *
 * @package bitebimuv-dernek
 */

$post_id = get_the_ID();
$type    = get_the_terms( $post_id, 'bbm_announcement_type' );
$type_name = $type && ! is_wp_error( $type ) ? $type[0]->name : '';

$icons = [
    'duyuru'    => '📢',
    'uyari'     => '⚠️',
    'bilgi'     => 'ℹ️',
    'basari'    => '✅',
    'haber'     => '📰',
];

$icon = $icons[ $type ? $type[0]->slug : '' ] ?? '📢';
?>

<div class="bbm-announcement-card" role="listitem">
    <div class="bbm-announcement-card__icon" aria-hidden="true"><?php echo $icon; ?></div>
    <div class="bbm-announcement-card__body">
        <?php if ( $type_name ) : ?>
        <span class="bbm-announcement-card__type"><?php echo esc_html( $type_name ); ?></span>
        <?php endif; ?>
        <h3 class="bbm-announcement-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <p class="bbm-announcement-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
        <div class="bbm-announcement-card__meta">
            <time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>"><?php echo get_the_date(); ?></time>
        </div>
    </div>
</div>
