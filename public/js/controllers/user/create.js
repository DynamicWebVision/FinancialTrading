app.controller('CreateUserController', function($scope, Users) {

    $scope.validation = 1;
    $scope.email_exist = false;

    $scope.new_user = {};

    $scope.success_new_user = "";

    $scope.submit = false;
    $scope.new_user_processing = false;
    $scope.create_user_success = false;

    $scope.roles = [];

    $scope.createNewUser = function() {
        $scope.submit = true;
        if ($scope.create_user_form.$valid) {
            $scope.new_user_processing = true;

            Users.createUser($scope.new_user).then(function(response) {
                $scope.create_user_success = true;
                $scope.new_user_processing = false;
                $scope.success_new_user = $scope.new_user.first_name+" "+$scope.new_user.last_name;

                $scope.create_user_form.first_name.$faded = false;
                $scope.create_user_form.last_name.$faded = false;
                $scope.create_user_form.email.$faded = false;
                $scope.create_user_form.role.$faded = false;
                $scope.create_user_form.password.$faded = false;
                $scope.create_user_form.retype_password.$faded = false;

                $scope.new_user = {};
                $scope.submit = false;
            });
        }
    }

    Users.getRoles().then(function(response) {
        $scope.roles = response.data;
    });

});