(function() {
    'use strict';

    angular
        .module('app')
        .controller('ProcessLoggerController', ProcessLoggerController);

    function ProcessLoggerController($timeout, UtilityService, $location, $http, BackTest, SweetAlert) {
        var vm = this;
        vm.account = 1;
        vm.dateTime = '';
        vm.exchange = -1;
        vm.utility = UtilityService;

        vm.processing = true;

        vm.totalLogCount = 0;
        vm.logs = [];
        vm.logMessagesSet = [];
        vm.logIndicators;
        vm.logApi;
        vm.onlyEvents = false;

        vm.accountName = '';
        vm.processes = [];
        vm.servers = [];
        vm.dataParmas = {};
        vm.dataParmas.selectedProcess = {};
        vm.dataParmas.selectedServer = {};

        vm.activeLog = {};

        vm.loadLog = loadLog;
        vm.fiftyOpacity = fiftyOpacity;
        vm.changeLogSet = changeLogSet;
        vm.loadMessages = loadMessages;
        vm.loadApi = loadApi;
        vm.loadIndicators = loadIndicators;
        vm.loadProcessLogs = loadProcessLogs;
        vm.loadServerLogs = loadServerLogs;

        $http.get('/process_logger').success(function(data){
            vm.processes = data.processes;
            vm.servers = data.servers;
            vm.processing = false;
        });

        function loadLog() {
            vm.processing = true;

            if (vm.dateTime.length < 1) {
                vm.dateTime = 'none';
            }
            var onlyEventsGet;

            if (vm.onlyEvents) {
                onlyEventsGet = 1;
            }
            else {
                onlyEventsGet = 0;
            }

            $http.get('strategy_logger/log_history/'+vm.account+'/'+vm.exchange+'/'+vm.dateTime+'/'+onlyEventsGet).then(function(response) {
                vm.processing = false;
                vm.logs = response.data.logs;
                vm.totalLogCount = response.data.count;

                vm.accountName = UtilityService.returnOneArrayFieldWithAnotherArrayFieldValue(vm.accounts, 'id', 'account_name', vm.account);
            });
        }

        function fiftyOpacity(value) {
            if (value) {
                return 'fifty-opacity';
            }
            else {
                return '';
            }
        }

        function changeLogSet(pageNumber) {
            vm.processing = true;

            var onlyEventsNumeric;

            if (vm.onlyEvents) {
                onlyEventsNumeric = 1;
            }
            else {
                onlyEventsNumeric = 0;
            }

            $http.get('strategy_logger/log_history_subset/'+pageNumber+'/'+vm.account+'/'+vm.exchange+'/'+vm.dateTime+'/'+onlyEventsNumeric).then(function(response) {
                vm.processing = false;
                vm.logs = response.data.logs;
            });
        }

        function loadMessages(log) {
            vm.processing = true;
            vm.activeLog = log;
            $http.get('strategy_logger/log_messages/'+log.id).then(function(response) {
                vm.processing = false;

                vm.logMessagesSet = response.data;

                $("#log-messages-modal").modal('toggle');
            });
        }

        function loadApi(log) {
            vm.processing = true;
            vm.activeLog = log;
            $http.get('strategy_logger/log_api/'+log.id).then(function(response) {
                vm.processing = false;
                console.log(response.data);
                vm.logApi = response.data;
                $("#log-api-modal").modal('toggle');
            });
        }

        function loadIndicators(log) {
            vm.processing = true;
            vm.activeLog = log;
            $http.get('strategy_logger/log_indicators/'+log.id).then(function(response) {
                vm.processing = false;

                vm.logIndicators = response.data;

                $("#log-indicators-modal").modal('toggle');
            });
        }

        function loadLogs() {
            $http.get('process_logger/load_logs', vm.dataParmas).then(function(response) {
                vm.processing = false;
                vm.logs = response.data.logs;
                vm.totalLogCount = response.data.count;

                vm.accountName = UtilityService.returnOneArrayFieldWithAnotherArrayFieldValue(vm.accounts, 'id', 'account_name', vm.account);
            });
        }

        function loadProcessLogs() {
            vm.dataParmas.selectedServer = {};
            loadLogs()
        }

        function loadServerLogs() {
            vm.dataParmas.selectedProcess = {};
            loadLogs()
        }

        document.title = 'Process Logs';
    }
})();