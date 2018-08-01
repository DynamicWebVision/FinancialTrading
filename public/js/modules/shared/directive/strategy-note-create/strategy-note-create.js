angular.module('app').directive('createStrategyNote', function() {
    var controller = function ($scope, StrategyNote) {
        $scope.strategyNote = StrategyNote;
        $scope.submit = false;
        $scope.processing = false;


        $scope.create = function() {
            $scope.submit = true;
            if ($scope.create_form.$valid) {
                $scope.processing = true;
                StrategyNote.create().success(function() {
                    $scope.processing = false;
                    $("#create-strategy-note-modal").modal('toggle');
                    StrategyNote.newStrategyNote = {};
                    $scope.create_form.note.$faded = false;
                    $scope.submit = false;
                });
            }
        }
    };

    return {
        restrict: 'EA',
        replace: true,
        scope: {
            closeMerchantSelect: '&'
        },
        templateUrl: 'js/modules/shared/directive/strategy-note-create/strategy-note-create.html',
        controller: controller,
        link: function(scope, element, attrs, fn) {


        }
    };
});
