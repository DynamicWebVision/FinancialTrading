(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksMainController', StocksMainController);

    function StocksMainController($timeout, Stock, StockSearch, SweetAlert, StockIndustry, StockSector) {
        var vm = this;
        vm.processing = false;
        vm.submit = false;

        vm.stockSearch = StockSearch;
        vm.stockIndustry = StockIndustry;
        vm.stockSector = StockSector;

    }
})();