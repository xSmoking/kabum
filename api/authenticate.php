<?php

header("Access-Control-Allow-Origin: http://127.0.0.1");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once('config/core.php');
require_once('models/User.php');

if ($request_method == 'post')
{
    $data = json_decode(file_get_contents("php://input"));

    if(!empty($data->cpf) && !empty($data->password))
    {
        $cpf = htmlspecialchars($data->cpf);

        $stmt = Database::connection()->prepare("SELECT * FROM users WHERE cpf=:cpf");
        $stmt->bindParam(":cpf", $cpf);
        $stmt->execute();
        
        if($stmt->rowCount() > 0)
        {
            $userData = $stmt->fetchObject();
            if(password_verify($data->password, $userData->password))
            {
                $user = new User($userData);
                $user->password = null;

                $response['success'] = true;
                $response['user'] = $user;
            }
            else
            {
                //header("HTTP/1.1 403 Forbidden");
                $response['success'] = false;
                $response['message'] = "Usuário ou senha incorreto";
            }
        }
        else
        {
            //header("HTTP/1.1 403 Forbidden");
            $response['success'] = false;
            $response['message'] = "Usuário ou senha incorreto";
        }
    }
    else
    {
        //header("HTTP/1.1 400 Bad Request");
        $response['success'] = false;
        $response['message'] = "Preencha todos os campos";
    }
}
else
{
    //header("HTTP/1.1 405 Method Not Allowed");
    $response['success'] = false;
    $response['message'] = "Tipo de requisição inválida";
}

echo json_encode($response);