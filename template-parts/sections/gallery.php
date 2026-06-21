<?php
/**
 * Galeri Bölümü
 *
 * @package bitebimuv-dernek
 */

$gallery_posts = new WP_Query( [
    'post_type'      => 'bbm_gallery',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

// Galeri yoksa veritabanındaki herhangi bir görseli göster
if ( ! $gallery_posts->have_posts() ) {
    $gallery_posts = new WP_Query( [
        'post_type'      => 'post',
        'posts_per_page' => 8,
        'post_status'    => 'publish',
        'meta_query'     => [ [ 'key' => '_thumbnail_id', 'compare' => 'EXISTS' ] ],
    ] );
}

if ( ! $gallery_posts->have_posts() ) return;
?>

<section class="bbm-section bbm-gallery-section" id="galeri" aria-labelledby="bbm-gallery-title">
    <div class="bbm-container">

        <header class="bbm-section-header" data-aos="fade-up">
            <span class="bbm-section-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <?php esc_html_e( 'Fotoğraf Galerisi', 'bitebimuv-dernek' ); ?>
            </span>
            <h2 id="bbm-gallery-title" class="bbm-section-title">
                <?php esc_html_e( 'Anılarımızı', 'bitebimuv-dernek' ); ?>
                <span class="bbm-gradient-text"><?php esc_html_e( 'Paylaşıyoruz', 'bitebimuv-dernek' ); ?></span>
            </h2>
            <p class="bbm-section-subtitle">
                <?php esc_html_e( 'Etkinliklerimizden, projelerimizden ve birlikteliklerimizden kareler.', 'bitebimuv-dernek' ); ?>
            </p>
        </header>

        <div class="bbm-gallery-grid" id="bbm-gallery-grid" data-aos="fade-up" data-aos-delay="100">
            <?php
            $all_images = [];
            while ( $gallery_posts->have_posts() ) :
                $gallery_posts->the_post();
                $post_id = get_the_ID();

                // Önce galeri özel alanından görselleri al
                $gallery_img_ids = get_post_meta( $post_id, '_bbm_gallery_images', true );

                if ( $gallery_img_ids ) {
                    $img_ids = array_filter( array_map( 'intval', explode( ',', $gallery_img_ids ) ) );
                    foreach ( $img_ids as $img_id ) {
                        $thumb_url = wp_get_attachment_image_url( $img_id, 'bbm-gallery' );
                        $full_url  = wp_get_attachment_image_url( $img_id, 'full' );
                        $alt       = get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ?: get_the_title();
                        if ( $thumb_url ) {
                            $all_images[] = [ 'thumb' => $thumb_url, 'full' => $full_url, 'alt' => $alt, 'caption' => get_the_title() ];
                        }
                    }
                } elseif ( has_post_thumbnail() ) {
                    $thumb_url = get_the_post_thumbnail_url( $post_id, 'bbm-gallery' );
                    $full_url  = get_the_post_thumbnail_url( $post_id, 'full' );
                    $all_images[] = [ 'thumb' => $thumb_url, 'full' => $full_url, 'alt' => get_the_title(), 'caption' => get_the_title() ];
                }
            endwhile;
            wp_reset_postdata();

            // Maksimum 9 görsel göster
            $all_images = array_slice( $all_images, 0, 9 );
            $total = count( $all_images );

            foreach ( $all_images as $idx => $image ) :
                $classes = 'bbm-gallery-item';
                if ( $idx === 0 ) $classes .= ' bbm-gallery-item--featured';
            ?>
            <div class="<?php echo esc_attr( $classes ); ?>" data-aos="zoom-in" data-aos-delay="<?php echo min( $idx * 50, 400 ); ?>">
                <a href="<?php echo esc_url( $image['full'] ); ?>"
                   class="bbm-gallery-item__link bbm-gallery-trigger"
                   data-gallery-index="<?php echo $idx; ?>"
                   data-gallery-total="<?php echo $total; ?>"
                   aria-label="<?php echo esc_attr( sprintf( __( 'Büyüt: %s', 'bitebimuv-dernek' ), $image['alt'] ) ); ?>">
                    <img src="<?php echo esc_url( $image['thumb'] ); ?>"
                         alt="<?php echo esc_attr( $image['alt'] ); ?>"
                         class="bbm-gallery-item__img" loading="lazy"
                         width="400" height="300">
                    <div class="bbm-gallery-item__overlay">
                        <svg class="bbm-gallery-item__zoom-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="32" height="32" aria-hidden="true">
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

        <?php if ( $total > 0 ) :
            $gallery_page = get_posts( [ 'post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/gallery.php', 'posts_per_page' => 1 ] );
            $gallery_url  = $gallery_page ? get_permalink( $gallery_page[0]->ID ) : home_url( '/galeri/' );
        ?>
        <div class="bbm-section-footer" data-aos="fade-up">
            <a href="<?php echo esc_url( $gallery_url ); ?>" class="bbm-btn bbm-btn--secondary bbm-btn--lg">
                <?php esc_html_e( 'Tüm Fotoğraflara Bak', 'bitebimuv-dernek' ); ?>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <?php endif; ?>

    </div>

    <!-- Gizli: Lightbox için tüm görsel URL'lerini JSON olarak sakla -->
    <script id="bbm-gallery-data" type="application/json">
        <?php
        echo wp_json_encode( array_map( function( $img, $idx ) {
            return [ 'index' => $idx, 'src' => $img['full'], 'thumb' => $img['thumb'], 'alt' => $img['alt'], 'caption' => $img['caption'] ];
        }, $all_images, array_keys( $all_images ) ) );
        ?>
    </script>

</section>
