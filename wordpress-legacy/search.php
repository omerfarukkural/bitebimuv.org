<?php
/**
 * Arama Sonuçları Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();
?>
<div class="bbm-search-page container">
    <header class="bbm-search-page__header">
        <?php bbm_breadcrumb(); ?>
        <h1 class="bbm-search-page__title">
            <?php printf(
                __( '<span>"%s"</span> için arama sonuçları', 'bitebimuv-dernek' ),
                '<em>' . esc_html( get_search_query() ) . '</em>'
            ); ?>
        </h1>
        <?php if ( have_posts() ) : ?>
        <p class="bbm-search-page__count">
            <?php printf( _n( '%d sonuç bulundu.', '%d sonuç bulundu.', $wp_query->found_posts, 'bitebimuv-dernek' ), $wp_query->found_posts ); ?>
        </p>
        <?php endif; ?>
    </header>
    <div class="bbm-search-page__form">
        <?php get_search_form(); ?>
    </div>
    <div class="bbm-search-page__results">
        <?php if ( have_posts() ) : ?>
        <div class="bbm-posts-grid">
            <?php while ( have_posts() ) : the_post();
                get_template_part( 'template-parts/content/content' );
            endwhile; ?>
        </div>
        <?php bbm_pagination(); ?>
        <?php else : ?>
        <div class="bbm-not-found">
            <?php echo bbm_get_smiley( 'medium', 'bbm-search-sad-smiley' ); ?>
            <p><?php printf( __( '&ldquo;%s&rdquo; için sonuç bulunamadı.', 'bitebimuv-dernek' ), esc_html( get_search_query() ) ); ?></p>
            <p><?php _e( 'Farklı bir anahtar kelime deneyin.', 'bitebimuv-dernek' ); ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
