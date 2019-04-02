angular.module('app').directive('stockCompanyProfile', function() {
    var controller = function ($scope, Stock, StockRates) {

        $scope.stock = Stock;
    };

    return {
        restrict: 'EA',
        replace: true,
        scope: {
            closeMerchantSelect: '&'
        },
        templateUrl: 'js/modules/shared/directive/stock-company-profile/stock-company-profile.html',
        controller: controller,
        link: function(scope, element, attrs, fn) {


        }
    };
});
