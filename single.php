<?php
/**
 * Tekil Blog Yazısı Şablonu - v3.0
 *
 * @package bitebimuv-dernek
 */

get_header();

while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $cats    = get_the_category( $post_id );
    $tags    = get_the_tags( $post_id );
?>

<!-- Sayfa başlığı -->
<div class="bbm-page-header <?php has_post_thumbnail() ? 'bbm-page-header--has-img' : ''; ?>">
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="bbm-page-header__bg-img"
         style="background-image:url('<?php echo esc_url( get_the_post_thumbnail_url( null, 'bbm-wide' ) ); ?>');" 
         aria-hidden="true"></div>
    <?php else : ?>
    <div class="bbm-page-header__bg" aria-hidden="true"></div>
    <?php endif; ?>

    <div class="bbm-container">
        <?php bbm_breadcrumb(); ?>
        <div class="bbm-page-header__content">
            <?php if ( $cats ) : ?>
            <div class="bbm-card__cats">
                <?php foreach ( array_slice( $cats, 0, 3 ) as $cat ) : ?>
                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="bbm-tag">
                    <?php echo esc_html( $cat->name ); ?>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <h1 class="bbm-page-header__title"><?php the_title(); ?></h1>
            <div class="bbm-single-meta">
                <div class="bbm-single-meta__author">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 40, '', '', [ 'class' => 'bbm-single-meta__avatar' ] ); ?>
                    <div>
                        <span class="bbm-single-meta__author-name"><?php the_author(); ?></span>
                        <span class="bbm-single-meta__date">
                            <time datetime="<?php echo get_the_date( 'Y-m-d' ); ?>"><?php echo get_the_date(); ?></time>
                            <?php if ( bbm_reading_time() ) : ?>
                            &middot; <?php printf( esc_html__( '%d dk okuma', 'bitebimuv-dernek' ), bbm_reading_time() ); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- İçerik + kenar çubuğu -->
<div class="bbm-section bbm-single-section">
    <div class="bbm-container">
        <div class="bbm-single-layout">

            <!-- Ana içerik -->
            <article class="bbm-single-main">
                <div class="bbm-prose bbm-single-content">
                    <?php the_content(); ?>
                    <?php
                    wp_link_pages( [
                        'before'    => '<nav class="bbm-page-links"><span>' . __( 'Sayfalar:', 'bitebimuv-dernek' ) . '</span>',
                        'after'     => '</nav>',
                        'link_before' => '<span class="bbm-btn bbm-btn--outline bbm-btn--sm">',
                        'link_after'  => '</span>',
                    ] );
                    ?>
                </div>

                <!-- Etiketler -->
                <?php if ( $tags ) : ?>
                <div class="bbm-single-tags">
                    <strong><?php esc_html_e( 'Etiketler:', 'bitebimuv-dernek' ); ?></strong>
                    <?php foreach ( $tags as $tag ) : ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="bbm-tag">
                        #<?php echo esc_html( $tag->name ); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Paylaş -->
                <div class="bbm-single-share">
                    <strong><?php esc_html_e( 'Paylaş:', 'bitebimuv-dernek' ); ?></strong>
                    <?php echo bbm_share_buttons(); ?>
                </div>

                <!-- Yazar kutusu -->
                <div class="bbm-author-box">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 80, '', '', [ 'class' => 'bbm-author-box__avatar' ] ); ?>
                    <div class="bbm-author-box__content">
                        <h3 class="bbm-author-box__name"><?php the_author(); ?></h3>
                        <p class="bbm-author-box__bio"><?php echo esc_html( get_the_author_meta( 'description' ) ?: __( 'Dernek üyesi', 'bitebimuv-dernek' ) ); ?></p>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
                           class="bbm-btn bbm-btn--outline bbm-btn--sm">
                            <?php printf( esc_html__( '%s\'in tüm yazıları', 'bitebimuv-dernek' ), get_the_author() ); ?>
                        </a>
                    </div>
                </div>

                <!-- Yorumlar -->
                <?php if ( comments_open() || get_comments_number() ) : ?>
                <div class="bbm-comments-section">
                    <?php comments_template(); ?>
                </div>
                <?php endif; ?>

                <!-- Önceki/Sonraki yazı -->
                <nav class="bbm-post-nav" aria-label="<?php esc_attr_e( 'Yazı navigasyonu', 'bitebimuv-dernek' ); ?>">
                    <?php
                    $prev = get_previous_post();
                    $next = get_next_post();
                    if ( $prev ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="bbm-post-nav__prev">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        <span>
                            <small><?php esc_html_e( 'Önceki yazı', 'bitebimuv-dernek' ); ?></small>
                            <strong><?php echo esc_html( get_the_title( $prev->ID ) ); ?></strong>
                        </span>
                    </a>
                    <?php endif; if ( $next ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="bbm-post-nav__next">
                        <span>
                            <small><?php esc_html_e( 'Sonraki yazı', 'bitebimuv-dernek' ); ?></small>
                            <strong><?php echo esc_html( get_the_title( $next->ID ) ); ?></strong>
                        </span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                    <?php endif; ?>
                </nav>

            </article>

            <!-- Kenar çubuğu -->
            <?php get_sidebar(); ?>

        </div>
    </div>
</div>

<?php endwhile; ?>

<!-- İlgili yazılar -->
<?php
$current_cats = wp_get_post_categories( get_the_ID() );
if ( $current_cats ) :
    $related = new WP_Query( [
        'post_type'      => 'post',
        'posts_per_page' => 3,
        'post__not_in'   => [ get_the_ID() ],
        'category__in'   => $current_cats,
    ] );
    if ( $related->have_posts() ) :
?>
<section class="bbm-section bbm-section--alt bbm-related-posts">
    <div class="bbm-container">
        <h2 class="bbm-section-title"><?php esc_html_e( 'İlgili Yazılar', 'bitebimuv-dernek' ); ?></h2>
        <div class="bbm-grid bbm-grid--3">
            <?php while ( $related->have_posts() ) : $related->the_post();
                get_template_part( 'template-parts/content/post-card' );
            endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; endif; ?>

<?php get_footer(); ?>
