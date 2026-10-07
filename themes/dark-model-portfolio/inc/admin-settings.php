<?php
if (!defined('ABSPATH')) exit;

function dmp_default_settings() {
    return [
        'brand_mark' => 'M',
        'brand_name' => 'MODEL',
        'brand_accent' => 'PORTFOLIO',
        'hero_eyebrow' => 'Editorial · Fashion · Lifestyle',
        'hero_title' => 'Quiet presence.',
        'hero_title_emphasis' => 'Strong image.',
        'hero_intro' => 'A refined modeling portfolio built around editorial storytelling, modern fashion, and cinematic portraiture.',
        'hero_primary_label' => 'View portfolio',
        'hero_primary_url' => '#portfolio',
        'hero_secondary_label' => 'Bookings',
        'hero_secondary_url' => '#contact',
        'hero_image' => '',
        'hero_caption_left' => 'Selected portrait',
        'hero_caption_right' => '2026 / 01',
        'hero_vertical' => 'MODEL · EDITORIAL · PORTFOLIO',
        'hero_meta_1_number' => '01', 'hero_meta_1_label' => 'Editorial',
        'hero_meta_2_number' => '02', 'hero_meta_2_label' => 'Fashion',
        'hero_meta_3_number' => '03', 'hero_meta_3_label' => 'Lifestyle',
        'marquee_items' => 'EDITORIAL, FASHION, BEAUTY, LIFESTYLE',
        'featured_eyebrow' => 'Selected work',
        'featured_title' => 'Images that feel',
        'featured_title_emphasis' => 'intentional.',
        'featured_text' => 'Minimal styling, controlled light, and a cinematic palette keep the focus on expression and presence.',
        'profile_eyebrow' => 'Profile',
        'profile_title' => 'Modern elegance,',
        'profile_title_emphasis' => 'without excess.',
        'profile_lead' => 'A portfolio designed to feel sophisticated and versatile — equally at home in clean studio work, beauty campaigns, street fashion, and natural lifestyle imagery.',
        'profile_text' => 'The site intentionally keeps personal measurements and representation details editable, so you can add verified information before publishing.',
        'profile_image' => '',
        'profile_image_index' => 'PROFILE / 01',
        'profile_row_1_label' => 'Focus', 'profile_row_1_value' => 'Editorial / Fashion',
        'profile_row_2_label' => 'Additional', 'profile_row_2_value' => 'Beauty / Lifestyle',
        'profile_row_3_label' => 'Availability', 'profile_row_3_value' => 'On request',
        'profile_row_4_label' => 'Representation', 'profile_row_4_value' => 'Open / Editable',
        'portfolio_eyebrow' => 'Portfolio',
        'portfolio_title' => 'Selected',
        'portfolio_title_emphasis' => 'frames.',
        'statement_eyebrow' => 'Visual direction',
        'statement_text' => '“Elegant enough to feel timeless.',
        'statement_emphasis' => 'Dark enough to feel cinematic.”',
        'contact_eyebrow' => 'Bookings & collaborations',
        'contact_title' => 'Let’s create something',
        'contact_title_emphasis' => 'memorable.',
        'contact_text' => 'For castings, campaigns, collaborations, and portfolio inquiries.',
        'contact_email_label' => 'Booking email',
        'contact_email' => 'booking@yourdomain.com',
        'contact_link_1_label' => 'Instagram',
        'contact_link_1_url' => '#',
        'contact_link_2_label' => 'Portfolio PDF',
        'contact_link_2_url' => '#',
        'footer_text' => 'Editorial portfolio.',
    ];
}

function dmp_register_settings() {
    register_setting('dmp_settings_group', 'dmp_settings', [
        'type' => 'array',
        'sanitize_callback' => 'dmp_sanitize_settings',
        'default' => dmp_default_settings(),
    ]);
}
add_action('admin_init', 'dmp_register_settings');

