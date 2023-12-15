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
            !empty($data->nome) &&
            !empty($data->email) &&
            !empty($data->telefone) &&
            !empty($data->senha)
        ) {
            $item->nome = $data->nome;
            $item->email = $data->email;
            $item->telefone = $data->telefone;
            $item->senha = $data->senha;

            if ($item->criarPessoa()) {
                echo json_encode(["message" => "Usuário cadastrado com sucesso"]);
            } else {
                echo json_encode(["message" => "Erro ao cadastrar usuário. Campos duplicados."]);
            }
        } else {
            echo json_encode(["message" => "Campos incompletos"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Método não permitido"]);
    }
?>

