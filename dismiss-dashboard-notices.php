<?php
/**
 * Plugin Name: Dismiss Dashboard Notices
 * Description: Adds a button to dismiss all admin notices from the dashboard.
 * Version: 1.0
 * Author: Christian N Mdima
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * GitHub URI: https://github.com/ChristianNMdima/dismiss-dashboard-notices.git
 */

// Enqueue admin scripts and styles
add_action('admin_enqueue_scripts', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    wp_enqueue_script(
        'dismiss-notices-js',
        plugin_dir_url(__FILE__) . 'assets/js/dismiss-notices.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_enqueue_style(
        'dismiss-notices-css',
        plugin_dir_url(__FILE__) . 'assets/css/dismiss-notices.css',
        [],
        '1.0'
    );

    // Pass PHP option to JS
    wp_localize_script('dismiss-notices-js', 'DismissNoticesSettings', [
        'remember' => get_option('dismiss_notices_remember', true),
    ]);
});

// Register plugin settings
add_action('admin_init', function () {
    register_setting('dismiss_notices_settings', 'dismiss_notices_remember', [
        'type'              => 'boolean',
        'default'           => true,
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);

    add_settings_section('dismiss_notices_section', '', null, 'dismiss-notices');

    add_settings_field(
        'dismiss_notices_remember',
        esc_html__('Remember visibility setting after reload?', 'dismiss-dashboard-notices'),
        function () {
            $checked = checked(get_option('dismiss_notices_remember', true), true, false);
            echo '<input type="checkbox" name="dismiss_notices_remember" value="1" ' . esc_attr($checked) . ' />';
        },
        'dismiss-notices',
        'dismiss_notices_section'
    );
});

// Add menu item for settings page
add_action('admin_menu', function () {
    add_options_page(
        esc_html__('Dismiss Notices Settings', 'dismiss-dashboard-notices'),
        esc_html__('Dismiss Notices', 'dismiss-dashboard-notices'),
        'manage_options',
        'dismiss-notices',
        function () {
            ?>
            <div class="wrap">
                <h1><?php echo esc_html__('Dismiss Dashboard Notices – Settings', 'dismiss-dashboard-notices'); ?></h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('dismiss_notices_settings');
                    do_settings_sections('dismiss-notices');
                    submit_button();
                    ?>
                </form>
            </div>
            <?php
        }
    );
});

// Add "Settings" link to plugin actions
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $url = esc_url(admin_url('options-general.php?page=dismiss-notices'));
    $settings_link = '<a href="' . $url . '">' . esc_html__('Settings', 'dismiss-dashboard-notices') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});
