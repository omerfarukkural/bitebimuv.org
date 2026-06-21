<?php
/**
 * Tek Yazı Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();
?>
<div class="bbm-single container">
    <div class="bbm-single__layout">
        <div class="bbm-single__main">
            <?php bbm_breadcrumb(); ?>
            <?php while ( have_posts() ) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'bbm-single-post' ); ?>>
                <header class="bbm-single-post__header">
                    <?php the_category( ', ' ); ?>
                    <h1 class="bbm-single-post__title"><?php the_title(); ?></h1>
                    <?php bbm_entry_meta(); ?>
                </header>
                <?php if ( has_post_thumbnail() ) : ?>
                <div class="bbm-single-post__thumbnail">
                    <?php the_post_thumbnail( 'bbm-hero' ); ?>
                </div>
                <?php endif; ?>
                <div class="bbm-single-post__content entry-content">
                    <?php
                    the_content();
                    wp_link_pages( [
                        'before' => '<div class="page-links">' . __( 'Sayfalar:', 'bitebimuv-dernek' ),
                        'after'  => '</div>',
                    ] );
                    ?>
                </div>
                <footer class="bbm-single-post__footer">
                    <?php the_tags( '<div class="bbm-tags-list">', ' ', '</div>' ); ?>
                    <?php bbm_share_buttons(); ?>
                </footer>
            </article>
            <?php bbm_post_navigation(); ?>
            <?php if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif; ?>
            <?php endwhile; ?>
        </div>
        <aside class="bbm-single__sidebar">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>
<?php get_footer(); ?>
