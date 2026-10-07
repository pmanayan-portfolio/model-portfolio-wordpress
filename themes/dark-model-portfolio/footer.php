<?php $dmp = dmp_get_settings(); ?>
<footer class="footer">
  <a class="brand footer-brand" href="#top"><span class="brand-mark"><?php echo esc_html($dmp['brand_mark']); ?></span><span class="brand-text"><?php echo esc_html($dmp['brand_name']); ?> <i><?php echo esc_html($dmp['brand_accent']); ?></i></span></a>
  <p>© <?php echo esc_html(wp_date('Y')); ?> — <?php echo esc_html($dmp['footer_text']); ?></p>
  <a href="#top" class="back-top"><?php esc_html_e('Back to top ↑', 'dark-model-portfolio'); ?></a>
</footer>
<dialog class="lightbox" id="lightbox" aria-label="<?php esc_attr_e('Image preview', 'dark-model-portfolio'); ?>">
  <button class="lightbox-close" aria-label="<?php esc_attr_e('Close image preview', 'dark-model-portfolio'); ?>">×</button>
  <img src="" alt="<?php esc_attr_e('Expanded portfolio image', 'dark-model-portfolio'); ?>">
</dialog>
<?php wp_footer(); ?>
</body>
</html>
