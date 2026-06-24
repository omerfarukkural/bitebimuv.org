<?php
/**
 * Kenar Çubuğu - v3.0
 *
 * @package bitebimuv-dernek
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside class="bbm-sidebar" id="bbm-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Kenar çubuğu', 'bitebimuv-dernek' ); ?>">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
