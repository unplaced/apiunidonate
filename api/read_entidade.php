<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../database.php';
    include_once '../entidade.php'; 

    $database = new Database();
    $db = $database->getConnection();
    $entidade = new Entidade($db); 

    
    if (isset($_GET['cod_entidade'])) {
        $entidade->cod_entidade = $_GET['cod_entidade'];

        if ($entidade->getSingleEntidade()) {
            echo json_encode([
                "cod_entidade" => $entidade->cod_entidade,
                "nome" => $entidade->nome,
                "email" => $entidade->email,
                "telefone" => $entidade->telefone,
                "descricao" => $entidade->descricao,
                "logradouro" => $entidade->logradouro,
                "numero" => $entidade->numero,
                "municipio" => $entidade->municipio,
                "uf" => $entidade->uf,
                "distribuido" => $entidade->distribuido
            ]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Nenhum registro encontrado para o cod_entidade fornecido."]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "cod_entidade é obrigatório na solicitação GET."]);
    }
?>