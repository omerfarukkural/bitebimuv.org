<?php
/**
 * Kenar Çubuğu
 *
 * @package bitebimuv-dernek
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) return;
?>
<aside class="bbm-sidebar" role="complementary">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
