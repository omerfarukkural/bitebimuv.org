<?php
/**
 * İçerik Şablonu
 *
 * @package bitebimuv-dernek
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'bbm-post-card' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="bbm-post-card__img">
        <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'bbm-card' ); ?>
        </a>
    </div>
    <?php endif; ?>
    <div class="bbm-post-card__body">
        <div class="bbm-post-card__meta">
            <time datetime="<?php the_date( DATE_W3C ); ?>"><?php the_date( 'd M Y' ); ?></time>
            <?php the_category( ' &bull; ' ); ?>
        </div>
        <h2 class="bbm-post-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <p class="bbm-post-card__excerpt"><?php the_excerpt(); ?></p>
        <a href="<?php the_permalink(); ?>" class="bbm-btn bbm-btn--sm bbm-btn--primary">
            <?php _e( 'Devamını Oku', 'bitebimuv-dernek' ); ?>
        </a>
    </div>
</article>
