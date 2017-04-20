interface LoadingSpinner {
    (): JQuery;
    (action: string): JQuery;
}

interface JQuery {
    loadingSpinner: LoadingSpinner;
}

declare let jQLS:JQuery;

declare module 'ekyna-spinner' {
    export = jQLS;
}
