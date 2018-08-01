app.factory('StrategyNote', function($http, $q) {

    var service = {};

    service.newStrategyNote = {};
    service.notes = [];
    service.strategySystemId = 0;
    service.frequencies = [];
    service.currentFrequencyId = -1;

    service.create = function() {
        return $http.post('strategy_notes', service.newStrategyNote);
    }

    service.loadNotes = function(strategyId) {
        $http.get('load_notes/'+strategyId).success(function(response) {
            service.notes = response;
        });
    }

    service.loadFrequencies = function() {
        $http.get('frequencies_exchanges').success(function(response) {
            service.frequencies = response.frequencies;
            service.frequencies.push({
                id: -1,
                frequency: 'Not Selected'
            });
        });
    }

    service.filterNotes = function(note) {
        console.log(note);
        if (note.strategy_system_id != service.strategySystemId && service.strategySystemId != 0
            || (note.frequency_id != service.currentFrequencyId && service.currentFrequencyId != -1)) {
            return false;
        }
        return true;
    }

    service.loadFrequencies();

    return service;
});