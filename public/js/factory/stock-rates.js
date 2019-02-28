app.factory('StockRates', function($http, $q) {

    var service = {};

    service.symbol = '';
    service.rates = [];
    service.labels = [];

    service.chartPeriods = ['1D', '1M', '3M', '6M', 'ytd', '1Y', '2Y', '5Y'];

    service.selectedPeriod = '1M';

    service.setRates = function(chartPeriod) {
        service.selectedPeriod = chartPeriod;
        $http.get('/stocks/rates/'+service.symbol+'/'+chartPeriod).success(function(response){
            service.rates = response.rates;
            service.labels = response.labels;
        });
    }

    service.currentPeriod = function(period) {
        if (service.selectedPeriod == period) {
            return 'btn-danger';
        }
        return 'btn-success';
    }

    return service;
});