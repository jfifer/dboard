var dashboardSrv = angular.module('dashboardSrv', ['ngResource']);

dashboardSrv.factory('Auth', ['$resource',
    function ($resource) {
        return $resource('api/auth', { }, {
            post: {method: 'POST', isArray: false},
            get: {method: 'GET', isArray: false}
        });
    }]);

dashboardSrv.factory('Portal', ['$resource',
    function ($resource) {
        return $resource('api/portal/:method/:id', { method: "@method", id: "@id" }, {
            post: {method: 'POST', isArray: false},
            query: {method: 'GET', isArray: true},
            get: {method: 'GET', isArray: false}
        });
    }]);

dashboardSrv.factory('Zenoss', ['$resource',
    function ($resource) {
        return $resource('api/zenoss/:action/:method/:type/:tid/:query', { action: "@action", method: "@method", type: "@type", tid: "@tid", query: "@query" }, {
            post: {method: 'POST', isArray: false},
            get: {method: 'GET', isArray: false}
        });
    }]);
