<?php
/**
 * Template Name: Projeler Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header(
        get_the_title() ?: __( 'Projelerimiz', 'bitebimuv' ),
        get_the_excerpt() ?: __( 'Toplumsal etki yaratmak için yürüttüğümüz projeler', 'bitebimuv' )
    ); ?>

    <section class="bbm-section bbm-projects-page">
        <div class="bbm-container">

            <!-- Status Filter -->
            <div class="bbm-projects-filter" role="navigation" aria-label="<?php esc_attr_e( 'Proje filtrele', 'bitebimuv' ); ?>">
                <button class="bbm-filter-tab active" data-filter="all"><?php _e( '🗂 Tümü', 'bitebimuv' ); ?></button>
                <button class="bbm-filter-tab" data-filter="ongoing"><?php _e( '🔄 Devam Eden', 'bitebimuv' ); ?></button>
                <button class="bbm-filter-tab" data-filter="completed"><?php _e( '✅ Tamamlanan', 'bitebimuv' ); ?></button>
                <button class="bbm-filter-tab" data-filter="planned"><?php _e( '📋 Planlanan', 'bitebimuv' ); ?></button>
            </div>

            <?php
            $projects_q = new WP_Query( [
                'post_type'      => 'bbm_project',
                'post_status'    => 'publish',
                'posts_per_page' => 12,
                'paged'          => get_query_var( 'paged' ) ?: 1,
            ] );
            ?>
            <div class="bbm-projects-grid">
            <?php if ( $projects_q->have_posts() ) :
                while ( $projects_q->have_posts() ) : $projects_q->the_post();
                    $status       = get_post_meta( get_the_ID(), '_bbm_project_status', true );
                    $start_date   = get_post_meta( get_the_ID(), '_bbm_project_start', true );
                    $end_date     = get_post_meta( get_the_ID(), '_bbm_project_end', true );
                    $budget       = get_post_meta( get_the_ID(), '_bbm_project_budget', true );
                    $beneficiaries= get_post_meta( get_the_ID(), '_bbm_project_beneficiaries', true );

                    $status_map = [
                        'ongoing'   => [ '🔄', __( 'Devam Ediyor', 'bitebimuv' ), '#dbeafe', '#1e40af' ],
                        'completed' => [ '✅', __( 'Tamamlandı', 'bitebimuv' ),   '#d1fae5', '#065f46' ],
                        'planned'   => [ '📋', __( 'Planlanıyor', 'bitebimuv' ),  '#fef3c7', '#92400e' ],
                    ];
                    $sb = $status_map[ $status ] ?? [ '📌', $status, '#f3f4f6', '#374151' ];
                ?>
                <article class="bbm-project-card" data-status="<?php echo esc_attr( $status ); ?>" data-aos="fade-up">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div class="bbm-project-card__thumb">
                        <a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                            <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
                        </a>
                        <?php if ( $status ) : ?>
                        <span class="bbm-project-status-badge" style="background:<?php echo esc_attr( $sb[2] ); ?>;color:<?php echo esc_attr( $sb[3] ); ?>">
                            <?php echo $sb[0] . ' ' . esc_html( $sb[1] ); ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="bbm-project-card__body">
                        <h3 class="bbm-project-card__title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
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
                <?php endwhile; wp_reset_postdata();
            else : ?>
                <div class="bbm-empty-state">
                    <div class="bbm-empty-state__icon">🚀</div>
                    <h3><?php _e( 'Henüz proje eklenmedi', 'bitebimuv' ); ?></h3>
                </div>
            <?php endif; ?>
            </div>

            <?php if ( isset( $projects_q ) && $projects_q->max_num_pages > 1 ) : ?>
            <div class="bbm-pagination">
                <?php echo paginate_links( [ 'total' => $projects_q->max_num_pages ] ); ?>
            </div>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php get_footer();
