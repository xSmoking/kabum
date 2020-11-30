var app = angular.module("app", ["ngRoute", "ngCookies", "ngCpfCnpj", "ui.mask"]);

app.config(function($routeProvider){
    $routeProvider
            .when("/", {
                resolve: {
                    check: function ($location, $rootScope) {
                        if ($rootScope.loggedIn) {
                            $location.path("/dashboard");
                        }
                    },
                },
                templateUrl: "views/login.html",
            })
            .when("/cadastro", {
                resolve: {
                    check: function ($location, $rootScope) {
                        if ($rootScope.loggedIn) {
                            $location.path("/dashboard");
                        }
                    },
                },
                templateUrl: "views/register.html",
            })
            .when("/dashboard", {
                resolve: {
                    check: function ($location, $rootScope) {
                        if (!$rootScope.loggedIn) {
                            $location.path("/");
                        }
                    },
                },
                templateUrl: "views/dashboard/home.html",
            })
            .when("/dashboard/enderecos", {
                resolve: {
                    check: function ($location, $rootScope) {
                        if (!$rootScope.loggedIn) {
                            $location.path("/");
                        }
                    },
                },
                templateUrl: "views/dashboard/addresses.html",
            })
            .when("/admin/", {
                templateUrl: "views/admin/home.html",
            })
            .when("/admin/user/:userId/addresses", {
                templateUrl: "views/admin/addresses.html",
            })
            .otherwise({ redirectTo: "/" });
});

app.controller('appController', function($scope, $rootScope, $cookies){
    $rootScope.loggedIn = false;
    $rootScope.currentUser = {};

    $scope.init = function(){
        if($cookies.getObject("currentUser")){
            $rootScope.currentUser = $cookies.getObject("currentUser");
            $rootScope.loggedIn = true;
        }
    }
});