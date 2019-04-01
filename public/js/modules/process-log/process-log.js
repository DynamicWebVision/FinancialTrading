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
        vm.logResults = {};
        vm.currentLog = {};
        vm.currentPage = 1;
        vm.logMessagesSet = [];
        vm.logMessages = [];
        vm.logIndicators;
        vm.logApi;
        vm.onlyEvents = false;
        vm.itemsPerPage = 25;

        vm.accountName = '';
        vm.processes = [];
        vm.servers = [];
        vm.dataParams = {};
        vm.dataParams.orderBy = 'start_date_time';
        vm.dataParams.orderDirection = 1;
        vm.dataParams.currentPage = 1;
        vm.dataParams.selectedProcess = {};
        vm.dataParams.selectedServer = {};

        vm.activeLog = {};

        vm.loadLog = loadLog;
        vm.fiftyOpacity = fiftyOpacity;
        vm.changeLogSet = changeLogSet;
        vm.loadMessages = loadMessages;
        vm.loadApi = loadApi;
        vm.loadIndicators = loadIndicators;
        vm.loadProcessLogs = loadProcessLogs;
        vm.loadServerLogs = loadServerLogs;
        vm.pageChanged = pageChanged;
        vm.changeOrderBy = changeOrderBy;
        vm.openFullLog = openFullLog;
        vm.dangerClass = dangerClass;

        $http.get('/process_logger').success(function(data){
            vm.processes = data.processes;
            vm.servers = data.servers;
            vm.results = data.logResults;
            vm.totalCount = data.recordCount;
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
            vm.processing = true;
            $http.post('process_logger/load_logs', vm.dataParams).then(function(response) {
                vm.processing = false;
                vm.results = response.data.logs;
                vm.processing = false;
            });
        }

        function loadLogsNew() {
            vm.processing = true;
            $http.post('process_logger/load_logs', vm.dataParams).then(function(response) {
                vm.processing = false;
                vm.totalCount = response.data.count;
                vm.results = response.data.logs;
                vm.processing = false;
            });
        }

        function loadProcessLogs() {
            vm.dataParams.selectedServer = {};
            vm.dataParams.currentPage = 1;
            loadLogsNew()
        }

        function loadServerLogs() {
            vm.dataParams.selectedProcess = {};
            vm.dataParams.currentPage = 1;
            loadLogsNew()
        }

        function fiftyOpacity(value) {
            if (value) {
                return 'fifty-opacity';
            }
            else {
                return '';
            }
        }

        function pageChanged() {
            vm.dataParams.currentPage = vm.currentPage;
            loadLogs();
        }

        function changeOrderBy() {
            vm.dataParams.currentPage = vm.currentPage;
        }

        function openFullLog(log) {
            vm.currentLog = log;
            $http.get('process_log/'+log.id).then(function(response) {
                vm.logMessages = response.data;
                $("#process-log-modal").modal('toggle');
            });
        }

        function dangerClass(messageType) {
            console.log(messageType);
            if (messageType == 1) {
                return 'text-danger';
            }
        }

        document.title = 'Process Logs';
    }
})();