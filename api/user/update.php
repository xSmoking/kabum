<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/core.php');
require_once('../models/User.php');

if ($request_method == 'put')
{
    $data = json_decode(file_get_contents("php://input"));
    
    if(!empty($data->name) && !empty($data->birthdate) && !empty($data->cpf) && !empty($data->rg) && !empty($data->phone) && isset($_GET['id']))
    {
        $user_id = $_GET['id'];
        $user = new User();
        $user = $user->find($user_id);

        $check_cpf = Database::connection()->prepare("SELECT * FROM users WHERE cpf = :cpf");
        $check_cpf->execute([":cpf" => $data->cpf]);

        $check_rg = Database::connection()->prepare("SELECT * FROM users WHERE rg = :rg");
        $check_rg->execute([":rg" => $data->rg]);

        $response['success'] = false;

        if($check_cpf->rowCount() > 0 && $data->cpf != $user->cpf)
        {
            $response['message'] = "CPF já cadastrado";
        }
        elseif($check_rg->rowCount() > 0 && $data->rg != $user->rg)
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
        elseif(!empty($data->password) && !password_verify($data->password, $user->password))
        {
            $response['message'] = "Senha antiga incorreta";
        }
        else
        {
            $user->name = $data->name;
            $user->birthdate = $data->birthdate;
            $user->cpf = $data->cpf;
            $user->rg = $data->rg;
            $user->phone = $data->phone;
            if(!empty($data->password))
                $user->password = password_hash($data->password, PASSWORD_DEFAULT);
            $result = $user->save();
    
            if($result)
            {
                $response['success'] = true;
                $response['message'] = "Dados alterados com sucesso";
                $response['user'] = $result;
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