module.exports = {
    'copy:ui': [
        'copy:ui_fonts',
        'copy:ui_libs',
        'copy:ui_fileupload',
        'copy:ui_contextmenu',
        'copy:ui_bootstrap'
    ],
    'cssmin:ui': [
        'cssmin:ui_lib',
        'cssmin:ui_content',
        'cssmin:ui_form'
    ],
    'build:ui_js': [
        'ts:ui',
        'uglify:ui_ts',
        'uglify:ui_js',
        'clean:ui_ts'
    ],
    'build:ui': [
        'clean:ui_pre',
        'copy:ui',
        'imagemin:ui',
        'less:ui',
        'cssmin:ui',
        'build:ui_js',
        'clean:ui_post'
    ]
};
