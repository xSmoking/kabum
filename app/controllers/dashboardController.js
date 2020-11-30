app.controller(
    "dashboard.homeController",
    function ($scope, $http, $rootScope, $filter) {
        $scope.init = function () {
            $http({
                url: "api/user/show.php?id=" + $rootScope.currentUser.id,
                method: "GET",
            }).then(function (response) {
                if (response.data) {
                    $scope.name = response.data.name;
                    $scope.cpf = response.data.cpf;
                    $scope.rg = response.data.rg;
                    $scope.phone = response.data.phone;
                    $scope.birthdate = $filter("date")(
                        response.data.birthdate,
                        "ddMMyyyy"
                    );
                    $rootScope.addresses = response.data.addresses;
                }
            });
        };

        $scope.submit = function () {
            $http({
                url: "api/user/update.php?id=" + $rootScope.currentUser.id,
                method: "PUT",
                data: {
                    name: $scope.name,
                    birthdate: $scope.birthdate,
                    cpf: $scope.cpf,
                    rg: $scope.rg,
                    phone: $scope.phone,
                },
            }).then(function (response) {
                $scope.showAlert = true;
                $scope.alertMessage = response.data.message;

                if (response.data.success) {
                    $scope.name = response.data.user.name;
                    $scope.cpf = response.data.user.cpf;
                    $scope.rg = response.data.user.rg;
                    $scope.phone = response.data.user.phone;
                    $scope.birthdate = $filter("date")(
                        response.data.user.birthdate,
                        "ddMMyyyy"
                    );
                    $scope.alertType = "alert-success";
                } else {
                    $scope.alertType = "alert-danger";
                }
            });
        };
    }
);

app.controller(
    "dashboard.addressesController",
    function ($scope, $http, $rootScope) {
        $scope.addAddress = {};
        $scope.selected = undefined;
        $scope.selectedIndex = undefined;

        $scope.init = function(){
            if($rootScope.addresses == undefined){
                $http({
                    url: "api/address/user.php?id=" + $rootScope.currentUser.id,
                    method: "GET",
                }).then(function (response) {
                    if (response.data) {
                        $rootScope.addresses = response.data;
                    }
                });
            }
        }

        $scope.lookupZip = function(){
            $http.get("https://viacep.com.br/ws/"+ $scope.addAddress.zip + "/json").then(function(response){
                $scope.addAddress.address = response.data.logradouro;
                $scope.addAddress.district = response.data.bairro;
                $scope.addAddress.city = response.data.localidade;
                $scope.addAddress.state = response.data.uf;
            });
        }

        $scope.select = function(address){
            $scope.selectedIndex = $rootScope.addresses.indexOf(address);
            $scope.selected = address;
            $scope.editAddress = address;
        }

        $scope.clearAdd = function(){
            $scope.addAddress = {};
        }

        $scope.add = function(){
            $http({
                url: "api/address/create.php",
                method: "POST",
                data: {
                    user_id: $rootScope.currentUser.id,
                    zip: $scope.addAddress.zip,
                    address: $scope.addAddress.address,
                    number: $scope.addAddress.number,
                    complement: $scope.addAddress.complement,
                    district: $scope.addAddress.district,
                    city: $scope.addAddress.city,
                    state: $scope.addAddress.state,
                },
            }).then(function (response) {
                $scope.addAddress.showAlert = true;
                $scope.addAddress.alertText = response.data.message;
                
                if(response.data.success){
                    $scope.addAddress.alertType = "alert-success";
                    $rootScope.addresses.push(response.data.address);
                }
                else{
                    $scope.addAddress.alertType = "alert-danger";
                }
            });
        }

        $scope.edit = function(){
            $http({
                url: "api/address/update.php?id=" + $scope.selected.id,
                method: "PUT",
                data: {
                    complement: $scope.editAddress.complement,
                },
            }).then(function (response) {
                $scope.editAddress.showAlert = true;
                $scope.editAddress.alertText = response.data.message;

                if(response.data.success){
                    $scope.editAddress.alertType = "alert-success";
                }
                else{
                    $scope.editAddress.alertType = "alert-danger";
                }
            });
        }

        $scope.delete = function(){
            $http({
                url: "api/address/delete.php?id=" + $scope.selected.id,
                method: "DELETE"
            }).then(function (response) {
                $scope.deleteAddress.showAlert = true;
                $scope.deleteAddress.alertText = response.data.message;

                if(response.data.success){
                    $scope.deleteAddress.alertType = "alert-success";
                    $rootScope.addresses.splice($scope.selectedIndex, 1);
                    $("#deleteModal").modal('hide');
                }
                else{
                    $scope.deleteAddress.alertType = "alert-danger";
                }
            });
        }
    }
);
