(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksBacktestListCtrl', StocksBacktestListCtrl);

    function StocksBacktestListCtrl(StockTechnicalCheck, $http, SweetAlert, StockBacktestGroups) {
        var vm = this;
        vm.data = {};
        vm.data.technicalCheckVariables = [];

        vm.stockTechnicalCheck = StockTechnicalCheck;
        vm.stocks = [];

        StockBacktestGroups.loadBacktestGroups();

    }
})();