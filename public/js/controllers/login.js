/**
 * Created by brianoneill on 2/19/15.
 */

app.controller('LoginController', function($http, $scope) {

    $scope.login = {};
    $scope.login_failed = false;
    $scope.login_processing = false;

	$scope.logonAttempt = function() {
        $scope.login_processing = true;
        //$scope.login.csrf_token = app.csrfToken;
        $http.post('/login_attempt', $scope.login).success(function(response){
            $scope.login_processing = false;
            if (response == 1) {
                window.location="/home";
            }
            else {
                $scope.login_failed = true;
            }
        });
	}

});