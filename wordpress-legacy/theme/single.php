<?php get_header(); ?>

<main class="site-content">
  <div class="container" style="max-width:800px">
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header" style="margin-bottom:32px">
          <h1 style="font-size:2rem;font-weight:800;line-height:1.3"><?php the_title(); ?></h1>
          <div class="post-meta" style="margin-top:12px;color:#888;font-size:.9rem">
            <time><?php echo get_the_date(); ?></time>
            &bull; <?php the_author(); ?>
          </div>
        </header>
        <?php if ( has_post_thumbnail() ) : ?>
          <div style="margin-bottom:32px;border-radius:12px;overflow:hidden">
            <?php the_post_thumbnail( 'full' ); ?>
          </div>
        <?php endif; ?>
        <div class="entry-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
