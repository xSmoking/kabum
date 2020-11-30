app.controller(
    "admin.homeController",
    function ($scope, $http, $rootScope, $filter, $location) {
        $scope.addUser = {};
        $scope.selectedUser = undefined;
        $scope.selectedRowIndex = undefined;
        $scope.users = [];

        $scope.init = function () {
            $http.get("api/user/show.php").then(function (response) {
                $scope.users = response.data;
                $scope.users.forEach(element => {
                    element.birthdate = $filter("date")(
                        element.birthdate,
                        "dd/MM/yyyy"
                    );
                });
            });
        };

        $scope.showAddresses = function(){
            $location.path('admin/user/' + $scope.selectedUser.id + '/addresses');
        }

        $scope.add = function () {
            $http({
                url: "api/user/create.php",
                method: "POST",
                data: {
                    name: $scope.addUser.name,
                    birthdate: $scope.addUser.birthdate,
                    cpf: $scope.addUser.cpf,
                    rg: $scope.addUser.rg,
                    phone: $scope.addUser.phone,
                    password: $scope.addUser.password
                },
            }).then(function (response) {
                $scope.addUser.showAlert = true;
                $scope.addUser.alertText = response.data.message;
                response.data.user.birthdate = $filter("date")(
                    response.data.user.birthdate,
                    "dd/MM/yyyy"
                );

                if(response.data.success){
                    $scope.addUser.alertType = "alert-success";
                    $scope.users.push(response.data.user);
                }
                else{
                    $scope.addUser.alertType = "alert-danger";
                }
            });
        };

        $scope.edit = function (){
            $http({
                url: "api/user/update.php?id=" + $scope.editUser.id,
                method: "PUT",
                data: {
                    name: $scope.editUser.name,
                    birthdate: $scope.editUser.birthdate,
                    cpf: $scope.editUser.cpf,
                    rg: $scope.editUser.rg,
                    phone: $scope.editUser.phone,
                },
            }).then(function (response) {
                $scope.editUser.showAlert = true;
                $scope.editUser.alertText = response.data.message;

                if(response.data.success){
                    $scope.editUser.alertType = "alert-success";
                }
                else{
                    $scope.editUser.alertType = "alert-danger";
                }
            });
        }

        $scope.select = function(user){
            $scope.selectedRowIndex = $scope.users.indexOf(user);
            $scope.selectedUser = user;
            $scope.editUser = user;
        }

        $scope.delete = function(){
            $http({
                url: "api/user/delete.php?id=" + $scope.selectedUser.id,
                method: "DELETE"
            }).then(function (response) {
                $scope.deleteUser.showAlert = true;
                $scope.deleteUser.alertText = response.data.message;

                if(response.data.success){
                    $scope.deleteUser.alertType = "alert-success";
                    $scope.users.splice($scope.selectedRowIndex, 1);
                    $("#deleteModal").modal('hide');
                }
                else{
                    $scope.deleteUser.alertType = "alert-danger";
                }
            });
        }
    }
);

app.controller(
    "admin.addressesController",
    function ($scope, $http, $routeParams) {
        $scope.addAddress = {};
        $scope.selected = undefined;
        $scope.selectedIndex = undefined;

        $scope.init = function () {
            $http.get("api/user/show.php?id=" + $routeParams.userId).then(function (response) {
                $scope.name = response.data.name;
                $scope.addresses = response.data.addresses;
            });
        };

        $scope.lookupZip = function(){
            $http.get("https://viacep.com.br/ws/"+ $scope.addAddress.zip + "/json").then(function(response){
                $scope.addAddress.address = response.data.logradouro;
                $scope.addAddress.district = response.data.bairro;
                $scope.addAddress.city = response.data.localidade;
                $scope.addAddress.state = response.data.uf;
            });
        }

        $scope.select = function(address){
            $scope.selectedIndex = $scope.addresses.indexOf(address);
            $scope.selected = address;
            $scope.editAddress = address;
        }

        $scope.add = function () {
            $http({
                url: "api/address/create.php",
                method: "POST",
                data: {
                    user_id: $routeParams.userId,
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
                    $scope.addresses.push(response.data.address);
                }
                else{
                    $scope.addAddress.alertType = "alert-danger";
                }
            });
        };

        $scope.edit = function (){
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
                    $scope.addresses.splice($scope.selectedIndex, 1);
                    $("#deleteModal").modal('hide');
                }
                else{
                    $scope.deleteAddress.alertType = "alert-danger";
                }
            });
        }
    }
);
