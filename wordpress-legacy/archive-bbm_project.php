<?php
/**
 * Proje Arşivi
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header();
?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header(
        __( 'Projelerimiz', 'bitebimuv' ),
        __( 'Toplumsal etki yaratmak için yürüttüğümüz projeler', 'bitebimuv' )
    ); ?>

    <section class="bbm-section bbm-projects-archive">
        <div class="bbm-container">

            <!-- Status Filter Tabs -->
            <div class="bbm-projects-filter" role="navigation" aria-label="<?php esc_attr_e( 'Proje filtrele', 'bitebimuv' ); ?>">
                <?php
                $current_status = get_query_var( 'project_status', '' );
                $statuses = [
                    ''          => __( '🗂 Tümü', 'bitebimuv' ),
                    'ongoing'   => __( '🔄 Devam Eden', 'bitebimuv' ),
                    'completed' => __( '✅ Tamamlanan', 'bitebimuv' ),
                    'planned'   => __( '📋 Planlanan', 'bitebimuv' ),
                ];
                foreach ( $statuses as $val => $label ) :
                    $active = ( $current_status === $val ) ? 'active' : '';
                    $url    = $val ? add_query_arg( 'project_status', $val ) : remove_query_arg( 'project_status' );
                ?>
                <a href="<?php echo esc_url( $url ); ?>" class="bbm-filter-tab <?php echo $active; ?>" aria-current="<?php echo $active ? 'page' : 'false'; ?>">
                    <?php echo $label; ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Projects Grid -->
            <div class="bbm-projects-grid">
            <?php if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    $status        = get_post_meta( get_the_ID(), '_bbm_project_status', true );
                    $start_date    = get_post_meta( get_the_ID(), '_bbm_project_start', true );
                    $end_date      = get_post_meta( get_the_ID(), '_bbm_project_end', true );
                    $beneficiaries = get_post_meta( get_the_ID(), '_bbm_project_beneficiaries', true );

                    $status_map = [
                        'ongoing'   => [ '🔄', __( 'Devam Ediyor', 'bitebimuv' ), '#dbeafe', '#1e40af' ],
                        'completed' => [ '✅', __( 'Tamamlandı', 'bitebimuv' ),   '#d1fae5', '#065f46' ],
                        'planned'   => [ '📋', __( 'Planlanıyor', 'bitebimuv' ),  '#fef3c7', '#92400e' ],
                    ];
                    $sb = $status_map[ $status ] ?? null;
                ?>
                <article class="bbm-project-card" data-aos="fade-up">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="bbm-project-card__thumb">
                        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                            <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
                        </a>
                        <?php if ( $sb ) : ?>
                        <span class="bbm-project-status-badge" style="background:<?php echo esc_attr( $sb[2] ); ?>;color:<?php echo esc_attr( $sb[3] ); ?>">
                            <?php echo $sb[0]; ?> <?php echo esc_html( $sb[1] ); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="bbm-project-card__body">
                        <h2 class="bbm-project-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="bbm-project-card__excerpt"><?php echo wp_trim_words( get_the_excerpt() ?: get_the_content(), 20, '…' ); ?></p>
                        <div class="bbm-project-card__meta">
                            <?php if ( $start_date ) : ?>
                            <span>📅 <?php echo date_i18n( 'Y', strtotime( $start_date ) ); ?><?php echo $end_date ? ' – ' . date_i18n( 'Y', strtotime( $end_date ) ) : '+'; ?></span>
                            <?php endif; ?>
                            <?php if ( $beneficiaries ) : ?>
                            <span>👥 <?php echo esc_html( $beneficiaries ); ?> <?php _e( 'kişi', 'bitebimuv' ); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="bbm-btn bbm-btn--outline bbm-btn--sm"><?php _e( 'Detaylar', 'bitebimuv' ); ?></a>
                    </div>
                </article>
                <?php endwhile;
            else : ?>
                <div class="bbm-empty-state">
                    <div class="bbm-empty-state__icon">🚀</div>
                    <h3><?php _e( 'Henüz proje eklenmedi.', 'bitebimuv' ); ?></h3>
                    <p><?php _e( 'Projelerimiz yakında eklenecek.', 'bitebimuv' ); ?></p>
                </div>
            <?php endif; ?>
            </div>

            <?php if ( $wp_query->max_num_pages > 1 ) : ?>
            <div class="bbm-pagination">
                <?php echo paginate_links( [
                    'total'     => $wp_query->max_num_pages,
                    'prev_text' => '← ' . __( 'Önceki', 'bitebimuv' ),
                    'next_text' => __( 'Sonraki', 'bitebimuv' ) . ' →',
                ] ); ?>
            </div>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php get_footer();
