(function() {
    'use strict';

    angular
        .module('app')
        .controller('StrategyCreateController', StrategyCreateController);

    function StrategyCreateController($timeout, Strategy, UtilityService, $location, $http, BackTest, SweetAlert) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.utility = UtilityService;
        vm.strategy = Strategy;

        vm.create = create;


        function create() {
            vm.submit = true;
            if (vm.create_form.$valid) {
                vm.processing = true;

                Strategy.create().success(function(response) {
                    Strategy.currentStrategy = response;
                    Strategy.newStrategy = {};

                    SweetAlert.swal({
                            title: "Strategy "+response.name+" Created!",
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