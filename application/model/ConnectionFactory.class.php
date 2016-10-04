<?php   
class ConnectionFactory{
    private $ora_user = "login"; 
    private $ora_senha = "senha"; 
    private $ora_bd = "(DESCRIPTION=
                        (ADDRESS_LIST=
                        (ADDRESS=(PROTOCOL=TCP)(HOST=ip)(PORT=1521))
                        )
                        (CONNECT_DATA=
                        (SERVICE_NAME=servico)
                        )
                        )"; 
    public  function  getConnection(){
            $ora_conexao = oci_connect($this->ora_user, $this->ora_senha, $this->ora_bd);
        return $ora_conexao;
                    
    }
    
    public function closeConnection($connection){
        $ora_conexao = oci_close($connection);
        
    }
    

}

?>
