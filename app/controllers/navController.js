app.controller("navController", function ($scope, $location, userService) {
    $scope.navClass = function (page) {
        var currentRoute = $location.path().substring(1) || "login";
        return page === currentRoute ? "active" : "";
    };

    $scope.loadLogin = function () {
        $location.url("/");
    };

    $scope.loadRegister = function () {
        $location.url("/cadastro");
    };

    $scope.loadHome = function () {
        $location.url("/dashboard");
    };

    $scope.loadAddresses = function () {
        $location.url("/dashboard/enderecos");
    };

    $scope.loadLogout = function () {
        userService.clearCredentials();
        $location.url("/");
    };
});
