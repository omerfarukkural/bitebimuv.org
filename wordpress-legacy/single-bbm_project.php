<?php
/**
 * Proje Tekil Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header();
the_post();

$post_id       = get_the_ID();
$status        = get_post_meta( $post_id, '_bbm_project_status', true );
$start_date    = get_post_meta( $post_id, '_bbm_project_start', true );
$end_date      = get_post_meta( $post_id, '_bbm_project_end', true );
$budget        = get_post_meta( $post_id, '_bbm_project_budget', true );
$beneficiaries = get_post_meta( $post_id, '_bbm_project_beneficiaries', true );
$location      = get_post_meta( $post_id, '_bbm_project_location', true );
$partners      = get_post_meta( $post_id, '_bbm_project_partners', true );
$website       = get_post_meta( $post_id, '_bbm_project_website', true );
$gallery_ids   = get_post_meta( $post_id, '_bbm_gallery_images', true );

$status_map = [
    'ongoing'   => [ 'label' => __( 'Devam Ediyor', 'bitebimuv' ), 'bg' => '#dbeafe', 'color' => '#1e40af' ],
    'completed' => [ 'label' => __( 'Tamamlandı', 'bitebimuv' ),   'bg' => '#d1fae5', 'color' => '#065f46' ],
    'planned'   => [ 'label' => __( 'Planlanıyor', 'bitebimuv' ),  'bg' => '#fef3c7', 'color' => '#92400e' ],
    'paused'    => [ 'label' => __( 'Duraklatıldı', 'bitebimuv' ), 'bg' => '#fee2e2', 'color' => '#991b1b' ],
];
$sb = $status_map[ $status ] ?? null;
?>

<main id="main" class="bbm-page-main">

    <!-- Page Header -->
    <div class="bbm-page-header bbm-page-header--project <?php echo has_post_thumbnail() ? 'has-bg' : ''; ?>">
        <?php if ( has_post_thumbnail() ) : ?>
        <div class="bbm-page-header__bg" aria-hidden="true">
            <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'decoding' => 'async' ] ); ?>
            <div class="bbm-page-header__overlay"></div>
        </div>
        <?php endif; ?>
        <div class="bbm-container">
            <?php bbm_breadcrumb(); ?>
            <?php if ( $sb ) : ?>
            <span class="bbm-project-status-badge" style="background:<?php echo esc_attr( $sb['bg'] ); ?>;color:<?php echo esc_attr( $sb['color'] ); ?>">
                <?php echo esc_html( $sb['label'] ); ?>
            </span>
            <?php endif; ?>
            <h1 class="bbm-page-header__title"><?php the_title(); ?></h1>
            <?php if ( $start_date ) : ?>
            <p class="bbm-page-header__subtitle">
                <?php
                echo date_i18n( 'F Y', strtotime( $start_date ) );
                if ( $end_date ) echo ' — ' . date_i18n( 'F Y', strtotime( $end_date ) );
                ?>
            </p>
            <?php endif; ?>
        </div>
    </div>

    <article class="bbm-single-project">
        <div class="bbm-container">
            <div class="bbm-single-project__layout">

                <!-- Main Content -->
                <div class="bbm-single-project__main">
                    <div class="bbm-prose">
                        <?php the_content(); ?>
                    </div>

                    <!-- Project Gallery -->
                    <?php if ( $gallery_ids ) :
                        $img_ids = array_map( 'absint', array_filter( explode( ',', $gallery_ids ) ) );
                        if ( $img_ids ) :
                    ?>
                    <div class="bbm-project-gallery">
                        <h3><?php _e( 'Proje Fotoğrafları', 'bitebimuv' ); ?></h3>
                        <div class="bbm-gallery-grid" id="bbm-project-gallery">
                            <?php foreach ( $img_ids as $img_id ) :
                                $full = wp_get_attachment_image_url( $img_id, 'full' );
                                $alt  = get_post_meta( $img_id, '_wp_attachment_image_alt', true );
                            ?>
                            <div class="bbm-gallery-item">
                                <button class="bbm-gallery-trigger"
                                        data-full="<?php echo esc_url( $full ); ?>"
                                        data-caption="<?php echo esc_attr( $alt ); ?>"
                                        aria-label="<?php echo esc_attr( sprintf( __( 'Fotoğrafı büyüt: %s', 'bitebimuv' ), $alt ) ); ?>">
                                    <?php echo wp_get_attachment_image( $img_id, 'medium', false, [ 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; endif; ?>

                    <!-- Share -->
                    <div class="bbm-share-section">
                        <h4><?php _e( 'Bu Projeyi Paylaş', 'bitebimuv' ); ?></h4>
                        <?php bbm_share_buttons(); ?>
                    </div>
                </div>

                <!-- Sidebar -->
                <aside class="bbm-single-project__sidebar">
                    <div class="bbm-project-info-card">
                        <h3><?php _e( 'Proje Bilgileri', 'bitebimuv' ); ?></h3>
                        <dl class="bbm-info-list">
                            <?php if ( $status && $sb ) : ?>
                            <dt><?php _e( 'Durum', 'bitebimuv' ); ?></dt>
                            <dd><span style="background:<?php echo esc_attr( $sb['bg'] ); ?>;color:<?php echo esc_attr( $sb['color'] ); ?>;padding:2px 10px;border-radius:4px;font-size:13px;font-weight:600"><?php echo esc_html( $sb['label'] ); ?></span></dd>
                            <?php endif; ?>

                            <?php if ( $start_date ) : ?>
                            <dt><?php _e( 'Başlangıç', 'bitebimuv' ); ?></dt>
                            <dd><?php echo esc_html( date_i18n( 'j F Y', strtotime( $start_date ) ) ); ?></dd>
                            <?php endif; ?>

                            <?php if ( $end_date ) : ?>
                            <dt><?php _e( 'Bitiş', 'bitebimuv' ); ?></dt>
                            <dd><?php echo esc_html( date_i18n( 'j F Y', strtotime( $end_date ) ) ); ?></dd>
                            <?php endif; ?>

                            <?php if ( $location ) : ?>
                            <dt><?php _e( 'Konum', 'bitebimuv' ); ?></dt>
                            <dd><?php echo esc_html( $location ); ?></dd>
                            <?php endif; ?>

                            <?php if ( $beneficiaries ) : ?>
                            <dt><?php _e( 'Faydalanan', 'bitebimuv' ); ?></dt>
                            <dd><?php echo esc_html( $beneficiaries ); ?> <?php _e( 'kişi', 'bitebimuv' ); ?></dd>
                            <?php endif; ?>

                            <?php if ( $budget ) : ?>
                            <dt><?php _e( 'Bütçe', 'bitebimuv' ); ?></dt>
                            <dd><?php echo esc_html( $budget ); ?></dd>
                            <?php endif; ?>

                            <?php if ( $partners ) : ?>
                            <dt><?php _e( 'Ortaklar', 'bitebimuv' ); ?></dt>
                            <dd><?php echo nl2br( esc_html( $partners ) ); ?></dd>
                            <?php endif; ?>
                        </dl>

                        <?php if ( $website ) : ?>
                        <a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="noopener" class="bbm-btn bbm-btn--outline bbm-btn--full" style="margin-top:1rem">
                            <?php _e( 'Proje Websitesi', 'bitebimuv' ); ?> ↗
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Related Projects -->
                    <?php
                    $related = get_posts( [
                        'post_type'   => 'bbm_project',
                        'numberposts' => 3,
                        'exclude'     => [ $post_id ],
                        'orderby'     => 'rand',
                    ] );
                    if ( $related ) :
                    ?>
                    <div class="bbm-related-posts">
                        <h4><?php _e( 'Diğer Projeler', 'bitebimuv' ); ?></h4>
                        <ul>
                        <?php foreach ( $related as $rp ) : ?>
                            <li>
                                <a href="<?php echo get_permalink( $rp ); ?>">
                                    <?php if ( has_post_thumbnail( $rp ) ) : ?>
                                    <span class="bbm-related-thumb"><?php echo get_the_post_thumbnail( $rp, [ 50, 50 ] ); ?></span>
                                    <?php endif; ?>
                                    <span><?php echo esc_html( get_the_title( $rp ) ); ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                </aside>
            </div>
        </div>
    </article>
</main>

<?php get_footer();
