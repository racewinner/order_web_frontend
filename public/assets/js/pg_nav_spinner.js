(function() {
    const spinner = $('#ajax-call-indicator');
    if (!spinner.length) return;

    // Show spinner on initial page load
    if (document.readyState === 'loading') {
        spinner.removeClass('d-none');
    }

    // Hide spinner when page is fully loaded
    $(window).on('load', function() {
        setTimeout(function() {
            spinner.addClass('d-none');
        }, 100);
    });

    // If page is already loaded (e.g., cached), hide spinner immediately
    if (document.readyState === 'complete') {
        setTimeout(function() {
            spinner.addClass('d-none');
        }, 100);
    }

    // Show spinner when navigating away (beforeunload)
    $(window).on('beforeunload', function() {
        spinner.removeClass('d-none');
    });

    // Show spinner on link clicks (for page navigation)
    $(document).on('click', 'a', function(e) {
        const link = $(this);
        const href = link.attr('href');
        const target = link.attr('target');

        // Skip if:
        // - No href or href is empty
        // - Anchor link (#)
        // - JavaScript link
        // - Opens in new tab/window
        // - Has data-no-spinner attribute
        if (!href ||
            href.startsWith('#') ||
            href.startsWith('javascript:') ||
            target === '_blank' ||
            link.data('no-spinner')) {
            return;
        }

        // Check if it's a same-origin link
        try {
            const url = new URL(href, window.location.origin);
            if (url.origin === window.location.origin) {
                spinner.removeClass('d-none');
            }
        } catch (e) {
            // If URL parsing fails, assume it's a relative URL
            spinner.removeClass('d-none');
        }
    });

    // Show spinner on form submissions (GET requests that cause page navigation)
    $(document).on('submit', 'form', function(e) {
        const form = $(this);
        if (form.attr('method') && form.attr('method').toLowerCase() === 'get') {
            spinner.removeClass('d-none');
        }
    });
})();