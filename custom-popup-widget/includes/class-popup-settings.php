<?php

class Custom_Popup_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Popup Widget Settings', 'custom-popup-widget'),
            __('Popup Widget', 'custom-popup-widget'),
            'manage_options',
            'custom-popup-widget',
            array($this, 'render_settings_page'),
            'dashicons-welcome-widgets-menus'
        );
    }

    public function register_settings() {
        register_setting('cpw_settings', 'cpw_settings');
        register_setting('cpw_settings', 'cpw_popup_post_id');

        add_settings_section(
            'cpw_settings_section',
            __('Popup Settings', 'custom-popup-widget'),
            array($this, 'settings_section_callback'),
            'custom-popup-widget'
        );

        add_settings_field(
            'popup_width',
            __('Popup Width (px)', 'custom-popup-widget'),
            array($this, 'render_number_field'),
            'custom-popup-widget',
            'cpw_settings_section',
            array('field' => 'popup_width')
        );

        add_settings_field(
            'popup_height',
            __('Popup Height (px)', 'custom-popup-widget'),
            array($this, 'render_number_field'),
            'custom-popup-widget',
            'cpw_settings_section',
            array('field' => 'popup_height')
        );

        add_settings_field(
            'popup_position',
            __('Popup Position', 'custom-popup-widget'),
            array($this, 'render_position_field'),
            'custom-popup-widget',
            'cpw_settings_section'
        );

        add_settings_field(
            'popup_delay',
            __('Popup Delay (seconds)', 'custom-popup-widget'),
            array($this, 'render_number_field'),
            'custom-popup-widget',
            'cpw_settings_section',
            array('field' => 'popup_delay')
        );

        add_settings_field(
            'popup_frequency',
            __('Display Frequency', 'custom-popup-widget'),
            array($this, 'render_frequency_field'),
            'custom-popup-widget',
            'cpw_settings_section'
        );

        add_settings_field(
            'popup_content_source',
            __('Popup Content Source', 'custom-popup-widget'),
            array($this, 'render_content_source_field'),
            'custom-popup-widget',
            'cpw_settings_section'
        );
    }

    public function settings_section_callback() {
        echo '<p>' . __('Configure how your popup widget appears and behaves.', 'custom-popup-widget') . '</p>';
    }

    public function render_number_field($args) {
        $settings = get_option('cpw_settings');
        $value = isset($settings[$args['field']]) ? $settings[$args['field']] : '';
        ?>
        <input type="number" name="cpw_settings[<?php echo esc_attr($args['field']); ?>]" 
               value="<?php echo esc_attr($value); ?>" class="regular-text">
        <?php
    }

    public function render_position_field() {
        $settings = get_option('cpw_settings');
        $position = isset($settings['popup_position']) ? $settings['popup_position'] : 'center';
        ?>
        <select name="cpw_settings[popup_position]">
            <option value="center" <?php selected($position, 'center'); ?>><?php _e('Center', 'custom-popup-widget'); ?></option>
            <option value="top-left" <?php selected($position, 'top-left'); ?>><?php _e('Top Left', 'custom-popup-widget'); ?></option>
            <option value="top-right" <?php selected($position, 'top-right'); ?>><?php _e('Top Right', 'custom-popup-widget'); ?></option>
            <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>><?php _e('Bottom Left', 'custom-popup-widget'); ?></option>
            <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>><?php _e('Bottom Right', 'custom-popup-widget'); ?></option>
        </select>
        <?php
    }

    public function render_frequency_field() {
        $settings = get_option('cpw_settings');
        $frequency = isset($settings['popup_frequency']) ? $settings['popup_frequency'] : 'once_per_session';
        ?>
        <select name="cpw_settings[popup_frequency]">
            <option value="once_per_session" <?php selected($frequency, 'once_per_session'); ?>><?php _e('Once per session', 'custom-popup-widget'); ?></option>
            <option value="once_per_day" <?php selected($frequency, 'once_per_day'); ?>><?php _e('Once per day', 'custom-popup-widget'); ?></option>
            <option value="once_per_two_weeks" <?php selected($frequency, 'once_per_two_weeks'); ?>><?php _e('Once every 2 weeks', 'custom-popup-widget'); ?></option>
            <option value="once_per_month" <?php selected($frequency, 'once_per_month'); ?>><?php _e('Once per month', 'custom-popup-widget'); ?></option>
            <option value="every_time" <?php selected($frequency, 'every_time'); ?>><?php _e('Every time', 'custom-popup-widget'); ?></option>
        </select>
        <?php
    }

    public function render_content_source_field() {
        $post_id = get_option('cpw_popup_post_id');
        $args = array(
            'post_type' => array('post', 'page'),
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        $posts = get_posts($args);
        ?>
        <select name="cpw_popup_post_id" class="regular-text">
            <option value=""><?php _e('Select a page/post', 'custom-popup-widget'); ?></option>
            <?php foreach ($posts as $post) : ?>
                <option value="<?php echo esc_attr($post->ID); ?>" <?php selected($post_id, $post->ID); ?>>
                    <?php echo esc_html($post->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description">
            <?php _e('Select the page or post that contains your popup content block.', 'custom-popup-widget'); ?>
        </p>
        <?php
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('cpw_settings');
                do_settings_sections('custom-popup-widget');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
} 