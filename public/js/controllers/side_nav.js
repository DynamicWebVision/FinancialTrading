app.controller('SideNavController', function($scope, $location, $http) {
    $scope.openUrl = '';
    $scope.parentLink = function(url) {
        $location.path('/'+url);
    }
});