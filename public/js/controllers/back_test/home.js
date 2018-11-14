(function() {
    'use strict';

    angular
        .module('app')
        .controller('HomeController', HomeController);

    function HomeController($timeout, UtilityService, $location, $http, BackTest, SweetAlert, StrategyNote) {
        var vm = this;

        vm.currentSortBy = 'id';
        vm.fullFilters = true;
        vm.searchFilters = true;
        vm.backTestGroup = -1;

        var backTestGroupIndex;

        vm.backTests = [];
        vm.backTestGroups = [];

        vm.sortByOptions = BackTest.homeSortOptions;

        vm.backTestFactory = BackTest;

        vm.viewFullBackTest = viewFullBackTest;
        vm.viewHighLowAnalysis = viewHighLowAnalysis;
        vm.gainLossClass = gainLossClass;
        vm.sortBackTest = sortBackTest;
        vm.getRatio = getRatio;
        vm.loadBTG = loadBTG;
        vm.fiftyOpacityOffset = fiftyOpacityOffset;
        vm.rollBackConfirm = rollBackConfirm;
        vm.openCreateStrategyNote = openCreateStrategyNote;
        vm.openIterationStrategyNote = openIterationStrategyNote;
        vm.markStrategyReviewed = markStrategyReviewed;
        vm.setBackTestGroup = setBackTestGroup;
        vm.loadStrategyNotes = loadStrategyNotes;
        vm.filterIterations = filterIterations;
        vm.rollBackStatsConfirm = rollBackStatsConfirm;
        vm.rollbackGroupStats = rollbackGroupStats;

        vm.pageProcessing = true;

        if (vm.backTestFactory.backTestIterations.length == 0) {
            vm.pageProcessing = true;
        }
        else {
            vm.pageProcessing = false;
        }

        $http.get('/get_back_tests').success(function(response){
            vm.backTestGroups = response.back_test_groups;
            vm.pageProcessing = false;
        });

        function markBackTestsEmpty() {
            vm.backTests = [];
        }

        function viewFullBackTest(back_test) {
            BackTest.info = back_test;
            BackTest.info.back_test_group_id = vm.backTestGroup.id;
            BackTest.info.strategy_system_id = vm.backTestGroup.strategy_system_id;
            BackTest.info.strategy_id = vm.backTestGroup.strategy_id;
            $location.path('back_test_full');
        }

        function viewHighLowAnalysis(back_test) {
            BackTest.info = back_test;
            $location.path('high_low_analysis');
        }

        function gainLossClass(gl) {
            if (gl > 0) {
                return 'positive-green'
            }
            else {
                return 'negative-red'
            }
        }

        function sortBackTest(back_test) {
            if (vm.currentSortBy == 'id') {
                return back_test.id;
            }
            else if (vm.currentSortBy == 'total_gain_loss_pips') {
                return -back_test.total_gain_loss_pips;
            }
            else if (vm.currentSortBy == 'month_ratio') {
                return -getRatio(back_test.positive_months, back_test.negative_months);
            }
            else if (vm.currentSortBy == 'total_gl_ratio') {
                return -getRatio(back_test.total_gain_transactions, back_test.total_loss_transactions);
            }
            else if (vm.currentSortBy == 'high_low_ratio') {
                return (back_test.median_max_gain - back_test.median_max_loss)*-1;
            }
            else if (vm.currentSortBy == 'high_low_ratio_by_total') {
                var total_transactions = back_test.total_gain_transactions + back_test.total_loss_transactions;
                return (total_transactions*(back_test.median_max_gain - back_test.median_max_loss))*-1;
            }
            else if (vm.currentSortBy == 'gl_ratio_by_tp_sl') {
                var total_transactions = back_test.total_gain_transactions + back_test.total_loss_transactions;
                return (total_transactions*(back_test.median_max_gain - back_test.median_max_loss))*-1;
            }
            else if (vm.currentSortBy == 'kelly_total') {
                return -back_test.total_kelly_criterion_gain_loss;
            }
        }

        function getRatio(value_1, value_2) {
            return UtilityService.getRatio(value_1, value_2);
        }

        function loadBTG() {
            vm.pageProcessing = true;
            BackTest.loadBackTestGroupIterations(vm.backTestGroup).then(function() {
                vm.pageProcessing = false;
            });
        }

        function fiftyOpacityOffset() {
            if (vm.pageProcessing) {
                return 'fifty-opacity';
            }
            else {
                return '';
            }
        }

        function rollBackConfirm() {
            SweetAlert.swal({
                    title: "Are you sure you want to roll back this group, "+vm.backTestGroup.id+" - "+vm.backTestGroup.name+"?",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#",
                    confirmButtonText: "YES"
                },
                function () {
                    rollbackGroup()
                });
        }

        function rollbackGroup() {
            $http.delete('/back_test_group/'+vm.backTestGroup.id).success(function(data){
                SweetAlert.swal({
                        title: vm.backTestGroup.id+" - "+vm.backTestGroup.name+" has been rolled back",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#",
                        confirmButtonText: "OK"
                    });
            });
        }

        function rollBackStatsConfirm() {
            SweetAlert.swal({
                    title: "Are you sure you want to roll back the stats for group, "+vm.backTestGroup.id+" - "+vm.backTestGroup.name+"?",
                    type: "success",
                    showCancelButton: true,
                    confirmButtonColor: "#",
                    confirmButtonText: "YES"
                },
                function () {
                    rollbackGroupStats()
                });
        }

        function rollbackGroupStats() {
            $http.delete('/back_test_stats/roll_back_group/'+vm.backTestGroup.id).success(function(data){
                SweetAlert.swal({
                        title: vm.backTestGroup.id+" - "+vm.backTestGroup.name+" has been rolled back",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#",
                        confirmButtonText: "OK"
                    });
            });
        }

        function openCreateStrategyNote() {
            $("#create-strategy-note-modal").modal('toggle');
            StrategyNote.newStrategyNote.back_test_group_id = vm.backTestGroup.id;
            StrategyNote.newStrategyNote.strategy_id = vm.backTestGroup.strategy_id;
            StrategyNote.newStrategyNote.strategy_system_id = vm.backTestGroup.strategy_system_id;
            StrategyNote.newStrategyNote.frequency_id = BackTest.backTestIterations[0]['frequency_id'];
            StrategyNote.newStrategyNote.exchange_id = BackTest.backTestIterations[0]['exchange_id'];
            StrategyNote.newStrategyNote.bt_feedback = true;
            StrategyNote.newStrategyNote.for_future = false;
            StrategyNote.newStrategyNote.live_feedback = false;
        }

        function openIterationStrategyNote(bt) {
            StrategyNote.newStrategyNote.back_test_process_id = bt.processed_id;
            openCreateStrategyNote();
        }

        function markStrategyReviewed() {
            $http.get('/back_test_group_reviewed/'+vm.backTestGroup.id).success(function(data){


                var backTestGroupIndex = UtilityService.findIndexByKeyValue(vm.backTestGroups, 'id',vm.backTestGroup.id);
                vm.backTestGroups[backTestGroupIndex]['reviewed'] = 1;

                console.log(vm.backTestGroups[backTestGroupIndex]);
                vm.backTestFactory.allUnReviewed = true;
                markBackTestsEmpty();

                SweetAlert.swal({
                        title: "Back Test "+vm.backTestGroup.id+" has been marked as reviewed!",
                        type: "success",
                        showCancelButton: true,
                        confirmButtonColor: "#488214",
                        confirmButtonText: "OK",
                        closeOnConfirm: true}, function() {

                });
            });
        }

        function setBackTestGroup(backTestGroup, index) {
            vm.backTestGroup = backTestGroup;
            loadBTG();
            vm.backTestFactory.searchText = '';
            vm.backTestFactory.allUnReviewed = false;
            backTestGroupIndex = index;
            console.log(backTestGroup);
            console.log(backTestGroupIndex);
            console.log(vm.backTestGroups[backTestGroupIndex]);
        }

        function loadStrategyNotes() {
            StrategyNote.loadNotes(vm.backTestGroup.strategy_id);
            StrategyNote.strategySystemId = vm.backTestGroup.strategy_system_id
            $("#strategy-note-view-modal").modal('toggle');
        }

        function numberCompare(value_1, value_2) {
            var value_1 = parseFloat(value_1);
            var value_2 = parseFloat(value_2);

            if (value_1 == value_2) {
                return true;
            }
            else {
                return false;
            }
        }

        function filterIterations(iteration) {
            var passValues = [];

            if (BackTest.iterationFilters.variable_1.length > 0) {

                passValues.push(numberCompare(iteration.variable_1, BackTest.iterationFilters.variable_1));
            }

            if (BackTest.iterationFilters.variable_2.length > 0) {

                passValues.push(numberCompare(iteration.variable_2, BackTest.iterationFilters.variable_2));
            }

            if (BackTest.iterationFilters.variable_3.length > 0) {

                passValues.push(numberCompare(iteration.variable_3, BackTest.iterationFilters.variable_3));
            }

            if (BackTest.iterationFilters.variable_4.length > 0) {

                passValues.push(numberCompare(iteration.variable_4, BackTest.iterationFilters.variable_4));
            }

            if (BackTest.iterationFilters.variable_5.length > 0) {

                passValues.push(numberCompare(iteration.variable_5, BackTest.iterationFilters.variable_5));
            }

            return !passValues.includes(false);
        }
    }
})();