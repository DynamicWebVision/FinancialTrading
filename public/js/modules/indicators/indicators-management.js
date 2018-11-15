(function() {
    'use strict';

    angular
        .module('app')
        .controller('IndicatorsManagementController', IndicatorsManagementController);

    function IndicatorsManagementController(Indicator, $location, $http, BackTest, SweetAlert, UtilityService, IndicatorEvent) {
        var vm = this;

        vm.processing = true;
        vm.indicator = Indicator;
        vm.indicatorEvent = IndicatorEvent;
        vm.utility = UtilityService;

        vm.search_text = '';

        vm.createIndicator = createIndicator;
        vm.createIndicatorEvent = createIndicatorEvent;
        vm.setIndicator = setIndicator;

        Indicator.index().success(function() {
            vm.processing = false;
        });

        function createIndicator() {
            $location.path('indicator_create');
        }

        function createIndicatorEvent() {
            IndicatorEvent.newIndicatorEvent = {};
            $location.path('indicator_event_create');
        }

        function setIndicator(indicator) {
            Indicator.setIndicator(indicator);
            IndicatorEvent.getIndicatorEvents(indicator.id);
        }
    }
})();