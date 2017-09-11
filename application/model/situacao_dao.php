<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 include 'ConnectionFactory.class.php';
 include_once '../beans/Paciente.class.php';
 include_once '../beans/SituacaoPaciente.class.php';
 include_once '../services/SituacaoList.class.php';
 include_once '../services/SituacaoListIterator.class.php';
 class Situacao_DAO  {
       
       
        public function lista($inicio, $fim){
            $conn = new ConnectionFactory();
            $con = $conn->getConnection();
            
			try{
				// executo a query
                            //$con = ociparse($connection_resource, $sql_text)
                                $query = "SELECT * FROM DBAMV.VIEW_PAINEL_POSTO_3 WHERE NUMERO BETWEEN $inicio AND $fim";
				$stmt = ociparse($con, $query);
                                        //("select p.nm_prestador nome from dbamv.prestador p");
				//$stmt = $this->conex->query($query);
                                oci_execute($stmt);
			   // desconecta 
                              
                           $situacaoList = new SituacaoList();
                           
                         while ($row = oci_fetch_array($stmt, OCI_ASSOC)){
                           
                             /*Instancia um objeto cliente para cada cliente que existe
                        * objeto Cliente chama o metodo setId e passa os dados como
                         um array de posição id
                              *  $cliente->setId(array("id'=>$dados->usu_id));
                              */
                             
                             //$paciente->setNome(array('ATENDIMENTO'=>$row->ATENDIMENTO));
                             
                             $sp =  new SituacaoPaciente(); 
                             $paciente = new Paciente();
                           //  $sp->setAtendimento(array('ATENDIMENTO'=>$row["ATENDIMENTO"]));
                             $paciente->setNome($row["NM_PACIENTE"]);
                             $sp->setPaciente($paciente);
                             $sp->setLeito($row["DS_LEITO"]);
                             $sp->setPrestador($row["NM_PRESTADOR"]);

                             $prescricao = "";
                             if(isset($row['PRESCRICAO'])){
                                 $prescricao = $row["PRESCRICAO"];
                                
                                if($prescricao == "verde"){
                                    $prescricao = "<img src=../../public/img/verde.png width=20 height=20>";
                                }else if($prescricao == "vermelha"){
                                    $prescricao = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                                else if($prescricao == "amarela"){
                                    $prescricao = "<img src=../../public/img/amarela.png width=20 height=20>";
                                }
                             }
                             if(isset($row['PARECER'])){
                                 $parecer = $row["PARECER"];
                                
                                if($parecer == "verde"){
                                    $parecer = "<img src=../../public/img/verde.png width=20 height=20>";
                                }else if($parecer == "vermelha"){
                                    $parecer = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                                else if($parecer == "amarela"){
                                    $parecer = "<img src=../../public/img/amarela.png width=20 height=20>";
                                }
                                
                             }else{
                                 $parecer = "";
                             }
                             
                             
                             if(isset($row["ISOLAMENTO"])){   
                                $isolamento = $row["ISOLAMENTO"];
                                if($isolamento == "verde"){
                                    $isolamento = "<img src=../../public/img/verde.png width=20 height=20>";
                                }else if($isolamento == "vermelha"){
                                    $isolamento = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                                else if($isolamento == "amarela"){
                                    $isolamento = "<img src=../../public/img/amarela.png width=20 height=20>";
                                }
                             }
                             else{
                                 $isolamento = "";
                             }
                             
                             if(isset($row["JEJUM"])){   
                                $jejum = $row["JEJUM"];
                                
                                if($jejum == "verde"){
                                    $jejum = "<img src=../../public/img/verde.png width=20 height=20>";
                                }else if($jejum == "vermelha"){
                                    $jejum = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                                else if($jejum == "amarela"){
                                    $jejum = "<img src=../../public/img/amarela.png width=20 height=20>";
                                }
                             }
                             else{
                                 $jejum = "";
                             }
                             
                             if(isset($row["MEWS"])){   
                                $mews = $row["MEWS"];
                                
                                if($mews == "verde"){
                                    $mews = "<img src=../../public/img/verde.png width=20 height=20>";
                                }else if($mews == "vermelha"){
                                    $mews = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                                else if($mews == "amarela"){
                                    $mews = "<img src=../../public/img/amarela.png width=20 height=20>";
                                }
                             }
                             else{
                                 $mews = "";
                             }
                             
                             
                                                         
                           
                             if(isset($row["SEPSE"])){   
                                $sepse = $row["SEPSE"];
                                if($sepse == "vermelha"){
                                    $sepse = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                             }
                            else{
                                 $sepse = "";
                             }
                        
                             
							 if(isset($row["PREV"])){   
                                $prev = $row["PREV"];
                                
                                if($prev == "verde"){
                                    $prev = "<img src=../../public/img/verde.png width=20 height=20>";
                                }else if($prev == "vermelha"){
                                    $prev = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                                else if($prev == "amarela"){
                                    $prev = "<img src=../../public/img/amarela.png width=20 height=20>";
                                }
                             }
                             else{
                                 $prev = "";
                             }


                             if(isset($row["USA_MED"])){
                                 $usa_med = $row["USA_MED"];

                                 if($usa_med == "verde"){
                                     $usa_med = "<img src=../../public/img/verde.png width=20 height=20>";
                                 }else if($usa_med == "vermelho"){
                                     $usa_med = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                 }
                                 else if($usa_med == "amarela"){
                                     $usa_med = "<img src=../../public/img/amarela.png width=20 height=20>";
                                 }
                             }
                             else{
                                 $usa_med = "";
                             }

                             if(isset($row["ESCORE"])){
                                 $score = $row["ESCORE"];

                                 if($score == "verde"){
                                     $score = "<img src=../../public/img/verde.png width=20 height=20>";
                                 }else if($score == "vermelha"){
                                     $score = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                 }
                                 else if($score == "amarela"){
                                     $score = "<img src=../../public/img/amarela.png width=20 height=20>";
                                 }
                             }
                             else{
                                 $score = "";
                             }





                             $sp->setJejum($jejum);
                             $sp->setIsolamento($isolamento);
                             $sp->setMews($mews);
                             $sp->setStrPrescricao($prescricao);
                             $sp->setStrParecer($parecer);
                             $sp->setSepse($sepse);
                             $sp->setPrevisao($prev);
                             $sp->setScore($score);
                             $sp->setUsaMed($usa_med);
                             $situacaoList->addSituacao($sp);
                             
                             
                         }  
                          
                               
			$conn->closeConnection($con);
			// retorna o resultado da query
			return $situacaoList;
		}catch ( PDOException $ex ){  echo "Erro: ".$ex->getMessage(); }
	}
        
        public function recuperarTotal(){
            $conn = new ConnectionFactory();
            $con = $conn->getConnection();
            $total = 0;
            
			try{
				// executo a query
                            //$con = ociparse($connection_resource, $sql_text)
                                $query = "SELECT COUNT(*) TOTAL FROM DBAMV.VIEW_PAINEL_POSTO_3 V ";
				$stmt = ociparse($con, $query);
                                        //("select p.nm_prestador nome from dbamv.prestador p");
				//$stmt = $this->conex->query($query);
                                oci_execute($stmt);
			   // desconecta 
                              
                           $situacaoList = new SituacaoList();
                           
                         if ($row = oci_fetch_array($stmt, OCI_ASSOC)){
                             $total = $row['TOTAL'];
                            
                             
                         }  
                          
                               
			$conn->closeConnection($con);
			// retorna o resultado da query
			return $total;
		}catch ( PDOException $ex ){  echo "Erro: ".$ex->getMessage(); }
                return $total;
	}
 }