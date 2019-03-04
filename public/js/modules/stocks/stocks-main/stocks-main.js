(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksMainController', StocksMainController);

    function StocksMainController($timeout, Stock, StockSearch, SweetAlert, StockIndustry, StockSector, StockRates, UtilityService) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.stockSearch = StockSearch;
        vm.stockIndustry = StockIndustry;
        vm.stockSector = StockSector;
        vm.utilityService = UtilityService;
        vm.currentTable = 'main';
        vm.tableDisplays = ['main', 'fundamental'];

        vm.openChart = openChart;
        vm.tableType = tableType;
        vm.openFinancial = openFinancial;

        function openFinancial(stock) {
            $("#stock-financial").modal('toggle');
            StockRates.symbol = stock.symbol;
            StockRates.setRates('1M');
        }

        function openChart(stock) {
            $("#stock-chart-modal").modal('toggle');
            StockRates.symbol = stock.symbol;
            StockRates.setRates('1M');
        }

        function tableType(tableType) {
            if (vm.currentTable == tableType) {
                return 'btn-danger';
            }
            return 'btn-success';
        }
    }
})();