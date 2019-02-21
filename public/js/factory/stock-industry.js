app.factory('StockIndustry', function($http, $q) {

    var service = {};

    service.industries = [];

    service.loadIndustries = function() {
        $http.get('/stocks/industry').then(function(response) {
            service.industries = response.data;
        });
    }

    service.loadIndustries();

    return service;
});