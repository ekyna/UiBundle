// TODO Move to resource bundle
define(['jquery', 'router', 'json!routes'],
    function ($, Router, routes) {
        Router.setRoutingData(routes);
        // Bug: Routes.locale is of array type
        // -> Override with html attribute
        Router.setLocale($('html').attr('lang'));
        return Router;
    }
);
