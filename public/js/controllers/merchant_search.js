/**
 * Created by boneill on 12/15/16.
 */
app.controller('MerchantSearchController', function($http, $scope, SearchResults, $location) {

    $scope.search_text = "";
    $scope.processing = false;
    SearchResults.search_status = 'no search';

    $scope.merchantSearch = function() {
        $scope.processing = true;
        var search_text = $scope.search_text;
        $http.post('/merchant_search', {search_text: search_text}).success(function(response){
            $scope.result_merchants = response;
            SearchResults.search_results = response;
            $location.path('search_results');
            $scope.processing = false;
        });
    }
});