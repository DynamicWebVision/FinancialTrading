(function() {
    'use strict';

    angular
        .module('app')
        .controller('FullController', FullController);

    function FullController($location, UtilityService, $http, BackTest, SweetAlert, StrategyNote) {
        var vm = this;

        vm.transactions = [];
        vm.monthData = [];

        vm.showPositionType = showPositionType;
        vm.gainLossClass = gainLossClass;
        vm.rollBackConfirm = rollBackConfirm;
        vm.copyToOtherPairs = copyToOtherPairs;
        vm.setUpNewBackTestGroupWithIteration = setUpNewBackTestGroupWithIteration;
        vm.openCreateStrategyNote = openCreateStrategyNote;
        vm.lowGainLossAnalysis = lowGainLossAnalysis;
        vm.highGainLossAnalysis = highGainLossAnalysis;

        vm.gl_low = {};
        vm.gl_high = {};

        vm.info = BackTest.info;

        if (typeof vm.info.id == 'undefined') {
            $location.path('back_test_home');
        }

        vm.backTestFactory = BackTest;

        vm.loadingBackTest = true;

        vm.chartStartingValue = 10000;
        vm.chartAccountPositionMultiplier = 5;


        $http.get('/full_test_stats/'+BackTest.info.id).success(function(data){
            vm.transactions = data.positions;
            vm.monthData = data.monthData;
            vm.loadingBackTest = false;

            loadChart();
        });

        function showPositionType(c) {
            if (c == 1) {
                return 'long'
            }
            else {
                return 'short';
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

        vm.chartAccountValues = [1, 4, 34, 15, 6];
        vm.chartLabels = ['abasdf', 'asdfas', 'asdfasfd', 'asdfasdf', 'aaa'];

        function loadChart() {
            var i;

            var chartAccountValueData = [];
            var chartDataLabels = [];

            var currentAccountValue = vm.chartStartingValue;

            for (i = 0; i < vm.monthData.length; i++) {

                var currentMonthTransactions = vm.monthData[i]['transactions'];

                var t_i;

                for (t_i = 0; t_i < currentMonthTransactions.length; t_i++) {


                    var currentTransaction = currentMonthTransactions[t_i];

                    currentTransaction.open_price = parseFloat(currentTransaction.open_price);
                    currentTransaction.gain_loss = parseFloat(currentTransaction.gain_loss);
                    var newAccountValue;

                    if (currentTransaction.position_type == 1) {
                        var open_price = currentTransaction.open_price;
                        var sell_price = currentTransaction.open_price + currentTransaction.gain_loss;

                        newAccountValue = currentAccountValue + (((currentAccountValue * vm.chartAccountPositionMultiplier) * sell_price) - ((currentAccountValue * vm.chartAccountPositionMultiplier) * open_price));
                    }
                    else {
                        var open_price = currentTransaction.open_price;
                        var sell_price = currentTransaction.open_price - currentTransaction.gain_loss;

                        newAccountValue = currentAccountValue + (((currentAccountValue * vm.chartAccountPositionMultiplier) * open_price) - ((currentAccountValue * vm.chartAccountPositionMultiplier) * sell_price));
                    }


                    currentAccountValue = newAccountValue;
                }

                chartAccountValueData.push(currentAccountValue);
                chartDataLabels.push(vm.monthData[i].transaction_month);
            }

            vm.chartAccountValues = chartAccountValueData;
            vm.chartLabels = chartDataLabels;
            }

        function rollBackConfirm() {
            SweetAlert.swal({
                    title: "Are you sure you want to roll back this iteration?",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#",
                    confirmButtonText: "YES"
                },
                function () {
                    rollBackIteration()
                });
        }

        function rollBackIteration() {
            $http.delete('/back_test_iteration/'+BackTest.info.processed_id).success(function(data){
                SweetAlert.swal({
                        title: "Process Has Been Rolled Back",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#",
                        confirmButtonText: "OK"
                    },
                    function () {
                        $location.path('back_test_home');
                    });
            });
        }

        function copyToOtherPairs() {
            $http.get('/copy_back_test_process_to_other_exchanges/'+BackTest.info.processed_id).success(function(data){
                SweetAlert.swal({
                        title: "New Back Test Group has been created with ID "+data.id,
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#",
                        confirmButtonText: "OK"
                    },
                    function () {
                        $location.path('back_test_home');
                    });
            });
        }

        function setUpNewBackTestGroupWithIteration() {
            $http.get('/back_test_group_from_iteration/'+BackTest.info.processed_id).success(function(data){
                BackTest.newBackTestGroup = data;



                $location.path('create_backtest_group');
            });
        }

        function lowGainLossAnalysis() {
            $http.get('/back_test/gain_loss_analysis_low/'+BackTest.info.id).success(function(response){
                console.log(response);
                vm.gl_low.labels = response.labels;
                vm.gl_low.data = response.data;

                $("#gain-loss-analysis-low").modal('toggle');
            });
        }

        function highGainLossAnalysis() {
            $http.get('/back_test/gain_loss_analysis_high/'+BackTest.info.id).success(function(response){
                vm.gl_high.labels = response.labels;
                vm.gl_high.data = response.data;

                $("#gain-loss-analysis-high").modal('toggle');
            });
        }

        function openCreateStrategyNote() {
            $("#create-strategy-note-modal").modal('toggle');
            StrategyNote.newStrategyNote.back_test_group_id = vm.info.back_test_group_id;
            StrategyNote.newStrategyNote.strategy_id = vm.info.strategy_id;
            StrategyNote.newStrategyNote.strategy_system_id = vm.info.strategy_system_id;
            StrategyNote.newStrategyNote.frequency_id = vm.info.frequency_id;
            StrategyNote.newStrategyNote.exchange_id = vm.info.exchange_id;
            StrategyNote.newStrategyNote.back_test_process_id = vm.info.processed_id;
            StrategyNote.newStrategyNote.bt_feedback = true;
            StrategyNote.newStrategyNote.for_future = false;
            StrategyNote.newStrategyNote.live_feedback = false;
        }
    }
})();