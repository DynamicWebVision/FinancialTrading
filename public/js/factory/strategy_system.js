app.factory('StrategySystem', function($http, $q) {

    var service = {};

    service.newStrategySystem = {};
    service.currentStrategySystem = {};
    service.id = false;
    service.strategy_id = false;

    service.strategySystems = [];

    service.searchText = '';

    service.resetNewStrategySystem = function() {
        service.newStrategySystem = {};
        service.newStrategySystem.newPositionConditions = [];
        service.newStrategySystem.newPositionPriceTargets = [];
        service.newStrategySystem.openPositionConditions = [];
        service.newStrategySystem.openPositionPriceTargets = [];
    }

    service.create = function() {
        service.newStrategySystem.strategy_id = service.strategy_id;
        return $http.post('strategy_system', service.newStrategySystem);
    }

    service.getStrategySystems = function(strategyId) {
        return $http.get('strategy_systems/'+strategyId).success(function(response) {
            service.strategySystems = response;
        });
    }

    service.search = function(StrategySystem) {
        var compareText = "*"+service.searchText+"*";
        return new RegExp("^" + compareText.split("*").join(".*") + "$").test(StrategySystem.name);
    }

    service.setStrategySystem = function(StrategySystem) {
        service.currentStrategySystem = StrategySystem;
        service.searchText = '';
    }

    return service;
});