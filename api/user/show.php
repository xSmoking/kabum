<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/core.php');
require_once('../models/User.php');
require_once('../models/UserAddresses.php');

if ($request_method == 'get')
{
    $user = new User();
    $user_addresses = new UserAddresses();

    if (isset($_GET['id']))
    {
        $id = htmlspecialchars($_GET['id']);
        $user_data = $user->find($id);
        $user_data->addresses = $user_addresses->showFromUser($id);
        $user_data->password = null;
        $response = $user_data;
    }
    else
    {
        $users_data = $user->show();
        $array = array();
        foreach($users_data as $user_data)
        {
            $user_data['addresses'] = $user_addresses->showFromUser($user_data['id']);
            $user_data['password'] = null;
            array_push($array, $user_data);
        }
        $response = $array;
    }
}
else
{
    $response['success'] = false;
    $response['message'] = "Tipo de requisição inválida";
}

echo json_encode($response);