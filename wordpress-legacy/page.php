<?php
/**
 * Sayfa Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();
?>
<div class="bbm-page container">
    <?php bbm_breadcrumb(); ?>
    <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'bbm-single-page' ); ?>>
        <header class="bbm-page__header">
            <h1 class="bbm-page__title"><?php the_title(); ?></h1>
        </header>
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="bbm-page__thumbnail">
            <?php the_post_thumbnail( 'bbm-hero' ); ?>
        </div>
        <?php endif; ?>
        <div class="bbm-page__content entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages( [
                'before' => '<div class="page-links">' . __( 'Sayfalar:', 'bitebimuv-dernek' ),
                'after'  => '</div>',
            ] );
            ?>
        </div>
    </article>
    <?php if ( comments_open() || get_comments_number() ) :
        comments_template();
    endif; ?>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
