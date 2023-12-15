<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database.php';
    include_once '../pessoa.php';

    $database = new Database();
    $db = $database->getConnection();
    $item = new Pessoa($db);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"));
        if (
            !empty($data->email) &&
            !empty($data->senha)
        ) {
            $item->email = $data->email;
            $item->senha = $data->senha;

            if ($codPessoa = $item->fazerLogin()) {
                echo json_encode(["cod_pessoa" => $codPessoa]);
            } else {
                echo json_encode(["message" => "Erro ao fazer login. Credenciais inválidas."]);
            }
        } else {
            echo json_encode(["message" => "Campos incompletos"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Método não permitido"]);
    }
?>