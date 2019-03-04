angular.module('app').directive('stockChart', function() {
    var controller = function ($scope, Stock, StockRates) {

        $scope.stockRates = StockRates;

        $scope.changeTimeSpan = function() {

        }
    };

    return {
        restrict: 'EA',
        replace: true,
        scope: {
            closeMerchantSelect: '&'
        },
        templateUrl: 'js/modules/shared/directive/stock-chart/stock-chart.html',
        controller: controller,
        link: function(scope, element, attrs, fn) {


        }
    };
});
