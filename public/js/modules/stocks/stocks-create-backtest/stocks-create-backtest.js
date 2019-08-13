(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksCreateBacktest', StocksCreateBacktest);

    function StocksCreateBacktest(StockTechnicalCheck, $http, SweetAlert) {
        var vm = this;
        vm.data = {};
        vm.data.technicalCheckVariables = [];

        vm.stockTechnicalCheck = StockTechnicalCheck;
        vm.stocks = [];

        vm.createBacktest = createBacktest;
        vm.getTcVariables = getTcVariables;

        StockTechnicalCheck.loadAllTechnicalChecks();

        function getTcVariables() {
            StockTechnicalCheck.getTechnicalCheckVariables(vm.data.technical_check).then(function(response) {
                vm.data.technicalCheckVariables = response.data;
            });
        }

        function createBacktest() {
            vm.data.submit = true;
            if (vm.create_form.$valid) {
                vm.data.processing = true;

                $http.post('stocks/back_test', vm.data).success(function(response){

                    vm.data.processing = false;
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

        // function loadStocks() {
        //     $http.get('/stocks/rates_profile').then(function(response) {
        //         vm.stocks = response.data;
        //     });
        // }
        //
        // loadStocks();
    }
})();