(function() {
    'use strict';

    angular
        .module('app')
        .controller('CreateBacktestGroupController', CreateBacktestGroupController);

    function CreateBacktestGroupController($timeout, UtilityService, $http, BackTest, SweetAlert, StrategySystem, Strategy, StrategyNote) {
        var vm = this;

        vm.transactions = [];

        vm.newBacktestGroup = {};

        vm.strategySystem = StrategySystem;

        vm.frequencies = [];
        vm.exchanges = [];
        vm.strategies = [];

        vm.rate_unix_options = [{
            unix_time: 1262304000,
            readable_date: '2010'
        },{
            unix_time: 1293840000,
            readable_date: '2011'
        },{
            unix_time: 1325376000,
            readable_date: '2012'
        },{
            unix_time: 1356998400,
            readable_date: '2013'
        },{
            unix_time: 1388534400,
            readable_date: '2014'
        },{
            unix_time: 1420070400,
            readable_date: '2015'
        },{
            unix_time: 1451606400,
            readable_date: '2016'
        },{
            unix_time: 1483228800,
            readable_date: '2017'
        }
        ];

        vm.fiftyOpacity = fiftyOpacity;
        vm.create = create;
        vm.calculateTotalCurrentTests = calculateTotalCurrentTests;
        vm.getStrategySystems = getStrategySystems;
        vm.loadStrategyNotes = loadStrategyNotes;

        vm.newBacktestGroup.processing = false;

        if (typeof vm.newBacktestGroup != 'undefined') {
            getStrategySystems();
        }

        function setNewBackTestObject() {

            if (typeof BackTest.newBackTestGroup.variable_1_name == 'undefined') {
                vm.newBacktestGroup.all_currency_pairs = false;
                vm.newBacktestGroup.all_frequencies = false;
                vm.newBacktestGroup.take_profit = '';
                vm.newBacktestGroup.stop_loss = '';
                vm.newBacktestGroup.trailing_stop = '';
                vm.newBacktestGroup.pair_variables_1_2 = false;

                vm.newBacktestGroup.variable_1_values = '';
                vm.newBacktestGroup.variable_2_values = '';
                vm.newBacktestGroup.variable_3_values = '';
                vm.newBacktestGroup.variable_4_values = '';
                vm.newBacktestGroup.variable_5_values = '';

                vm.newBacktestGroup.variable_1_name = '';
                vm.newBacktestGroup.variable_2_name = '';
                vm.newBacktestGroup.variable_3_name = '';
                vm.newBacktestGroup.variable_4_name = '';
                vm.newBacktestGroup.variable_5_name = '';
            }
            else {
                vm.newBacktestGroup = BackTest.newBackTestGroup;
            }
        }

        $http.get('/frequencies_exchanges').success(function(data){
            vm.frequencies = data.frequencies;
            vm.exchanges = data.exchanges;
            vm.strategies = data.strategies;
        });

        function fiftyOpacity(value) {
            if (value) {
                return 'fifty-opacity';
            }
            else {
                return '';
            }
        }

        function create() {
            vm.newBacktestGroup.submit = true;
            if (vm.create_form.$valid) {
                vm.newBacktestGroup.processing = true;

                $http.post('/back_test/create_group', vm.newBacktestGroup).success(function(response){

                    vm.newBacktestGroup.processing = false;
                    SweetAlert.swal({
                            title: "New Back Test Group Successfully Created with id #"+response.back_test_group_id,
                            text: "Click Clear Form to Clear Form or Cancel to Use Variables for Another Group",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonColor: "#488214",
                            confirmButtonText: "Clear Form",
                            closeOnConfirm: true},
                        function(){
                            //setNewBackTestObject();
                        });

                });
            }
        }

        function calculateTotalCurrentTests() {
            if (vm.newBacktestGroup.pair_variables_1_2) {

            }
            else {
                var stop_loss_count = UtilityService.commaStringCount(vm.newBacktestGroup.stop_loss);
                var take_profit_count = UtilityService.commaStringCount(vm.newBacktestGroup.take_profit);
                var trailing_stop_count = UtilityService.commaStringCount(vm.newBacktestGroup.trailing_stop);
                var variable_1_count = UtilityService.commaStringCount(vm.newBacktestGroup.variable_1_values);
                var variable_2_count = UtilityService.commaStringCount(vm.newBacktestGroup.variable_2_values);
                var variable_3_count = UtilityService.commaStringCount(vm.newBacktestGroup.variable_3_values);
                var variable_4_count = UtilityService.commaStringCount(vm.newBacktestGroup.variable_4_values);
                var variable_5_count = UtilityService.commaStringCount(vm.newBacktestGroup.variable_5_values);

                return stop_loss_count*take_profit_count*trailing_stop_count*variable_1_count*variable_2_count*variable_3_count*variable_4_count*variable_5_count;
            }
        }

        function getStrategySystems() {
            StrategySystem.getStrategySystems(vm.newBacktestGroup.strategy);

        }

        function loadStrategyNotes() {
            StrategyNote.loadNotes(vm.newBacktestGroup.strategy);
            $("#strategy-note-view-modal").modal('toggle');
        }

        setNewBackTestObject();

    }
})();