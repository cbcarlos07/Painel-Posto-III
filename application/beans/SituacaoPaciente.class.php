<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'Paciente.class.php';
class SituacaoPaciente {
    private $atendimento;  
     private $paciente;
     private $leito;
     private $prestador;
     private $dateInternacao;
     private $strDataUltimaPrescricao;
     private $strPrescricao;
     private $strParecer;
     private $strLaboratorio;
     private $strImagem;
     private $jejum;
     private $isolamento;
     private $mews;
     private $sepse;
     private $previsao;
     
     public function getPrevisao() {
         return $this->previsao;
     }

     public function setPrevisao($previsao) {
         $this->previsao = $previsao;
         return $this;
     }

          function getJejum() {
        return $this->jejum;
    }

    function getIsolamento() {
        return $this->isolamento;
    }

    function getMews() {
        return $this->mews;
    }

    function getSepse() {
        return $this->sepse;
    }

    function setJejum($jejum) {
        $this->jejum = $jejum;
    }

    function setIsolamento($isolamento) {
        $this->isolamento = $isolamento;
    }

    function setMews($mews) {
        $this->mews = $mews;
    }

    function setSepse($sepse) {
        $this->sepse = $sepse;
    }

    
     function getAtendimento() {
         return $this->atendimento;
     }

     function getPaciente() {
         return $this->paciente;
     }

     function getLeito() {
         return $this->leito;
     }

     function getPrestador() {
         return $this->prestador;
     }

     function getDateInternacao() {
         return $this->dateInternacao;
     }

     function getStrDataUltimaPrescricao() {
         return $this->strDataUltimaPrescricao;
     }

     function getStrPrescricao() {
         return $this->strPrescricao;
     }

     function getStrParecer() {
         return $this->strParecer;
     }

     function getStrLaboratorio() {
         return $this->strLaboratorio;
     }

     function getStrImagem() {
         return $this->strImagem;
     }

     function setAtendimento($atendimento) {
         $this->atendimento = $atendimento;
     }

     function setPaciente(Paciente $paciente) {
         $this->paciente = $paciente;
     }

     function setLeito($leito) {
         $this->leito = $leito;
     }

     function setPrestador($prestador) {
         $this->prestador = $prestador;
     }

     function setDateInternacao($dateInternacao) {
         $this->dateInternacao = $dateInternacao;
     }

     function setStrDataUltimaPrescricao($strDataUltimaPrescricao) {
         $this->strDataUltimaPrescricao = $strDataUltimaPrescricao;
     }

     function setStrPrescricao($strPrescricao) {
         $this->strPrescricao = $strPrescricao;
     }

     function setStrParecer($strParecer) {
         $this->strParecer = $strParecer;
     }

     function setStrLaboratorio($strLaboratorio) {
         $this->strLaboratorio = $strLaboratorio;
     }

     function setStrImagem($strImagem) {
         $this->strImagem = $strImagem;
     }


}