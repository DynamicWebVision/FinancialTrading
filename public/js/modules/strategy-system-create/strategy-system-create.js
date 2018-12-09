(function() {
    'use strict';

    angular
        .module('app')
        .controller('StrategySystemCreateController', StrategySystemCreateController);

    function StrategySystemCreateController($timeout, UtilityService, $location, $http, BackTest, SweetAlert, StrategySystem, IndicatorEvent, Indicator) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.utility = UtilityService;
        vm.strategySystem = StrategySystem;
        vm.strategySystem.resetNewStrategySystem();

        IndicatorEvent.getIndicatorEvents();
        Indicator.index();

        vm.create = create;
        vm.addNewPositionIndicator = addNewPositionIndicator;
        vm.addNewPositionPricePoint = addNewPositionPricePoint;
        vm.addOpenPositionIndicator = addOpenPositionIndicator;
        vm.addOpenPositionPricePoint = addOpenPositionPricePoint;
        vm.setIndicator = setIndicator;
        var currentEventIndicatorAdd;

        function create() {
            vm.submit = true;
            if (vm.create_form.$valid) {
                vm.processing = true;

                StrategySystem.create().success(function(response) {
                    StrategySystem.currentStrategySystem = response;

                    SweetAlert.swal({
                            title: "Strategy System "+response.name+" Created!",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#488214",
                            confirmButtonText: "OK",
                            closeOnConfirm: true},
                    function(){
                        //$location.path('strategy_management');
                    });
                });
            }
        }

        function openIndicatorSelect() {
            $("#indicator-select-modal").modal('toggle');
            Indicator.selectedIndicator = {};
            Indicator.selectedIndicatorEvent = {};
            Indicator.indicatorEvents = [];
        }

        function addNewPositionIndicator() {
            currentEventIndicatorAdd = 'new_position_indicator';
            openIndicatorSelect();
        }

        function addNewPositionPricePoint() {
            currentEventIndicatorAdd = 'new_position_price_point';
            openIndicatorSelect();
        }

        function addOpenPositionIndicator() {
            currentEventIndicatorAdd = 'open_position_indicator';
            openIndicatorSelect();
        }

        function addOpenPositionPricePoint() {
            currentEventIndicatorAdd = 'open_position_price_point';
            openIndicatorSelect();
        }

        function setIndicator() {
            $("#indicator-select-modal").modal('toggle');

            if (currentEventIndicatorAdd == 'new_position_indicator') {
                vm.strategySystem.newStrategySystem.newPositionConditions.push(IndicatorEvent.selectedIndicatorEvent);
            }
            else if (currentEventIndicatorAdd == 'new_position_price_point') {
                vm.strategySystem.newStrategySystem.newPositionPriceTargets.push(IndicatorEvent.selectedIndicatorEvent);
            }
            else if (currentEventIndicatorAdd == 'open_position_indicator') {
                vm.strategySystem.newStrategySystem.openPositionConditions.push(IndicatorEvent.selectedIndicatorEvent);
            }
            else if (currentEventIndicatorAdd == 'open_position_price_point') {
                vm.strategySystem.newStrategySystem.openPositionPriceTargets.push(IndicatorEvent.selectedIndicatorEvent);
            }
        }
    }
})();