(function() {
    'use strict';

    angular
        .module('app')
        .controller('LustreDumpController', LustreDumpController);

    function LustreDumpController($http) {
        var vm = this;

        vm.productTypeId = '';
        vm.jsonText = '';

        vm.rentals = [];

        vm.loadJson = function() {
            $http.post('lustre/load', {jsonObject: vm.jsonText, productTypeId: vm.productTypeId}).success(function(data){
                alert('Loaded');
                vm.jsonText = '';
            });
        }
    }
})();