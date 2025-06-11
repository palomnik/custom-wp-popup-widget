<?php

class Custom_Popup_Widget {
    public function __construct() {
        add_action('init', array($this, 'register_block_type'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_popup'));
    }

    public function register_block_type() {
        register_block_type('custom-popup-widget/popup-content', array(
            'editor_script' => 'custom-popup-widget-editor',
            'editor_style' => 'custom-popup-widget-editor-style',
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'content' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )
        ));

        wp_register_script(
            'custom-popup-widget-editor',
            CPW_PLUGIN_URL . 'assets/js/editor.js',
            array('wp-blocks', 'wp-element', 'wp-editor'),
            CPW_VERSION
        );
    }

    public function enqueue_scripts() {
        wp_enqueue_style(
            'custom-popup-widget',
            CPW_PLUGIN_URL . 'assets/css/popup.css',
            array(),
            CPW_VERSION
        );

        wp_enqueue_script(
            'custom-popup-widget',
            CPW_PLUGIN_URL . 'assets/js/popup.js',
            array('jquery'),
            CPW_VERSION,
            true
        );

        $settings = get_option('cpw_settings');
        wp_localize_script('custom-popup-widget', 'cpwSettings', $settings);
    }

    public function render_block($attributes) {
        ob_start();
        ?>
        <div class="cpw-content">
            <?php echo do_blocks($attributes['content']); ?>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_popup() {
        $settings = get_option('cpw_settings');
        $post = get_post(get_option('cpw_popup_post_id'));
        
        if (!$post) {
            return;
        }

        $content = $post->post_content;
        ?>
        <div id="cpw-popup" class="cpw-popup" style="display: none;">
            <div class="cpw-popup-overlay"></div>
            <div class="cpw-popup-content" style="width: <?php echo esc_attr($settings['popup_width']); ?>px; height: <?php echo esc_attr($settings['popup_height']); ?>px;">
                <button class="cpw-close">&times;</button>
                <div class="cpw-inner-content">
                    <?php echo do_blocks($content); ?>
                </div>
            </div>
        </div>
        <?php
    }
} 