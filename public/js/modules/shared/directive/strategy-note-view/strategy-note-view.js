angular.module('app').directive('strategyNoteView', function() {
    var controller = function ($scope, StrategyNote) {
        $scope.strategyNote = StrategyNote;
        $scope.submit = false;
        $scope.processing = false;


        StrategyNote.loadNotes();
    };

    return {
        restrict: 'EA',
        replace: true,
        scope: {
            closeMerchantSelect: '&'
        },
        templateUrl: 'js/modules/shared/directive/strategy-note-view/strategy-note-view.html',
        controller: controller,
        link: function(scope, element, attrs, fn) {


        }
    };
});
