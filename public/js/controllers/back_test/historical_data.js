(function() {
    'use strict';

    angular
        .module('app')
        .controller('HistoricalDataController', HistoricalDataController);

    function HistoricalDataController($timeout, UtilityService, $http, BackTest) {
        var vm = this;

        vm.transactions = [];

        vm.frequencies = [];
        vm.exchanges = [];

        vm.currentFrequency = 1;
        vm.currentExchange = 1;

        vm.updateHistoricalData = updateHistoricalData;

        vm.back_test_info = BackTest.current_back_test;

        vm.loadingBackTest = true;

        $http.get('/frequencies_exchanges').success(function(data){
            vm.frequencies = data.frequencies;
            vm.exchanges = data.exchanges;
        });

        function updateHistoricalData() {
            $http.get('/evaluate_historical_data/'+vm.currentFrequency+'/'+vm.currentExchange).success(function(data){
                vm.monthYearSets = data.monthYearSets;
                vm.rateMonthYears = data.rateMonthYears;
            });
        }
    }
})();