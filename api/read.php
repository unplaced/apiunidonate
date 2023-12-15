<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../database.php';
    include_once '../pessoa.php';

    $database = new Database();
    $db = $database->getConnection();
    $items = new Pessoa($db);

    if (isset($_GET['cod_pessoa'])) 
    {
        $items->cod_pessoa = $_GET['cod_pessoa'];

        if ($items->getSinglePessoa()) 
        {
            echo json_encode([
                "cod_pessoa" => $items->cod_pessoa,
                "nome" => $items->nome,
                "email" => $items->email,
                "telefone" => $items->telefone
            ]);
        } 
        else 
        {
            http_response_code(404);
            echo json_encode(["message" => "Nenhum registro encontrado para o cod_pessoa fornecido."]);
        }
    } 
    else 
    {
        http_response_code(400);
        echo json_encode(["message" => "cod_pessoa é obrigatório na solicitação"]);
    }
?>


