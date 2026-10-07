<?php
if (!defined('ABSPATH')) exit;

require_once get_template_directory() . '/inc/admin-settings.php';

function dmp_theme_setup() {
    load_theme_textdomain('dark-model-portfolio', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', [
        'height' => 80,
        'width' => 280,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus([
        'primary' => __('Primary Navigation', 'dark-model-portfolio'),
    ]);
}
add_action('after_setup_theme', 'dmp_theme_setup');

function dmp_enqueue_assets() {
    wp_enqueue_style('dmp-fonts', 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=Inter:wght@300;400;500;600&display=swap', [], null);
    wp_enqueue_style('dmp-style', get_stylesheet_uri(), ['dmp-fonts'], wp_get_theme()->get('Version'));
    wp_enqueue_script('dmp-theme', get_template_directory_uri() . '/assets/js/theme.js', [], wp_get_theme()->get('Version'), true);
}
add_action('wp_enqueue_scripts', 'dmp_enqueue_assets');

function dmp_register_portfolio() {
    $labels = [
        'name' => __('Portfolio', 'dark-model-portfolio'),
        'singular_name' => __('Portfolio Item', 'dark-model-portfolio'),
        'add_new' => __('Add New', 'dark-model-portfolio'),
        'add_new_item' => __('Add New Portfolio Item', 'dark-model-portfolio'),
        'edit_item' => __('Edit Portfolio Item', 'dark-model-portfolio'),
        'new_item' => __('New Portfolio Item', 'dark-model-portfolio'),
        'view_item' => __('View Portfolio Item', 'dark-model-portfolio'),
        'search_items' => __('Search Portfolio', 'dark-model-portfolio'),
        'not_found' => __('No portfolio items found.', 'dark-model-portfolio'),
        'menu_name' => __('Model Portfolio', 'dark-model-portfolio'),
    ];

    register_post_type('portfolio_item', [
        'labels' => $labels,
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-format-gallery',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive' => false,
        'rewrite' => ['slug' => 'portfolio'],
        'menu_position' => 20,
    ]);

    register_taxonomy('portfolio_category', ['portfolio_item'], [
        'labels' => [
            'name' => __('Portfolio Categories', 'dark-model-portfolio'),
            'singular_name' => __('Portfolio Category', 'dark-model-portfolio'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => false,
        'rewrite' => ['slug' => 'portfolio-category'],
        'show_admin_column' => true,
    ]);
}
add_action('init', 'dmp_register_portfolio');

function dmp_portfolio_meta_boxes() {
    add_meta_box('dmp_portfolio_display', __('Portfolio Display', 'dark-model-portfolio'), 'dmp_portfolio_display_box', 'portfolio_item', 'side', 'default');
}
add_action('add_meta_boxes', 'dmp_portfolio_meta_boxes');

function dmp_portfolio_display_box($post) {
    wp_nonce_field('dmp_save_portfolio_meta', 'dmp_portfolio_nonce');
    $featured = get_post_meta($post->ID, '_dmp_featured', true);
    $order = get_post_meta($post->ID, '_dmp_order', true);
    ?>
    <p><label><input type="checkbox" name="dmp_featured" value="1" <?php checked($featured, '1'); ?>> <?php esc_html_e('Show in Selected Work', 'dark-model-portfolio'); ?></label></p>
    <p><label for="dmp_order"><strong><?php esc_html_e('Display order', 'dark-model-portfolio'); ?></strong></label></p>
    <p><input id="dmp_order" name="dmp_order" type="number" min="0" step="1" value="<?php echo esc_attr($order !== '' ? $order : 0); ?>" style="width:100%"></p>
    <p class="description"><?php esc_html_e('Lower numbers appear first.', 'dark-model-portfolio'); ?></p>
    <?php
}

function dmp_save_portfolio_meta($post_id) {
    if (!isset($_POST['dmp_portfolio_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dmp_portfolio_nonce'])), 'dmp_save_portfolio_meta')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, '_dmp_featured', isset($_POST['dmp_featured']) ? '1' : '0');
    $order = isset($_POST['dmp_order']) ? absint($_POST['dmp_order']) : 0;
    update_post_meta($post_id, '_dmp_order', $order);
}
add_action('save_post_portfolio_item', 'dmp_save_portfolio_meta');

function dmp_switch_theme() {
    dmp_register_portfolio();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'dmp_switch_theme');

function dmp_get_settings() {
    return wp_parse_args((array) get_option('dmp_settings', []), dmp_default_settings());
}

function dmp_img($relative) {
    return get_template_directory_uri() . '/assets/images/' . ltrim($relative, '/');
}

function dmp_nav_fallback() {
    echo '<a href="' . esc_url(home_url('/#home')) . '">Home</a>';
    echo '<a href="' . esc_url(home_url('/#portfolio')) . '">Portfolio</a>';
    echo '<a href="' . esc_url(home_url('/#profile')) . '">Profile</a>';
    echo '<a href="' . esc_url(home_url('/#contact')) . '">Contact</a>';
}

function dmp_get_image_alt($attachment_id, $fallback = '') {
    $alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
    return $alt ? $alt : $fallback;
}
