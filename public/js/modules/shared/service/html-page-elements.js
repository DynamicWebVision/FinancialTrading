app.factory('HtmlPageElements', function($http) {

    var service = {};

    service.setDocumentTitle = function() {
        var all_users  = $http.get('/all_users');
        all_users.then(function(response){
            return response;
        });
        return all_users;
    }

    return service;
})