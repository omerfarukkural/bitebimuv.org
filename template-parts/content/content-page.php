<?php
/**
 * Sayfa İçerigi
 *
 * @package bitebimuv-dernek
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'bbm-page-content' ); ?>>
    <header class="bbm-page-content__header">
        <h1 class="bbm-page-content__title"><?php the_title(); ?></h1>
    </header>
    <div class="bbm-page-content__body entry-content">
        <?php the_content(); ?>
    </div>
</article>