function dmp_sanitize_settings($input) {
    $defaults = dmp_default_settings();
    $output = [];
    $url_keys = ['hero_primary_url','hero_secondary_url','hero_image','profile_image','contact_link_1_url','contact_link_2_url'];
    $email_keys = ['contact_email'];
    $textarea_keys = ['hero_intro','featured_text','profile_lead','profile_text','contact_text'];

    foreach ($defaults as $key => $default) {
        $value = isset($input[$key]) ? wp_unslash($input[$key]) : $default;
        if (in_array($key, $url_keys, true)) {
            $output[$key] = esc_url_raw($value);
        } elseif (in_array($key, $email_keys, true)) {
            $output[$key] = sanitize_email($value);
        } elseif (in_array($key, $textarea_keys, true)) {
            $output[$key] = sanitize_textarea_field($value);
        } else {
            $output[$key] = sanitize_text_field($value);
        }
    }
    return $output;
}

function dmp_add_settings_page() {
    add_submenu_page(
        'edit.php?post_type=portfolio_item',
        __('Portfolio Settings', 'dark-model-portfolio'),
        __('Portfolio Settings', 'dark-model-portfolio'),
        'manage_options',
        'dmp-settings',
        'dmp_render_settings_page'
    );
}
add_action('admin_menu', 'dmp_add_settings_page');

function dmp_admin_assets($hook) {
    if ($hook !== 'portfolio_item_page_dmp-settings') return;
    wp_enqueue_media();
    wp_enqueue_script('dmp-admin', get_template_directory_uri() . '/assets/js/admin.js', ['jquery'], wp_get_theme()->get('Version'), true);
    wp_enqueue_style('dmp-admin', get_template_directory_uri() . '/assets/admin.css', [], wp_get_theme()->get('Version'));
}
add_action('admin_enqueue_scripts', 'dmp_admin_assets');

function dmp_field($settings, $key, $label, $type = 'text', $description = '') {
    $value = isset($settings[$key]) ? $settings[$key] : '';
    echo '<tr><th scope="row"><label for="dmp_' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td>';
    if ($type === 'textarea') {
        echo '<textarea class="large-text" rows="4" id="dmp_' . esc_attr($key) . '" name="dmp_settings[' . esc_attr($key) . ']">' . esc_textarea($value) . '</textarea>';
    } elseif ($type === 'image') {
        echo '<div class="dmp-image-field">';
        echo '<input class="regular-text dmp-image-url" type="url" id="dmp_' . esc_attr($key) . '" name="dmp_settings[' . esc_attr($key) . ']" value="' . esc_attr($value) . '"> ';
        echo '<button type="button" class="button dmp-select-image">' . esc_html__('Choose image', 'dark-model-portfolio') . '</button>';
        echo '<div class="dmp-image-preview">';
        if ($value) echo '<img src="' . esc_url($value) . '" alt="">';
        echo '</div></div>';
    } elseif ($type === 'email') {
        echo '<input class="regular-text" type="email" id="dmp_' . esc_attr($key) . '" name="dmp_settings[' . esc_attr($key) . ']" value="' . esc_attr($value) . '">';
    } elseif ($type === 'url') {
        echo '<input class="regular-text" type="text" id="dmp_' . esc_attr($key) . '" name="dmp_settings[' . esc_attr($key) . ']" value="' . esc_attr($value) . '">';
    } else {
        echo '<input class="regular-text" type="text" id="dmp_' . esc_attr($key) . '" name="dmp_settings[' . esc_attr($key) . ']" value="' . esc_attr($value) . '">';
    }
    if ($description) echo '<p class="description">' . esc_html($description) . '</p>';
    echo '</td></tr>';
}

function dmp_section_title($title, $description = '') {
    echo '<h2>' . esc_html($title) . '</h2>';
    if ($description) echo '<p class="dmp-section-description">' . esc_html($description) . '</p>';
}

