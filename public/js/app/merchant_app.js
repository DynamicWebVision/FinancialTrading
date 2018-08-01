/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var app = angular.module('app', ['utility.directives', 'ngRoute', 'toggle-switch']);

//This Handles the Angular Side Bar Routing
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "template/merchant/general_info.html",
            controller : "InfoController",
            controllerAs: 'info'
        })
        .when("/general_info", {
        templateUrl : "template/merchant/general_info.html",
        controller : "InfoController",
        controllerAs: 'info'
        })
        .when("/contact", {
            templateUrl : "template/merchant/contact.html",
            controller : "ContactController",
            controllerAs: 'contact'
        })
        .when("/hours", {
            templateUrl : "template/merchant/hours.html",
            controller : "HoursController",
            controllerAs: 'vm_hour'
        })
        .when("/tax", {
            templateUrl : "template/merchant/tax.html",
            controller : "TaxController",
            controllerAs: 'tax'
        })
        .when("/delivery", {
            templateUrl : "template/merchant/delivery.html",
            controller : "DeliveryController",
            controllerAs: 'delivery'
        })
        .when("/ordering", {
            templateUrl : "template/merchant/ordering.html",
            controller : "OrderingController",
            controllerAs: 'ordering'
        })
        .when("/send_order", {
        templateUrl : "template/merchant/send_order.html",
        controller : "MerchantSendOrderController"
        })
        .when("/search_results", {
            templateUrl : "template/merchant/search_results.html",
            controller : "SearchResultsController"
        })
});

app.run(function($rootScope, $location, $anchorScroll, $routeParams) {
    $rootScope.$on('$routeChangeSuccess', function(newRoute, oldRoute) {
         $anchorScroll.yOffset = 140;
        $location.hash($routeParams.scrollTo);
        $anchorScroll();
    });
});