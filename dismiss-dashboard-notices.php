<?php

/**
 * Plugin Name: Dismiss Dashboard Notices
 * Description: Adds a button to dismiss all admin notices from the dashboard.
 * Version: 1.0
 * Author: Christian N Mdima
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

add_action('admin_footer', function () {
    if (!current_user_can('manage_options')) return;
    ?>
    <script>
        jQuery(document).ready(function ($) {
            const btnWrap = $('<div style="margin: 15px 0;"></div>');
            const dismissBtn = $('<button class="button button-secondary"></button>');
            const noticeSelector = '.notice, .update-nag, .woocommerce-message, #message';
            const STORAGE_KEY = 'dashboardNoticesVisible';

            let noticesVisible = localStorage.getItem(STORAGE_KEY);
            if (noticesVisible === null) {
                noticesVisible = 'true'; // default to visible
            }

            function updateButton() {
                if (noticesVisible === 'true') {
                    $(noticeSelector).show(); // ensure they are visible
                    dismissBtn.text('Hide Notices');
                } else {
                    $(noticeSelector).hide(); // hide immediately on load
                    dismissBtn.text('Show Notices');
                }
            }

            dismissBtn.on('click', function () {
                if (noticesVisible === 'true') {
                    $(noticeSelector).slideUp();
                    dismissBtn.text('Show Notices');
                    noticesVisible = 'false';
                } else {
                    $(noticeSelector).slideDown();
                    dismissBtn.text('Hide Notices');
                    noticesVisible = 'true';
                }
                localStorage.setItem(STORAGE_KEY, noticesVisible);
            });

            updateButton(); // run on page load

            const noticesAnchor = $('.wrap h1').first();
            if (noticesAnchor.length) {
                btnWrap.append(dismissBtn);
                noticesAnchor.after(btnWrap);
            } else {
                $('#wpbody-content').prepend(dismissBtn);
            }
        });
    </script>
    <?php
});


// Register plugin settings
add_action('admin_init', function () {
    register_setting('dismiss_notices_settings', 'dismiss_notices_remember', [
        'type' => 'boolean',
        'default' => true
    ]);

    add_settings_section('dismiss_notices_section', '', null, 'dismiss-notices');

    add_settings_field('dismiss_notices_remember', 'Remember visibility setting after reload?', function () {
        $checked = checked(get_option('dismiss_notices_remember', true), true, false);
        echo "<input type='checkbox' name='dismiss_notices_remember' value='1' $checked />";
    }, 'dismiss-notices', 'dismiss_notices_section');
});

// Add menu item
add_action('admin_menu', function () {
    add_options_page(
        'Dismiss Notices Settings',
        'Dismiss Notices',
        'manage_options',
        'dismiss-notices',
        function () {
            ?>
            <div class="wrap">
                <h1>Dismiss Dashboard Notices – Settings</h1>
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


