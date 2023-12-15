<?php
    class Database 
    {

        public $db;

        public function getConnection() 
        {

            $this->db = null;
            
            try
            {
                $this->db = new mysqli('localhost','root','','caridade');
            }
            catch(Exception $e)
            {
                echo "Database nao pode ser conectado: " . $e->getMessage();
            }
            
            return $this->db;
        }
    }
?>