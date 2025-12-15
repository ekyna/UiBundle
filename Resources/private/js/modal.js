define(['require', 'jquery', 'bootstrap/dialog', 'ekyna-polyfill'], function (require, $, BootstrapDialog) {
    "use strict";

    const triggerSelector = 'button[data-modal], a[data-modal], [data-modal] > a';

    let modal = null;

    function EkynaModal() {
        modal = this;
        this.dialog = new BootstrapDialog();
        this.form = null;
        this.shown = false;

        const that = this,
            $that = $(this);

        // Handle shown dialog
        this.dialog.onShow(function () {
            const event = $.Event('ekyna.modal.show');
            event.modal = that;
            $that.trigger(event);

            // Auto modal buttons and links
            that.dialog
                .getModalBody()
                .on('click', triggerSelector, function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    that.load({url: $(e.currentTarget).attr('href')});

                    return false;
                });
        });

        // Handle shown dialog
        this.dialog.onShown(function () {
            that.shown = true;

            const event = $.Event('ekyna.modal.shown');
            event.modal = that;
            $that.trigger(event);
        });

        // Handle hide dialog
        this.dialog.onHide(function () {
            that.shown = false;

            const event = $.Event('ekyna.modal.hide');
            event.modal = that;
            $that.trigger(event);
            if (event.isDefaultPrevented()) {
                return false;
            }

            if (that.form) {
                that.form.destroy();
                that.form = null;
            }
        });

        // Handle hide dialog
        this.dialog.onHidden(function () {
            modal = null;

            const event = $.Event('ekyna.modal.hidden');
            event.modal = that;
            $that.trigger(event);
        });
    }

    EkynaModal.getInstance = function() {
        if (null === modal) {
            modal = new EkynaModal();
        }
        return modal;
    };

    EkynaModal.prototype = {
        constructor: EkynaModal,
        load: function (params) {
            params.cache = false;

            const that = this,
                xhr = $.ajax(params);

            xhr.done(function (data, status, jqXHR) {
                that.handleResponse(data, status, jqXHR);
            });

            xhr.fail(function () {
                console.log('Failed to load modal.');
                const event = $.Event('ekyna.modal.load_fail');
                $(that).trigger(event);
            });

            return xhr;
        },
        initForm: function ($form) {
            const that = this;

            // @see https://github.com/select2/select2/issues/600
            $(this.dialog.getModal()).removeAttr('tabindex');

            require(['ekyna-form'], function (Form) {
                that.form = Form.create($form);
                that.form.init(that.dialog.getModal());

                const submitForm = function ($button) {
                    const formDom = that.form.getElement().get(0);
                    if (formDom.reportValidity && !formDom.reportValidity()) {
                        return;
                    }

                    if (formDom.hasOwnProperty('_submitted') && formDom._submitted) {
                        return;
                    }

                    formDom._submitted = true;

                    const $form = that.form.getElement(), data = {};
                    that.dialog.enableButtons(false);

                    $button = $button || $form.find('button[type=submit]').eq(0);

                    // .icon-spin class from bootstrap/dialog
                    if (1 === $button.length) {
                        $button.find('span, i').removeClass().addClass('glyphicon glyphicon-asterisk icon-spin');
                        if ($button.attr('name') && $button.attr('value')) {
                            data[$button.attr('name')] = $button.attr('value');
                        }
                    } else {
                        const button = that.dialog.getButton('submit');
                        if (button) {
                            button.toggleSpin(true);
                        }
                    }

                    $form.find('button').prop('disabled', true);

                    that.form.save();
                    setTimeout(function () {
                        that.form.getElement().ajaxSubmit({
                            data: data,
                            success: function (data, status, jqXHR) {
                                that.handleResponse(data, status, jqXHR);
                            },
                            error: function() {
                                formDom._submitted = false;
                            }
                        });
                    }, 100);
                };

                that.form.getElement()
                    .on('keyup', 'input, select', function (e) {
                        if ((e.keyCode ? e.keyCode : e.which) !== 13) {
                            return;
                        }

                        e.preventDefault();
                        e.stopPropagation();

                        submitForm();

                        return false;
                    })
                    .on('mouseup', 'button[type=submit]', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        submitForm($(e.target));

                        return false;
                    })
                    .on('submit', function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        submitForm();

                        return false;
                    });
            });
        },
        getContentType: function (jqXHR) {
            let type = 'html',
                header = jqXHR.getResponseHeader('Content-Type');

            if (null === header) {
                header = jqXHR.getResponseHeader('content-type')
            }

            if (/json/.test(header)) {
                type = 'json';
            } else if (/xml/.test(header)) {
                type = 'xml';
            }

            return type;
        },
        handleResponse: function (data, status, jqXHR) {
            const that = this,
                $that = $(this);

            let type = this.getContentType(jqXHR);

            this.submitButton = null;

            if (this.form) {
                this.form.destroy();
                this.form = null;
            }

            function redirectOrReload(data) {
                // Load url
                if (data.hasOwnProperty('load_url') && data.load_url) {
                    that.load({
                        url: data.load_url,
                        method: 'GET'
                    });

                    return true;
                }

                // Redirect url
                if (data.hasOwnProperty('redirect_url') && data.redirect_url) {
                    window.location.href = data.redirect_url;

                    return true;
                }

                return false;
            }

            let event = $.Event('ekyna.modal.response');
            event.modal = this;
            event.contentType = type;
            event.content = data;
            event.jqXHR = jqXHR;

            $that.trigger(event);

            if (event.isDefaultPrevented()) {
                return this;
            }

            // Redirect or reload
            if (type === 'json' && redirectOrReload(event.content)) {
                return this;
            }

            if (type !== 'xml') {
                return this;
            }

            const $xmlData = $(data);

            // Content
            const $content = $xmlData.find('content');
            if ($content.length > 0) {
                type = $content.attr('type');
                event = $.Event('ekyna.modal.content');
                event.modal = this;
                event.contentType = type;

                const content = $content.text();

                // Data content type
                if (type === 'data') {
                    event.content = JSON.parse(content);
                    $that.trigger(event);

                    if (event.isDefaultPrevented()) {
                        return this;
                    }

                    // Redirect or reload
                    if (redirectOrReload(event.content)) {
                        return this;
                    }

                    // (default) Close modal
                    return this.close();
                }

                // Html content type
                const $html = $(content);
                event.content = $html;
                $that.trigger(event);
                if (event.isDefaultPrevented()) {
                    return this.close();
                }

                this.dialog.setMessage($html);

                // Form content type
                const $form = $html.is('form.modal-form') ? $html : $html.find('form.modal-form:first');
                if ($form.length === 1) {
                    if (that.shown) {
                        that.initForm($form);
                    } else {
                        $that.one('ekyna.modal.shown', function () {
                            that.initForm($form);
                        });
                    }
                }

                // Updated content event
                $that.trigger($.Event('ekyna.modal.updated'));
            } else {
                // No content => abort
                return this;
            }

            // Title
            const $title = $xmlData.find('title');
            if (1 === $title.length) {
                this.dialog.setTitle($title.text());
            }

            // Buttons
            const $buttons = $xmlData.find('buttons');
            if (1 === $buttons.length) {
                const buttons = JSON.parse($buttons.text(), function (key, value) {
                    if (value && (typeof value === 'string') && value.indexOf("function") === 0) {
                        return new Function('return ' + value)();
                    }
                    return value;
                });
                $(buttons).each(function (index, button) {
                    if (typeof button.action !== "function") {
                        if (button.id === 'close') {
                            button.action = function (dialog) {
                                dialog.enableButtons(false);
                                dialog.close();
                            };
                        } else {
                            button.action = function (dialog) {
                                var event = $.Event('ekyna.modal.button_click');
                                event.modal = that;
                                event.buttonId = button.id;
                                $that.trigger(event);

                                if (event.isDefaultPrevented()) {
                                    return;
                                }

                                if (button.id !== 'submit') {
                                    return;
                                }

                                if (that.form) {
                                    that.form.getElement().trigger('submit');
                                    return;
                                }

                                dialog.enableButtons(false);
                            };
                        }
                    }
                });
                this.dialog.setButtons(buttons);
            } else {
                this.dialog.setButtons([]);
            }

            // Type and Size
            const config = JSON.parse($xmlData.find('config').text());
            if (typeof config.type !== 'undefined') {
                this.dialog.setType(config.type);
            }
            if (typeof config.size !== 'undefined') {
                this.dialog.setSize(config.size);
            }
            if (typeof config.cssClass !== 'undefined') {
                this.dialog.setCssClass(config.cssClass);
            }

            // Handle open/shown dialog
            if (!this.dialog.isOpened()) {
                this.dialog.open();
            }

            if (typeof config.condensed !== 'undefined') {
                if (config.condensed) {
                    this.dialog.getModal().addClass('condensed');
                } else {
                    this.dialog.getModal().removeClass('condensed');
                }
            }

            return this;
        },
        close: function () {
            if (this.dialog.isOpened()) {
                this.dialog.close();
            }
            return this;
        },
        getDialog: function () {
            return this.dialog;
        }
    };

    // Auto modal buttons and links
    $(document).on('click', triggerSelector, (e) => {
        e.preventDefault();

        EkynaModal.getInstance().load({url: $(e.currentTarget).attr('href')});

        return false;
    });

    return EkynaModal;
});
