<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: DELETE"); 
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../database.php';
    include_once '../entidade.php'; 

    $database = new Database();
    $db = $database->getConnection();
    $entidade = new Entidade($db);

    
    if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
        http_response_code(405); 
        echo json_encode(array("message" => "Método não permitido. Use DELETE para excluir uma entidade."));
        exit;
    }

    $data = json_decode(file_get_contents("php://input"));

    if (empty($data->cod_entidade)) {
        http_response_code(400);
        echo json_encode(array("message" => "Cod_entidade e obrigatorio."));
        exit;
    }

    $entidade->cod_entidade = $data->cod_entidade;

    if ($entidade->deleteEntidade()) { 
        http_response_code(200);
        echo json_encode(array("message" => "Entidade deletada."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Sua tentativa de exclusão foi negada!"));
    }
?>