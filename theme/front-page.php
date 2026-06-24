<?php get_header(); ?>

<!-- Hero -->
<section class="hero">
  <div class="container">
    <h1>Mülteciler için <span>Umut</span>,<br>Toplum için <span>Dayanışma</span></h1>
    <p>Bitebimuv.org — Mültecilere eğitim, barınma, hukuki destek ve sosyal entegrasyon imkânı sağlayan platform.</p>
    <div>
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'bagis' ) ) ); ?>" class="btn btn-primary">Bağış Yap</a>
      <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'gonullu-ol' ) ) ); ?>" class="btn btn-outline">Gönüllü Ol</a>
    </div>
  </div>
</section>

<!-- Son Yazılar -->
<section class="site-content">
  <div class="container">
    <h2 style="font-size:1.8rem;font-weight:700;margin-bottom:8px">Son Haberler</h2>
    <p style="color:#666">Platformumuzdan en güncel gelişmeler</p>

    <?php
    $recent = new WP_Query( [ 'posts_per_page' => 6, 'post_status' => 'publish' ] );
    if ( $recent->have_posts() ) :
    ?>
    <div class="posts-grid">
      <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
        <article class="post-card">
          <?php if ( has_post_thumbnail() ) : ?>
            <div style="height:200px;overflow:hidden">
              <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large', [ 'style' => 'width:100%;height:200px;object-fit:cover' ] ); ?></a>
            </div>
          <?php endif; ?>
          <div class="post-card-body">
            <div class="post-meta"><time><?php echo get_the_date(); ?></time></div>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="post-excerpt"><?php the_excerpt(); ?></div>
          </div>
        </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
