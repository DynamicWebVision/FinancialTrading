app.factory('StockSearch', function($http, $q) {

    var service = {};

    service.currentPage = 1;
    service.totalResults = 0;
    service.itemsPerPage = 25;

    service.searchCriteria = {};

    service.resetSearchParams = function() {
        service.searchCriteria.industry = -1;
        service.searchCriteria.sector = -1;
    }

    service.pageChanged = function() {
        console.log('abcdeafg');
    }

    service.search = function() {
        service.currentPage = 1;
        $http.post('/stocks/search', {searchCriteria: service.searchCriteria}).then(function(response) {
            console.log(response);
        });
    }

    service.resetSearchParams();

    return service;
});