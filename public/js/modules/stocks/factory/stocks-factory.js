app.factory('StockFactory', function($http) {

    var stockTechnicalChecks = {};
    stockTechnicalChecks.allTechnicalChecks = [];
    stockTechnicalChecks.technicalCheckVariables = [];

    stockTechnicalChecks.loadAllTechnicalChecks = loadAllTechnicalChecks;
    stockTechnicalChecks.getTechnicalCheckVariables = getTechnicalCheckVariables;

    function loadAllTechnicalChecks() {
        var allTechnicalChecks  = $http.get('/stocks/technical_checks');
        allTechnicalChecks.then(function(response){
            stockTechnicalChecks.allTechnicalChecks = response.data;
        });
        return allTechnicalChecks;
    }

    return stockTechnicalChecks;
})