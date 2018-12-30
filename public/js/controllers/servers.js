(function() {
    'use strict';

    angular
        .module('app')
        .controller('ServersController', ServersController);

    function ServersController($timeout, UtilityService, $http, BackTest) {
        var vm = this;

        vm.servers = [];
        vm.tasks = [];
        vm.monthData = [];
        var inputChangedPromise = false;
        vm.updateSuccess = false;

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

        vm.editServer = {};
        var edit_server_index;
        vm.utility = UtilityService;

        vm.updateServers = updateServers;
        vm.typeTaskChange = typeTaskChange;
        vm.updateServerOpen = updateServerOpen;
        vm.serverLink = serverLink;
        vm.copyToLocal = copyToLocal;
        vm.copyFromLocal = copyFromLocal;
        vm.openEditServer = openEditServer;
        vm.updateServer = updateServer;

        function loadServers() {
            $http.get('/servers').success(function(response){
                vm.servers = response.servers;
                vm.tasks = response.serverTasks;

            });
        }

        function updateServers() {
            $http.post('/servers', vm.servers).success(function(response){
                vm.updateSuccess = true;
                $timeout(function () {
                    vm.updateSuccess = false;
                }, 1500);
                loadServers();
        })

        }

        function copyToLocal(server) {
            $http.post('/servers_copy_local', server).success(function(response){
                vm.updateSuccess = true;
                $timeout(function () {
                    vm.updateSuccess = false;
                }, 1500);
                loadServers();
        })

        }

        function copyFromLocal(server) {
            $http.post('/servers_copy_from_local', server).success(function(response){
                vm.updateSuccess = true;
                $timeout(function () {
                    vm.updateSuccess = false;
                }, 1500);
                loadServers();
        })

        }

        function typeTaskChange(server) {
            if(inputChangedPromise){
                $timeout.cancel(inputChangedPromise);
            }
            inputChangedPromise = $timeout(function() {
                    updateServers();
            },
             1500);
        }

        function updateServerOpen(index, server) {
            server.task='-------';
            vm.servers[index]['current_task'] = '-------';
            updateServers();
        }

        function serverLink(address) {
            return 'http://'+address;
        }

        function openEditServer(index, server) {
            vm.editServer = server;
            edit_server_index = index;
        }

        function updateServer() {
            $http.post('/update_server', vm.editServer).success(function(response){
                console.log(response.data);
                vm.servers[edit_server_index] = response.data;
                updateServers();
                $("#edit-server-modal").modal('toggle');

                vm.updateSuccess = true;
                $timeout(function () {
                    vm.updateSuccess = false;
                }, 1500);
                loadServers();
            })


        }
        loadServers();
    }
})();