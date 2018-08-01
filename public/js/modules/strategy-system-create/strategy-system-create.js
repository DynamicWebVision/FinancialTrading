(function() {
    'use strict';

    angular
        .module('app')
        .controller('StrategySystemCreateController', StrategySystemCreateController);

    function StrategySystemCreateController($timeout, UtilityService, $location, $http, BackTest, SweetAlert, StrategySystem) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.utility = UtilityService;
        vm.strategySystem = StrategySystem;

        vm.create = create;

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
                        $location.path('strategy_management');
                    });
                });
            }
        }
    }
})();