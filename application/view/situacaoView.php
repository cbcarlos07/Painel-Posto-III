<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Painel do Posto III</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <!-- <meta HTTP-EQUIV="refresh" CONTENT="15">  -->
        <link rel="stylesheet" type="text/css" href="../../public/style/situacao.css">
        <link rel="shortcut icon" href="../../public/img/ham.png">
      <!--
        
        <script language="javascript" type="text/javascript">
                var http = false;

                if (window.ActiveXObject){
                http = new ActiveXObject("Microsoft.XMLHTTP");
                } else {
                http = new XMLHttpRequest();
                }


                function chamaphp(){
                http.abort();
                
                     http.open("GET", "situacaoView.php?pagina=2"); //Monta a tabela com os dados que quero. Neste arquivo dou o include para os comandos sql.
                
               
                http.onreadystatechange=function() {
                if(http.readyState == 4){
                document.getElementById('tab').innerHTML = http.responseText; //Nome da div onde o tabprodutos.php vai ser montado
                }
                }
                http.send(null);
                }

                setInterval("chamaphp()", 5000) //chama a função de 5 em 5 segundos
                
              
                

                </script>
                -->
               
    </head>
      <body >
           <div id=tab >
                          <div id=tabela >
                               <table border=0 width=100% onload=chamaphp()>
                                       <tr id="titulo" height="50">
                                           <td >PACIENTE</TD><TD WIDTH=150> LEITO</TD><TD>M&Eacute;DICO</TD><TD>PRESCRIÇÃO</TD><TD>PARECER</TD> 
                                           <TD>JEJUM</TD><TD>ISOLAMENTO</TD> 
                                           <TD>ADES&Atilde;O MEWS</td>
                                           <TD>MEWS > 4</td>
                                           <TD>SEPSE</td> 

                                           <TD>USA MEDICAMENTO</td>
                                           <TD>ALTA</td>
                                      </tr>
                                        <tbody>          
                                        <?php
                                            /* @var $pagina type */
                                            
                                                $pagina = $_GET['pagina'];                                                
                                             //   echo "Página: ".$pagina;
                     
                     
                                            // bloco 2 - defina o número de registros exibidos por página
                                            $num_por_pagina = 11; 

                                            // bloco 3 - descubra o número da página que será exibida
                                            // se o numero da página não for informado, definir como 1
                                            session_start();
                                            
                                            
                                            
                                                
                                              
                                              
                                            
                                            
                                            // bloco 4 - construa uma cláusula SQL "SELECT" que nos retorne somente os registros desejados
                                            // definir o número do primeiro registro da página. Faça a continha na calculadora que você entenderá minha fórmula.
                                            $primeiro_registro = ($pagina*$num_por_pagina) - $num_por_pagina;

                                             // consulta apenas os registros da página em questão utilizando como auxílio a definição LIMIT. Ordene os registros pela quantidade de pontos, começando do maior para o menor DESC.


                                             /* 
                                             * To change this license header, choose License Headers in Project Properties.
                                             * To change this template file, choose Tools | Templates
                                             * and open the template in the editor.
                                             */

                                            include_once '../controller/Situacao_Controller.class.php';
                                            include_once '../beans/SituacaoPaciente.class.php'; 
                                            include_once '../services/SituacaoList.class.php';
                                            include_once '../services/SituacaoListIterator.class.php';
                                            $dao = new Situacao_Controller();


                                            $total = $dao->recuperarTotal();
                                            //echo "Total: $total";
                                            $refresh = "";
                                           // echo "total: $total  Numero por pagina: $num_por_pagina";
                                            if($total > $num_por_pagina){
                                          //       if(!isset($_SESSION['pagina'])){
                                            //      $pagina = 1;
                                             //    $_SESSION['pagina'] = $pagina ;
                                            // }  else{
                                               /*  if( $_SESSION['pagina'] == 1){
                                                     $pagina = 2;
                                                     $_SESSION['pagina'] = $pagina ;
                                                 }
                                                 else {
                                                     $pagina = 1;
                                                     $_SESSION['pagina'] = $pagina;
                                                     
                                                 }
                                             //}
                                                */
                                                
                                                if($pagina == 1){
                                                //$rs = $dao->lista($primeiro_registro, $num_por_pagina);
                                              //  $refresh = "refresh:20; url={$_SERVER['PHP_SELF']}?pagina=2" ;
                                               // header($refresh);
                                                
                                                }else{
                                                    /*    $primeiro_registro = $primeiro_registro +1;
                                                        $ultimo_resgistro = $pagina + $num_por_pagina; 
                                                        $rs = $dao->lista($primeiro_registro, $ultimo_resgistro);
                                                      */  /*echo '<meta http-equiv="refresh" content="6" />';
                                                        $refresh = "refresh:6; url={$_SERVER['PHP_SELF']}?pagina=1" ;
                                                        header($refresh);*/
                                                        

                                                }
                                            
                                                    $total_paginas = $total / $num_por_pagina;
                                                    

                                                  // vamos arredondar para o alto o número de páginas que serão necessárias para exibir todos os registros. Por exemplo, se temos 20 registros e mostramos 6 por página, nossa variável $total_paginas será igual a 20/6, que resultará em 3.33. Para exibir os 2 registros restantes dos 18 mostrados nas primeiras 3 páginas (0.33), será necessária a quarta página. Logo, sempre devemos arredondar uma fração de número real para um inteiro de cima e isto é feito com a função ceil().
                                                  //echo "onload=chamaphp()";
                                                  $total_paginas = ceil($total_paginas);
                                                  $painel = "";
                                                   $atual = 0;
                                                  for ($x=1; $x<=$total_paginas; $x++) {
                                                    if ($x==$pagina) { // se estivermos na página corrente, não exibir o link para visualização desta página
                                                      $painel .= "<b> [ $x ] </b>";
													  $atual = $x;
                                                    } else {
                                                      $painel .= " <a href='situacaoView.php?pagina=$x'>[ $x ]</a>";
                                                    }
                                                  }
                                                  $_SESSION['pagina'] = $pagina; 
                                                  
                                                /***** TRABALHO DE ATUALIZAÇÃO  ***/  
                                                  if($pagina == 1){
                                                      $rs = $dao->lista($primeiro_registro, $num_por_pagina);
                                                      if($x > 1){
                                                        //$refresh = "refresh:6; url={$_SERVER['PHP_SELF']}?pagina=".$pagina+1;
                                                          $pagina = $pagina +1;
                                                          $refresh = "<meta HTTP-EQUIV='refresh' CONTENT='15;URL=situacaoView.php?pagina=$pagina'>";
                                                      //  echo "[ PAGINA = 1 ] ";
                                                        //echo "<meta HTTP-EQUIV='refresh' CONTENT='5;URL=nome_do_arquivo.php'>";
                                                        echo $refresh;
                                                       // header($refresh);
                                                      }else{
                                                          
                                                          echo '<meta HTTP-EQUIV="refresh" CONTENT="15">';
                                                      }
                                                      
                                                  }else{
                                                        
                                                      if($pagina >  $x ){
                                                            //$rs = $dao->lista($primeiro_registro, $num_por_pagina); 
                                                            $pagina = 1;
                                                            $refresh = "<meta HTTP-EQUIV='refresh' CONTENT='0;URL=situacaoView.php?pagina=$pagina'>";
                                                            echo $refresh; 
                                                           
                                                      }else{
                                                             $primeiro_registro = $primeiro_registro +1;
                                                             $ultimo_resgistro = $pagina * $num_por_pagina; 
                                                             $rs = $dao->lista($primeiro_registro, $ultimo_resgistro);
                                                             $pagina = $pagina +1;
                                                             if($pagina >= $x){
                                                                 $pagina = 1;
                                                                 $refresh = "<meta HTTP-EQUIV='refresh' CONTENT='15;URL=situacaoView.php?pagina=$pagina'>";
                                                             }else{
                                                                 $refresh = "<meta HTTP-EQUIV='refresh' CONTENT='15;URL=situacaoView.php?pagina=$pagina'>";
                                                             }
                                                             
                                                             echo $refresh; 
                                                      }
                                                      
                                                  }
                                                    
                                                  
                                                 $prev = $atual-1;
                                                 $next = $atual+1;
												 $pagina = $pagina-1;
												 	 
                                                    
                                                                if ($pagina > 1) {
                                                                    $prev_link = "<a href='{$_SERVER['PHP_SELF']}?pagina=$prev' >Anterior</a>";

                                                                    } else if ($pagina == 0)
																	{
																		
																		$prev_link = "<a href='{$_SERVER['PHP_SELF']}?pagina=$prev' >Anterior</a>";
																	}else { // senão não há link para a página anterior

                                                                    $prev_link = "Anterior";

                                                                    }

												
                                                    // se número total de páginas for maior que a página corrente, então temos link para a próxima página
                                                  if (!($pagina == 0) && ($total_paginas > $pagina)) {
                                                  $next_link = "<a href='{$_SERVER['PHP_SELF']}?pagina=$next' >Próxima</a>";
                                                  }/*else if(){
													  $next_link = "Próxima";
												  } */else { // senão não há link para a próxima página
                                                      $next_link = "Próxima";

                                                  } 
                                                  
                                                  
                                                  
                                            // exibir painel na tela
                                            echo "$prev_link | $painel | $next_link";

                                            }else{
                                             //   echo 'não é maior que o total';
                                                $rs = $dao->lista($primeiro_registro, $num_por_pagina);
                                                echo '<meta http-equiv="refresh" content="10" />';
                                            }
                                            
                                            // se página maior que 1 (um), então temos link para a página anterior

                                            $i = 0;
                                            $spList = new SituacaoListIterator($rs);
                                            $sp = new SituacaoPaciente();
                                            $paciente = new Paciente();
                                            
                                            
                                            
                                     
                                           while($spList->hasNextSituacao()){
                                                $i++;
                                               $sp = $spList->getNextSituacao();
                                              if($i % 2 == 0){
                                                  $par = "#d5e6ef";
                                              }else{
                                                  $par = "#ffffff";
                                              }  



                                                echo "<tr bgcolor=$par id=corpo height=60>";
                                                echo "<td>".$sp->getPaciente()->getNome()."</td>";
                                                echo "<td align=center> ".$sp->getLeito()."</td>";
                                                echo "<td align=center>".$sp->getPrestador()."</td>";
                                                echo "<td align=center>".$sp->getStrPrescricao()."</td>";        
                                                echo "<td align=center>".$sp->getStrParecer()."</td>"; 
                                                echo "<td align=center>".$sp->getJejum()."</td>";        
                                                echo "<td align=center>".$sp->getIsolamento()."</td>"; 
                                                echo "<td align=center>".$sp->getMews()."</td>";
                                                echo "<td align=center>".$sp->getScore()."</td>";
                                                echo "<td align=center>".$sp->getSepse()."</td>";
                                                echo "<td align=center>".$sp->getUsaMed()."</td>";
                                                echo "<td align=center>".$sp->getPrevisao()."</td>";
                                                echo "</tr>";
                                                
                                            }
                                        
                                            
                       
                    ?>
                                            <tbody>              
                      </table>    
                  </div>
                </div>
                                             
          </body>
                           
           
</html>
<?php

// Abre ou cria o arquivo bloco1.txt
// "a" representa que o arquivo é aberto para ser escrito
$fp = fopen("C:\portal.txt", "a");
 
// Escreve "exemplo de escrita" no bloco1.txt
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Manaus');
$dia_hoje = date('d');
$ano_hoje = date('Y');
$hora_hoje = date('H:i:s');
$data =  'Manaus, '.ucfirst(gmstrftime('%A')).', '.$dia_hoje.' de '.ucfirst(gmstrftime('%B')).' '.$ano_hoje.' '.$hora_hoje;
$texto = "Última atualização do painel: $data";
$escreve = fwrite($fp, "\r\n".$texto);
 
// Fecha o arquivo
fclose($fp);//-> OK
