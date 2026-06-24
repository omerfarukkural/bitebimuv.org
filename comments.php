<?php
/**
 * Yorumlar Şablonu
 *
 * @package bitebimuv-dernek
 */

if ( post_password_required() ) return;
?>
<div id="bbm-comments" class="bbm-comments">

    <?php if ( have_comments() ) : ?>
    <h2 class="bbm-comments__title">
        <?php printf(
            _n( '%s Yorum', '%s Yorum', get_comments_number(), 'bitebimuv-dernek' ),
            number_format_i18n( get_comments_number() )
        ); ?>
    </h2>
    <ol class="bbm-comment-list">
        <?php
        wp_list_comments( [
            'style'      => 'ol',
            'short_ping' => true,
            'avatar_size' => 48,
        ] );
        ?>
    </ol>
    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    <nav class="bbm-comment-nav">
        <?php
        paginate_comments_links( [
            'prev_text' => '&larr; ' . __( 'Önceki', 'bitebimuv-dernek' ),
            'next_text' => __( 'Sonraki', 'bitebimuv-dernek' ) . ' &rarr;',
        ] );
        ?>
    </nav>
    <?php endif; ?>
    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() > 0 ) : ?>
    <p class="bbm-comments-closed"><?php _e( 'Yorumlar kapatıldı.', 'bitebimuv-dernek' ); ?></p>
    <?php endif; ?>

    <?php comment_form( [
        'class_form'   => 'bbm-comment-form',
        'class_submit' => 'bbm-btn bbm-btn--primary',
        'title_reply'  => __( 'Yorum Yaz', 'bitebimuv-dernek' ),
        'label_submit' => __( 'Gönder', 'bitebimuv-dernek' ),
    ] ); ?>

</div><!-- #bbm-comments -->
