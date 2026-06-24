<?php
/**
 * Template Name: Blog Sayfası
 * Bite Bi Muv Derneği Teması v4.0
 */
get_header(); ?>

<main id="main" class="bbm-page-main">

    <?php bbm_page_header( get_the_title(), get_the_excerpt() ?: __( 'Haberler, duyurular ve makaleler', 'bitebimuv' ) ); ?>

    <section class="bbm-section bbm-blog-page">
        <div class="bbm-container">
            <div class="bbm-blog-layout">

                <div class="bbm-blog-main">
                    <!-- Category tabs -->
                    <?php
                    $cats = get_categories( [ 'hide_empty' => true, 'number' => 10 ] );
                    if ( $cats ) :
                    ?>
                    <div class="bbm-blog-cats" role="navigation" aria-label="<?php esc_attr_e( 'Blog kategorileri', 'bitebimuv' ); ?>">
                        <a href="<?php echo get_permalink(); ?>" class="bbm-blog-cat-tag <?php echo ! get_query_var( 'cat' ) ? 'active' : ''; ?>">
                            <?php _e( 'Tümü', 'bitebimuv' ); ?>
                        </a>
                        <?php foreach ( $cats as $cat ) : ?>
                        <a href="<?php echo esc_url( get_category_link( $cat ) ); ?>" class="bbm-blog-cat-tag">
                            <?php echo esc_html( $cat->name ); ?> <sup><?php echo $cat->count; ?></sup>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Posts Grid -->
                    <?php
                    $paged   = get_query_var( 'paged' ) ?: 1;
                    $blog_q  = new WP_Query( [
                        'post_type'      => 'post',
                        'post_status'    => 'publish',
                        'posts_per_page' => 9,
                        'paged'          => $paged,
                    ] );
                    ?>
                    <div class="bbm-posts-grid">
                    <?php if ( $blog_q->have_posts() ) :
                        while ( $blog_q->have_posts() ) : $blog_q->the_post();
                            get_template_part( 'template-parts/content/post-card' );
                        endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <div class="bbm-empty-state">
                            <div class="bbm-empty-state__icon"><?php echo bbm_get_smiley( 'medium' ); ?></div>
                            <h3><?php _e( 'Henüz yazı yok', 'bitebimuv' ); ?></h3>
                        </div>
                    <?php endif; ?>
                    </div>

                    <?php if ( $blog_q->max_num_pages > 1 ) : ?>
                    <div class="bbm-pagination">
                        <?php echo paginate_links( [ 'total' => $blog_q->max_num_pages, 'prev_text' => '← ' . __( 'Önceki', 'bitebimuv' ), 'next_text' => __( 'Sonraki', 'bitebimuv' ) . ' →' ] ); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <aside class="bbm-blog-sidebar">
                    <!-- Search -->
                    <div class="bbm-sidebar-widget">
                        <h3 class="bbm-sidebar-widget__title"><?php _e( 'Ara', 'bitebimuv' ); ?></h3>
                        <?php get_search_form(); ?>
                    </div>

                    <!-- Son Yazılar -->
                    <div class="bbm-sidebar-widget">
                        <h3 class="bbm-sidebar-widget__title"><?php _e( 'Son Yazılar', 'bitebimuv' ); ?></h3>
                        <?php
                        $recent = get_posts( [ 'numberposts' => 5 ] );
                        echo '<ul class="bbm-sidebar-list">';
                        foreach ( $recent as $rp ) :
                        ?>
                        <li>
                            <a href="<?php echo get_permalink( $rp ); ?>">
                                <?php if ( has_post_thumbnail( $rp ) ) : ?>
                                <span class="bbm-sidebar-thumb"><?php echo get_the_post_thumbnail( $rp, [ 50, 50 ] ); ?></span>
                                <?php endif; ?>
                                <span>
                                    <?php echo esc_html( get_the_title( $rp ) ); ?>
                                    <small><?php echo get_the_date( 'j M Y', $rp ); ?></small>
                                </span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Kategoriler -->
                    <?php if ( $cats ) : ?>
                    <div class="bbm-sidebar-widget">
                        <h3 class="bbm-sidebar-widget__title"><?php _e( 'Kategoriler', 'bitebimuv' ); ?></h3>
                        <ul class="bbm-sidebar-cats">
                        <?php foreach ( $cats as $cat ) : ?>
                            <li>
                                <a href="<?php echo esc_url( get_category_link( $cat ) ); ?>">
                                    <?php echo esc_html( $cat->name ); ?>
                                    <span class="bbm-cat-count"><?php echo $cat->count; ?></span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- Tag Cloud -->
                    <div class="bbm-sidebar-widget">
                        <h3 class="bbm-sidebar-widget__title"><?php _e( 'Etiketler', 'bitebimuv' ); ?></h3>
                        <div class="bbm-tag-cloud">
                            <?php wp_tag_cloud( [ 'smallest' => 11, 'largest' => 18, 'unit' => 'px', 'number' => 20 ] ); ?>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>
</main>

<?php get_footer();
