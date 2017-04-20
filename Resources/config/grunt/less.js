module.exports = function (grunt, options) {
    // @see https://github.com/gruntjs/grunt-contrib-less
    return {
        ui: {
            files: {
                'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/glyphicons.css':
                    'src/Ekyna/Bundle/UiBundle/Resources/private/less/glyphicons.less',
                'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/bootstrap.css':
                    'src/Ekyna/Bundle/UiBundle/Resources/private/less/bootstrap.less',
                'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/ui.css':
                    'src/Ekyna/Bundle/UiBundle/Resources/private/less/ui.less',
                'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/flags.css':
                    'src/Ekyna/Bundle/UiBundle/Resources/private/less/flags.less'
            }
        }
    }
};
