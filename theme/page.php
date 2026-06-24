<?php get_header(); ?>

<main class="site-content">
  <div class="container" style="max-width:900px">
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header style="margin-bottom:32px">
          <h1 style="font-size:2.2rem;font-weight:800"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content"><?php the_content(); ?></div>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php get_footer(); ?>
