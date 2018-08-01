/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var app = angular.module('app', ['ngRoute','ngAnimate','utility.directives', 'oitozero.ngSweetAlert', 'chart.js']);

app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "template/back_test/home.html",
            controller : "HomeController",
            controllerAs: 'home'
        })
        .when("/back_test_full", {
            templateUrl : "template/back_test/back_test_full.html",
            controller : "FullController",
            controllerAs: 'full'
        })
        .when("/historical_data", {
            templateUrl : "template/back_test/historical_data.html",
            controller : "HistoricalDataController",
            controllerAs: 'hd'
        })
        .when("/create_backtest_group", {
            templateUrl : "template/back_test/create_backtest_group.html",
            controller : "CreateBacktestGroupController",
            controllerAs: 'cbg'
        })
        .when("/high_low_analysis", {
            templateUrl : "template/back_test/high_low_analysis.html",
            controller : "HighLowAnalysisController",
            controllerAs: 'hl'
        })
        .when("/servers", {
            templateUrl : "template/servers.html",
            controller : "ServersController",
            controllerAs: 's'
        })
});
