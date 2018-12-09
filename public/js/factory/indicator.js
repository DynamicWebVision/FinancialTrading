app.factory('Indicator', function($http, $q, IndicatorEvent) {

    var service = {};

    service.newIndicator = {};
    service.currentIndicator = {};
    service.currentSet = false;
    service.id = false;

    service.indicators = [];

    service.searchText = '';

    service.selectedIndicator = {};

    service.create = function() {
        return $http.post('indicator', service.newIndicator);
    }

    service.index = function() {
        return $http.get('indicator').success(function(response) {
            service.indicators = response;
            console.log(response);
        });
    }

    service.search = function(indicator) {
        var compareText = "*"+service.searchText+"*";
        return new RegExp("^" + compareText.toUpperCase().split("*").join(".*") + "$").test(indicator.name.toUpperCase());
    }

    service.setIndicator = function(indicator) {
        service.currentIndicator = indicator;
        service.currentSet = true;
        service.searchText = '';
        IndicatorEvent.indicator_id = indicator.id;
    }

    return service;
});