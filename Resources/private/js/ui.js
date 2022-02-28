define(['require', 'jquery', 'aos', 'bootstrap', 'ekyna-clipboard-copy'], function(require, $, AOS) {

    var EkynaUi = function() {};

    EkynaUi.prototype.init = function(aos) {
        // hack to fix jquery 3.6 focus security patch that bugs auto search in select-2
        // https://forums.select2.org/t/search-being-unfocused/1203/10
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        AOS.init($.extend({}, aos, {
            offset: 200
        }));

        // Forms
        var $forms = $('form');
        if (0 < $forms.length) {
            require(['ekyna-form'], function(Form) {
                $forms.each(function(i, f) {
                    var form = Form.create(f);
                    form.init();
                });
            });
        }

        // Toggle details
        $(document).on('click', 'a[data-toggle-details]', function(e) {
            e.preventDefault();

            var $this = $(this), $target = $('#' + $this.data('toggle-details'));

            if (1 === $target.length) {
                if ($target.is(':visible')) {
                    $target.hide();
                } else {
                    $target.show();
                }
            }

            return false;
        });
    };

    return new EkynaUi;
});
