(function() {
    'use strict';

    angular
        .module('app')
        .controller('HighLowAnalysisController', HighLowAnalysisController);

    function HighLowAnalysisController($timeout, UtilityService, $http, BackTest) {
        var vm = this;

        vm.transactions = [];
        vm.monthData = [];

        vm.info = BackTest.info;
        var pipValue = parseFloat(BackTest.info.pip);

        vm.loadingBackTest = true;

        vm.takeProfitValue = 20;
        vm.stopLossValue = 10;

        vm.evaluateTpSl = evaluateTpSl;
        vm.gainLossClass = gainLossClass;

        vm.tpHitCount = 0;
        vm.slHitCount = 0;
        vm.npHitCount = 0;
        vm.netPips = 0;

        vm.fullFilters = false;

        $http.get('/high_low_analysis/'+BackTest.info.id).success(function(data){
            vm.transactions = data;
            vm.loadingBackTest = false;
        });

        function evaluateTpSl() {
            vm.tpHitCount = 0;
            vm.slHitCount = 0;
            vm.npHitCount = 0;
            vm.netPips = 0;

            var takeProfitValue = parseFloat(vm.takeProfitValue);
            var stopLossValue = parseFloat(vm.stopLossValue);

            var i;

            for (i = 0; i < vm.transactions.length; i++) {
                if (vm.transactions[i]['highLowFirst'] == 'highFirst') {
                    if (vm.transactions[i]['highPips'] >= takeProfitValue) {
                        vm.tpHitCount++;
                        vm.netPips = vm.netPips + takeProfitValue;
                        vm.transactions[i]['outcome'] = 'tp';
                    }
                    else if (vm.transactions[i]['lowPips'] >= stopLossValue) {
                        vm.slHitCount++;
                        vm.netPips = vm.netPips - stopLossValue;
                        vm.transactions[i]['outcome'] = 'sl';
                    }
                    else {
                        vm.transactions[i]['outcome'] = 'np';
                        vm.npHitCount++;
                        vm.transactions[i]['np_gl'] = calculateNewPositionPipAmount(vm.transactions[i]);
                        vm.netPips = vm.netPips + parseFloat(vm.transactions[i]['np_gl']);
                    }
                }
                else if (vm.transactions[i]['highLowFirst'] == 'lowEarly' || vm.transactions[i]['highLowFirst'] == 'lowFirst') {
                    if (vm.transactions[i]['lowPips'] >= stopLossValue) {
                        vm.slHitCount++;
                        vm.netPips = vm.netPips - stopLossValue;
                        vm.transactions[i]['outcome'] = 'sl';
                    }
                    else if (vm.transactions[i]['highPips'] >= takeProfitValue) {
                        vm.tpHitCount++;
                        vm.netPips = vm.netPips + takeProfitValue;
                        vm.transactions[i]['outcome'] = 'tp';
                    }
                    else {
                        vm.transactions[i]['outcome'] = 'np';
                        vm.npHitCount++;
                        vm.transactions[i]['np_gl'] = calculateNewPositionPipAmount(vm.transactions[i]);
                        vm.netPips = vm.netPips + parseFloat(vm.transactions[i]['np_gl']);
                    }
                }
            }
        }

        function gainLossClass(gl) {
            if (gl > 0) {
                return 'positive-green'
            }
            else {
                return 'negative-red'
            }
        }

        function calculateNewPositionPipAmount(transaction){
            if (transaction.position_type == 1) {
                return Math.round(((parseFloat(transaction.close_price) - parseFloat(transaction.open_price))/pipValue));
            }
            else {
                return Math.round(((parseFloat(transaction.open_price) - parseFloat(transaction.close_price))/pipValue));
            }
        }

    }
})();