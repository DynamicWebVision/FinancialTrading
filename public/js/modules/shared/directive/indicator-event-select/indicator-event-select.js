angular.module('app').directive('indicatorEventSelect', function() {
    var controller = function ($scope,IndicatorEvent, Indicator) {
        $scope.indicator = Indicator;
        $scope.indicatorEvent = IndicatorEvent;
        $scope.submit = false;
        $scope.processing = false;

         $scope.indicator.index();

        $scope.getIndicatorEvents = function(indicator) {
            $scope.indicatorEvent.getIndicatorEvents(indicator);
        }

        $scope.setIndicatorEvent = function() {
            $scope.setIndicatorSelect();
        }
    };

    return {
        restrict: 'EA',
        replace: true,
        scope: {
            setIndicatorSelect: '&'
        },
        templateUrl: 'js/modules/shared/directive/indicator-event-select/indicator-event-select.html',
        controller: controller,
        link: function(scope, element, attrs, fn) {


        }
    };
});
