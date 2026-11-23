<?php
/**
 * İçerik Bulunamadı Şablonu
 *
 * @package bitebimuv-dernek
 */
?>
<div class="bbm-not-found">
    <?php echo bbm_get_smiley( 'medium', 'bbm-notfound-smiley' ); ?>
    <h2 class="bbm-not-found__title"><?php _e( 'Hiçbir şey bulunamadı.', 'bitebimuv-dernek' ); ?></h2>
    <p><?php _e( 'Aradığınızı bulamadık. Aramayı deneyin.', 'bitebimuv-dernek' ); ?></p>
    <?php get_search_form(); ?>
</div>
