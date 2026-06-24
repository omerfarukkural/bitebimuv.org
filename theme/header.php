<?php
/**
 * Header template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

  <header class="site-header">
    <div class="container">
      <div class="site-logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
            <span>bitebim<span>uv</span>.org</span>
          <?php endif; ?>
        </a>
      </div>

      <nav class="main-nav" aria-label="<?php esc_attr_e( 'Ana Menü', 'bitebimuv' ); ?>">
        <?php
        wp_nav_menu( [
          'theme_location' => 'primary',
          'menu_id'        => 'primary-menu',
          'container'      => false,
          'fallback_cb'    => false,
        ] );
        ?>
      </nav>
    </div>
  </header>
