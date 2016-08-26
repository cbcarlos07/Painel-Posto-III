<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SituacaoListIterator {
    protected $situacaoList;
    protected $currentSituacao = 0;

    public function __construct(SituacaoList $situacaoList_in) {
      $this->situacaoList = $situacaoList_in;
    }
    public function getCurrentSituacao() {
      if (($this->currentSituacao > 0) && 
          ($this->situacaoList->getSituacaoCount() >= $this->currentSituacao)) {
        return $this->situacaoList->getSituacao($this->currentSituacao);
      }
    }
    public function getNextSituacao() {
      if ($this->hasNextSituacao()) {
        return $this->situacaoList->getSituacao(++$this->currentSituacao);
      } else {
        return NULL;
      }
    }
    public function hasNextSituacao() {
      if ($this->situacaoList->getSituacaoCount() > $this->currentSituacao) {
        return TRUE;
      } else {
        return FALSE;
      }
    }
}