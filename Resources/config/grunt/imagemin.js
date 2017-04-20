module.exports = function (grunt, options) {
    return {
        ui: {
            options: {
                optimizationLevel: 6
            },
            files: [{
                expand: true,
                cwd: 'src/Ekyna/Bundle/UiBundle/Resources/private/img/',
                src: ['**/*.{png,jpg,gif,svg}'],
                dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/img/'
            }]
        }
    }
};
