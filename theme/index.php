<?php get_header(); ?>

<main class="site-content">
  <div class="container">
    <?php if ( is_home() && ! is_front_page() ) : ?>
      <header class="page-header">
        <h1 class="page-title"><?php single_post_title(); ?></h1>
      </header>
    <?php endif; ?>

    <?php if ( have_posts() ) : ?>
      <div class="posts-grid">
        <?php while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail( 'medium_large' ); ?>
                </a>
              </div>
            <?php endif; ?>
            <div class="post-card-body">
              <div class="post-meta">
                <time datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date(); ?></time>
              </div>
              <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <div class="post-excerpt"><?php the_excerpt(); ?></div>
              <a href="<?php the_permalink(); ?>" class="btn btn-primary" style="margin-top:16px;font-size:.9rem;padding:10px 20px;"><?php _e( 'Devamını Oku', 'bitebimuv' ); ?></a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>

      <?php the_posts_navigation(); ?>

    <?php else : ?>
      <p><?php _e( 'İçerik bulunamadı.', 'bitebimuv' ); ?></p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
