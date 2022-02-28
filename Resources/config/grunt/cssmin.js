module.exports = function (grunt, options) {
    return {
        ui_lib: {
            files: {
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/glyphicons.css': [
                    'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/glyphicons.css'
                ],
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/bootstrap.css': [
                    'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/bootstrap.css'
                ],
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/ui.css': [
                    'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/ui.css'
                ],
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/flags.css': [
                    'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/flags.css'
                ],
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/jquery-ui.css': [
                    'node_modules/jquery-ui-themes/themes/smoothness/jquery-ui.css',
                    'src/Ekyna/Bundle/UiBundle/Resources/private/css/jquery-ui.css',
                    'node_modules/jquery-contextmenu/dist/jquery.contextMenu.css'
                ]
            }
        },
        ui_content: {
            files: {
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/content.css': [
                    'node_modules/bootstrap/dist/css/bootstrap.css',
                    'src/Ekyna/Bundle/UiBundle/Resources/private/css/content.css'
                ],
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/tinymce-content.css': [
                    'src/Ekyna/Bundle/UiBundle/Resources/private/css/tinymce-content.css'
                ]
            }
        },
        ui_form: {
            files: {
                'src/Ekyna/Bundle/UiBundle/Resources/public/css/form.css': [
                    'node_modules/bootstrap3-dialog/dist/css/bootstrap-dialog.css',
                    'node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
                    'node_modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css',
                    'node_modules/select2/dist/css/select2.css',
                    'node_modules/select2-bootstrap-theme/dist/select2-bootstrap.css',
                    'node_modules/qtip2/dist/jquery.qtip.css',
                    'src/Ekyna/Bundle/UiBundle/Resources/private/css/form.css'
                ]
            }
        }
    }
};
