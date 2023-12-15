<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database.php'; 
    include_once '../doacao.php'; 

    $database = new Database();
    $db = $database->getConnection();
    $doacao = new Doacao($db);

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        $data = json_decode(file_get_contents("php://input"));

        
        if (
            !empty($data->cod_pessoa) &&
            !empty($data->cod_entidade) &&
            !empty($data->valor)
        ) {
            $doacao->cod_pessoa = $data->cod_pessoa;
            $doacao->cod_entidade = $data->cod_entidade;
            $doacao->valor = $data->valor;

            $doacao->criarDoacao();
        } else {
            echo json_encode(["status" => "error", "message" => "Campos incompletos"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Método não permitido"]);
    }
?>