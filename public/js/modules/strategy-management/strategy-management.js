(function() {
    'use strict';

    angular
        .module('app')
        .controller('StrategyManagementController', StrategyManagementController);

    function StrategyManagementController(Strategy, $location, $http, BackTest, SweetAlert, UtilityService, StrategyNote, StrategySystem) {
        var vm = this;

        vm.processing = true;
        vm.strategy = Strategy;
        vm.strategyNote = StrategyNote;
        vm.strategySystem = StrategySystem;
        vm.utility = UtilityService;

        vm.search_text = '';

        vm.createStrategy = createStrategy;
        vm.createStrategySystem = createStrategySystem;
        vm.setStrategy = setStrategy;

        Strategy.index().success(function() {
            vm.processing = false;
        });

        function createStrategy() {
            $location.path('strategy_create');
        }

        function createStrategySystem() {
            StrategySystem.newStrategySystem = {};
            $location.path('strategy_system_create');
        }

        function setStrategy(strategy) {
            Strategy.setStrategy(strategy);
            StrategyNote.loadNotes(strategy.id);
            StrategyNote.strategySystemId = 0;
            StrategySystem.getStrategySystems(strategy.id);
        }
    }
})();