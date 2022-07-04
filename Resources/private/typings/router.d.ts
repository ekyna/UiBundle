declare let router: {
    generate(name: string, opt_params?: object, absolute?: boolean): string;
};

declare module 'routing' {
    export = router;
}
