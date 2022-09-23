define(
    ['require', 'jquery', 'json!ekyna-form/plugins', 'select2', 'jquery/form'],
    function(require, $, plugins) {
    "use strict";

    $.fn.select2.defaults.set('theme', 'bootstrap');
    $.fn.select2.defaults.set('width', null);

    // Textarea auto resize
    document.querySelectorAll('textarea:not(.tinymce)').forEach(function (element) {
        element.style.boxSizing = 'border-box';
        element.style.resize = 'none';

        const offset = element.offsetHeight - element.clientHeight;
        element.addEventListener('input', function (event) {
            event.target.style.height = 'auto';
            event.target.style.height = event.target.scrollHeight + offset + 'px';
        });
    });

    let EkynaForm = function ($elem, options) {
        this.$elem = $($elem);
        this.options = options;
    };

    EkynaForm.prototype = {
        constructor: EkynaForm,
        getElement: function() {
            return this.$elem;
        },
        init: function($parent) {
            //console.log('Form.init()', this.$elem, $parent);

            /* Select2 */
            let select2options = {
                //selectOnClose: true // For tests
            };
            if ($parent && 1 === $parent.length) {
                select2options.dropdownParent = $parent;
            }
            this.$elem.find('select.select2').select2(select2options);

            /* Submit */
            /* TODO (conflicts with payment-forms.js)
            this.$elem.once('submit', () => {
                this.$elem.on('submit', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
                this.$elem.find('button[type="submit"]').each(function() {
                    $(this)
                        .prop('disabled', true)
                        .addClass('disabled')
                        .find('.glyphicon,.fa')
                        .replaceWith($('<i></i>')
                            .addClass('fa fa-spinner fa-spin'));
                });
            });*/

            /* Plugins */
            $.each(plugins, (selector, paths) => {
                let $target = this.$elem;
                if (!$target.is(selector)) {
                    $target = this.$elem.find(selector);
                }
                if (0 < $target.length) {
                    paths.forEach(function(path) {
                        require([path], function (plugin) {
                            //console.log('Form.plugin.init', path, $target);
                            plugin.init($target);
                        });
                    });
                }
            });

            this.$elem.data('form', this);
        },
        destroy: function() {
            /* Destroy select2 */
            this.$elem.find('select.select2').select2('destroy');

            $.each(plugins, (selector, paths) => {
                let $target = this.$elem;
                if (!$target.is(selector)) {
                    $target = this.$elem.find(selector);
                }
                if (0 < $target.length) {
                    paths.forEach(function(path) {
                        require([path], function (plugin) {
                            if (plugin.hasOwnProperty('destroy')) {
                                plugin.destroy($target);
                            }
                        });
                    });
                }
            });
        },
        save: function() {
            $.each(plugins, (selector, paths) => {
                let $target = this.$elem;
                if (!$target.is(selector)) {
                    $target = this.$elem.find(selector);
                }
                if (0 < $target.length) {
                    paths.forEach(function(path) {
                        require([path], function (plugin) {
                            if (plugin.hasOwnProperty('save')) {
                                plugin.save($target);
                            }
                        });
                    });
                }
            });
        }
    };

    /**
     * Form with tabs error handler
     * @see http://jsfiddle.net/GJeez/8/
     * @see http://www.html5rocks.com/en/tutorials/forms/constraintvalidation/?redirect_from_locale=fr#toc-checkValidity
     */
    $(".form-with-tabs input, .form-with-tabs textarea, .form-with-tabs select").on('invalid', function(event) {
        let $tabs = $(event.target).eq(0).parents('.tab-pane');
        if ($tabs.length) {
            showTabs($tabs);
            return;
        }
        event.preventDefault();
    });

    const $errorFields = $('form .has-error');
    if ($errorFields.length) {
        showTabs($errorFields.eq(0).parents('.tab-pane'));
    }

    function showTabs($tabs) {
        $tabs.each(function() {
            const $a = $('a[href="#' + $(this).attr('id') + '"]');
            if ($a.length === 1) {
                $a.tab('show');
            }
        });
    }

    return {
        create: function($element, options) {
            return new EkynaForm($element, options);
        }
    };
});
