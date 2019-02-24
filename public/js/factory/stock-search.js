app.factory('StockSearch', function($http, $q) {

    var service = {};

    service.currentPage = 1;
    service.totalResults = 0;
    service.itemsPerPage = 25;
    service.results = [];
    service.count = 0;
    service.processing = false;

    service.searchCriteria = {};
    service.searchCriteria.orderBy = 'id';
    service.searchCriteria.symbol = '';
    service.searchCriteria.name = '';

    service.resetSearchParams = function() {
        service.searchCriteria.industry = -1;
        service.searchCriteria.sector = -1;
    }

    service.pageChanged = function() {
        console.log(service.currentPage);
        service.loadResults();
    }

    service.search = function() {
        service.currentPage = 1;
        service.loadResults();
    }

    service.loadResults = function() {
        service.processing = true;
        $http.post('/stocks/search', {searchCriteria: service.searchCriteria, currentPage: service.currentPage}).then(function(response) {
            service.results = response.data.results;
            service.totalResults = response.data.totalCount;
            service.processing = false;
        });
    }

    service.orderByChange = function(orderBy) {
        service.searchCriteria.orderBy = orderBy;
        service.loadResults();
    }

    service.resetSearchParams();

    service.fiftyOpacity = function() {
        if (service.processing) {
            return 'fifty-opacity';
        }
        else {
            return '';
        }
    }

    return service;
});