app.controller(
    "registerController",
    function ($scope, $http, $location, userService) {
        $scope.submit = function () {
            $http({
                url: "api/user/create.php",
                method: "POST",
                data: {
                    name: $scope.name,
                    birthdate: $scope.birthdate,
                    password: $scope.password,
                    cpf: $scope.cpf,
                    rg: $scope.rg,
                    phone: $scope.phone,
                },
            }).then(function (response) {
                if (response.data.success) {
                    userService.setCredentials(response.data.user.id, $scope.cpf, $scope.password);
                    $location.path("/dashboard");
                } else {
                    $scope.showAlert = true;
                    $scope.alertMessage = response.data.message;
                }
            });
        };
    }
);
