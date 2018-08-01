app.factory('TransactionFactory', function($http, $q) {

    var service = {};


    //Return Basic Data on Load
    service.index = function() {
        var index = $http.get('/dash_load');

        index.then(function(response){
            return response;
        }, function errorCallback(response) {
            console.log(response);
        });
        return index;
    }

    service.get = function(url) {
        var get = $http.get('/transactions/'+url);

        get.then(function(response){
            return response;
        }, function errorCallback(response) {
            console.log(response);
        });
        return get;
    }


    return service;
});