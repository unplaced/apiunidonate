<?php
class Entidade
{

    private $db;

    public $db_table = "entidade";

    public $cod_entidade;
    public $nome;
    public $email;
    public $telefone;
    public $senha;
    public $descricao;
    public $logradouro;
    public $numero;
    public $municipio;
    public $uf;
    public $distribuido;

    public $result;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // GET ALL
    public function getEntidades()
    {
        $sqlQuery = "SELECT cod_entidade, nome, email, telefone, senha, descricao, logradouro, numero, municipio, uf, distribuido FROM " . $this->db_table . "";

        $this->result = $this->db->query($sqlQuery);

        return $this->result;
    }

    // CREATE
    public function criarEntidade()
    {
        $checkNomeQuery = "SELECT COUNT(*) as count FROM " . $this->db_table . " WHERE nome = '" . $this->nome . "'";
        $checkNomeResult = $this->db->query($checkNomeQuery);

        $checkEmailQuery = "SELECT COUNT(*) as count FROM " . $this->db_table . " WHERE email = '" . $this->email . "'";
        $checkEmailResult = $this->db->query($checkEmailQuery);

        $checkTelefoneQuery = "SELECT COUNT(*) as count FROM " . $this->db_table . " WHERE telefone = '" . $this->telefone . "'";
        $checkTelefoneResult = $this->db->query($checkTelefoneQuery);

        if ($checkNomeResult && $checkEmailResult && $checkTelefoneResult) 
        {
            $nomeCount = $checkNomeResult->fetch_assoc()['count'];
            $emailCount = $checkEmailResult->fetch_assoc()['count'];
            $telefoneCount = $checkTelefoneResult->fetch_assoc()['count'];

            if ($nomeCount > 0 || $emailCount > 0 || $telefoneCount > 0) 
            {
                return false;
            }
        } 
        else 
        {
            return false;
        }

        $this->senha = htmlspecialchars(strip_tags($this->senha));
        $hashedSenha = password_hash($this->senha, PASSWORD_DEFAULT);
        
        $sqlQuery = "INSERT INTO " . $this->db_table . " SET nome = '" . $this->nome . "', email = '" . $this->email . "', telefone = '" . $this->telefone . "', senha = '" . $hashedSenha . "', descricao = '" . $this->descricao . "', logradouro = '" . $this->logradouro . "', numero = " . $this->numero . ", municipio = '" . $this->municipio . "', uf = '" . $this->uf . "', distribuido = '" . $this->distribuido . "'";
        
        $this->db->query($sqlQuery);
        
        if ($this->db->affected_rows > 0) 
        {
            return true;
        }
        
        return false;
    }
    
    // READ (getSingleEntidade)
    public function getSingleEntidade() 
    {

        $sqlQuery = "SELECT cod_entidade, nome, email, telefone, senha, descricao, logradouro, numero, municipio, uf, distribuido FROM " . $this->db_table . " WHERE cod_entidade = ?";
    
        $stmt = $this->db->prepare($sqlQuery);

        if ($stmt) 
        {
            $stmt->bind_param("i", $this->cod_entidade);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) 
            {
                $dataRow = $result->fetch_assoc();
                $this->cod_entidade = $dataRow['cod_entidade'];
                $this->nome = $dataRow['nome'];
                $this->email = $dataRow['email'];
                $this->telefone = $dataRow['telefone'];
                $this->senha = $dataRow['senha'];
                $this->descricao = $dataRow['descricao'];
                $this->logradouro = $dataRow['logradouro'];
                $this->numero = $dataRow['numero'];
                $this->municipio = $dataRow['municipio'];
                $this->uf = $dataRow['uf'];
                $this->distribuido = $dataRow['distribuido'];
                return true;
            }

            $stmt->close();
        }

        return false;
    }

    public function toArray() 
    {
        $entidadeData = array
        (
            "cod_entidade" => $this->cod_entidade,
            "nome" => $this->nome,
            "email" => $this->email,
            "telefone" => $this->telefone,
            "senha" => $this->senha,
            "descricao" => $this->descricao,
            "logradouro" => $this->logradouro,
            "numero" => $this->numero,
            "municipio" => $this->municipio,
            "uf" => $this->uf,
            "distribuido" => $this->distribuido
        );

        return $entidadeData;
    }

    // UPDATE (updateEntidade)
    public function updateEntidade($novaSenha = null)
    {
        $this->cod_entidade = htmlspecialchars(strip_tags($this->cod_entidade));
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));

        if (!empty($novaSenha)) 
        {
            $novaSenha = htmlspecialchars(strip_tags($novaSenha));
            $hashedNovaSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
            $sqlQuery = "UPDATE " . $this->db_table . " SET nome = '" . $this->nome . "', email = '" . $this->email . "', telefone = '" . $this->telefone . "', senha = '" . $hashedNovaSenha . "', descricao = '" . $this->descricao . "', logradouro = '" . $this->logradouro . "', numero = " . $this->numero . ", municipio = '" . $this->municipio . "', uf = '" . $this->uf . "', distribuido = '" . $this->distribuido . "' WHERE cod_entidade = " . $this->cod_entidade;
        } 
        else 
        {
            $sqlQuery = "UPDATE " . $this->db_table . " SET nome = '" . $this->nome . "', email = '" . $this->email . "', telefone = '" . $this->telefone . "', descricao = '" . $this->descricao . "', logradouro = '" . $this->logradouro . "', numero = " . $this->numero . ", municipio = '" . $this->municipio . "', uf = '" . $this->uf . "', distribuido = '" . $this->distribuido . "' WHERE cod_entidade = " . $this->cod_entidade;
        }

        $this->db->query($sqlQuery);

        if ($this->db->affected_rows > 0) 
        {
            return true;
        }

        return false;
    }

    // DELETE
    function deleteEntidade()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE cod_entidade = ".$this->cod_entidade;
        $this->db->query($sqlQuery);

        if($this->db->affected_rows > 0)
        {
            return true;
        }

        return false;
    }
}
?>