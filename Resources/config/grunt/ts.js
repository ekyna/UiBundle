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
                rootDir: 'src/Ekyna/Bundle/UiBundle/Resources/private/ts',
                //verbose: true,
                lib: ['dom', 'es2015', 'esnext'],
                target: 'es5',
                module: 'amd',
                moduleResolution: 'classic',
                noImplicitAny: false,
                removeComments: true,
                preserveConstEnums: true,
                sourceMap: false
            }
        }
    }
};
