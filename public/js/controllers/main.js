(function() {
    'use strict';

    angular
        .module('app')
        .controller('MainController', MainController);

    function MainController($http) {
        var vm = this;

        vm.transactionParameters = {};
        vm.transactionParameters.pastThisMonth = false;

        vm.frequencies = [];
        vm.exchanges = [];
        vm.strategies = [];
        vm.oandaAccounts = [];

        vm.transactions = [];

        vm.loadingTransactions = false;

        vm.fullFilters = true;

        vm.loadTransactions = loadTransactions;
        vm.fiftyOpacityOffset = fiftyOpacityOffset;
        vm.gainLossClass = gainLossClass;
        vm.loadAllAccountTransactions = loadAllAccountTransactions;
        vm.decodeTransactionReason = decodeTransactionReason;

        $http.get('/frequencies_exchanges').success(function(data){
            vm.frequencies = data.frequencies;
            vm.exchanges = data.exchanges;
            console.log(vm.exchanges);

            vm.exchanges.push({'exchange' : 'ALL'});

            vm.strategies = data.strategies;
            vm.oandaAccounts = data.oandaAccounts;
        });

        function loadTransactions() {
            vm.loadingTransactions = true;
            $http.post('/load_transactions', vm.transactionParameters).success(function(response){
                vm.transactions = response;
                vm.loadingTransactions = false;
            });
        }

        function fiftyOpacityOffset() {
            if (vm.loadingTransactions) {
                return 'fifty-opacity';
            }
            else {
                return '';
            }
        }

        function gainLossClass(gl) {
            if (parseFloat(gl) > 0) {
                return 'positive-green'
            }
            else {
                return 'negative-red'
            }
        }

        function loadAllAccountTransactions() {
            vm.transactionParameters.exchange = 'ALL';
            loadTransactions();
        }

        function decodeTransactionReason(reason) {
            if (reason == 'STOP_LOSS_ORDER') {
                return '<span class="red">SL</span>';
            }
            else {
                return '<span class="red">MK</span>';
            }
        }

        document.title = 'Oanda Positions';
    }
})();