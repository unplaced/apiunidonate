<?php
    class Pessoa
    {
        private $db;

        public $db_table = "pessoa";
        
        public $cod_pessoa;
        public $nome;
        public $email;
        public $telefone;
        public $senha;

        public $result;

        public function __construct($db)
        {
            $this->db = $db;
        }

        // GET ALL
        public function getPessoa()
        {
            $sqlQuery = "SELECT cod_pessoa, nome, email, telefone FROM " . $this->db_table . "";

            $this->result = $this->db->query($sqlQuery);

            return $this->result;
        }

        // LOGIN
        public function fazerLogin()
        {
            $loginQuery = "SELECT * FROM " . $this->db_table . " WHERE email = '" . $this->email . "'";
            $loginResult = $this->db->query($loginQuery);

            if ($loginResult) {
                if ($loginResult->num_rows > 0) {
                    $usuario = $loginResult->fetch_assoc();
                    $senhaCorreta = password_verify($this->senha, $usuario['senha']);

                    if ($senhaCorreta) {
                        return $usuario['cod_pessoa']; 
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        // CREATE
        public function criarPessoa()
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
            
            $sqlQuery = "INSERT INTO " . $this->db_table . " SET nome = '" . $this->nome . "', email = '" . $this->email . "', telefone = '" . $this->telefone . "', senha = '" . $hashedSenha . "'";
            
            $this->db->query($sqlQuery);
            
            if ($this->db->affected_rows > 0) 
            {
                return true;
            }
            
            return false;
        }
        
        // READ (getSinglePessoa)
        public function getSinglePessoa() 
        {

            $sqlQuery = "SELECT cod_pessoa, nome, email, telefone FROM " . $this->db_table . " WHERE cod_pessoa = ?";
    
            $stmt = $this->db->prepare($sqlQuery);

                if ($stmt) 
                {
                    $stmt->bind_param("i", $this->cod_pessoa); 
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 1) 
                    {

                        $dataRow = $result->fetch_assoc();
                        $this->cod_pessoa = $dataRow['cod_pessoa'];
                        $this->nome = $dataRow['nome'];
                        $this->email = $dataRow['email'];
                        $this->telefone = $dataRow['telefone'];
                        return true;
                        
                    }

                    $stmt->close();
                }

                return false;
        }

        public function toArray() 
        {
            $personData = array
            (
                "cod_pessoa" => $this->cod_pessoa,
                "nome" => $this->nome,
                "email" => $this->email,
                "telefone" => $this->telefone
            );
    
            return $personData;
        }

        // UPDATE (updatePessoa)
        public function updatePessoa($novaSenha = null)
        {
            $this->nome = htmlspecialchars(strip_tags($this->nome));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->telefone = htmlspecialchars(strip_tags($this->telefone));
            $this->cod_pessoa = htmlspecialchars(strip_tags($this->cod_pessoa));

            if (!empty($novaSenha)) 
            {
                $novaSenha = htmlspecialchars(strip_tags($novaSenha));
                $hashedNovaSenha = password_hash($novaSenha, PASSWORD_DEFAULT);
                $sqlQuery = "UPDATE " . $this->db_table . " SET nome = '" . $this->nome . "',
                    email = '" . $this->email . "',
                    telefone = '" . $this->telefone . "',
                    senha = '" . $hashedNovaSenha . "'
                    WHERE cod_pessoa = " . $this->cod_pessoa;
            } 
            else 
            {
                $sqlQuery = "UPDATE " . $this->db_table . " SET nome = '" . $this->nome . "',
                    email = '" . $this->email . "',
                    telefone = '" . $this->telefone . "'
                    WHERE cod_pessoa = " . $this->cod_pessoa;
            }

            $this->db->query($sqlQuery);

            if ($this->db->affected_rows > 0) 
            {
                return true;
            }

            return false;
        }

        
        // DELETE
        function deletePessoa()
        {
            $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE cod_pessoa = ".$this->cod_pessoa;
            $this->db->query($sqlQuery);

            if($this->db->affected_rows > 0)
            {
                return true;
            }

            return false;
        }

    }
?>