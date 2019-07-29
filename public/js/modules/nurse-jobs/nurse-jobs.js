(function() {
    'use strict';

    angular
        .module('app')
        .controller('NurseJobsController', NurseJobsController);

    function NurseJobsController($http) {
        var vm = this;

        vm.googleMapsLink = googleMapsLink;
        vm.googlePopulationLink = googlePopulationLink;
        vm.googleCrimeLink = googleCrimeLink;
        vm.googleImagesLink = googleImagesLink;
        vm.yelpLink = yelpLink;

        $http.get('nurse_jobs').then(function(response) {
            vm.jobs = response.data;
        });

        function googleMapsLink(job) {
            return 'https://www.google.com/maps/place/'+job.city+',+'+job.state;
        }

        function googlePopulationLink(job) {
            return 'https://www.google.com/search?q='+job.city+'+'+job.state+"+population";
        }

        function googleImagesLink(job) {
            return 'https://www.google.com/search?q='+job.city.replace(" ", "+")+'+'+job.state.replace(" ", "+")+"+&source=lnms&tbm=isch";
        }

        function googleCrimeLink(job) {
            return 'https://www.areavibes.com/'+job.city.replace(" ", "+")+'-'+job.state.replace(" ", "+")+"/crime";
        }

        function yelpLink(job) {
            return 'https://www.yelp.com/search?find_desc=Restaurants&find_loc='+job.city.replace(" ", "%20")+'%2C%20'+job.state.replace(" ", "%20");
        }
    }
})();