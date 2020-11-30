app.controller("loginController", function ($scope, $http, $location, userService) {
  $scope.submit = function () {
      $http({
        url: 'api/authenticate.php',
        method: 'POST',
        data: {cpf: $scope.cpf, password: $scope.password}
      })
      .then(function(response){
        if(response.data.success){
          userService.setCredentials(response.data.user.id, $scope.cpf, $scope.password);
          $location.path("/dashboard");
        }
        else{
          $scope.showAlert = true;
          $scope.alertMessage = response.data.message;
        }
      });
  }
});