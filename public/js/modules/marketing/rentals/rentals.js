(function() {
    'use strict';

    angular
        .module('app')
        .controller('HarRentalsController', HarRentalsController);

    function HarRentalsController($http) {
        var vm = this;

        vm.rentalText = '';

        vm.rentals = [];

        vm.loadRentals = function() {
            $http.post('rentals/load', {rentals: vm.rentalText}).success(function(data){
                alert('Loaded');
            });
        }

        vm.getRentals = function() {
            $http.get('rentals/get').success(function(data){
                vm.rentals = data;
                console.log(vm.rentals);
            });
        }

        vm.openEmail = function() {
            $http.get('rentals/send_email').success(function(data){
                console.log(data);
                window.open('https://mail.google.com/mail/?view=cm&fs=1&to='+data.email+'&body='+data.body+'&su='+data.subject);
            });
            //Line Break  %0A

        }

        vm.getRentals();
    }
})();