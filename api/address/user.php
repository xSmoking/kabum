<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/core.php');
require_once('../models/UserAddresses.php');

if ($request_method == 'get')
{
    $user_addresses = new UserAddresses();

    if (isset($_GET['id']))
    {
        $response = $user_addresses->showFromUser($_GET['id']);
    }
}
else
{
    $response['success'] = false;
    $response['message'] = "Tipo de requisição inválida";
}

echo json_encode($response);