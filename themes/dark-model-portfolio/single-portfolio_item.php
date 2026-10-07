<?php get_header(); ?>
<main class="single-portfolio-shell">
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
      <p class="eyebrow"><?php esc_html_e('Portfolio', 'dark-model-portfolio'); ?></p>
      <h1><?php the_title(); ?></h1>
      <?php if (has_post_thumbnail()) : ?><div class="single-image"><?php the_post_thumbnail('full'); ?></div><?php endif; ?>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
