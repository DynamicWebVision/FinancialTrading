(function() {
    'use strict';

    angular
        .module('app')
        .controller('HistoricalRatesController', HistoricalRatesController);

    function HistoricalRatesController($timeout, UtilityService, $location, $http, BackTest, SweetAlert) {
        var vm = this;
        vm.frequencyId = 1;
        vm.currencyId = 1;
        vm.processing = false;

        vm.runSpecificHistoricalRates = runSpecificHistoricalRates;
        vm.fiftyOpacity = fiftyOpacity;

        $http.get('/frequencies_exchanges').success(function(data){
            vm.frequencies = data.frequencies;
            vm.exchanges = data.exchanges;
            vm.strategies = data.strategies;
        });

        function runSpecificHistoricalRates() {
            vm.processing = true;
            $http.get('historical_rates/run_specific/'+vm.frequencyId+'/'+vm.currencyId).then(function(response) {
                vm.processing = false;
                SweetAlert.swal({
                        title: "Rates Update, most recent date is now "+response.data,
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#",
                        confirmButtonText: "YES"
                    });
            });
        }

        function fiftyOpacity(value) {
            if (value) {
                return 'fifty-opacity';
            }
            else {
                return '';
            }
        }
    }
})();