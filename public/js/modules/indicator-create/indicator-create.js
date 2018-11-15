(function() {
    'use strict';

    angular
        .module('app')
        .controller('IndicatorCreateController', IndicatorCreateController);

    function IndicatorCreateController($timeout, Indicator, UtilityService, $location, $http, BackTest, SweetAlert) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.utility = UtilityService;
        vm.indicator = Indicator;

        vm.create = create;


        function create() {
            vm.submit = true;
            if (vm.create_form.$valid) {
                vm.processing = true;

                Indicator.create().success(function(response) {
                    Indicator.currentIndicator = response;
                    Indicator.newIndicator = {};

                    SweetAlert.swal({
                            title: "Indicator "+response.name+" Created!",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#488214",
                            confirmButtonText: "OK",
                            closeOnConfirm: true},
                        function(){
                            $location.path('indicator_management');
                        });
                });
            }
        }
    }
})();