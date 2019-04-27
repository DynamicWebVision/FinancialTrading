app.factory('StockTechnicalCheck', function($http, $q) {

    var service = {};

    service.technicalChecks = [
        {
            code: 0,
            name: 'none'
        },
        {
            code: 1,
            name: 'Long'
        },
        {
            code: -1,
            name: 'Short'
        },
    ];

    // service.loadTechnicalChecks = function() {
    //     $http.get('/stocks/technical_checks').then(function(response) {
    //         service.technical_checks = response.data;
    //     });
    // }
    //
    // service.loadTechnicalChecks();

    return service;
});