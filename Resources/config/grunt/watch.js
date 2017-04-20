module.exports = function (grunt, options) {
    return {
        ui_css: {
            files: ['src/Ekyna/Bundle/UiBundle/Resources/private/css/**/*.css'],
            tasks: ['cssmin:ui_content', 'cssmin:ui_form'],
            options: {
                spawn: false
            }
        },
        ui_less: {
            files: ['src/Ekyna/Bundle/UiBundle/Resources/private/less/**/*.less'],
            tasks: ['less:ui', 'copy:ui_less', 'clean:ui_less'],
            options: {
                spawn: false
            }
        },
        ui_js: {
            files: ['src/Ekyna/Bundle/UiBundle/Resources/private/js/**/*.js'],
            tasks: ['copy:ui_js'],
            options: {
                spawn: false
            }
        },
        ui_ts: {
            files: ['src/Ekyna/Bundle/UiBundle/Resources/private/ts/**/*.ts'],
            tasks: ['ts:ui', 'copy:ui_ts', 'clean:ui_ts'],
            options: {
                spawn: false
            }
        }
    }
};
