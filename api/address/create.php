<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/core.php');
require_once('../models/UserAddresses.php');

if ($request_method == 'post')
{
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->user_id) && !empty($data->zip) && !empty($data->address) && !empty($data->number) && !empty($data->district) && !empty($data->city) && !empty($data->state))
    {
        $user_addresses = new UserAddresses();
        $user_addresses->zip = $data->zip;
        $user_addresses->user_id = $data->user_id;
        $user_addresses->address = $data->address;
        $user_addresses->number = $data->number;
        if(isset($data->complement))
            $user_addresses->complement = $data->complement;
        $user_addresses->district = $data->district;
        $user_addresses->city = $data->city;
        $user_addresses->state = $data->state;
        $result = $user_addresses->create();

        if($result)
        {
            $response['success'] = true;
            $response['address'] = $result;
            $response['message'] = "Endereço cadastrado com sucesso";
        }
        else
        {
            $response['message'] = "Não foi possível realizar a operação";
        }
    }
    else
    {
        $response['success'] = false;
        $response['message'] = "Preencha todos os campos";
    }
}
else
{
    header("HTTP/1.1 405 Not Found");
    $response['success'] = false;
    $response['message'] = "Tipo de requisição inválida";
}

echo json_encode($response);