module.exports = function (grunt, options) {
    return {
        ui: {
            files: [
                {
                    src: 'src/Ekyna/Bundle/UiBundle/Resources/private/ts/**/*.ts',
                    dest: 'src/Ekyna/Bundle/UiBundle/Resources/public/tmp/js'
                }
            ],
            options: {
                fast: 'never',
                module: 'amd',
                rootDir: 'src/Ekyna/Bundle/UiBundle/Resources/private/ts',
                noImplicitAny: false,
                removeComments: true,
                preserveConstEnums: true,
                sourceMap: false
            }
        }
    }
};
