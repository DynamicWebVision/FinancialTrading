app.factory('StockTechnicalCheck', function($http) {

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

    function getTechnicalCheckVariables(technical_check_id) {
        var technicalCheckVariables  = $http.get('/stocks/technical_check_variables/'+technical_check_id);
        return technicalCheckVariables;
    }



    return stockTechnicalChecks;
})