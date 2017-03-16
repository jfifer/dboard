var dashboardApp = angular.module('dashboardApp', [
    'ngRoute',
    'dashboardCtrl',
    'dashboardDirectives',
    'dashboardSrv'
]).config(['$routeProvider', function ($routeProvider) {
        $routeProvider
                .when('/', {
                    templateUrl: 'assets/partials/home.html',
                    controller: 'homeController'
                })
                .otherwise({
                    redirectTo: '/'
                });
    }]);
