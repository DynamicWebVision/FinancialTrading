var app = angular.module("app", ['utility.directives', 'ui.bootstrap','ngRoute']);

app.config(['$routeProvider',
    function($routeProvider) {
        console.log($routeProvider);

        $routeProvider.
            when('/main#abc', {
                templateUrl: 'templates/add-order.html',
                controller: 'AddOrderController'
            }).
            when('/showOrders', {
                templateUrl: 'templates/show-orders.html',
                controller: 'ShowOrdersController'
            }).
            otherwise({
                redirectTo: '/addOrder'
            });
    }]);