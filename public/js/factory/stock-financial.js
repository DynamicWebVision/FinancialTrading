app.factory('StockFinancial', function($http, $q) {

    var service = {};

    service.industries = [];
    service.data = {};

    service.loadIndustries = function(stockId) {
        $http.get('/stocks/financial/'+stockId).then(function(response) {
            service.data = response.data;
        });
    }

    service.loadIndustries();

    return service;
});