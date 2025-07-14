jQuery(document).ready(function ($) {
    const btnWrap = $('<div class="dismiss-notices-btn-wrap"></div>');
    const dismissBtn = $('<button class="button button-secondary"></button>');
    const noticeSelector = '.notice, .update-nag, .woocommerce-message, #message';
    const STORAGE_KEY = 'dashboardNoticesVisible';

    let noticesVisible = localStorage.getItem(STORAGE_KEY);
    if (noticesVisible === null) {
        noticesVisible = 'true'; // default visible
    }

    function updateButton() {
        if (noticesVisible === 'true') {
            $(noticeSelector).show();
            dismissBtn.text('Hide Notices');
        } else {
            $(noticeSelector).hide();
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

        if (DismissNoticesSettings.remember) {
            localStorage.setItem(STORAGE_KEY, noticesVisible);
        }
    });

    updateButton();

    const noticesAnchor = $('.wrap h1').first();
    if (noticesAnchor.length) {
        btnWrap.append(dismissBtn);
        noticesAnchor.after(btnWrap);
    } else {
        $('#wpbody-content').prepend(dismissBtn);
    }
});
