<?php
/**
 * Template Name: Galeri Sayfası
 * Template Post Type: page
 *
 * @package bitebimuv-dernek
 */

get_header();

// Galeri kategorilerini al
$gallery_cats = get_terms( [ 'taxonomy' => 'bbm_gallery_category', 'hide_empty' => true ] );

// Aktif kategori
$current_cat = isset( $_GET['kategori'] ) ? sanitize_text_field( $_GET['kategori'] ) : '';

// Filtreli sorgu
$query_args = [
    'post_type'      => 'bbm_gallery',
    'posts_per_page' => 18,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'paged'          => max( 1, get_query_var( 'paged' ) ),
];

if ( $current_cat ) {
    $query_args['tax_query'] = [ [
        'taxonomy' => 'bbm_gallery_category',
        'field'    => 'slug',
        'terms'    => $current_cat,
    ] ];
}

$gallery_query = new WP_Query( $query_args );
?>

<div class="bbm-page-header">
    <div class="bbm-container">
        <?php bbm_breadcrumb(); ?>
        <div class="bbm-page-header__content">
            <h1 class="bbm-page-header__title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <?php echo esc_html( get_the_title() ); ?>
            </h1>
        </div>
    </div>
</div>

<section class="bbm-section" aria-labelledby="bbm-gallery-page-title">
    <div class="bbm-container">

        <!-- Kategori filtreleri -->
        <?php if ( ! is_wp_error( $gallery_cats ) && ! empty( $gallery_cats ) ) : ?>
        <div class="bbm-filter-bar" role="navigation" aria-label="<?php esc_attr_e( 'Galeri kategorileri', 'bitebimuv-dernek' ); ?>">
            <a href="<?php echo esc_url( get_permalink() ); ?>"
               class="bbm-filter-btn <?php echo ! $current_cat ? 'is-active' : ''; ?>">
                <?php esc_html_e( 'Tümü', 'bitebimuv-dernek' ); ?>
            </a>
            <?php foreach ( $gallery_cats as $cat ) : ?>
            <a href="<?php echo esc_url( add_query_arg( 'kategori', $cat->slug, get_permalink() ) ); ?>"
               class="bbm-filter-btn <?php echo $current_cat === $cat->slug ? 'is-active' : ''; ?>">
                <?php echo esc_html( $cat->name ); ?>
                <span class="bbm-filter-btn__count"><?php echo intval( $cat->count ); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Galeri grid -->
        <?php if ( $gallery_query->have_posts() ) :
            $all_images = [];
            $post_ids   = wp_list_pluck( $gallery_query->posts, 'ID' );

            foreach ( $post_ids as $post_id ) {
                $img_ids_str = get_post_meta( $post_id, '_bbm_gallery_images', true );
                if ( $img_ids_str ) {
                    $img_ids = array_filter( array_map( 'intval', explode( ',', $img_ids_str ) ) );
                    foreach ( $img_ids as $img_id ) {
                        $thumb = wp_get_attachment_image_url( $img_id, 'bbm-gallery' );
                        $full  = wp_get_attachment_image_url( $img_id, 'full' );
                        if ( $thumb ) {
                            $all_images[] = [
                                'thumb'   => $thumb,
                                'full'    => $full,
                                'alt'     => get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ),
                                'caption' => get_the_title( $post_id ),
                            ];
                        }
                    }
                } elseif ( has_post_thumbnail( $post_id ) ) {
                    $all_images[] = [
                        'thumb'   => get_the_post_thumbnail_url( $post_id, 'bbm-gallery' ),
                        'full'    => get_the_post_thumbnail_url( $post_id, 'full' ),
                        'alt'     => get_the_title( $post_id ),
                        'caption' => get_the_title( $post_id ),
                    ];
                }
            }
        ?>
        <div class="bbm-gallery-grid bbm-gallery-grid--full" id="bbm-gallery-grid">
            <?php foreach ( $all_images as $idx => $image ) : ?>
            <div class="bbm-gallery-item" data-aos="zoom-in" data-aos-delay="<?php echo min( $idx * 40, 400 ); ?>">
                <a href="<?php echo esc_url( $image['full'] ); ?>"
                   class="bbm-gallery-item__link bbm-gallery-trigger"
                   data-gallery-index="<?php echo $idx; ?>"
                   data-gallery-total="<?php echo count( $all_images ); ?>"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Büyüt: %s', 'bitebimuv-dernek' ), $image['alt'] ) ); ?>">
                    <img src="<?php echo esc_url( $image['thumb'] ); ?>"
                         alt="<?php echo esc_attr( $image['alt'] ); ?>"
                         class="bbm-gallery-item__img" loading="lazy"
                         width="400" height="300">
                    <div class="bbm-gallery-item__overlay">
                        <svg class="bbm-gallery-item__zoom-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="28" height="28" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><path d="M11 8v6M8 11h6"/>
                        </svg>
                        <?php if ( $image['caption'] ) : ?>
                        <p class="bbm-gallery-item__caption"><?php echo esc_html( $image['caption'] ); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Lightbox verileri -->
        <script id="bbm-gallery-data" type="application/json">
            <?php echo wp_json_encode( array_map( function( $img, $idx ) {
                return [ 'index' => $idx, 'src' => $img['full'], 'thumb' => $img['thumb'], 'alt' => $img['alt'], 'caption' => $img['caption'] ];
            }, $all_images, array_keys( $all_images ) ) ); ?>
        </script>

        <!-- Sayfalama -->
        <?php
        echo paginate_links( [
            'total'   => $gallery_query->max_num_pages,
            'current' => max( 1, get_query_var( 'paged' ) ),
            'format'  => '?paged=%#%',
            'prev_text' => '&larr; ' . __( 'Önceki', 'bitebimuv-dernek' ),
            'next_text' => __( 'Sonraki', 'bitebimuv-dernek' ) . ' &rarr;',
            'type'    => 'list',
        ] );
        ?>

        <?php else : ?>
        <div class="bbm-empty-state">
            <?php echo bbm_get_smiley( 'medium', 'bbm-empty-smiley' ); ?>
            <h2><?php esc_html_e( 'Henüz galeri içeriği yok', 'bitebimuv-dernek' ); ?></h2>
            <p><?php esc_html_e( 'Yakında fotoğraflar eklenecek.', 'bitebimuv-dernek' ); ?></p>
        </div>
        <?php endif; wp_reset_postdata(); ?>

    </div>
</section>

<?php get_footer(); ?>
