<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database.php';
    include_once '../pessoa.php';

    $database = new Database();
    $db = $database->getConnection();
    $item = new Pessoa($db);

    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->cod_pessoa)) 
    {
        http_response_code(400);
        echo json_encode(array("message" => "Cod_pessoa e obrigatorio"));
        exit;
    }

    $item->cod_pessoa = $data->cod_pessoa;

    if ($item->deletePessoa()) 
    {
        http_response_code(200);
        echo json_encode(array("message" => "Pessoa deletada."));
    } 
    else 
    {
        http_response_code(503);
        echo json_encode(array("message" => "Sua tentativa de deletar foi negada!"));
    }
?>

