<?php
/**
 * Duyuru Tekil Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header();
the_post();

$post_id     = get_the_ID();
$ann_type    = wp_get_post_terms( $post_id, 'bbm_announcement_type', [ 'fields' => 'names' ] );
$type_name   = ! is_wp_error( $ann_type ) && $ann_type ? $ann_type[0] : '';

$type_icons = [
    'duyuru'     => '📢',
    'haber'      => '📰',
    'etkinlik'   => '🎉',
    'üyelik'     => '👥',
    'genel'      => '📌',
    'acil'       => '🚨',
];
$type_slug  = wp_get_post_terms( $post_id, 'bbm_announcement_type', [ 'fields' => 'slugs' ] );
$type_slug  = ! is_wp_error( $type_slug ) && $type_slug ? $type_slug[0] : 'genel';
$icon       = $type_icons[ $type_slug ] ?? '📌';
?>

<main id="main" class="bbm-page-main">

    <!-- Page Header -->
    <div class="bbm-page-header bbm-page-header--announcement <?php echo has_post_thumbnail() ? 'has-bg' : ''; ?>">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="bbm-page-header__bg" aria-hidden="true">
            <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'decoding' => 'async' ] ); ?>
            <div class="bbm-page-header__overlay"></div>
        </div>
        <?php endif; ?>
        <div class="bbm-container">
            <?php bbm_breadcrumb(); ?>
            <?php if ( $type_name ) : ?>
            <span class="bbm-ann-type-badge">
                <?php echo $icon; ?> <?php echo esc_html( $type_name ); ?>
            </span>
            <?php endif; ?>
            <h1 class="bbm-page-header__title"><?php the_title(); ?></h1>
            <div class="bbm-page-header__meta">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                    📅 <?php echo get_the_date( 'j F Y' ); ?>
                </time>
            </div>
        </div>
    </div>

    <article class="bbm-single-announcement">
        <div class="bbm-container bbm-container--narrow">

            <?php if ( has_post_thumbnail() ) : ?>
            <div class="bbm-single-ann__featured">
                <?php the_post_thumbnail( 'large', [ 'loading' => 'eager', 'decoding' => 'async' ] ); ?>
            </div>
            <?php endif; ?>

            <div class="bbm-prose">
                <?php the_content(); ?>
            </div>

            <!-- Share -->
            <div class="bbm-share-section">
                <h4><?php _e( 'Bu Duyuruyu Paylaş', 'bitebimuv' ); ?></h4>
                <?php bbm_share_buttons(); ?>
            </div>

            <!-- Navigation -->
            <nav class="bbm-post-nav" aria-label="<?php esc_attr_e( 'Duyuru navigasyonu', 'bitebimuv' ); ?>">
                <?php
                $prev = get_previous_post( false, '', 'bbm_announcement_type' );
                $next = get_next_post( false, '', 'bbm_announcement_type' );
                if ( $prev ) :
                ?>
                <a href="<?php echo get_permalink( $prev ); ?>" class="bbm-post-nav__prev" rel="prev">
                    <span><?php _e( '← Önceki Duyuru', 'bitebimuv' ); ?></span>
                    <strong><?php echo esc_html( get_the_title( $prev ) ); ?></strong>
                </a>
                <?php endif;
                if ( $next ) :
                ?>
                <a href="<?php echo get_permalink( $next ); ?>" class="bbm-post-nav__next" rel="next">
                    <span><?php _e( 'Sonraki Duyuru →', 'bitebimuv' ); ?></span>
                    <strong><?php echo esc_html( get_the_title( $next ) ); ?></strong>
                </a>
                <?php endif; ?>
            </nav>

        </div>
    </article>
</main>

<?php get_footer();
