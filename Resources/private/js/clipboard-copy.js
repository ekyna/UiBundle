define(['jquery', 'bootstrap'], function ($) {
    $(document).on('click', '[data-clipboard-copy]', function (e) {
        if (typeof window['ontouchstart'] !== 'undefined') {
            return true;
        }

        e.preventDefault();
        e.stopPropagation();

        let element = e.currentTarget,
            $element = $(element);

        navigator.clipboard.writeText($element.data('clipboard-copy')).then(() => {
            $element
                .tooltip({
                    title: 'Copied to clipboard',
                    placement: 'auto',
                    trigger: 'manual',
                    container: 'body'
                })
                .tooltip('show');

            setTimeout(function () {
                $element.tooltip('hide');
            }, 1500);
        }, () => {
            /* clipboard write failed */
        });

        return false;
    });
});
