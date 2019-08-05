(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksCreateBacktest', StocksCreateBacktest);

    function StocksCreateBacktest(StockTechnicalCheck, $http, SweetAlert) {
        var vm = this;
        vm.data = {};

        vm.stockTechnicalCheck = StockTechnicalCheck;

        vm.createBacktest = createBacktest;
        vm.getTcVariables = getTcVariables;

        StockTechnicalCheck.loadAllTechnicalChecks();

        function getTcVariables() {
            StockTechnicalCheck.getTechnicalCheckVariables(vm.data.technical_check);
        }

        function createBacktest() {
            vm.newBacktestGroup.submit = true;
            if (vm.create_form.$valid) {
                vm.newBacktestGroup.processing = true;

                $http.post('/back_test/create_group', vm.newBacktestGroup).success(function(response){

                    vm.newBacktestGroup.processing = false;
                    SweetAlert.swal({
                            title: "New Back Test Group Successfully Created with id #"+response.back_test_group_id,
                            text: "Click Clear Form to Clear Form or Cancel to Use Variables for Another Group",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#488214",
                            confirmButtonText: "Clear Form",
                            closeOnConfirm: true},
                        function(){
                            //setNewBackTestObject();
                        });

                });
            }
        }


    }
})();