<?php
/**
 * Üye Detay Şablonu
 *
 * @package bitebimuv-dernek
 */

get_header();

while ( have_posts() ) : the_post();
    $member_id    = get_the_ID();
    $member_title = get_post_meta( $member_id, '_bbm_member_title', true );
    $member_phone = get_post_meta( $member_id, '_bbm_member_phone', true );
    $member_email = get_post_meta( $member_id, '_bbm_member_email', true );
    $member_linkedin = get_post_meta( $member_id, '_bbm_member_linkedin', true );
    $member_twitter  = get_post_meta( $member_id, '_bbm_member_twitter', true );
?>

<div class="bbm-page-header">
    <div class="bbm-container">
        <?php bbm_breadcrumb(); ?>
        <div class="bbm-page-header__content">
            <h1 class="bbm-page-header__title"><?php the_title(); ?></h1>
            <?php if ( $member_title ) : ?>
            <p class="bbm-page-header__subtitle"><?php echo esc_html( $member_title ); ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<article class="bbm-section bbm-member-single">
    <div class="bbm-container">
        <div class="bbm-member-single__layout">

            <!-- Sol: Avatar + iletişim -->
            <aside class="bbm-member-single__sidebar">
                <div class="bbm-member-single__avatar">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'bbm-square', [ 'class' => 'bbm-member-single__img', 'loading' => 'eager' ] ); ?>
                    <?php else : ?>
                    <div class="bbm-member-single__avatar-placeholder">
                        <?php echo bbm_get_smiley( 'large', 'bbm-member-single-smiley' ); ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="bbm-member-single__contact-card">
                    <?php if ( $member_email ) : ?>
                    <a href="mailto:<?php echo esc_attr( $member_email ); ?>" class="bbm-member-single__contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <?php echo esc_html( $member_email ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( $member_phone ) : ?>
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $member_phone ) ); ?>" class="bbm-member-single__contact-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <?php echo esc_html( $member_phone ); ?>
                    </a>
                    <?php endif; ?>
                    <?php if ( $member_linkedin ) : ?>
                    <a href="<?php echo esc_url( $member_linkedin ); ?>" class="bbm-member-single__contact-item"
                       target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        LinkedIn
                    </a>
                    <?php endif; ?>
                    <?php if ( $member_twitter ) : ?>
                    <a href="https://twitter.com/<?php echo esc_attr( ltrim( $member_twitter, '@' ) ); ?>"
                       class="bbm-member-single__contact-item"
                       target="_blank" rel="noopener noreferrer">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18" aria-hidden="true"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                        @<?php echo esc_html( ltrim( $member_twitter, '@' ) ); ?>
                    </a>
                    <?php endif; ?>
                </div>

                <a href="<?php echo esc_url( get_post_type_archive_link( 'bbm_member' ) ); ?>" class="bbm-btn bbm-btn--outline bbm-btn--sm">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    <?php esc_html_e( 'Tüm Üyeler', 'bitebimuv-dernek' ); ?>
                </a>
            </aside>

            <!-- Sağ: İçerik -->
            <div class="bbm-member-single__main">
                <?php if ( $member_title ) : ?>
                <p class="bbm-member-single__role">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                    <?php echo esc_html( $member_title ); ?>
                </p>
                <?php endif; ?>
                <div class="bbm-prose">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>
