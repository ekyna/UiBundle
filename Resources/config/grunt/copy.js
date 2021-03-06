module.exports = function (grunt, options) {
    return {
        ui_fonts: {
            files: [
                // Fontawesome
                {
                    expand: true,
                    cwd: 'node_modules/font-awesome/fonts',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/fonts'
                },
                // Glyphicons
                {
                    expand: true,
                    cwd: 'node_modules/bootstrap/dist/fonts',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/fonts'
                }
            ]
        },
        ui_libs: {
            files: [
                // Jquery
                {
                    src: 'node_modules/jquery/dist/jquery.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery/jquery.js'
                },
                {
                    src: 'node_modules/jquery-match-height/dist/jquery.matchHeight-min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery/match-height.js'
                },
                {
                    src: 'node_modules/qtip2/dist/jquery.qtip.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery/qtip.js'
                },
                {
                    src: 'node_modules/jquery-form/dist/jquery.form.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery/form.js'
                },
                {
                    src: 'node_modules/validator/validator.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/validator.js'
                },
                {
                    expand: true,
                    cwd: 'node_modules/jquery-ui-themes/themes/smoothness/images',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/css/images'
                },
                // Bootstrap
                {
                    src: 'node_modules/bootstrap/dist/js/bootstrap.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/bootstrap/bootstrap.js'
                },
                {
                    src: 'node_modules/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/bootstrap/hover-dropdown.js'
                },
                {
                    src: 'node_modules/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/bootstrap/colorpicker.js'
                },
                {
                    expand: true,
                    cwd: 'node_modules/bootstrap-colorpicker/dist/img',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/img'
                },
                {
                    src: 'node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/bootstrap/datetimepicker.js'
                },
                {
                    src: 'node_modules/bootstrap-notify/bootstrap-notify.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/bootstrap/notify.js'
                },
                // Fontawesome
                {
                    src: 'node_modules/font-awesome/css/font-awesome.min.css',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/css/fontawesome.css'
                },
                // Gsap
                {
                    expand: true,
                    cwd: 'node_modules/gsap/src/minified',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/gsap',
                    rename: function(dest, src) {
                        return dest + '/' + src.replace(/\.min\.js/, '.js');
                    }
                },
                // Others
                {
                    expand: true,
                    cwd: 'node_modules/intl/locale-data/jsonp',
                    src: ['en.js', 'fr.js', 'de.js', 'es.js', 'pt.js'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/intl/locales'
                },
                {
                    src: 'node_modules/twig/twig.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/twig.js'
                },
                {
                    src: 'node_modules/moment/min/moment-with-locales.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/moment.js'
                },
                {
                    src: 'node_modules/select2/dist/js/select2.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/select2.js'
                },
                {
                    src: 'node_modules/tinycolor2/dist/tinycolor-min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/tinycolor.js'
                },
                {
                    src: 'node_modules/es6-promise/dist/es6-promise.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/es6-promise.js'
                },
                {
                    src: 'node_modules/backbone/backbone-min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/backbone.js'
                },
                {
                    src: 'node_modules/underscore/underscore-min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/underscore.js'
                },
                {
                    expand: true,
                    cwd: 'node_modules/tinymce',
                    src: [
                        'plugins/**/*.min.js',
                        'plugins/**/*.css',
                        'plugins/**/*.swf',
                        'plugins/**/*.gif',
                        'skins/**',
                        'themes/**',
                        'tinymce.min.js'
                    ],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/tinymce'
                },
                {
                    src: 'node_modules/aos/dist/aos.css',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/css/aos.css'
                },
                {
                    src: 'node_modules/aos/dist/aos.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/aos.js'
                },
                {
                    src: 'node_modules/chart.js/dist/Chart.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/chart.js'
                },
                {
                    src: 'node_modules/intl/dist/Intl.min.js',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/intl/intl.js'
                },
                {
                    expand: true,
                    cwd: 'src/Ekyna/Bundle/UiBundle/Resources/private/lib',
                    src: ['**'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib'
                },
                {
                    expand: true,
                    cwd: 'node_modules/jquery-ui/ui',
                    src: ['*.js', 'effects/*.js', 'i18n/*.js', 'vendor/*/*.js', 'widgets/*.js'],
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/jquery-ui/ui'
                }
            ]
        },
        ui_bootstrap: {
            src: 'node_modules/bootstrap3-dialog/dist/js/bootstrap-dialog.min.js',
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/bootstrap/dialog.js',
            options: {
                process: function (content, srcpath) {
                    console.log('bootstrap dialog', srcpath);
                    return content.replace('"bootstrap-dialog",', '');
                }
            }
        },
        ui_fileupload: {
            src: 'node_modules/blueimp-file-upload/js/jquery.fileupload.js',
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/lib/jquery/fileupload.js', // tmp to minify
            /*options: {
                process: function (content) {
                    return content.replace(/jquery-ui\/ui\/widget/g, 'jquery-ui/widget');
                }
            }*/
        },
        ui_contextmenu: {
            src: 'node_modules/jquery-contextmenu/dist/jquery.contextMenu.min.js',
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery-ui/ui/widgets/contextmenu.js',
            /*src: 'node_modules/ui-contextmenu/jquery.ui-contextmenu.min.js',
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/lib/jquery-ui/ui/widgets/contextmenu.js',*/
            /*options: {
                process: function (content, srcpath) {
                    if (/jquery\.ui-contextmenu/.test(srcpath)) {
                        content = content.replace(/jquery-ui\/ui\/widgets/g, 'jquery-ui/widgets');
                    }

                    return content;
                }
            }*/
        },
        ui_less: { // For watch:ui_less
            expand: true,
            cwd: 'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/css',
            src: ['**'],
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/css'
        },
        ui_ts: { // For watch:ui_ts
            expand: true,
            cwd: 'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/js',
            src: ['**/*.js'],
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/js'
        },
        ui_js: { // For watch:ui_js
            expand: true,
            cwd: 'src/Ekyna/Bundle/UiBundle/Resources/private/js',
            src: ['**/*.js'],
            dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/js'
        }
    }
};