function dmp_render_settings_page() {
    if (!current_user_can('manage_options')) return;
    $s = dmp_get_settings();
    ?>
    <div class="wrap dmp-settings-wrap">
        <h1><?php esc_html_e('Model Portfolio Settings', 'dark-model-portfolio'); ?></h1>
        <p><?php esc_html_e('Edit the main one-page sections here. Portfolio photos and categories are managed from the Model Portfolio menu.', 'dark-model-portfolio'); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields('dmp_settings_group'); ?>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Branding'); ?>
                <table class="form-table" role="presentation">
                    <?php dmp_field($s,'brand_mark','Brand mark'); dmp_field($s,'brand_name','Brand name'); dmp_field($s,'brand_accent','Brand accent'); ?>
                </table>
            </div>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Hero Section','Change the main headline, buttons, image and three specialties.'); ?>
                <table class="form-table" role="presentation">
                    <?php
                    dmp_field($s,'hero_eyebrow','Eyebrow'); dmp_field($s,'hero_title','Headline'); dmp_field($s,'hero_title_emphasis','Headline emphasis');
                    dmp_field($s,'hero_intro','Intro','textarea'); dmp_field($s,'hero_primary_label','Primary button label'); dmp_field($s,'hero_primary_url','Primary button link','url');
                    dmp_field($s,'hero_secondary_label','Secondary link label'); dmp_field($s,'hero_secondary_url','Secondary link URL','url'); dmp_field($s,'hero_image','Hero image','image');
                    dmp_field($s,'hero_caption_left','Image caption left'); dmp_field($s,'hero_caption_right','Image caption right'); dmp_field($s,'hero_vertical','Vertical label');
                    for ($i=1;$i<=3;$i++) { dmp_field($s,"hero_meta_{$i}_number","Specialty {$i} number"); dmp_field($s,"hero_meta_{$i}_label","Specialty {$i} label"); }
                    ?>
                </table>
            </div>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Marquee & Selected Work','Featured cards use portfolio items marked “Show in Selected Work”.'); ?>
                <table class="form-table" role="presentation">
                    <?php dmp_field($s,'marquee_items','Marquee items','text','Separate items with commas.'); dmp_field($s,'featured_eyebrow','Eyebrow'); dmp_field($s,'featured_title','Heading'); dmp_field($s,'featured_title_emphasis','Heading emphasis'); dmp_field($s,'featured_text','Description','textarea'); ?>
                </table>
            </div>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Profile Section'); ?>
                <table class="form-table" role="presentation">
                    <?php dmp_field($s,'profile_eyebrow','Eyebrow'); dmp_field($s,'profile_title','Heading'); dmp_field($s,'profile_title_emphasis','Heading emphasis'); dmp_field($s,'profile_lead','Lead text','textarea'); dmp_field($s,'profile_text','Body text','textarea'); dmp_field($s,'profile_image','Profile image','image'); dmp_field($s,'profile_image_index','Image index');
                    for ($i=1;$i<=4;$i++) { dmp_field($s,"profile_row_{$i}_label","Profile row {$i} label"); dmp_field($s,"profile_row_{$i}_value","Profile row {$i} value"); } ?>
                </table>
            </div>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Portfolio Gallery'); ?>
                <table class="form-table" role="presentation">
                    <?php dmp_field($s,'portfolio_eyebrow','Eyebrow'); dmp_field($s,'portfolio_title','Heading'); dmp_field($s,'portfolio_title_emphasis','Heading emphasis'); ?>
                </table>
            </div>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Statement'); ?>
                <table class="form-table" role="presentation">
                    <?php dmp_field($s,'statement_eyebrow','Eyebrow'); dmp_field($s,'statement_text','Statement first line'); dmp_field($s,'statement_emphasis','Statement emphasis'); ?>
                </table>
            </div>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Contact'); ?>
                <table class="form-table" role="presentation">
                    <?php dmp_field($s,'contact_eyebrow','Eyebrow'); dmp_field($s,'contact_title','Heading'); dmp_field($s,'contact_title_emphasis','Heading emphasis'); dmp_field($s,'contact_text','Description','textarea'); dmp_field($s,'contact_email_label','Email label'); dmp_field($s,'contact_email','Booking email','email'); dmp_field($s,'contact_link_1_label','Link 1 label'); dmp_field($s,'contact_link_1_url','Link 1 URL','url'); dmp_field($s,'contact_link_2_label','Link 2 label'); dmp_field($s,'contact_link_2_url','Link 2 URL','url'); ?>
                </table>
            </div>

            <div class="dmp-settings-card">
                <?php dmp_section_title('Footer'); ?>
                <table class="form-table" role="presentation"><?php dmp_field($s,'footer_text','Footer text'); ?></table>
            </div>

            <?php submit_button(__('Save Portfolio Settings', 'dark-model-portfolio')); ?>
        </form>
    </div>
    <?php
}
