app.factory('Users', function($http) {

    var service = {};

    service.getAllUsers = function() {
        var all_users  = $http.get('/all_users');
        all_users.then(function(response){
            return response;
        });
        return all_users;
    }

    service.createUser = function(new_user) {
        var create_user = $http.post('/create_user', new_user).success(function(data){
            return data;
        });
        return create_user;
    }

    service.getRoles = function() {
        var get_roles = $http.get('/get_roles').success(function(data){
            return data;
        });
        return get_roles;
    }

    service.removeUser = function(userId) {
        $http.post('/remove_user', {"userId":userId}).success(function(data){
            $("#removeUserModal").modal('toggle');
        });
    }

    service.logOn = function(logOnCriteria) {
        var log_on_attempt  = $http.post('/log_on', logOnCriteria);
        log_on_attempt.then(function(response){
            return response;
        });
        return log_on_attempt;
    }

    service.delete = function(delete_user) {
        var delete_user  = $http.post('/user/delete', {"id": delete_user});
        delete_user.then(function(response){
            return response;
        });
        return delete_user;
    }

    return service;
})