<?php
/**
 * Üyeler Arşiv Sayfası
 *
 * @package bitebimuv-dernek
 */

get_header();

$members_query = new WP_Query( [
    'post_type'      => 'bbm_member',
    'posts_per_page' => 12,
    'post_status'    => 'publish',
    'meta_key'       => '_bbm_member_order',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
    'paged'          => max( 1, get_query_var( 'paged' ) ),
] );
?>

<div class="bbm-page-header">
    <div class="bbm-container">
        <?php bbm_breadcrumb(); ?>
        <div class="bbm-page-header__content">
            <span class="bbm-page-header__badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <?php esc_html_e( 'Yönetim Kurulu', 'bitebimuv-dernek' ); ?>
            </span>
            <h1 class="bbm-page-header__title">
                <?php esc_html_e( 'Ekibimiz', 'bitebimuv-dernek' ); ?>
            </h1>
            <p class="bbm-page-header__subtitle">
                <?php esc_html_e( 'Derneğimizi yöneten ve geliştiren değerli ekip üyelerimizle tanışın.', 'bitebimuv-dernek' ); ?>
            </p>
        </div>
    </div>
</div>

<section class="bbm-section">
    <div class="bbm-container">
        <?php if ( $members_query->have_posts() ) : ?>
        <div class="bbm-members-grid">
            <?php while ( $members_query->have_posts() ) : $members_query->the_post();
                get_template_part( 'template-parts/content/member-card' );
            endwhile; wp_reset_postdata(); ?>
        </div>

        <div class="bbm-pagination">
            <?php echo paginate_links( [
                'total'     => $members_query->max_num_pages,
                'current'   => max( 1, get_query_var( 'paged' ) ),
                'prev_text' => '&larr;',
                'next_text' => '&rarr;',
                'type'      => 'list',
            ] ); ?>
        </div>

        <?php else : ?>
        <div class="bbm-empty-state">
            <?php echo bbm_get_smiley( 'medium', 'bbm-no-members-smiley' ); ?>
            <h2><?php esc_html_e( 'Henüz üye eklenmemiş.', 'bitebimuv-dernek' ); ?></h2>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
