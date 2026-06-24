<?php
/**
 * Footer template
 */
?>

  <footer class="site-footer">
    <div class="container">
      <p>
        &copy; <?php echo date( 'Y' ); ?>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
        &mdash; <?php _e( 'Tüm hakları saklıdır.', 'bitebimuv' ); ?>
      </p>
      <?php
      wp_nav_menu( [
        'theme_location' => 'footer',
        'container'      => false,
        'fallback_cb'    => false,
        'depth'          => 1,
      ] );
      ?>
      <?php wp_footer(); ?>
    </div>
  </footer>

</div><!-- #page -->
</body>
</html>
