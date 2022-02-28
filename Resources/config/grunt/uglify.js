module.exports = function (grunt, options) {
    return {
        ui_js: {
            files: [
                {
                    'src/Ekyna/Bundle/UiBundle/Resources/public/js/ie/fix-10.js': [
                        'src/Ekyna/Bundle/UiBundle/Resources/private/js/ie/ie10-viewport-bug-workaround.js'
                    ],
                    'src/Ekyna/Bundle/UiBundle/Resources/public/js/ie/fix-9.js': [
                        'src/Ekyna/Bundle/UiBundle/Resources/private/js/ie/html5shiv.min.js',
                        'src/Ekyna/Bundle/UiBundle/Resources/private/js/ie/respond.min.js',
                        'src/Ekyna/Bundle/UiBundle/Resources/private/js/ie/excanvas.min.js'
                    ],
                    'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery/fileupload.js': [
                        'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/lib/jquery/fileupload.js'
                    ]
                },
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/UiBundle/Resources/private/js',
                    src: ['*.js', 'form/*.js'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/js'
                },
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/jquery-ui',
                    src: ['**/*.js'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery-ui'
                }
            ]
        },
        ui_ts: {
            expand: true,
            cwd: 'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/js',
            src: '**/*.js',
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/js'
        }
    }
};
