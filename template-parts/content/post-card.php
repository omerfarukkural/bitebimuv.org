<?php
/**
 * Blog Post Kart İçeriği
 *
 * @package bitebimuv-dernek
 */

$post_id = get_the_ID();
$cats    = get_the_category( $post_id );
?>

<article class="bbm-card bbm-post-card" aria-labelledby="post-title-<?php echo $post_id; ?>">

    <?php if ( has_post_thumbnail() ) : ?>
    <div class="bbm-card__image">
        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php the_post_thumbnail( 'bbm-card', [ 'class' => 'bbm-card__img', 'loading' => 'lazy' ] ); ?>
        </a>
    </div>
    <?php endif; ?>

    <div class="bbm-card__body">
        <?php if ( $cats ) : ?>
        <div class="bbm-card__cats">
            <?php foreach ( array_slice( $cats, 0, 2 ) as $cat ) : ?>
            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="bbm-tag">
                <?php echo esc_html( $cat->name ); ?>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h3 class="bbm-card__title" id="post-title-<?php echo $post_id; ?>">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="bbm-card__meta">
            <span class="bbm-card__meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>"><?php echo get_the_date(); ?></time>
            </span>
            <?php if ( bbm_reading_time( get_the_ID() ) ) : ?>
            <span class="bbm-card__meta-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <?php printf( esc_html__( '%d dk okuma', 'bitebimuv-dernek' ), bbm_reading_time( get_the_ID() ) ); ?>
            </span>
            <?php endif; ?>
        </div>

        <?php if ( has_excerpt() ) : ?>
        <p class="bbm-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
        <?php endif; ?>
    </div>

    <div class="bbm-card__footer">
        <div class="bbm-card__author">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', '', [ 'class' => 'bbm-card__avatar' ] ); ?>
            <span class="bbm-card__author-name"><?php the_author(); ?></span>
        </div>
        <a href="<?php the_permalink(); ?>" class="bbm-btn bbm-btn--ghost bbm-btn--sm">
            <?php esc_html_e( 'Oku', 'bitebimuv-dernek' ); ?>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>

</article>
