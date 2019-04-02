/**
 * Created by Brian on 6/15/15.
 */
app.service('UtilityService', function($http, $q) {

    var service = {};

    //Weekdays
    service.week_days = [];
    service.week_days[1] = 'Sunday';
    service.week_days[2] = 'Monday';
    service.week_days[3] = 'Tuesday';
    service.week_days[4] = 'Wednesday';
    service.week_days[5] = 'Thursday';
    service.week_days[6] = 'Friday';
    service.week_days[7] = 'Saturday';

    service.getAllUsers = function() {
        var csrfToken  = $http.get('/crfToken');
        csrfToken.then(function(response){
            return response;
        });
        return csrfToken;
    }

    service.sessionCheck = function() {
        var csrfToken  = $http.get('/sessionCheck');
        csrfToken.then(function(response){
            if (response == 0) {
                window.location="/";
            }
        });
    }

    service.sessionCheck = function() {
        var csrfToken  = $http.get('/sessionCheck');
        csrfToken.then(function(response){
            if (response == 0) {
                window.location="/";
            }
        });
    }
    service.findIndexByKeyValue = function(arraytosearch, key, valuetosearch) {

        for (var i = 0; i < arraytosearch.length; i++) {

            if (arraytosearch[i][key] == valuetosearch) {
                return i;
            }
        }
        return null;
    }

    service.sortArrayByPropertyAlpha = function (array , propertyName) {
        return array.sort(function(a, b){
            var nameA=a[propertyName].toLowerCase(), nameB=b[propertyName].toLowerCase()
            if (nameA < nameB) //sort string ascending
                return -1
            if (nameA > nameB)
                return 1
            return 0 //default return value (no sorting)
        });
    }

    service.convertEmptyJsonArrayToObject = function(object) {
        if (Array.isArray(object)) {
            if (object.length == 0) {
                return {};
            }
        }
        return {};
    }

    service.formatPhone = function(phone_no) {
        return phone_no.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
    }

    service.shortenText = function(val, length) {
        if (!val){
            return "";
        }
        else if (val.length > 22) {
            return val.substring(val, length)+"...";
        }
        else {
            return val;
        }
    }

    service.shortenTextBackend = function(val) {
        if (!val){
            return "";
        }
        else if (val.length > 22) {
            return "~..."+val.substr(val.length - 22);
        }
        else {
            return val;
        }
    }

    service.positiveNegativeClass = function(number) {
        if (number > 0) {
            return "positive-green";
        }
        else if (number < 0) {
            return "negative-red";
        }
        else {
            return "";
        }
    }

    service.getRatio = function(val1, val2) {
        return Math.round((val1/(val1 + val2))*100);
    }

    service.convertArrayToGrammarList = function (a) {
        return [a.slice(0, -1).join(', '), a.slice(-1)[0]].join(a.length < 2 ? '' : ' and ');
    }

    service.returnOneArrayFieldWithAnotherArrayFieldValue = function (array_to_search, field_to_search, field_to_return, search_field_value) {
        for (var i = 0; i < array_to_search.length; i++) {

            if (array_to_search[i][field_to_search] == search_field_value) {
                return array_to_search[i][field_to_return];
            }
        }
        return null;
    }

    service.commaStringCount = function(string) {
        string = string.toString();
        if (string.length < 1) {
            return 1;
        }
        else {
            return parseInt((string.match(/,/g) || []).length) + 1;
        }
    }

    service.fiftyOpacity = function(value) {
        if (value) {
            return 'fifty-opacity';
        }
        else {
            return '';
        }
    }

    service.beautifyJson = function(json_text, id) {
        var element = document.getElementById(id);
        element.innerHTML = document.createTextNode(JSON.stringify(json_text, null, 4));
    }

    service.numberWithCommas = function(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    return service;
});


