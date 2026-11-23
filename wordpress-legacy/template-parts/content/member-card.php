<?php
/**
 * Üye Kart İçeriği
 *
 * @package bitebimuv-dernek
 */

$member_id    = get_the_ID();
$member_title = get_post_meta( $member_id, '_bbm_member_title', true );
$member_linkedin = get_post_meta( $member_id, '_bbm_member_linkedin', true );
$member_twitter  = get_post_meta( $member_id, '_bbm_member_twitter', true );
$member_email    = get_post_meta( $member_id, '_bbm_member_email', true );
?>

<article class="bbm-member-card" aria-labelledby="member-<?php echo $member_id; ?>">
    <div class="bbm-member-card__avatar">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php the_post_thumbnail( 'bbm-square', [ 'class' => 'bbm-member-card__img', 'loading' => 'lazy', 'alt' => get_the_title() ] ); ?>
        <?php else : ?>
            <div class="bbm-member-card__avatar-placeholder">
                <?php echo bbm_get_smiley( 'medium', 'bbm-member-card-' . $member_id ); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="bbm-member-card__body">
        <h3 class="bbm-member-card__name" id="member-<?php echo $member_id; ?>"><?php the_title(); ?></h3>
        <?php if ( $member_title ) : ?>
        <p class="bbm-member-card__title"><?php echo esc_html( $member_title ); ?></p>
        <?php endif; ?>
        <?php if ( has_excerpt() ) : ?>
        <p class="bbm-member-card__bio"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
        <?php endif; ?>
        <div class="bbm-member-card__social">
            <?php if ( $member_email ) : ?>
            <a href="mailto:<?php echo esc_attr( $member_email ); ?>" class="bbm-member-card__social-link" aria-label="E-posta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
            </a>
            <?php endif; ?>
            <?php if ( $member_linkedin ) : ?>
            <a href="<?php echo esc_url( $member_linkedin ); ?>" class="bbm-member-card__social-link" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                <svg viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
            </a>
            <?php endif; ?>
            <?php if ( $member_twitter ) : ?>
            <a href="https://twitter.com/<?php echo esc_attr( ltrim( $member_twitter, '@' ) ); ?>" class="bbm-member-card__social-link" aria-label="Twitter" target="_blank" rel="noopener noreferrer">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
            </a>
            <?php endif; ?>
        </div>
    </div>
</article>
