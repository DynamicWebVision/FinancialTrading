(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksMainController', StocksMainController);

    function StocksMainController($timeout, Stock, StockSearch, SweetAlert, StockIndustry, StockSector, StockRates) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.stockSearch = StockSearch;
        vm.stockIndustry = StockIndustry;
        vm.stockSector = StockSector;

        vm.openChart = openChart;

        function openChart(stock) {
            $("#stock-chart-modal").modal('toggle');
            StockRates.symbol = stock.symbol;
            StockRates.setRates('1M');
        }
    }
})();