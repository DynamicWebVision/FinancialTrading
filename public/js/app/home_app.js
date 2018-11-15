/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var app = angular.module('app', ['ngRoute','ngAnimate','utility.directives','oitozero.ngSweetAlert',
    'chart.js', 'angularUtils.directives.dirPagination', 'jsonFormatter']);

app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "template/main.html",
            controller : "MainController",
            controllerAs: 'main'
        })
        .when("/home", {
            templateUrl : "template/main.html",
            controller : "MainController",
            controllerAs: 'main'
        })
        .when("/back_test_home", {
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
        .when("/historical_rates", {
            templateUrl : "js/modules/historical_rates/run-historical-rates/run-historical-rates.html",
            controller : "HistoricalRatesController",
            controllerAs: 'hr'
        })
        .when("/strategy_logger", {
            templateUrl : "js/modules/strategy-log/strategy-log.html",
            controller : "StrategyLoggerController",
            controllerAs: 'sl'
        })
        .when("/strategy_management", {
            templateUrl : "js/modules/strategy-management/strategy-management.html",
            controller : "StrategyManagementController",
            controllerAs: 'sm'
        })
        .when("/strategy_create", {
            templateUrl : "js/modules/strategy-create/strategy-create.html",
            controller : "StrategyCreateController",
            controllerAs: 'sc'
        })
        .when("/strategy_system_create", {
            templateUrl : "js/modules/strategy-system-create/strategy-system-create.html",
            controller : "StrategySystemCreateController",
            controllerAs: 'ssc'
        })
        .when("/indicator_management", {
            templateUrl : "js/modules/indicators/indicators-management.html",
            controller : "IndicatorsManagementController",
            controllerAs: 'im'
        })
        .when("/indicator_create", {
            templateUrl : "js/modules/indicator-create/indicator-create.html",
            controller : "IndicatorCreateController",
            controllerAs: 'ic'
        })
        .when("/indicator_event_create", {
            templateUrl : "js/modules/indicator-event-create/indicator-event-create.html",
            controller : "IndicatorEventCreateController",
            controllerAs: 'iec'
        })
});
