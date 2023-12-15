<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: PUT");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database.php';
    include_once '../entidade.php';

    $database = new Database();
    $db = $database->getConnection();
    $entidade = new Entidade($db);

    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->cod_entidade)) 
    {
        http_response_code(400);
        echo json_encode(array("message" => "Cod_entidade is required."));
        exit;
    }

    $entidade->cod_entidade = $data->cod_entidade;
    $entidade->nome = $data->nome;
    $entidade->email = $data->email;
    $entidade->telefone = $data->telefone;
    $entidade->senha = $data->senha;
    $entidade->descricao = $data->descricao;
    $entidade->logradouro = $data->logradouro;
    $entidade->numero = $data->numero;
    $entidade->municipio = $data->municipio;
    $entidade->uf = $data->uf;

    if (!empty($data->novaSenha)) 
    {
        $novaSenha = $data->novaSenha;
        if ($entidade->updateEntidade($novaSenha)) 
        {
            http_response_code(200);
            echo json_encode(array("message" => "Dados da Entidade atualizados"));
        } 
        else 
        {
            http_response_code(503);
            echo json_encode(array("message" => "Entidade nao foi atualizada. ERRO!"));
        }
    } 
    else 
    {
        if ($entidade->updateEntidade()) 
        {
            http_response_code(200);
            echo json_encode(array("message" => "Entidade foi atualizada"));
        } 
        else 
        {
            http_response_code(503);
            echo json_encode(array("message" => "Entidade nao foi atualizada. ERRO!"));
        }
    }
?>