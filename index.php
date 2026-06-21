<?php
/**
 * Ana Şablon (Fallback)
 *
 * @package bitebimuv-dernek
 */

get_header();
?>
<div class="bbm-archive-page container">
    <div class="bbm-archive-page__header">
        <?php bbm_breadcrumb(); ?>
        <?php
        if ( is_home() && ! is_front_page() ) :
            echo '<h1 class="bbm-archive-page__title">' . single_post_title() . '</h1>';
        else :
            bbm_archive_title();
        endif;
        ?>
    </div>

    <div class="bbm-archive-page__content">
        <?php if ( have_posts() ) : ?>
        <div class="bbm-posts-grid">
            <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'template-parts/content/content' );
            endwhile;
            ?>
        </div>
        <?php bbm_pagination(); ?>
        <?php else : ?>
            <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();
