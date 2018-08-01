app.factory('Lookup', function($http, $q) {

    var service = {};

    service.singleLookup = function(lookup_type) {
        var lookup = $http.get('/lookup/'+lookup_type);

        lookup.then(function(data){
            return data;
        });
        return lookup;
    }

    service.multipleLookup = function(lookups) {
        var lookups = $http.post('/multi_lookup', lookups);

        lookups.then(function(data){
            return data;
        });
        return lookups;
    }

    service.yesNoTrueFalseConversion = function(value) {
        if (value == "Y") {
            return true;
        }
        else {
            return false;
        }
    }
    return service;
});