(function() {
    'use strict';

    angular
        .module('app')
        .controller('TransactionController', TransactionController);

    function TransactionController($timeout, UtilityService, TransactionFactory) {
        var vm = this;

        vm.transactions = [];
        vm.tradeStats = {};
        vm.oandaAccounts = [];

        vm.posNegClass = posNegClass;
        vm.changeOandaAccount = changeOandaAccount;

        function load() {
            TransactionFactory.index().then(function(response) {
                vm.transactions = response.data.trades;
                vm.tradeStats = response.data.tradeStats;
                vm.oandaAccounts = response.data.oandaAccounts;
            });
        }

        load();

        function posNegClass(val) {
            return UtilityService.positiveNegativeClass(val);
        }

        function changeOandaAccount() {
            TransactionFactory.get(vm.currentOandaAccount).then(function(response) {
                vm.transactions = response.data.trades;
                vm.tradeStats = response.data.tradeStats;
                vm.oandaAccounts = response.data.oandaAccounts;
            });
        }
    }
})();