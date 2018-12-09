app.factory('IndicatorEvent', function($http, $q) {

    var service = {};

    service.newIndicatorEvent = {};
    service.currentIndicatorEvent = {};
    service.id = false;
    service.indicator_id = false;
    service.selectedIndicatorEvent = {};

    service.indicatorEvents = [];

    service.searchText = '';

    service.indicatorEventTypes = [];

    service.create = function() {
        service.newIndicatorEvent.indicator_id = service.indicator_id;
        return $http.post('indicator_event', service.newIndicatorEvent);
    }

    service.getIndicatorEvents = function(indicatorId) {
        service.indicator_id = indicatorId;
        return $http.get('indicator_events/'+indicatorId).success(function(response) {
            service.indicatorEvents = response;
        });
    }

    service.getIndicatorEvents = function(indicatorId) {
        service.indicator_id = indicatorId;
        return $http.get('indicator_events/'+indicatorId).success(function(response) {
            service.indicatorEvents = response;
        });
    }

    service.search = function(IndicatorEvent) {
        var compareText = "*"+service.searchText+"*";
        return new RegExp("^" + compareText.split("*").join(".*") + "$").test(IndicatorEvent.name);
    }

    service.setIndicatorEvent = function(IndicatorEvent) {
        service.currentIndicatorEvent = IndicatorEvent;
        service.searchText = '';

    }

    service.setIndicatorEventTypes = function() {
        return $http.get('indicator_event_types').success(function(response) {
            service.indicatorEventTypes = response;
        });
    }

    return service;
});