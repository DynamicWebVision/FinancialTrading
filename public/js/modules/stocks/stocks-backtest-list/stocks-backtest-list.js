(function() {
    'use strict';

    angular
        .module('app')
        .controller('StocksBacktestListCtrl', StocksBacktestListCtrl);

    function StocksBacktestListCtrl(StockTechnicalCheck, $http, SweetAlert, StockBacktestGroups) {
        var vm = this;
        vm.dataParams = {};

        vm.orderBy = 'final_account_value';

        vm.stockTechnicalCheck = StockTechnicalCheck;
        vm.stocks = [];

        vm.backTestGroupsFactory = StockBacktestGroups;

        vm.getGroupIterations = getGroupIterations;
        vm.orderByButtonClass = orderByButtonClass;
        vm.sortBackTestList = sortBackTestList;

        vm.iterations = [];

        function getGroupIterations() {
            console.log(vm.dataParams.selectedGroup);
            $http.get('stocks/backtest_group_iterations/'+vm.dataParams.selectedGroup.id).then(function(response) {
                vm.iterations = response.data;
                console.log(response);
            });
        }

        function orderByButtonClass(val) {
            if (vm.orderBy == val) {
                return 'btn-success';
            }
            else {
                return 'btn-default';
            }
        }

        function sortBackTestList(back_test) {
            if (vm.orderBy == 'final_account_value') {
                return -back_test.final_account_value;
            }
        }

        StockBacktestGroups.loadBacktestGroups();
    }
})();