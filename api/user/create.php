<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/core.php');
require_once('../models/User.php');

if ($request_method == 'post')
{
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->name) && !empty($data->birthdate) && !empty($data->password) && !empty($data->cpf) && !empty($data->rg) && !empty($data->phone))
    {
        $check_cpf = Database::connection()->prepare("SELECT * FROM users WHERE cpf = :cpf");
        $check_cpf->execute([":cpf" => $data->cpf]);

        $check_rg = Database::connection()->prepare("SELECT * FROM users WHERE rg = :rg");
        $check_rg->execute([":rg" => $data->rg]);

        $response['success'] = false;

        if($check_cpf->rowCount() > 0)
        {
            $response['message'] = "CPF já cadastrado";
        }
        elseif($check_rg->rowCount() > 0)
        {
            $response['message'] = "RG já cadastrado";
        }
        elseif(!is_numeric($data->cpf))
        {
            $response['message'] = "CPF inválido";
        }
        elseif(!is_numeric($data->rg))
        {
            $response['message'] = "RG inválido";
        }
        else
        {
            $user = new User();
            $user->name = $data->name;
            $user->birthdate = $data->birthdate;
            $user->password = password_hash($data->password, PASSWORD_DEFAULT);
            $user->cpf = $data->cpf;
            $user->rg = $data->rg;
            $user->phone = $data->phone;
            $result = $user->create();
    
            if($result)
            {
                $response['success'] = true;
                $response['user'] = $result;
                $response['message'] = "Usuário cadastrado com sucesso";
            }
            else
            {
                $response['message'] = "Não foi possível realizar a operação";
            }
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
    $response['success'] = false;
    $response['message'] = "Tipo de requisição inválida";
}

echo json_encode($response);