
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../Controller/Teste_Controller.class.php';
$dao = new Teste_Controller();
$rs = $dao->lista();

for($i  = 0;count($rs); $i++){
    	
    echo $row["NOME"]."<br>";
}