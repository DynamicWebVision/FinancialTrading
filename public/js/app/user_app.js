/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var app = angular.module('app', ['utility.directives', 'ngRoute', 'toggle-switch']);

//This Handles the Angular Side Bar Routing
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : "/template/user/manage_users.html",
            controller : "ManageUsersController"
        })
        .when("/create_user", {
            templateUrl : "/template/user/create_user.html",
            controller : "CreateUserController"
        })
        .when("/manage_users", {
            templateUrl : "/template/user/manage_users.html",
            controller : "ManageUsersController"
        })
});