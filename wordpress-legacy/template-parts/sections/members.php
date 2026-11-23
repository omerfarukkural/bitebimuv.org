<?php
/**
 * Yönetim Kurulu / Ekip Üyeleri Bölümü
 *
 * @package bitebimuv-dernek
 */

$members_query = new WP_Query( [
    'post_type'      => 'bbm_member',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
    'meta_key'       => '_bbm_member_order',
    'orderby'        => 'meta_value_num',
    'order'          => 'ASC',
] );

if ( ! $members_query->have_posts() ) return;
?>

<section class="bbm-section bbm-members-section" id="ekibimiz" aria-labelledby="bbm-members-title">
    <div class="bbm-container">

        <header class="bbm-section-header" data-aos="fade-up">
            <span class="bbm-section-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                <?php esc_html_e( 'Yönetim Kurulu', 'bitebimuv-dernek' ); ?>
            </span>
            <h2 id="bbm-members-title" class="bbm-section-title">
                <?php esc_html_e( 'Bizimle Çalışan', 'bitebimuv-dernek' ); ?>
                <span class="bbm-gradient-text"><?php esc_html_e( 'Değerli Ekip', 'bitebimuv-dernek' ); ?></span>
            </h2>
            <p class="bbm-section-subtitle">
                <?php esc_html_e( 'Derneğimizi yöneten ve geliştiren deneyimli yönetim kurulu üyelerimiz.', 'bitebimuv-dernek' ); ?>
            </p>
        </header>

        <div class="bbm-members-grid" role="list">
            <?php while ( $members_query->have_posts() ) : $members_query->the_post();
                $member_id    = get_the_ID();
                $member_title = get_post_meta( $member_id, '_bbm_member_title', true );
                $member_email = get_post_meta( $member_id, '_bbm_member_email', true );
                $member_phone = get_post_meta( $member_id, '_bbm_member_phone', true );
                $member_linkedin = get_post_meta( $member_id, '_bbm_member_linkedin', true );
                $member_twitter  = get_post_meta( $member_id, '_bbm_member_twitter', true );
            ?>
            <article class="bbm-member-card" role="listitem" data-aos="fade-up" data-aos-delay="<?php echo $members_query->current_post * 80; ?>">
                <div class="bbm-member-card__avatar">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'bbm-square', [
                        'class' => 'bbm-member-card__img',
                        'alt'   => get_the_title(),
                        'loading' => 'lazy',
                    ] ); ?>
                    <?php else : ?>
                    <div class="bbm-member-card__avatar-placeholder" aria-hidden="true">
                        <?php echo bbm_get_smiley( 'medium', 'bbm-member-' . $member_id . '-smiley' ); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="bbm-member-card__body">
                    <h3 class="bbm-member-card__name"><?php the_title(); ?></h3>
                    <?php if ( $member_title ) : ?>
                    <p class="bbm-member-card__title"><?php echo esc_html( $member_title ); ?></p>
                    <?php endif; ?>
                    <?php if ( has_excerpt() ) : ?>
                    <p class="bbm-member-card__bio"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                    <?php endif; ?>
                    <div class="bbm-member-card__social">
                        <?php if ( $member_email ) : ?>
                        <a href="mailto:<?php echo esc_attr( $member_email ); ?>" class="bbm-member-card__social-link"
                           aria-label="<?php printf( esc_attr__( '%s e-posta', 'bitebimuv-dernek' ), get_the_title() ); ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </a>
                        <?php endif; ?>
                        <?php if ( $member_linkedin ) : ?>
                        <a href="<?php echo esc_url( $member_linkedin ); ?>" class="bbm-member-card__social-link"
                           aria-label="<?php printf( esc_attr__( '%s LinkedIn profili', 'bitebimuv-dernek' ), get_the_title() ); ?>"
                           target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        </a>
                        <?php endif; ?>
                        <?php if ( $member_twitter ) : ?>
                        <a href="https://twitter.com/<?php echo esc_attr( ltrim( $member_twitter, '@' ) ); ?>"
                           class="bbm-member-card__social-link"
                           aria-label="<?php printf( esc_attr__( '%s Twitter profili', 'bitebimuv-dernek' ), get_the_title() ); ?>"
                           target="_blank" rel="noopener noreferrer">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16" aria-hidden="true"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    </div>
</section>
