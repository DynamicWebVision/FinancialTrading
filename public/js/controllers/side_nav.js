app.controller('SideNavController', function($scope, $location, $http) {
    $scope.parentLink = function(url) {
        $location.path('/'+url);
    }
});