<?php
include_once '../model/situacao_dao.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Situacao_Controller{
     
    
    public function lista($inicio, $fim){
        $teste_DAO = new Situacao_DAO();
        $lista =   $teste_DAO->lista($inicio, $fim);
        return $lista;
    }
    public function recuperarTotal(){
        $teste_DAO = new Situacao_DAO();
        $lista =   $teste_DAO->recuperarTotal();
        return $lista;
    }
  
  
}