/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

var fciDirectives = angular.module('fciDirectives', []);

fciDirectives.directive('fciNavbar', ['$location',
    function ($location) {
        return function (scope, element, attrs) {
            var patternMap = {},
                hashPattern,
                activeItem;

            if (!$location.$$html5) {
                hashPattern = /^#[^/]*/;
            }

            element.find('li').each(function () {
                var item = angular.element(this);

                var link = item.find('a'),
                    pattern = link.attr('data-nav-pattern');

                if (!pattern) {
                    pattern = link.attr('href');
                    if (!$location.$$html5) {
                        pattern.replace(hashPattern, '');
                    }
                    pattern = '^' + pattern + '$';
                }

                patternMap[pattern] = item;
            });

            scope.$on('$routeChangeStart', function () {
                var routeItem,
                    path = $location.path();

                jQuery.each(patternMap, function (pattern, item) {
                    pattern = new RegExp(pattern);

                    if (pattern.test(path)) {
                        routeItem = item;
                        return false;
                    }
                });

                if (routeItem) {
                    if (activeItem) {
                        activeItem.removeClass('active');
                    }

                    activeItem = routeItem;
                    activeItem.addClass('active');
                }
            });
        };
    }
]);


fciDirectives.directive('fciStatus', [
    function () {
        return {
            link: function (scope, element, attrs) {
                var statuses = {
                    passed: {text: 'Passed', color: 'success'},
                    failed: {text: 'Failed', color: 'warning'},
                    borked: {text: 'Borked', color: 'danger'}
                };

                scope.$watch(attrs.ngModel, function (status) {
                    if (status) {
                        element
                            .addClass('label')
                            .addClass('label-' + statuses[status].color)
                            .addClass('pull-right')
                            .text(statuses[status].text);
                    }
                });
            },
            restrict: 'E'
        };
    }
]);
