app.controller('ManageUsersController', function($scope, Users) {

    $scope.validation = 1;

    $scope.submit = false;
    $scope.new_user_processing = false;
    $scope.create_user_success = false;

    $scope.users = [];

    var delete_user;
    var delete_user_index;

    //Load the Users Table
    Users.getAllUsers().then(function(response) {
        $scope.users = response.data;
    });


    //Opens the Dialog to Delete an User
    $scope.deleteUserDialog = function(user , indx) {
        delete_user = user;
        delete_user_index = indx;
        $scope.delete_user_name = user.first_name+" "+user.last_name;
    }

    //Confirmation of Deleting an User
    $scope.confirmDeleteUser = function() {
        Users.delete(delete_user.id).then(function(response) {
            //Update the Users array by removing the deleted user
            $scope.users.splice(delete_user_index, 1);
            //Hide Bootstrap Modal
            $("#delete-user-modal").modal('toggle');
        });
    }

});