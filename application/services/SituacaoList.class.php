<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SituacaoList {
    private $situacao = array();
    private $situacaoCount = 0;
    public function __construct() {
    }
    public function getSituacaoCount() {
      return $this->situacaoCount;
    }
    private function setSituacaoCount($newCount) {
      $this->situacaoCount = $newCount;
    }
    public function getSituacao($situacaoNumberToGet) {
      if ( (is_numeric($situacaoNumberToGet)) && 
           ($situacaoNumberToGet <= $this->getSituacaoCount())) {
           return $this->situacao[$situacaoNumberToGet];
         } else {
           return NULL;
         }
    }
    public function addSituacao(SituacaoPaciente $situacao_in) {
      $this->setSituacaoCount($this->getSituacaoCount() + 1);
      $this->situacao[$this->getSituacaoCount()] = $situacao_in;
      return $this->getSituacaoCount();
    }
    public function removeSituacao(SituacaoPaciente $situacao_in) {
      $counter = 0;
      while (++$counter <= $this->getSituacaoCount()) {
        if ($situacao_in->getAuthorAndTitle() == 
          $this->situacao[$counter]->getAuthorAndTitle())
          {
            for ($x = $counter; $x < $this->getSituacaoCount(); $x++) {
              $this->situacao[$x] = $this->situacao[$x + 1];
          }
          $this->setSituacaoCount($this->getSituacaoCount() - 1);
        }
      }
      return $this->getSituacaoCount();
    }
}
