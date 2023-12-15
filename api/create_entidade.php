<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database.php'; 
    include_once '../entidade.php'; 

    $database = new Database();
    $db = $database->getConnection();

    $entidade = new Entidade($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"));

        if (
            !empty($data->nome) &&
            !empty($data->email) &&
            !empty($data->telefone) &&
            !empty($data->senha) &&
            !empty($data->descricao) &&
            !empty($data->logradouro) &&
            isset($data->numero) &&
            !empty($data->municipio) &&
            !empty($data->uf) &&
            isset($data->distribuido)
        ) {
            $entidade->nome = $data->nome;
            $entidade->email = $data->email;
            $entidade->telefone = $data->telefone;
            $entidade->senha = $data->senha;
            $entidade->descricao = $data->descricao;
            $entidade->logradouro = $data->logradouro;
            $entidade->numero = $data->numero;
            $entidade->municipio = $data->municipio;
            $entidade->uf = $data->uf;
            $entidade->distribuido = $data->distribuido;

            if ($entidade->criarEntidade()) {
                http_response_code(201);
                echo json_encode(["message" => "Entidade cadastrada com sucesso"]);
            } else {
                http_response_code(503); 
                echo json_encode(["message" => "Erro ao cadastrar a entidade. Campos duplicados ou faltantes."]);
            }
        } else {
            http_response_code(400); 
            echo json_encode(["message" => "Campos incompletos ou inválidos"]);
        }
    } else {
        http_response_code(405); 
        echo json_encode(["message" => "Método não permitido"]);
    }
?>