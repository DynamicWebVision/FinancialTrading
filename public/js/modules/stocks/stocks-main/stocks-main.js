(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksMainController', StocksMainController);

    function StocksMainController($timeout, Stock, StockSearch, SweetAlert, StockIndustry,
                                StockSector, StockRates, UtilityService, StockTechnicalCheck) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.stockSearch = StockSearch;
        vm.stockIndustry = StockIndustry;
        vm.stockSector = StockSector;
        vm.stockTechnicalCheck = StockTechnicalCheck;
        vm.utilityService = UtilityService;
        vm.currentTable = 'main';
        vm.tableDisplays = ['main', 'fundamental'];

        vm.openChart = openChart;
        vm.tableType = tableType;
        vm.openFinancial = openFinancial;
        vm.openProfile = openProfile;

        function openFinancial(stock) {
            $("#stock-financial").modal('toggle');
            StockRates.symbol = stock.symbol;
            StockRates.setRates('1M');
        }

        function openProfile(stock) {
            Stock.companyProfile(stock.id);
            $("#stock-company-profile-modal").modal('toggle');
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