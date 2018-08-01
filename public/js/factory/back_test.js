app.factory('BackTest', function(UtilityService, $http, $q) {

    var service = {};

    service.info = {};

    service.backTestIterations = [];

    service.backTestGroupVariables = {};

    service.newBackTestGroup = {};

    service.searchText = '';
    service.currentBackTestGroup = {};

    service.iterationFilters = {
        variable_1: '',
        variable_2: '',
        variable_3: '',
        variable_4: '',
        variable_5: ''
    };

    service.allUnReviewed = false;

    service.homeSortOptions = [
        {
            code: 'id',
            desc: 'ID'
        },
        {
            code: 'total_gain_loss_pips',
            desc: 'Total GL'
        },
        {
            code: 'month_ratio',
            desc: 'Month Ratio'
        },
        {
            code: 'total_gl_ratio',
            desc: 'GL Ratio'
        },
        {
            code: 'high_low_ratio',
            desc: 'HL Ratio'
        },
        {
            code: 'kelly_total',
            desc: 'Kelly Criterion Total'
        },
    ];

    service.loadBackTestGroupIterations = function(backTestGroup) {
        var defer = $q.defer();

        service.currentBackTestGroup = backTestGroup;

        $http.get('/get_back_test_group_tests/'+backTestGroup.id).success(function(response){
            service.backTestIterations = response.back_tests;

            service.backTestGroupVariables.variable_1 = UtilityService.convertArrayToGrammarList(response.variable_1_values);
            service.backTestGroupVariables.variable_2 = UtilityService.convertArrayToGrammarList(response.variable_2_values);
            service.backTestGroupVariables.variable_3 = UtilityService.convertArrayToGrammarList(response.variable_3_values);
            service.backTestGroupVariables.variable_4 = UtilityService.convertArrayToGrammarList(response.variable_4_values);
            service.backTestGroupVariables.variable_5 = UtilityService.convertArrayToGrammarList(response.variable_5_values);

            service.backTestGroupVariables.take_profits = UtilityService.convertArrayToGrammarList(response.take_profits);
            service.backTestGroupVariables.stop_losses = UtilityService.convertArrayToGrammarList(response.stop_losses);
            service.backTestGroupVariables.trailing_stops = UtilityService.convertArrayToGrammarList(response.trailing_stops);


            defer.resolve(response);
        });

        return defer.promise;
    }

    service.filterGroups = function(group) {

        var searchTexts = service.searchText.split(' ');

        var searchPasses = [];
        var nameCompare;
        var idCompare;
        var currentSearchText;
        var compareTextWild;
        var frequencyCompare;
        var frequencyCodeCompare;
        var strategyCompare;
        var strategySystemCompare;
        var strategySystemMethodCompare;

        if (!service.allUnReviewed) {
            for (indx = 0; indx < searchTexts.length; indx++) {
                currentSearchText = searchTexts[indx];
                compareTextWild = "*"+currentSearchText+"*";

                //Name Comparison
                nameCompare = new RegExp("^" + compareTextWild.toUpperCase().split("*").join(".*") + "$").test(group.name.toUpperCase());

                //Id Comparison
                if (group.id == currentSearchText) {
                    idCompare = true;
                }
                else {
                    idCompare = false;
                }

                //Frequency Name Compare
                frequencyCompare = new RegExp("^" + compareTextWild.toUpperCase().split("*").join(".*") + "$").test(group.frequency_name.toUpperCase());
                //Frequency Code Name Compare
                frequencyCodeCompare = new RegExp("^" + compareTextWild.toUpperCase().split("*").join(".*") + "$").test(group.frequency_code.toUpperCase());

                strategyCompare = new RegExp("^" + compareTextWild.toUpperCase().split("*").join(".*") + "$").test(group.strategy_name.toUpperCase());
                strategySystemCompare = new RegExp("^" + compareTextWild.toUpperCase().split("*").join(".*") + "$").test(group.strategy_system_name.toUpperCase());
                strategySystemMethodCompare = new RegExp("^" + compareTextWild.toUpperCase().split("*").join(".*") + "$").test(group.strategy_method.toUpperCase());

                if (nameCompare || idCompare || frequencyCompare || frequencyCodeCompare || strategyCompare || strategySystemCompare || strategySystemMethodCompare) {
                    searchPasses.push(true);
                }
                else {
                    searchPasses.push(false);
                }
            }
            return !searchPasses.includes(false);
        }
        else {
            if (group.reviewed == 0 && group.stats_run == 1 && group.process_run == 1) {
                return true;
            }
        }


    }


    return service;
});