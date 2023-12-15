<?php
    class Doacao
    {
        private $db;
        public $db_table = "doacao";
    
        public $cod_pessoa;
        public $cod_entidade;
        public $valor;
    
        public function __construct($db)
        {
            $this->db = $db;
        }
    
        public function criarDoacao()
        {
            $input = json_decode(file_get_contents('php://input'));
    
            if (!empty($input->cod_pessoa) && !empty($input->cod_entidade) && !empty($input->valor)) {
                $cod_pessoa = $input->cod_pessoa;
                $cod_entidade = $input->cod_entidade;
                $valor = $input->valor;
    
                $sqlQuery = "INSERT INTO " . $this->db_table . " (cod_pessoa, cod_entidade, valor) VALUES (?, ?, ?)";
                $stmt = $this->db->prepare($sqlQuery);
    
                if ($stmt) {
                    $stmt->bind_param('iis', $cod_pessoa, $cod_entidade, $valor);
    
                    if ($stmt->execute()) {
                        $this->responderSucesso("Doação realizada com sucesso!");
                    } else {
                        $this->responderErro("Erro ao inserir doação no banco de dados: " . $stmt->error);
                    }
    
                    $stmt->close();
                } else {
                    $this->responderErro("Erro na preparação da consulta: " . $this->db->error);
                }
            } else {
                $this->responderErro("Parâmetros ausentes.");
            }
        }
    
        private function responderSucesso($mensagem)
        {
            $response = array('status' => 'success', 'message' => $mensagem);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    
        private function responderErro($mensagem)
        {
            $response = array('status' => 'error', 'message' => $mensagem);
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }
?>