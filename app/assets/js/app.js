/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

var fciApp = angular.module('fciApp', [
    'ngRoute',
    'ui.bootstrap',
    'fciControllers',
    'fciDirectives'
]);

fciApp.config(['$routeProvider', '$locationProvider',
    function ($routeProvider, $locationProvider) {
        $routeProvider
            .when('/', {
                templateUrl: '/template/home',
                controller: 'HomeCtrl'
            })
            .when('/build', {
                templateUrl: '/template/build/list',
                controller: 'BuildListCtrl'
            })
            .when('/build/:suiteCn/:buildCn', {
                templateUrl: '/template/build/show',
                controller: 'BuildShowCtrl'
            })
            .otherwise({
                redirectTo: '/'
            });

        $locationProvider.html5Mode(true);
    }
]);
