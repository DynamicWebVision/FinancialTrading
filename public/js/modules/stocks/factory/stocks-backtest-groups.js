app.factory('StockBacktestGroups', function($http) {

    var stockBackTestGroups = {};
    stockBackTestGroups.backtestGroups = [];

    stockBackTestGroups.loadBacktestGroups = loadBacktestGroups;

    function loadBacktestGroups() {
        var allTechnicalChecks  = $http.get('/stocks/backtest_groups');
        allTechnicalChecks.then(function(response){
            stockBackTestGroups.backtestGroups = response.data;
        });
        return allTechnicalChecks;
    }

    return stockBackTestGroups;
})