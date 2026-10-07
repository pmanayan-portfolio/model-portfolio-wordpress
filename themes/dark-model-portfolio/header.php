<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); $dmp = dmp_get_settings(); ?>
<div class="grain" aria-hidden="true"></div>
<header class="site-header" id="top">
  <?php if (has_custom_logo()) : ?>
    <?php the_custom_logo(); ?>
  <?php else : ?>
    <a class="brand" href="<?php echo esc_url(home_url('/#home')); ?>" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?> home">
      <span class="brand-mark"><?php echo esc_html($dmp['brand_mark']); ?></span>
      <span class="brand-text"><?php echo esc_html($dmp['brand_name']); ?> <i><?php echo esc_html($dmp['brand_accent']); ?></i></span>
    </a>
  <?php endif; ?>
  <button class="menu-toggle" aria-label="<?php esc_attr_e('Open menu', 'dark-model-portfolio'); ?>" aria-expanded="false"><span></span><span></span></button>
  <nav class="nav" aria-label="<?php esc_attr_e('Primary navigation', 'dark-model-portfolio'); ?>">
    <?php
      if (has_nav_menu('primary')) {
        wp_nav_menu(['theme_location'=>'primary','container'=>false,'menu_class'=>'menu','fallback_cb'=>false,'depth'=>1]);
      } else {
        dmp_nav_fallback();
      }
    ?>
  </nav>
</header>
