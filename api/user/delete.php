<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('../config/core.php');
require_once('../models/User.php');

if ($request_method == 'delete')
{
    if(isset($_GET['id']))
    {
        $user_id = $_GET['id'];
        $user = new User();
        
        if($user->delete($user_id))
        {
            $response['success'] = true;
            $response['message'] = "Usuário removido com sucesso";
        }
        else
        {
            $response['success'] = false;
            $response['message'] = "Erro ao remover usuário";
        }
    }
    else
    {
        $response['success'] = false;
        $response['message'] = "Parâmetro ID não encontrado";
    }
}
else
{
    $response['success'] = false;
    $response['message'] = "Tipo de requisição inválida";
}

echo json_encode($response);