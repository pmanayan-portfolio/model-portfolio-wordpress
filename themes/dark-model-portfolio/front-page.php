<?php
get_header();
$s = dmp_get_settings();
$hero_image = $s['hero_image'] ?: dmp_img('editorial-01.webp');
$profile_image = $s['profile_image'] ?: dmp_img('editorial-10.webp');
$marquee = array_values(array_filter(array_map('trim', explode(',', $s['marquee_items']))));
if (!$marquee) $marquee = ['EDITORIAL','FASHION','BEAUTY','LIFESTYLE'];

$featured_query = new WP_Query([
    'post_type' => 'portfolio_item',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'meta_key' => '_dmp_order',
    'orderby' => ['meta_value_num'=>'ASC','date'=>'DESC'],
    'meta_query' => [[ 'key'=>'_dmp_featured', 'value'=>'1' ]],
]);
?>
<main>
  <section class="hero" id="home">
    <div class="hero-copy reveal">
      <p class="eyebrow"><?php echo esc_html($s['hero_eyebrow']); ?></p>
      <h1><?php echo esc_html($s['hero_title']); ?><br><em><?php echo esc_html($s['hero_title_emphasis']); ?></em></h1>
      <p class="hero-intro"><?php echo esc_html($s['hero_intro']); ?></p>
      <div class="hero-actions">
        <a class="button button-primary" href="<?php echo esc_url($s['hero_primary_url']); ?>"><?php echo esc_html($s['hero_primary_label']); ?></a>
        <a class="text-link" href="<?php echo esc_url($s['hero_secondary_url']); ?>"><?php echo esc_html($s['hero_secondary_label']); ?> <span>↗</span></a>
      </div>
      <div class="hero-meta" aria-label="<?php esc_attr_e('Portfolio specialties', 'dark-model-portfolio'); ?>">
        <?php for ($i=1;$i<=3;$i++) : ?>
          <div><span><?php echo esc_html($s["hero_meta_{$i}_number"]); ?></span><p><?php echo esc_html($s["hero_meta_{$i}_label"]); ?></p></div>
        <?php endfor; ?>
      </div>
    </div>
    <div class="hero-visual reveal reveal-delay">
      <figure class="hero-image-wrap">
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($s['hero_caption_left']); ?>" class="hero-image">
        <figcaption><span><?php echo esc_html($s['hero_caption_left']); ?></span><span><?php echo esc_html($s['hero_caption_right']); ?></span></figcaption>
      </figure>
      <p class="vertical-label"><?php echo esc_html($s['hero_vertical']); ?></p>
    </div>
  </section>

  <section class="marquee" aria-label="<?php esc_attr_e('Specialties', 'dark-model-portfolio'); ?>">
    <div class="marquee-track">
      <?php for ($loop=0;$loop<2;$loop++) : foreach ($marquee as $item) : ?>
        <span><?php echo esc_html($item); ?></span><i>✦</i>
      <?php endforeach; endfor; ?>
    </div>
  </section>

  <section class="featured section-pad">
    <div class="section-heading reveal">
      <p class="eyebrow"><?php echo esc_html($s['featured_eyebrow']); ?></p>
      <h2><?php echo esc_html($s['featured_title']); ?><br><em><?php echo esc_html($s['featured_title_emphasis']); ?></em></h2>
      <p><?php echo esc_html($s['featured_text']); ?></p>
    </div>
    <div class="featured-grid">
      <?php if ($featured_query->have_posts()) : $count=0; while ($featured_query->have_posts()) : $featured_query->the_post(); $count++; $thumb = get_the_post_thumbnail_url(get_the_ID(), 'full'); $terms = get_the_terms(get_the_ID(),'portfolio_category'); $term_name = ($terms && !is_wp_error($terms)) ? $terms[0]->name : __('Portfolio','dark-model-portfolio'); ?>
        <button class="image-card <?php echo $count===1 ? 'tall ' : ''; ?>reveal" data-full="<?php echo esc_url($thumb); ?>" aria-label="<?php echo esc_attr(sprintf(__('Open %s','dark-model-portfolio'), get_the_title())); ?>">
          <?php if ($thumb) : ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy"><?php endif; ?>
          <span class="card-caption"><b><?php the_title(); ?></b><small><?php echo esc_html($term_name); ?></small></span>
        </button>
      <?php endwhile; wp_reset_postdata(); else :
        $defaults = [
          ['editorial-04.webp','Velvet Story','Editorial'],
          ['editorial-05.webp','Monochrome','Beauty'],
          ['editorial-06.webp','Afterglow','Fashion'],
        ];
        foreach ($defaults as $i=>$item) : ?>
          <button class="image-card <?php echo $i===0 ? 'tall ' : ''; ?>reveal" data-full="<?php echo esc_url(dmp_img($item[0])); ?>">
            <img src="<?php echo esc_url(dmp_img($item[0])); ?>" alt="<?php echo esc_attr($item[1]); ?>" loading="lazy">
            <span class="card-caption"><b><?php echo esc_html($item[1]); ?></b><small><?php echo esc_html($item[2]); ?></small></span>
          </button>
        <?php endforeach; endif; ?>
    </div>
  </section>

  <section class="profile section-pad" id="profile">
    <div class="profile-image reveal">
      <img src="<?php echo esc_url($profile_image); ?>" alt="<?php echo esc_attr($s['profile_title']); ?>" loading="lazy">
      <span class="image-index"><?php echo esc_html($s['profile_image_index']); ?></span>
    </div>
    <div class="profile-copy reveal">
      <p class="eyebrow"><?php echo esc_html($s['profile_eyebrow']); ?></p>
      <h2><?php echo esc_html($s['profile_title']); ?><br><em><?php echo esc_html($s['profile_title_emphasis']); ?></em></h2>
      <p class="profile-lead"><?php echo esc_html($s['profile_lead']); ?></p>
      <p><?php echo esc_html($s['profile_text']); ?></p>
      <div class="profile-list">
        <?php for ($i=1;$i<=4;$i++) : ?><div><span><?php echo esc_html($s["profile_row_{$i}_label"]); ?></span><strong><?php echo esc_html($s["profile_row_{$i}_value"]); ?></strong></div><?php endfor; ?>
      </div>
    </div>
  </section>

  <section class="portfolio section-pad" id="portfolio">
    <div class="portfolio-top reveal">
      <div><p class="eyebrow"><?php echo esc_html($s['portfolio_eyebrow']); ?></p><h2><?php echo esc_html($s['portfolio_title']); ?> <em><?php echo esc_html($s['portfolio_title_emphasis']); ?></em></h2></div>
      <div class="filters" role="group" aria-label="<?php esc_attr_e('Filter portfolio', 'dark-model-portfolio'); ?>">
        <button class="filter is-active" data-filter="all"><?php esc_html_e('All', 'dark-model-portfolio'); ?></button>
        <?php $filter_terms = get_terms(['taxonomy'=>'portfolio_category','hide_empty'=>true]); if (!is_wp_error($filter_terms) && $filter_terms) : foreach ($filter_terms as $term) : ?>
          <button class="filter" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
        <?php endforeach; else : ?>
          <button class="filter" data-filter="editorial">Editorial</button><button class="filter" data-filter="lifestyle">Lifestyle</button>
        <?php endif; ?>
      </div>
    </div>
    <div class="gallery" aria-live="polite">
      <?php
      $portfolio_query = new WP_Query([
        'post_type'=>'portfolio_item','post_status'=>'publish','posts_per_page'=>-1,
        'meta_key'=>'_dmp_order','orderby'=>['meta_value_num'=>'ASC','date'=>'DESC']
      ]);
      if ($portfolio_query->have_posts()) : while ($portfolio_query->have_posts()) : $portfolio_query->the_post();
        $thumb = get_the_post_thumbnail_url(get_the_ID(),'full'); if (!$thumb) continue;
        $terms = get_the_terms(get_the_ID(),'portfolio_category');
        $slugs = ($terms && !is_wp_error($terms)) ? implode(' ', wp_list_pluck($terms,'slug')) : '';
      ?>
        <button class="gallery-item portrait reveal" data-category="<?php echo esc_attr($slugs); ?>" data-full="<?php echo esc_url($thumb); ?>"><img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy"></button>
      <?php endwhile; wp_reset_postdata(); else :
        $fallback = [
          ['editorial-02.webp','editorial','Studio white shirt editorial'],['editorial-03.webp','editorial','Luxury street-style editorial'],['editorial-07.webp','editorial','White couture staircase portrait'],['editorial-08.webp','editorial','Night city fashion editorial'],['editorial-09.webp','editorial','Dark tailored studio portrait'],['editorial-01.webp','editorial','Black tailored fashion studio portrait'],
          ['casual-01.webp','lifestyle','Natural city sidewalk portrait'],['casual-02.webp','lifestyle','Warm cafe lifestyle portrait'],['casual-03.webp','lifestyle','Park lifestyle portrait'],['casual-04.webp','lifestyle','Denim wall portrait'],['casual-05.webp','lifestyle','Outdoor steps lifestyle portrait'],['casual-06.webp','lifestyle','Bookstore lifestyle portrait'],['casual-07.webp','lifestyle','Golden hour portrait'],['casual-08.webp','lifestyle','Campus lifestyle portrait'],['casual-09.webp','lifestyle','Window lifestyle portrait'],['casual-10.webp','lifestyle','Crosswalk street portrait']
        ];
        foreach ($fallback as $item) : ?>
          <button class="gallery-item portrait reveal" data-category="<?php echo esc_attr($item[1]); ?>" data-full="<?php echo esc_url(dmp_img($item[0])); ?>"><img src="<?php echo esc_url(dmp_img($item[0])); ?>" alt="<?php echo esc_attr($item[2]); ?>" loading="lazy"></button>
        <?php endforeach; endif; ?>
    </div>
  </section>

  <section class="statement section-pad">
    <div class="statement-inner reveal"><p class="eyebrow"><?php echo esc_html($s['statement_eyebrow']); ?></p><blockquote><?php echo esc_html($s['statement_text']); ?><br><em><?php echo esc_html($s['statement_emphasis']); ?></em></blockquote></div>
  </section>

  <section class="contact section-pad" id="contact">
    <div class="contact-copy reveal"><p class="eyebrow"><?php echo esc_html($s['contact_eyebrow']); ?></p><h2><?php echo esc_html($s['contact_title']); ?><br><em><?php echo esc_html($s['contact_title_emphasis']); ?></em></h2><p><?php echo esc_html($s['contact_text']); ?></p></div>
    <div class="contact-card reveal">
      <p><?php echo esc_html($s['contact_email_label']); ?></p>
      <a href="mailto:<?php echo esc_attr(antispambot($s['contact_email'])); ?>"><?php echo esc_html(antispambot($s['contact_email'])); ?></a>
      <div class="contact-rule"></div>
      <div class="contact-links">
        <?php if ($s['contact_link_1_label']) : ?><a href="<?php echo esc_url($s['contact_link_1_url']); ?>"><?php echo esc_html($s['contact_link_1_label']); ?> <span>↗</span></a><?php endif; ?>
        <?php if ($s['contact_link_2_label']) : ?><a href="<?php echo esc_url($s['contact_link_2_url']); ?>"><?php echo esc_html($s['contact_link_2_label']); ?> <span>↗</span></a><?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
