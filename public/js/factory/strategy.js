app.factory('Strategy', function($http, $q, StrategySystem) {

    var service = {};

    service.newStrategy = {};
    service.currentStrategy = {};
    service.currentSet = false;
    service.id = false;

    service.strategies = [];

    service.searchText = '';

    service.create = function() {
        return $http.post('strategy', service.newStrategy);
    }

    service.index = function() {
        return $http.get('strategy').success(function(response) {
            service.strategies = response;
        });
    }

    service.search = function(strategy) {
        var compareText = "*"+service.searchText+"*";
        return new RegExp("^" + compareText.toUpperCase().split("*").join(".*") + "$").test(strategy.name.toUpperCase());
    }

    service.setStrategy = function(strategy) {
        service.currentStrategy = strategy;
        service.currentSet = true;
        service.searchText = '';
        StrategySystem.strategy_id = strategy.id;
    }

    return service;
});