app.factory("userService", function ($rootScope, $cookies, $http) {
    var service = {};

    service.setCredentials = function (id, cpf, password) {
        $rootScope.loggedIn = true;
        $rootScope.currentUser = {
            id: id,
            cpf: cpf
        };

        var cookieExp = new Date();
        cookieExp.setDate(cookieExp.getDate() + 7);
        $cookies.putObject("currentUser", $rootScope.currentUser, {
            expires: cookieExp,
        });
    };

    service.clearCredentials = function () {
        $rootScope.loggedIn = false;
        $rootScope.currentUser = {};
        $cookies.remove("currentUser");
    };

    return service;
});