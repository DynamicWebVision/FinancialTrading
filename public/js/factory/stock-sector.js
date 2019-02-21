app.factory('StockSector', function($http, $q) {

    var service = {};

    service.sectors = [];

    service.loadSectors = function() {
        $http.get('/stocks/sectors').then(function(response) {
            service.sectors = response.data;
        });
    }

    service.loadSectors();

    return service;
});