jQuery(document).ready(function($) {

    // Wrapper container for the dismiss button
    const btnWrap = $('<div class="dismiss-notices-btn-wrap"></div>');

    // The toggle button used to show / hide dashboard notices
    const dismissBtn = $('<button class="button button-secondary"></button>');

    // CSS selectors for WordPress and WooCommerce admin notices
    const noticeSelector = '.notice, .update-nag, .woocommerce-message, #message';

    // LocalStorage key used to remember user preference
    const STORAGE_KEY = 'dashboardNoticesVisible';

    // Retrieve saved visibility state from localStorage
    let noticesVisible = localStorage.getItem(STORAGE_KEY);

    // Default to showing notices if no preference exists
    if (noticesVisible === null) {
        noticesVisible = 'true';
    }

    /**
     * Updates the notice visibility and button label
     * based on the current noticesVisible state
     */
    function updateButton() {
        if (noticesVisible === 'true') {
            $(noticeSelector).show();
            dismissBtn.text('Hide Notices');
        } else {
            $(noticeSelector).hide();
            dismissBtn.text('Show Notices');
        }
    }

    /**
     * Handle toggle button click
     * Shows or hides notices and updates stored preference
     */
    dismissBtn.on('click', function() {

        if (noticesVisible === 'true') {
            // Hide notices with animation
            $(noticeSelector).slideUp();
            dismissBtn.text('Show Notices');
            noticesVisible = 'false';
        } else {
            // Show notices with animation
            $(noticeSelector).slideDown();
            dismissBtn.text('Hide Notices');
            noticesVisible = 'true';
        }

        // Persist preference only if "remember" setting is enabled
        if (DismissNoticesSettings.remember) {
            localStorage.setItem(STORAGE_KEY, noticesVisible);
        }
    });

    // Initialise button state on page load
    updateButton();

    /**
     * Insert the button after the main admin page heading
     * Fallback to prepending it to wpbody-content if heading is missing
     */
    const noticesAnchor = $('.wrap h1').first();

    if (noticesAnchor.length) {
        btnWrap.append(dismissBtn);
        noticesAnchor.after(btnWrap);
    } else {
        $('#wpbody-content').prepend(dismissBtn);
    }
});