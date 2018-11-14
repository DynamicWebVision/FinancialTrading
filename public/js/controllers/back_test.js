(function() {
    'use strict';

    angular
        .module('app')
        .controller('BacktestController', BacktestController);

    function BacktestController($timeout, UtilityService, $http) {
        var vm = this;

        vm.transactions = [];

        vm.showPositionType = showPositionType;
        vm.gainLossClass = gainLossClass;

        $http.get('/get_back_test_data/14').success(function(data){
            vm.transactions = data.positions;

            vm.monthData = data.monthData;
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

        function getRatio(value_1, value_2) {
            return UtilityService.getRatio(value_1, value_2);
        }
    }
})();