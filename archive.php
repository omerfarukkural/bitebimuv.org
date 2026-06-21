<?php
/**
 * Arşiv Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();
?>
<div class="bbm-archive-page container">
    <header class="bbm-archive-page__header">
        <?php bbm_breadcrumb(); ?>
        <?php bbm_archive_title(); ?>
        <?php the_archive_description( '<div class="bbm-archive-description">', '</div>' ); ?>
    </header>
    <div class="bbm-archive-page__content">
        <?php if ( have_posts() ) :
            if ( is_post_type_archive( 'bbm_event' ) || is_post_type_archive( 'bbm_announcement' ) ) : ?>
            <div class="bbm-events-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php if ( get_post_type() === 'bbm_event' ) :
                        bbm_event_card( get_the_ID() );
                    else :
                        get_template_part( 'template-parts/content/content' );
                    endif; ?>
                <?php endwhile; ?>
            </div>
            <?php else : ?>
            <div class="bbm-posts-grid">
                <?php while ( have_posts() ) : the_post();
                    get_template_part( 'template-parts/content/content' );
                endwhile; ?>
            </div>
            <?php endif; ?>
            <?php bbm_pagination(); ?>
        <?php else :
            get_template_part( 'template-parts/content/content', 'none' );
        endif; ?>
    </div>
</div>
<?php get_footer(); ?>
