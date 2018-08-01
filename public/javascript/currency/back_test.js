/**
 * Created by brianoneill on 2/19/15.
 */

app.controller('BacktestController', function(Currency, $scope, $http) {

    $scope.transactions = [];

    $http.get('/get_back_test_data/11').success(function(data){
        $scope.transactions = data;
    });

    $scope.showPositionType = function(c) {
        if (c == 1) {
            return 'long'
        }
        else {
            return 'short';
        }
    }

    $scope.gainLossClass = function(gl) {
        if (gl > 0) {
            return 'gain'
        }
        else {
            return 'loss'
        }
    }

});