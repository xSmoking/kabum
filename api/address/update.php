<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/core.php');
require_once('../models/UserAddresses.php');

if ($request_method == 'put')
{
    $data = json_decode(file_get_contents("php://input"));

    if(isset($_GET['id']))
    {
        $user_addresses = new UserAddresses();
        $user_addresses = $user_addresses->find($_GET['id']);
        $user_addresses->complement = $data->complement;
        $result = $user_addresses->save();

        if($result)
        {
            $response['success'] = true;
            $response['address'] = $result;
            $response['message'] = "Dados alterados com sucesso";
        }
        else
        {
            $response['message'] = "Não foi possível realizar a operação";
        }
    }
}
else
{
    header("HTTP/1.1 405 Not Found");
    $response['success'] = false;
    $response['message'] = "Tipo de requisição inválida";
}

echo json_encode($response);