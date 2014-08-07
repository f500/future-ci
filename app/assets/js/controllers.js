/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

var fciControllers = angular.module('fciControllers', []);

fciControllers.controller('HomeCtrl',
    ['$scope', '$http', function ($scope) {
        // ...
    }]
);

fciControllers.controller('BuildListCtrl',
    ['$scope', '$http', function ($scope, $http) {
        $http.get('/api/build').success(function (buildSets) {
            $scope.buildSets = buildSets;
        });
    }]
);

fciControllers.controller('BuildShowCtrl',
    ['$scope', '$routeParams', '$http', function ($scope, $routeParams, $http) {
        var suiteCn = $routeParams.suiteCn,
            buildCn = $routeParams.buildCn;

        $http.get('/api/build/' + suiteCn + '/' + buildCn).success(function (buildResult) {
            $scope.metadata = buildResult.metadata;
            $scope.results  = buildResult.results;
            $scope.statuses = buildResult.statuses;

            $scope.tasks = Object.keys($scope.metadata.tasks);
        });
    }]
);
