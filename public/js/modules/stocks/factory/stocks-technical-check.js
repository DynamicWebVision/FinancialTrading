app.factory('StockTechnicalCheck', function($http) {

    var stockTechnicalChecks = {};
    stockTechnicalChecks.all = [];

    stockTechnicalChecks.loadAllTechnicalChecks = loadAllTechnicalChecks;
    stockTechnicalChecks.getTechnicalCheckVariables = getTechnicalCheckVariables;

    function loadAllTechnicalChecks() {
        var allTechnicalChecks  = $http.get('/stocks/technical_checks');
        allTechnicalChecks.then(function(response){
            return response;
        });
        return allTechnicalChecks;
    }

    function getTechnicalCheckVariables(technical_check_id) {
        var technicalCheckVariables  = $http.get('/stocks/technical_check_variables/'+technical_check_id);
        technicalCheckVariables.then(function(response){
            return response;
        });
        return technicalCheckVariables;
    }



    return stockTechnicalChecks;
})