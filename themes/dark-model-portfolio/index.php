<?php get_header(); ?>
<main class="single-portfolio-shell">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <article <?php post_class(); ?>>
      <p class="eyebrow"><?php esc_html_e('Journal', 'dark-model-portfolio'); ?></p>
      <h1><?php the_title(); ?></h1>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
  <?php endwhile; else : ?>
    <p><?php esc_html_e('Nothing found.', 'dark-model-portfolio'); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
