app.factory('Stock', function($http, $q) {

    var service = {};
    service.companyProfileData = {};

    service.companyProfile = function(stock_id) {
        $http.get('/stocks/company_profile/'+stock_id).success(function(response){
            service.companyProfileData = response;
        });
    }

    return service;
});