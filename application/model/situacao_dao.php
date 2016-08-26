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
                                $query = "SELECT * FROM (

SELECT GERAL.*,ROWNUM NUMERO
  FROM (
                                            
                SELECT 
                 Z.CD_ATENDIMENTO
                ,Z.NM_PACIENTE
                ,Z.DS_LEITO
                ,Z.NM_PRESTADOR
                ,Z.PRESCRICAO
                ,Z.PARECER
                ,Z.ISOLAMENTO
                ,Z.JEJUM
                ,Z.PREV
                ,Z.MEWS
                ,Z.SEPSE
                ,Z.PLACE
                FROM
                (
                            SELECT A.CD_ATENDIMENTO
                                  ,A.NM_PACIENTE
                                  ,A.DS_LEITO
                                  ,A.PLACE
                                  ,A.NM_PRESTADOR
                                  ,CASE  
                                                                            WHEN (A.DATA) >= TRUNC(SYSDATE) 
                                                                              THEN 'verde' 
                                                                            WHEN (A.DATA) <> TRUNC(SYSDATE) 
                                                                              THEN 'vermelha' 
                                                                            WHEN (A.DATA) IS NULL 
                                                                              THEN 'vermelha' 
                                                                       END  PRESCRICAO 
                                  ,A.PARECER
                                  ,A.STATUS_ISO ISOLAMENTO
                                  ,A.SITUACAO JEJUM
                                  ,A.MEWS
                                  ,CASE 
                                      WHEN A.SEPSE IS NULL
                                         THEN ' '
                                      WHEN A.SEPSE IS NOT NULL
                                         THEN 'vermelha'
                                   END SEPSE
                                  ,PREV



                             FROM (

                                      SELECT A.NM_PACIENTE
                                            ,A.CD_ATENDIMENTO
                                            ,A.DS_LEITO
                                            ,A.PLACE
                                            ,A.NM_PRESTADOR
                                            ,A.DT_ATENDIMENTO
                                            ,G.STATUS_ISO
                                            ,H.SITUACAO
                                            ,MAX(DATA) DATA
                                            ,C.STATUS PARECER
                                            ,J.SITUACAO MEWS
                                            ,EXA.CD_ATENDIMENTO SEPSE
                                            ,PREV.STATUS PREV
                                        FROM (



                                                SELECT  A.CD_ATENDIMENTO
                                                       ,P.NM_PACIENTE
                                                       ,L.DS_RESUMO DS_LEITO
                                                       ,TO_NUMBER(SUBSTR(L.DS_RESUMO,0,3)) PLACE
                                                       ,PR.NM_MNEMONICO NM_PRESTADOR
                                                       ,A.DT_ATENDIMENTO
                                                  FROM  DBAMV.ATENDIME      A
                                                       ,DBAMV.PACIENTE      P
                                                       ,DBAMV.LEITO         L
                                                       ,DBAMV.PRESTADOR     PR

                                                 WHERE  A.CD_PACIENTE        =   P.CD_PACIENTE
                                                   AND  A.CD_PRESTADOR       =   PR.CD_PRESTADOR
                                                   AND  A.CD_LEITO           =   L.CD_LEITO
                                                   AND  A.TP_ATENDIMENTO     =   'I'
                                                   AND  L.CD_UNID_INT        IN   (7)
                                                   AND  A.DT_ALTA            IS NULL
                                                   AND  P.CD_PACIENTE        <> 1412335



                                            ) A 


                                           ,(

                                           SELECT   P.NM_PACIENTE 
                                                                                           ,PM.CD_PRE_MED 
                                                                                           ,PM.DH_CRIACAO DATA 
                                                                                     FROM   ATENDIME A 
                                                                                           ,PRE_MED  PM 
                                                                                           ,PACIENTE P 
                                                                                    WHERE   A.CD_ATENDIMENTO  = PM.CD_ATENDIMENTO 
                                                                                      AND   A.CD_PACIENTE     = P.CD_PACIENTE 
                                                                                      AND   A.TP_ATENDIMENTO  = 'I' 
                                                                                      AND   PM.CD_OBJETO      IN (1,22,54)   
                                                                                      AND   A.DT_ALTA  IS NULL 


                                           ) PRESC





                                        ,( 
                                                                                  SELECT  
                                                                                            PAR.CD_ATENDIMENTO 
                                                                                          ,CASE  
                                                                                             WHEN (PAR.TOTAL - SOL.TOTAL) >= 0 
                                                                                               THEN 'vermelha' 
                                                                                             ELSE 
                                                                                                    'verde' 
                                                                                           END STATUS 
                                                                                      FROM  ( 
                                                                                               SELECT  PM.CD_ATENDIMENTO 
                                                                                                      ,COUNT(1)            TOTAL 
                                                                                                 FROM  DBAMV.PAR_MED       PM 
                                                                                                      ,ACAO_PARECER_MEDICO A 
                                                                                                      ,DBAMV.ESPECIALID    E 
                                                                                                WHERE  A.CD_PAR_MED      =  PM.CD_PAR_MED 
                                                                                                  AND  E.CD_ESPECIALID   =  PM.CD_ESPECIALID 
                                                                                                  AND  A.SN_CANCELADO    =  'N' 
                                                                                                  AND  A.DH_ACAO        >= SYSDATE - 7 

                                                                                             GROUP BY PM.CD_ATENDIMENTO) PAR 
                                                                                             ,(SELECT  PM.CD_ATENDIMENTO 
                                                                                                      ,COUNT(1)            TOTAL 
                                                                                                 FROM  DBAMV.PAR_MED       PM 
                                                                                                      ,ACAO_PARECER_MEDICO A 
                                                                                                      ,DBAMV.ESPECIALID    E 
                                                                                                WHERE  A.CD_PAR_MED      =  PM.CD_PAR_MED 
                                                                                                  AND  E.CD_ESPECIALID   =  PM.CD_ESPECIALID 
                                                                                                  AND  PM.DS_SITUACAO    IN ('Solicitado','Em Análise') 
                                                                                                  AND  A.SN_CANCELADO    =  'N' 
                                                                                                  AND  A.TP_ACAO         =  'SOL' 
                                                                                                  AND  A.DH_ACAO        >= SYSDATE - 7 

                                                                                             GROUP BY PM.CD_ATENDIMENTO) SOL 
                                                                                        WHERE PAR.CD_ATENDIMENTO = SOL.CD_ATENDIMENTO(+) 
                                                                               )  C   





                                   ,(SELECT DISTINCT ISO_CCIH.CD_ATENDIMENTO
                                                   ,ISO_CCIH.PACIENTE
                                                   ,ISO_ENF.CD_ATENDIMENTO

                                                   ,CASE 
                                                       WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NULL AND FIM_ISOLAMENTO IS NULL
                                                         THEN 'amarela' 
                                                       WHEN ISO_ENF.CD_ATENDIMENTO IS NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL AND FIM_ISOLAMENTO IS NULL
                                                         THEN 'verde'
                                                       WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL AND FIM_ISOLAMENTO IS NULL
                                                         THEN 'verde'
                                                     END STATUS_ISO 

                                                   ,CASE 
                                                      WHEN ISO_ENF.CD_ATENDIMENTO IS NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL
                                                        THEN ISO_CCIH.CD_ATENDIMENTO
                                                      WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL
                                                        THEN ISO_CCIH.CD_ATENDIMENTO
                                                      WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NULL
                                                        THEN ISO_ENF.CD_ATENDIMENTO
                                                     END ATENDIME  
                                      FROM (



                                              SELECT A.CD_ATENDIMENTO
                                                    ,PACIENTE
                                                    ,TP_ISOLAMENTO
                                                    ,FIM_ISOLAMENTO
                                                    ,ident
                                                FROM 


                                                        (SELECT  PAC.NM_PACIENTE  PACIENTE
                                                                ,DECODE(D.DS_CAMPO,'isolamento_goticulas','Isolamento de Gotículas'
                                                                                  ,'Isolamento_Goticula','Isolamento de Gotículas'
                                                                                  ,'isolamento_repiratorio','Isolamento Respiratório'
                                                                                  ,'Isolamento_Respiratorio','Isolamento Respiratório'
                                                                                  ,'isolamento_contato','Isolamento de Contato'
                                                                                  ,'Isolamento_Contato','Isolamento de Contato'
                                                                                  ,'isolamento_Imunodeprimido','Isolamento Imunodeprimido'
                                                                                  ,'Isolamento_Imunodeprimido','Isolamento Imunodeprimido') TP_ISOLAMENTO
                                                                                  ,d.ds_campo
                                                                ,a.cd_documento_clinico
                                                                ,DBMS_LOB.SUBSTR(LO_VALOR)
                                                                ,E.CD_ATENDIMENTO
                                                           FROM  pw_editor_clinico           a
                                                                ,dbamv.editor_documento      b 
                                                                ,editor_registro_campo       c
                                                                ,editor_campo                d
                                                                ,dbamv.pw_documento_clinico  e
                                                                ,DBAMV.PACIENTE              PAC


                                                         WHERE  a.cd_documento           =   b.cd_documento
                                                           AND  a.cd_editor_registro     =   c.cd_registro(+)
                                                           AND  c.cd_campo               =   d.cd_campo(+)
                                                           AND  a.cd_documento_clinico   =   e.cd_documento_clinico
                                                           AND  E.CD_PACIENTE            =   PAC.CD_PACIENTE
                                                           AND  E.TP_STATUS              =   'FECHADO'
                                                           and  a.cd_documento           =   34
                                                           and  d.cd_campo in (80615,80613,80612,80611,80753,80759,80757,80755)
                                                           and  DBMS_LOB.SUBSTR(LO_VALOR) = 'true'

                                                           ) A 


                                                        ,(SELECT  TO_DATE(DBMS_LOB.SUBSTR(LO_VALOR),'DD/MM/YYYY') FIM_ISOLAMENTO
                                                                 ,a.cd_documento_clinico
                                                           FROM   pw_editor_clinico           a
                                                                 ,dbamv.editor_documento      b 
                                                                 ,editor_registro_campo       c
                                                                 ,editor_campo                d
                                                                 ,dbamv.pw_documento_clinico  e
                                                                 ,DBAMV.PACIENTE              PAC


                                                           WHERE  a.cd_documento           =   b.cd_documento
                                                             AND  a.cd_editor_registro     =   c.cd_registro(+)
                                                             AND  c.cd_campo               =   d.cd_campo(+)
                                                             AND  a.cd_documento_clinico   =   e.cd_documento_clinico
                                                             AND  E.CD_PACIENTE            =   PAC.CD_PACIENTE
                                                             AND  E.TP_STATUS              =   'FECHADO'
                                                             and  a.cd_documento           =   34
                                                             and  d.cd_campo in (80618,80739) ) D  


                                                           ,(SELECT distinct 
                                                                    (e.cd_atendimento) 
                                                                    ,MAX(a.cd_documento_clinico) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO) cd_documento_clinico
                                                                    ,MAX(DBMS_LOB.SUBSTR(LO_VALOR)) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO) ident
                                                                    ,MAX(DH_CRIACAO) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO) criacao


                                                               FROM  pw_editor_clinico           a
                                                                    ,dbamv.editor_documento      b 
                                                                    ,editor_registro_campo       c
                                                                    ,editor_campo                d
                                                                    ,dbamv.pw_documento_clinico  e


                                                              WHERE  a.cd_documento           =   b.cd_documento
                                                                AND  a.cd_editor_registro     =   c.cd_registro(+)
                                                                AND  c.cd_campo               =   d.cd_campo(+)
                                                                AND  a.cd_documento_clinico   =   e.cd_documento_clinico
                                                                AND  E.TP_STATUS              =   'FECHADO'
                                                                and  a.cd_documento           =   34
                                                                and  d.cd_campo in (80622,80741)

                                                           group by e.cd_atendimento, DBMS_LOB.SUBSTR(LO_VALOR) ) F 

                                                  WHERE A.CD_DOCUMENTO_CLINICO = D.CD_DOCUMENTO_CLINICO
                                                    and A.CD_DOCUMENTO_CLINICO = F.CD_DOCUMENTO_CLINICO           


                                                   AND FIM_ISOLAMENTO IS NULL ) ISO_CCIH

                                             ,(                          
                                               SELECT PM.CD_ATENDIMENTO
                                                 FROM TIP_PRESC TP
                                                     ,ITPRE_MED IPM
                                                     ,PRE_MED   PM
                                                WHERE PM.CD_PRE_MED     = IPM.CD_PRE_MED
                                                  AND TP.CD_TIP_PRESC   = IPM.CD_TIP_PRESC
                                                  AND IPM.SN_CANCELADO  = 'N'
                                                  AND TP.CD_TIP_PRESC   = 14194
                                               ) ISO_ENF 

                                              ,( SELECT  A.CD_ATENDIMENTO
                                                               ,P.NM_PACIENTE
                                                               ,L.DS_RESUMO DS_LEITO
                                                               ,PR.NM_MNEMONICO NM_PRESTADOR
                                                               ,A.DT_ATENDIMENTO
                                                          FROM  DBAMV.ATENDIME      A
                                                               ,DBAMV.PACIENTE      P
                                                               ,DBAMV.LEITO         L
                                                               ,DBAMV.PRESTADOR     PR

                                                         WHERE  A.CD_PACIENTE        =   P.CD_PACIENTE
                                                           AND  A.CD_PRESTADOR       =   PR.CD_PRESTADOR
                                                           AND  A.CD_LEITO           =   L.CD_LEITO
                                                           AND  A.TP_ATENDIMENTO     =   'I'
                                                           AND  A.DT_ALTA            IS NULL


                                                         ) INTER 


                                       WHERE INTER.CD_ATENDIMENTO = ISO_CCIH.CD_ATENDIMENTO(+)
                                         AND INTER.CD_ATENDIMENTO = ISO_ENF.CD_ATENDIMENTO(+)

                                         AND (ISO_ENF.CD_ATENDIMENTO IS NOT NULL OR ISO_CCIH.CD_ATENDIMENTO IS NOT NULL)) G 






                             ,(

                                     SELECT 
                                           CD_ATENDIMENTO
                                          ,A.NM_PACIENTE     PACIENTE
                                          ,SUBSTR(A.ITEM,4)            JEJUM
                                          ,to_CHAR(A.DATA_PRESC,'DD/MM/YYYY HH24:MI:SS')         INICIO_JEJUM

                                                   ,CASE 
                                                         WHEN (A.ITEM) = '10||INICIAR JEJUM ULTRASSONOGRAFIA' AND (SYSDATE-DATA_PRESC) <= 0.1666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '10||INICIAR JEJUM ULTRASSONOGRAFIA' AND (SYSDATE-DATA_PRESC) BETWEEN 0.1666666666 AND 0.2083333333 

                                                         THEN 'amarela'
                                                         WHEN (A.ITEM) = '10||INICIAR JEJUM ULTRASSONOGRAFIA' AND (SYSDATE-DATA_PRESC) > 0.2083333333 

                                                         THEN 'vermelha'

                                                         WHEN (A.ITEM) = '9||INICIAR JEJUM TOMOGRAFIA COMPUTADORIZADA' AND (SYSDATE-DATA_PRESC) <= 0.29166666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '9||INICIAR JEJUM TOMOGRAFIA COMPUTADORIZADA' AND (SYSDATE-DATA_PRESC) BETWEEN 0.29166666666666 AND 0.33333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '9||INICIAR JEJUM TOMOGRAFIA COMPUTADORIZADA' AND (SYSDATE-DATA_PRESC) > 0.33333333333333 

                                                          THEN 'vermelha'

                                                         WHEN (A.ITEM) = '8||INICIAR JEJUM RESSONANCIA MAGNETICA' AND (SYSDATE-DATA_PRESC) <= 0.29166666666666

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '8||INICIAR JEJUM RESSONANCIA MAGNETICA' AND (SYSDATE-DATA_PRESC) BETWEEN 0.29166666666666 AND 0.33333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '8||INICIAR JEJUM RESSONANCIA MAGNETICA' AND (SYSDATE-DATA_PRESC) > 0.33333333333333 

                                                           THEN 'amarela'

                                                         WHEN (A.ITEM) = '7||INICIAR JEJUM RADIOLOGIA VASCULAR'  AND (SYSDATE-DATA_PRESC) <= 0.5416666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '7||INICIAR JEJUM RADIOLOGIA VASCULAR'  AND (SYSDATE-DATA_PRESC) BETWEEN 0.5416666666666 AND 0.58333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '7||INICIAR JEJUM RADIOLOGIA VASCULAR'  AND (SYSDATE-DATA_PRESC) > 0.58333333333333 

                                                           THEN 'vermelha'

                                                         WHEN (A.ITEM) = '1||INICIAR JEJUM COLANGIORESSONANCIA'  AND (SYSDATE-DATA_PRESC) <= 0.5416666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '1||INICIAR JEJUM COLANGIORESSONANCIA'  AND (SYSDATE-DATA_PRESC) BETWEEN 0.5416666666666 AND 0.58333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '1||INICIAR JEJUM COLANGIORESSONANCIA'  AND (SYSDATE-DATA_PRESC) > 0.58333333333333 

                                                          THEN 'vermelha'

                                                         WHEN (A.ITEM) = '2||INICIAR JEJUM COLONOSCOPIA'  AND (SYSDATE-DATA_PRESC) <= 0.416666666666 

                                                         THEN 'verde'
                                                         WHEN (A.ITEM) = '2||INICIAR JEJUM COLONOSCOPIA'  AND (SYSDATE-DATA_PRESC) BETWEEN 0.416666666666 AND 0.458333333333333 

                                                         THEN 'amarela'
                                                         WHEN (A.ITEM) = '2||INICIAR JEJUM COLONOSCOPIA'  AND (SYSDATE-DATA_PRESC) > 0.458333333333333 

                                                        THEN 'vermelha'

                                                         WHEN (A.ITEM) = '3||INICIAR JEJUM ENDOSCOPIA'   AND (SYSDATE-DATA_PRESC) <= 0.416666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '3||INICIAR JEJUM ENDOSCOPIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.416666666666 AND 0.458333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '3||INICIAR JEJUM ENDOSCOPIA'   AND (SYSDATE-DATA_PRESC) > 0.458333333333333 

                                                           THEN 'vermelha'

                                                          WHEN (A.ITEM) =  '5||INICIAR JEJUM PROCEDIMENTO CIRURGICO (OUTROS)'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333

                                                        THEN 'verde'
                                                        WHEN (A.ITEM) =  '5||INICIAR JEJUM PROCEDIMENTO CIRURGICO (OUTROS)'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 

                                                          THEN 'amarela'
                                                         WHEN (A.ITEM) =  '5||INICIAR JEJUM PROCEDIMENTO CIRURGICO (OUTROS)'   AND (SYSDATE-DATA_PRESC) > 0.625 

                                                           THEN 'vermelha'

                                                         WHEN (A.ITEM) = '6||INICIAR JEJUM PROCEDIMENTO CIRURGICO DO TRATO GASTRO'  AND (SYSDATE-DATA_PRESC) <= 1 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '6||INICIAR JEJUM PROCEDIMENTO CIRURGICO DO TRATO GASTRO'  AND (SYSDATE-DATA_PRESC) BETWEEN 1 AND 1.083333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '6||INICIAR JEJUM PROCEDIMENTO CIRURGICO DO TRATO GASTRO'  AND (SYSDATE-DATA_PRESC) > 1.0833333333 

                                                           THEN 'vermelha'

                                                        WHEN (A.ITEM) = '4||INICIAR JEJUM OUTROS MOTIVOS' 

                                                           THEN 'vermelha'
                                                             
                                                       
                                                         WHEN (A.ITEM) = '11||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CIRURGIA GERAL'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '11||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CIRURGIA GERAL'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '11||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CIRURGIA GERAL'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '12||INICIAR JEJUM PROCEDIMENTO CIRURGICO - ORTOPEDIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '12||INICIAR JEJUM PROCEDIMENTO CIRURGICO - ORTOPEDIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '12||INICIAR JEJUM PROCEDIMENTO CIRURGICO - ORTOPEDIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '13||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CARDIOLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '13||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CARDIOLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '13||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CARDIOLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '16||INICIAR JEJUM PROCEDIMENTO CIRURGICO - UROLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '16||INICIAR JEJUM PROCEDIMENTO CIRURGICO - UROLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '16||INICIAR JEJUM PROCEDIMENTO CIRURGICO - UROLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '15||INICIAR JEJUM PROCEDIMENTO CIRURGICO - GINECOLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '15||INICIAR JEJUM PROCEDIMENTO CIRURGICO - GINECOLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '15||INICIAR JEJUM PROCEDIMENTO CIRURGICO - GINECOLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '14||INICIAR JEJUM PROCEDIMENTO CIRURGICO - NEUROLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '14||INICIAR JEJUM PROCEDIMENTO CIRURGICO - NEUROLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '14||INICIAR JEJUM PROCEDIMENTO CIRURGICO - NEUROLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha'
                                                     END SITUACAO






                                      FROM (                   

                                         select A.CD_ATENDIMENTO
                                               ,A.NM_PACIENTE
                                               ,TO_DATE(A.DATA||' '||B.HORA||' '||MINUTO,'DD/MM/YYYY HH24:MI:SS') DATA_PRESC
                                               ,ITEM
                                               ,A.CRIACAO
                                           from (  

                                                             SELECT    p.nm_paciente
                                                                     ,F.CD_ATENDIMENTO
                                                                     ,MAX(DBMS_LOB.substr(lo_valor))  KEEP (DENSE_RANK LAST ORDER BY E.DH_CRIACAO) DATA
                                                                     ,MAX(a.cd_documento_clinico) KEEP (DENSE_RANK LAST ORDER BY E.DH_CRIACAO) CD_DOCUMENTO_CLINICO
                                                                     ,MAX(E.DH_CRIACAO)              CRIACAO
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  d.cd_metadado              in (63512)

                                                                 GROUP BY P.NM_PACIENTE,F.CD_ATENDIMENTO


                                                   ) a




                                                   ,(

                                                    SELECT  p.nm_paciente
                                                                     ,DBMS_LOB.substr(lo_valor) HORA
                                                                     ,a.cd_documento_clinico
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  d.cd_metadado              in (63513)
                                                                                                  

                                                   ) b


                                                   ,(

                                                    SELECT  p.nm_paciente
                                                                     ,DBMS_LOB.substr(lo_valor) minuto
                                                                     ,a.cd_documento_clinico
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  d.cd_metadado              in (63515)


                                                   ) c



                                                   ,(

                                                    SELECT  p.nm_paciente
                                                                     ,DBMS_LOB.substr(lo_valor) ITEM
                                                                     ,a.cd_documento_clinico
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  e.dh_criacao              >=     sysdate-2
                                                                 and  d.cd_metadado              in (63514)

                                                   ) d





                                              WHERE A.NM_PACIENTE = B.NM_PACIENTE
                                                AND A.NM_PACIENTE = C.NM_PACIENTE
                                                AND A.NM_PACIENTE = D.NM_PACIENTE
                                                and a.cd_documento_clinico = b.cd_documento_clinico
                                                AND a.cd_documento_clinico = C.CD_DOCUMENTO_CLINICO
                                                AND a.cd_documento_clinico = D.CD_DOCUMENTO_CLINICO


                                          ) A


                                          ,(

                                               SELECT  P.NM_PACIENTE
                                                      ,MAX(TP.DS_TIP_PRESC) ITEM
                                                      ,MAX(TP.CD_TIP_PRESC) CD_ITEM
                                                      ,MAX(IPM.DH_INICIAL) DATA

                                                 FROM  PRE_MED   PM
                                                      ,ITPRE_MED IPM
                                                      ,DBAMV.TIP_PRESC TP
                                                      ,DBAMV.ATENDIME  A
                                                      ,DBAMV.PACIENTE  P


                                                WHERE  PM.CD_PRE_MED    = IPM.CD_PRE_MED
                                                  AND TP.CD_TIP_PRESC   = IPM.CD_TIP_PRESC
                                                  AND PM.CD_ATENDIMENTO = A.CD_ATENDIMENTO
                                                  AND A.CD_PACIENTE     = P.CD_PACIENTE
                                                  AND PM.DH_CRIACAO    >= sysdate-2
                                                  AND TP.CD_TIP_PRESC   IN  (13662)
                                                  GROUP BY P.NM_PACIENTE
                                                  ORDER BY 2 DESC
                                          ) B

                                          WHERE A.NM_PACIENTE = B.NM_PACIENTE(+)
                                            AND (B.CD_ITEM IS NULL OR A.CRIACAO > B.DATA)
                                       

                                       ) H    





                                        ,(

                            SELECT ATENDIMENTO
                                  ,MAX(PROXIMA) PROXIMA
                                  ,MAX(SITUACAO)KEEP (DENSE_RANK LAST ORDER BY PROXIMA) SITUACAO
                             FROM (

                             SELECT ATENDIMENTO
                                   ,PROXIMA
                                                  ,CASE
                                                      WHEN (SYSDATE) > PROXIMA-(0.5/24) AND SYSDATE <= PROXIMA

                                                        THEN 'amarela'
                                                      WHEN (SYSDATE) < PROXIMA-(0.5/24)

                                                        THEN 'verde'
                                                      WHEN (SYSDATE) > PROXIMA

                                                        THEN 'vermelha'            
                                                   END SITUACAO

                                              FROM (


                                            SELECT ESCORE
                                                  ,DATA_COLETA
                                                  ,TP_ATENDIMENTO
                                                  ,ATENDIMENTO
                                                  ,CASE
                                                     WHEN ESCORE IN (0,1,2)
                                                       THEN DATA_COLETA+(4/24)
                                                     WHEN ESCORE IN (3,4)
                                                       THEN DATA_COLETA+(2/24)
                                                     WHEN ESCORE IN (5,6)
                                                       THEN DATA_COLETA+(1/24)
                                                     WHEN ESCORE IN (7,8,9,10,11,12,13,14,15)
                                                       THEN DATA_COLETA+(0.5/24)
                                                    END PROXIMA


                                              FROM



                                            (SELECT  
                                                   SUM(TEMP) + SUM(PA_SIS) + SUM(FREQ_RES) + SUM(FREQ_CARD) + SUM(NV_CON) ESCORE
                                                   ,DATA_COLETA
                                                   ,TP_ATENDIMENTO
                                                   ,ATENDIMENTO

                                              FROM (

                                            SELECT ICSV.CD_COLETA_SINAL_VITAL COD_COLETA
                                                   ,A.CD_ATENDIMENTO ATENDIMENTO            
                                                   ,P.NM_PACIENTE    PACIENTE
                                                   ,CSV.DATA_COLETA
                                                   ,A.TP_ATENDIMENTO
                                                   ,CASE
                                                      WHEN  ((SV.CD_SINAL_VITAL) = 1 AND (VALOR) <= 35)
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 1 AND (VALOR) BETWEEN 35.1 AND 37.8
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 1 AND (VALOR) > 37.8
                                                        THEN 2
                                                   END TEMP


                                                  ,CASE
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) < 70
                                                        THEN 3
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) BETWEEN 71 AND 80
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) BETWEEN 81 AND 100
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) BETWEEN 101 AND 199
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) >= 200
                                                        THEN 2
                                                   END PA_SIS


                                                   ,CASE
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) < 9
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) BETWEEN 10 AND 14
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) BETWEEN 15 AND 20
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) BETWEEN 21 AND 29
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) >= 30
                                                        THEN 3
                                                   END FREQ_RES
                                                    ,CASE
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) < 40
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 40 AND 50
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 51 AND 100
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 101 AND 110
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 111 AND 120
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) > 120
                                                        THEN 3
                                                    END FREQ_CARD

                                                    ,CASE
                                                     WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 24
                                                        THEN 3
                                                      WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 25
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 26
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 23
                                                        THEN 0
                                                    END NV_CON
                                              FROM DBAMV.COLETA_SINAL_VITAL   CSV
                                                  ,DBAMV.ITCOLETA_SINAL_VITAL ICSV
                                                  ,DBAMV.SINAL_VITAL          SV
                                                  ,ATENDIME                   A
                                                  ,PACIENTE                   P
                                             WHERE CSV.CD_COLETA_SINAL_VITAL    =   ICSV.CD_COLETA_SINAL_VITAL
                                               AND SV.CD_SINAL_VITAL            =   ICSV.CD_SINAL_VITAL
                                               AND CSV.CD_ATENDIMENTO           =   A.CD_ATENDIMENTO
                                               AND P.CD_PACIENTE                =   A.CD_PACIENTE
                                               AND CSV.SN_FINALIZADO            =   'S'


                                            )
                                            GROUP BY DATA_COLETA,TP_ATENDIMENTO,ATENDIMENTO
                                            )

                                            WHERE ESCORE IS NOT NULL
                                              AND TP_ATENDIMENTO = 'I'
                                            

                                       )
                                   )

                            GROUP BY ATENDIMENTO         

                                        ) J      
                                        
                                    
                            
                            
                             ,(
                                      SELECT DISTINCT D.CD_ATENDIMENTO
                                        FROM DBAMV.PED_LAB   F
                                            ,DBAMV.ITPED_LAB G
                                            ,DBAMV.EXA_LAB   H
                                            ,DBAMV.ATENDIME   D              
                                       WHERE F.CD_PED_LAB     = G.CD_PED_LAB
                                         AND H.CD_EXA_LAB     = G.CD_EXA_LAB
                                         AND F.CD_ATENDIMENTO = D.CD_ATENDIMENTO       
                                         AND H.CD_EXA_LAB IN (1483,1484,1485,1486,1470,1471,1472,1473,1474,1475,1476,1477,1478,1479,1480,1481,1482)

                                         ) exa
                                   
                             ,(
                                     SELECT ATEND
       
                                            ,CASE
                                                WHEN (DATA_ATUAL) < PREV_ALTA-1
                                                  THEN 'verde'
                                                WHEN (DATA_ATUAL) = PREV_ALTA-1 OR (DATA_ATUAL) = PREV_ALTA
                                                  THEN 'amarela'
                                                WHEN (DATA_ATUAL) > PREV_ALTA
                                                  THEN 'vermelha'
                                             END STATUS   

                                        FROM (

                                      SELECT  e.cd_atendimento ATEND
                                             ,to_date(MAX(DBMS_LOB.SUBSTR(LO_VALOR)) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO),'dd/mm/yyyy') prev_alta
                                             ,to_date(sysdate) DATA_ATUAL
                                        
                                        FROM  pw_editor_clinico           a
                                             ,dbamv.editor_documento      b 
                                             ,editor_registro_campo       c
                                             ,editor_campo                d
                                             ,dbamv.pw_documento_clinico  e
                                             
                                       WHERE a.cd_documento           =   b.cd_documento
                                         AND a.cd_editor_registro     =   c.cd_registro(+)
                                         AND c.cd_campo               =   d.cd_campo(+)
                                         AND a.cd_documento_clinico   =   e.cd_documento_clinico
                                         AND E.TP_STATUS              =   'FECHADO'
                                         and a.cd_documento           =   178
                                         and cd_metadado              =   50738
                                      --   AND TRUNC(E.DH_REFERENCIA) > '01/01/2016'
                                            AND E.CD_ATENDIMENTO NOT IN (352665)
                                         
                                         and DBMS_LOB.SUBSTR(LO_VALOR) is not null
                                         
                                       GROUP BY E.CD_ATENDIMENTO)
   
                                      
                                    ) PREV



                                      WHERE A.CD_ATENDIMENTO =  G.ATENDIME(+)
                                        AND A.NM_PACIENTE = PRESC.NM_PACIENTE(+)
                                        AND A.CD_ATENDIMENTO =  C.CD_ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO =  H.CD_ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO =  J.ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO = EXA.CD_ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO = PREV.ATEND(+)
                                   GROUP BY A.NM_PACIENTE    ,A.CD_ATENDIMENTO
                                           ,A.DS_LEITO       ,A.NM_PRESTADOR
                                           ,A.DT_ATENDIMENTO ,C.STATUS
                                           ,G.STATUS_ISO     ,EXA.CD_ATENDIMENTO
                                           ,H.SITUACAO       ,PREV.STATUS
                                           ,J.SITUACAO  
                                           ,A.PLACE 
                                    ) A   


                       )Z 
                   ORDER BY 3
            )  GERAL              
          )           
                                             WHERE NUMERO BETWEEN $inicio AND $fim
                                             
                                             ";
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
                             
                             
                             if(isset($row['PRESCRICAO'])){
                                 $prescricao = $row["PRESCRICAO"];
                                
                                if($prescricao == "verde"){
                                    $prescricao = "<img src=../../public/img/verde.png width=20 height=20>";
                                }else if($prescricao == "vermelha"){
                                    $prescricao = "<img src=../../public/img/vermelha.png width=20 height=20>";
                                }
                                else if($mews == "amarela"){
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
                             
                             $sp->setJejum($jejum);
                             $sp->setIsolamento($isolamento);
                             $sp->setMews($mews);
                             $sp->setStrPrescricao($prescricao);
                             $sp->setStrParecer($parecer);
                             $sp->setSepse($sepse);
                             $sp->setPrevisao($prev);
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
                                $query = "          
              SELECT COUNT(*) TOTAL FROM (

SELECT GERAL.*,ROWNUM NUMERO
  FROM (
                                            
                SELECT 
                 Z.CD_ATENDIMENTO
                ,Z.NM_PACIENTE
                ,Z.DS_LEITO
                ,Z.NM_PRESTADOR
                ,Z.PRESCRICAO
                ,Z.PARECER
                ,Z.ISOLAMENTO
                ,Z.JEJUM
                ,Z.PREV
                ,Z.MEWS
                ,Z.SEPSE
                ,Z.PLACE
                FROM
                (
                            SELECT A.CD_ATENDIMENTO
                                  ,A.NM_PACIENTE
                                  ,A.DS_LEITO
                                  ,A.PLACE
                                  ,A.NM_PRESTADOR
                                  ,CASE  
                                                                            WHEN (A.DATA) >= TRUNC(SYSDATE) 
                                                                              THEN 'verde' 
                                                                            WHEN (A.DATA) <> TRUNC(SYSDATE) 
                                                                              THEN 'vermelha' 
                                                                            WHEN (A.DATA) IS NULL 
                                                                              THEN 'vermelha' 
                                                                       END  PRESCRICAO 
                                  ,A.PARECER
                                  ,A.STATUS_ISO ISOLAMENTO
                                  ,A.SITUACAO JEJUM
                                  ,A.MEWS
                                  ,CASE 
                                      WHEN A.SEPSE IS NULL
                                         THEN ' '
                                      WHEN A.SEPSE IS NOT NULL
                                         THEN 'vermelha'
                                   END SEPSE
                                  ,PREV



                             FROM (

                                      SELECT A.NM_PACIENTE
                                            ,A.CD_ATENDIMENTO
                                            ,A.DS_LEITO
                                            ,A.PLACE
                                            ,A.NM_PRESTADOR
                                            ,A.DT_ATENDIMENTO
                                            ,G.STATUS_ISO
                                            ,H.SITUACAO
                                            ,MAX(DATA) DATA
                                            ,C.STATUS PARECER
                                            ,J.SITUACAO MEWS
                                            ,EXA.CD_ATENDIMENTO SEPSE
                                            ,PREV.STATUS PREV
                                        FROM (



                                                SELECT  A.CD_ATENDIMENTO
                                                       ,P.NM_PACIENTE
                                                       ,L.DS_RESUMO DS_LEITO
                                                       ,TO_NUMBER(SUBSTR(L.DS_RESUMO,0,3)) PLACE
                                                       ,PR.NM_MNEMONICO NM_PRESTADOR
                                                       ,A.DT_ATENDIMENTO
                                                  FROM  DBAMV.ATENDIME      A
                                                       ,DBAMV.PACIENTE      P
                                                       ,DBAMV.LEITO         L
                                                       ,DBAMV.PRESTADOR     PR

                                                 WHERE  A.CD_PACIENTE        =   P.CD_PACIENTE
                                                   AND  A.CD_PRESTADOR       =   PR.CD_PRESTADOR
                                                   AND  A.CD_LEITO           =   L.CD_LEITO
                                                   AND  A.TP_ATENDIMENTO     =   'I'
                                                   AND  L.CD_UNID_INT        IN   (7)
                                                   AND  A.DT_ALTA            IS NULL
                                                   AND  P.CD_PACIENTE        <> 1412335



                                            ) A 


                                           ,(

                                           SELECT   P.NM_PACIENTE 
                                                                                           ,PM.CD_PRE_MED 
                                                                                           ,PM.DH_CRIACAO DATA 
                                                                                     FROM   ATENDIME A 
                                                                                           ,PRE_MED  PM 
                                                                                           ,PACIENTE P 
                                                                                    WHERE   A.CD_ATENDIMENTO  = PM.CD_ATENDIMENTO 
                                                                                      AND   A.CD_PACIENTE     = P.CD_PACIENTE 
                                                                                      AND   A.TP_ATENDIMENTO  = 'I' 
                                                                                      AND   PM.CD_OBJETO      IN (1,22,54)   
                                                                                      AND   A.DT_ALTA  IS NULL 


                                           ) PRESC





                                        ,( 
                                                                                  SELECT  
                                                                                            PAR.CD_ATENDIMENTO 
                                                                                          ,CASE  
                                                                                             WHEN (PAR.TOTAL - SOL.TOTAL) >= 0 
                                                                                               THEN 'vermelha' 
                                                                                             ELSE 
                                                                                                    'verde' 
                                                                                           END STATUS 
                                                                                      FROM  ( 
                                                                                               SELECT  PM.CD_ATENDIMENTO 
                                                                                                      ,COUNT(1)            TOTAL 
                                                                                                 FROM  DBAMV.PAR_MED       PM 
                                                                                                      ,ACAO_PARECER_MEDICO A 
                                                                                                      ,DBAMV.ESPECIALID    E 
                                                                                                WHERE  A.CD_PAR_MED      =  PM.CD_PAR_MED 
                                                                                                  AND  E.CD_ESPECIALID   =  PM.CD_ESPECIALID 
                                                                                                  AND  A.SN_CANCELADO    =  'N' 
                                                                                                  AND  A.DH_ACAO        >= SYSDATE - 7 

                                                                                             GROUP BY PM.CD_ATENDIMENTO) PAR 
                                                                                             ,(SELECT  PM.CD_ATENDIMENTO 
                                                                                                      ,COUNT(1)            TOTAL 
                                                                                                 FROM  DBAMV.PAR_MED       PM 
                                                                                                      ,ACAO_PARECER_MEDICO A 
                                                                                                      ,DBAMV.ESPECIALID    E 
                                                                                                WHERE  A.CD_PAR_MED      =  PM.CD_PAR_MED 
                                                                                                  AND  E.CD_ESPECIALID   =  PM.CD_ESPECIALID 
                                                                                                  AND  PM.DS_SITUACAO    IN ('Solicitado','Em Análise') 
                                                                                                  AND  A.SN_CANCELADO    =  'N' 
                                                                                                  AND  A.TP_ACAO         =  'SOL' 
                                                                                                  AND  A.DH_ACAO        >= SYSDATE - 7 

                                                                                             GROUP BY PM.CD_ATENDIMENTO) SOL 
                                                                                        WHERE PAR.CD_ATENDIMENTO = SOL.CD_ATENDIMENTO(+) 
                                                                               )  C   





                                   ,(SELECT DISTINCT ISO_CCIH.CD_ATENDIMENTO
                                                   ,ISO_CCIH.PACIENTE
                                                   ,ISO_ENF.CD_ATENDIMENTO

                                                   ,CASE 
                                                       WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NULL AND FIM_ISOLAMENTO IS NULL
                                                         THEN 'amarela' 
                                                       WHEN ISO_ENF.CD_ATENDIMENTO IS NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL AND FIM_ISOLAMENTO IS NULL
                                                         THEN 'verde'
                                                       WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL AND FIM_ISOLAMENTO IS NULL
                                                         THEN 'verde'
                                                     END STATUS_ISO 

                                                   ,CASE 
                                                      WHEN ISO_ENF.CD_ATENDIMENTO IS NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL
                                                        THEN ISO_CCIH.CD_ATENDIMENTO
                                                      WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NOT NULL
                                                        THEN ISO_CCIH.CD_ATENDIMENTO
                                                      WHEN ISO_ENF.CD_ATENDIMENTO IS NOT NULL AND ISO_CCIH.CD_ATENDIMENTO IS NULL
                                                        THEN ISO_ENF.CD_ATENDIMENTO
                                                     END ATENDIME  
                                      FROM (



                                              SELECT A.CD_ATENDIMENTO
                                                    ,PACIENTE
                                                    ,TP_ISOLAMENTO
                                                    ,FIM_ISOLAMENTO
                                                    ,ident
                                                FROM 


                                                        (SELECT  PAC.NM_PACIENTE  PACIENTE
                                                                ,DECODE(D.DS_CAMPO,'isolamento_goticulas','Isolamento de Gotículas'
                                                                                  ,'Isolamento_Goticula','Isolamento de Gotículas'
                                                                                  ,'isolamento_repiratorio','Isolamento Respiratório'
                                                                                  ,'Isolamento_Respiratorio','Isolamento Respiratório'
                                                                                  ,'isolamento_contato','Isolamento de Contato'
                                                                                  ,'Isolamento_Contato','Isolamento de Contato'
                                                                                  ,'isolamento_Imunodeprimido','Isolamento Imunodeprimido'
                                                                                  ,'Isolamento_Imunodeprimido','Isolamento Imunodeprimido') TP_ISOLAMENTO
                                                                                  ,d.ds_campo
                                                                ,a.cd_documento_clinico
                                                                ,DBMS_LOB.SUBSTR(LO_VALOR)
                                                                ,E.CD_ATENDIMENTO
                                                           FROM  pw_editor_clinico           a
                                                                ,dbamv.editor_documento      b 
                                                                ,editor_registro_campo       c
                                                                ,editor_campo                d
                                                                ,dbamv.pw_documento_clinico  e
                                                                ,DBAMV.PACIENTE              PAC


                                                         WHERE  a.cd_documento           =   b.cd_documento
                                                           AND  a.cd_editor_registro     =   c.cd_registro(+)
                                                           AND  c.cd_campo               =   d.cd_campo(+)
                                                           AND  a.cd_documento_clinico   =   e.cd_documento_clinico
                                                           AND  E.CD_PACIENTE            =   PAC.CD_PACIENTE
                                                           AND  E.TP_STATUS              =   'FECHADO'
                                                           and  a.cd_documento           =   34
                                                           and  d.cd_campo in (80615,80613,80612,80611,80753,80759,80757,80755)
                                                           and  DBMS_LOB.SUBSTR(LO_VALOR) = 'true'

                                                           ) A 


                                                        ,(SELECT  TO_DATE(DBMS_LOB.SUBSTR(LO_VALOR),'DD/MM/YYYY') FIM_ISOLAMENTO
                                                                 ,a.cd_documento_clinico
                                                           FROM   pw_editor_clinico           a
                                                                 ,dbamv.editor_documento      b 
                                                                 ,editor_registro_campo       c
                                                                 ,editor_campo                d
                                                                 ,dbamv.pw_documento_clinico  e
                                                                 ,DBAMV.PACIENTE              PAC


                                                           WHERE  a.cd_documento           =   b.cd_documento
                                                             AND  a.cd_editor_registro     =   c.cd_registro(+)
                                                             AND  c.cd_campo               =   d.cd_campo(+)
                                                             AND  a.cd_documento_clinico   =   e.cd_documento_clinico
                                                             AND  E.CD_PACIENTE            =   PAC.CD_PACIENTE
                                                             AND  E.TP_STATUS              =   'FECHADO'
                                                             and  a.cd_documento           =   34
                                                             and  d.cd_campo in (80618,80739) ) D  


                                                           ,(SELECT distinct 
                                                                    (e.cd_atendimento) 
                                                                    ,MAX(a.cd_documento_clinico) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO) cd_documento_clinico
                                                                    ,MAX(DBMS_LOB.SUBSTR(LO_VALOR)) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO) ident
                                                                    ,MAX(DH_CRIACAO) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO) criacao


                                                               FROM  pw_editor_clinico           a
                                                                    ,dbamv.editor_documento      b 
                                                                    ,editor_registro_campo       c
                                                                    ,editor_campo                d
                                                                    ,dbamv.pw_documento_clinico  e


                                                              WHERE  a.cd_documento           =   b.cd_documento
                                                                AND  a.cd_editor_registro     =   c.cd_registro(+)
                                                                AND  c.cd_campo               =   d.cd_campo(+)
                                                                AND  a.cd_documento_clinico   =   e.cd_documento_clinico
                                                                AND  E.TP_STATUS              =   'FECHADO'
                                                                and  a.cd_documento           =   34
                                                                and  d.cd_campo in (80622,80741)

                                                           group by e.cd_atendimento, DBMS_LOB.SUBSTR(LO_VALOR) ) F 

                                                  WHERE A.CD_DOCUMENTO_CLINICO = D.CD_DOCUMENTO_CLINICO
                                                    and A.CD_DOCUMENTO_CLINICO = F.CD_DOCUMENTO_CLINICO           


                                                   AND FIM_ISOLAMENTO IS NULL ) ISO_CCIH

                                             ,(                          
                                               SELECT PM.CD_ATENDIMENTO
                                                 FROM TIP_PRESC TP
                                                     ,ITPRE_MED IPM
                                                     ,PRE_MED   PM
                                                WHERE PM.CD_PRE_MED     = IPM.CD_PRE_MED
                                                  AND TP.CD_TIP_PRESC   = IPM.CD_TIP_PRESC
                                                  AND IPM.SN_CANCELADO  = 'N'
                                                  AND TP.CD_TIP_PRESC   = 14194
                                               ) ISO_ENF 

                                              ,( SELECT  A.CD_ATENDIMENTO
                                                               ,P.NM_PACIENTE
                                                               ,L.DS_RESUMO DS_LEITO
                                                               ,PR.NM_MNEMONICO NM_PRESTADOR
                                                               ,A.DT_ATENDIMENTO
                                                          FROM  DBAMV.ATENDIME      A
                                                               ,DBAMV.PACIENTE      P
                                                               ,DBAMV.LEITO         L
                                                               ,DBAMV.PRESTADOR     PR

                                                         WHERE  A.CD_PACIENTE        =   P.CD_PACIENTE
                                                           AND  A.CD_PRESTADOR       =   PR.CD_PRESTADOR
                                                           AND  A.CD_LEITO           =   L.CD_LEITO
                                                           AND  A.TP_ATENDIMENTO     =   'I'
                                                           AND  A.DT_ALTA            IS NULL


                                                         ) INTER 


                                       WHERE INTER.CD_ATENDIMENTO = ISO_CCIH.CD_ATENDIMENTO(+)
                                         AND INTER.CD_ATENDIMENTO = ISO_ENF.CD_ATENDIMENTO(+)

                                         AND (ISO_ENF.CD_ATENDIMENTO IS NOT NULL OR ISO_CCIH.CD_ATENDIMENTO IS NOT NULL)) G 






                             ,(

                                     SELECT 
                                           CD_ATENDIMENTO
                                          ,A.NM_PACIENTE     PACIENTE
                                          ,SUBSTR(A.ITEM,4)            JEJUM
                                          ,to_CHAR(A.DATA_PRESC,'DD/MM/YYYY HH24:MI:SS')         INICIO_JEJUM

                                                   ,CASE 
                                                         WHEN (A.ITEM) = '10||INICIAR JEJUM ULTRASSONOGRAFIA' AND (SYSDATE-DATA_PRESC) <= 0.1666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '10||INICIAR JEJUM ULTRASSONOGRAFIA' AND (SYSDATE-DATA_PRESC) BETWEEN 0.1666666666 AND 0.2083333333 

                                                         THEN 'amarela'
                                                         WHEN (A.ITEM) = '10||INICIAR JEJUM ULTRASSONOGRAFIA' AND (SYSDATE-DATA_PRESC) > 0.2083333333 

                                                         THEN 'vermelha'

                                                         WHEN (A.ITEM) = '9||INICIAR JEJUM TOMOGRAFIA COMPUTADORIZADA' AND (SYSDATE-DATA_PRESC) <= 0.29166666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '9||INICIAR JEJUM TOMOGRAFIA COMPUTADORIZADA' AND (SYSDATE-DATA_PRESC) BETWEEN 0.29166666666666 AND 0.33333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '9||INICIAR JEJUM TOMOGRAFIA COMPUTADORIZADA' AND (SYSDATE-DATA_PRESC) > 0.33333333333333 

                                                          THEN 'vermelha'

                                                         WHEN (A.ITEM) = '8||INICIAR JEJUM RESSONANCIA MAGNETICA' AND (SYSDATE-DATA_PRESC) <= 0.29166666666666

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '8||INICIAR JEJUM RESSONANCIA MAGNETICA' AND (SYSDATE-DATA_PRESC) BETWEEN 0.29166666666666 AND 0.33333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '8||INICIAR JEJUM RESSONANCIA MAGNETICA' AND (SYSDATE-DATA_PRESC) > 0.33333333333333 

                                                           THEN 'amarela'

                                                         WHEN (A.ITEM) = '7||INICIAR JEJUM RADIOLOGIA VASCULAR'  AND (SYSDATE-DATA_PRESC) <= 0.5416666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '7||INICIAR JEJUM RADIOLOGIA VASCULAR'  AND (SYSDATE-DATA_PRESC) BETWEEN 0.5416666666666 AND 0.58333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '7||INICIAR JEJUM RADIOLOGIA VASCULAR'  AND (SYSDATE-DATA_PRESC) > 0.58333333333333 

                                                           THEN 'vermelha'

                                                         WHEN (A.ITEM) = '1||INICIAR JEJUM COLANGIORESSONANCIA'  AND (SYSDATE-DATA_PRESC) <= 0.5416666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '1||INICIAR JEJUM COLANGIORESSONANCIA'  AND (SYSDATE-DATA_PRESC) BETWEEN 0.5416666666666 AND 0.58333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '1||INICIAR JEJUM COLANGIORESSONANCIA'  AND (SYSDATE-DATA_PRESC) > 0.58333333333333 

                                                          THEN 'vermelha'

                                                         WHEN (A.ITEM) = '2||INICIAR JEJUM COLONOSCOPIA'  AND (SYSDATE-DATA_PRESC) <= 0.416666666666 

                                                         THEN 'verde'
                                                         WHEN (A.ITEM) = '2||INICIAR JEJUM COLONOSCOPIA'  AND (SYSDATE-DATA_PRESC) BETWEEN 0.416666666666 AND 0.458333333333333 

                                                         THEN 'amarela'
                                                         WHEN (A.ITEM) = '2||INICIAR JEJUM COLONOSCOPIA'  AND (SYSDATE-DATA_PRESC) > 0.458333333333333 

                                                        THEN 'vermelha'

                                                         WHEN (A.ITEM) = '3||INICIAR JEJUM ENDOSCOPIA'   AND (SYSDATE-DATA_PRESC) <= 0.416666666666 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '3||INICIAR JEJUM ENDOSCOPIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.416666666666 AND 0.458333333333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '3||INICIAR JEJUM ENDOSCOPIA'   AND (SYSDATE-DATA_PRESC) > 0.458333333333333 

                                                           THEN 'vermelha'

                                                          WHEN (A.ITEM) =  '5||INICIAR JEJUM PROCEDIMENTO CIRURGICO (OUTROS)'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333

                                                        THEN 'verde'
                                                        WHEN (A.ITEM) =  '5||INICIAR JEJUM PROCEDIMENTO CIRURGICO (OUTROS)'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 

                                                          THEN 'amarela'
                                                         WHEN (A.ITEM) =  '5||INICIAR JEJUM PROCEDIMENTO CIRURGICO (OUTROS)'   AND (SYSDATE-DATA_PRESC) > 0.625 

                                                           THEN 'vermelha'

                                                         WHEN (A.ITEM) = '6||INICIAR JEJUM PROCEDIMENTO CIRURGICO DO TRATO GASTRO'  AND (SYSDATE-DATA_PRESC) <= 1 

                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '6||INICIAR JEJUM PROCEDIMENTO CIRURGICO DO TRATO GASTRO'  AND (SYSDATE-DATA_PRESC) BETWEEN 1 AND 1.083333333 

                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '6||INICIAR JEJUM PROCEDIMENTO CIRURGICO DO TRATO GASTRO'  AND (SYSDATE-DATA_PRESC) > 1.0833333333 

                                                           THEN 'vermelha'

                                                        WHEN (A.ITEM) = '4||INICIAR JEJUM OUTROS MOTIVOS' 

                                                           THEN 'vermelha'
                                                             
                                                       
                                                         WHEN (A.ITEM) = '11||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CIRURGIA GERAL'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '11||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CIRURGIA GERAL'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '11||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CIRURGIA GERAL'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '12||INICIAR JEJUM PROCEDIMENTO CIRURGICO - ORTOPEDIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '12||INICIAR JEJUM PROCEDIMENTO CIRURGICO - ORTOPEDIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '12||INICIAR JEJUM PROCEDIMENTO CIRURGICO - ORTOPEDIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '13||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CARDIOLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '13||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CARDIOLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '13||INICIAR JEJUM PROCEDIMENTO CIRURGICO - CARDIOLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '16||INICIAR JEJUM PROCEDIMENTO CIRURGICO - UROLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '16||INICIAR JEJUM PROCEDIMENTO CIRURGICO - UROLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '16||INICIAR JEJUM PROCEDIMENTO CIRURGICO - UROLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '15||INICIAR JEJUM PROCEDIMENTO CIRURGICO - GINECOLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '15||INICIAR JEJUM PROCEDIMENTO CIRURGICO - GINECOLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '15||INICIAR JEJUM PROCEDIMENTO CIRURGICO - GINECOLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha' 
                                                             
                                                           
                                                           
                                                         WHEN (A.ITEM) = '14||INICIAR JEJUM PROCEDIMENTO CIRURGICO - NEUROLOGIA'   AND (SYSDATE-DATA_PRESC) <= 0.583333333333 -- 14 HORAS
                                                           THEN 'verde'
                                                         WHEN (A.ITEM) = '14||INICIAR JEJUM PROCEDIMENTO CIRURGICO - NEUROLOGIA'   AND (SYSDATE-DATA_PRESC) BETWEEN 0.583333333333 AND 0.625 -- ENTRE 14 E 15 HORAS
                                                           THEN 'amarela'
                                                         WHEN (A.ITEM) = '14||INICIAR JEJUM PROCEDIMENTO CIRURGICO - NEUROLOGIA'   AND (SYSDATE-DATA_PRESC) > 0.625 -- MAIOR QUE 15 HORAS
                                                           THEN 'vermelha'
                                                     END SITUACAO






                                      FROM (                   

                                         select A.CD_ATENDIMENTO
                                               ,A.NM_PACIENTE
                                               ,TO_DATE(A.DATA||' '||B.HORA||' '||MINUTO,'DD/MM/YYYY HH24:MI:SS') DATA_PRESC
                                               ,ITEM
                                               ,A.CRIACAO
                                           from (  

                                                             SELECT    p.nm_paciente
                                                                     ,F.CD_ATENDIMENTO
                                                                     ,MAX(DBMS_LOB.substr(lo_valor))  KEEP (DENSE_RANK LAST ORDER BY E.DH_CRIACAO) DATA
                                                                     ,MAX(a.cd_documento_clinico) KEEP (DENSE_RANK LAST ORDER BY E.DH_CRIACAO) CD_DOCUMENTO_CLINICO
                                                                     ,MAX(E.DH_CRIACAO)              CRIACAO
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  d.cd_metadado              in (63512)

                                                                 GROUP BY P.NM_PACIENTE,F.CD_ATENDIMENTO


                                                   ) a




                                                   ,(

                                                    SELECT  p.nm_paciente
                                                                     ,DBMS_LOB.substr(lo_valor) HORA
                                                                     ,a.cd_documento_clinico
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  d.cd_metadado              in (63513)
                                                                                                  

                                                   ) b


                                                   ,(

                                                    SELECT  p.nm_paciente
                                                                     ,DBMS_LOB.substr(lo_valor) minuto
                                                                     ,a.cd_documento_clinico
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  d.cd_metadado              in (63515)


                                                   ) c



                                                   ,(

                                                    SELECT  p.nm_paciente
                                                                     ,DBMS_LOB.substr(lo_valor) ITEM
                                                                     ,a.cd_documento_clinico
                                                                FROM  pw_editor_clinico           a
                                                                     ,dbamv.editor_documento      b 
                                                                     ,editor_registro_campo       c
                                                                     ,editor_campo                d
                                                                     ,dbamv.pw_documento_clinico  e
                                                                     ,atendime f
                                                                     ,paciente p
                                                               WHERE  f.cd_paciente              =     p.cd_paciente
                                                                 and  a.cd_documento             =     b.cd_documento
                                                                 AND  a.cd_editor_registro       =     c.cd_registro(+)
                                                                 AND  c.cd_campo                 =     d.cd_campo(+)
                                                                 and  f.cd_atendimento           =     e.cd_atendimento
                                                                 AND  a.cd_documento_clinico     =     e.cd_documento_clinico
                                                                 and  e.tp_status                =     'FECHADO'
                                                                 and  a.cd_documento             =     195
                                                                 and  e.dh_criacao              >=     sysdate-2
                                                                 and  d.cd_metadado              in (63514)

                                                   ) d





                                              WHERE A.NM_PACIENTE = B.NM_PACIENTE
                                                AND A.NM_PACIENTE = C.NM_PACIENTE
                                                AND A.NM_PACIENTE = D.NM_PACIENTE
                                                and a.cd_documento_clinico = b.cd_documento_clinico
                                                AND a.cd_documento_clinico = C.CD_DOCUMENTO_CLINICO
                                                AND a.cd_documento_clinico = D.CD_DOCUMENTO_CLINICO


                                          ) A


                                          ,(

                                               SELECT  P.NM_PACIENTE
                                                      ,MAX(TP.DS_TIP_PRESC) ITEM
                                                      ,MAX(TP.CD_TIP_PRESC) CD_ITEM
                                                      ,MAX(IPM.DH_INICIAL) DATA

                                                 FROM  PRE_MED   PM
                                                      ,ITPRE_MED IPM
                                                      ,DBAMV.TIP_PRESC TP
                                                      ,DBAMV.ATENDIME  A
                                                      ,DBAMV.PACIENTE  P


                                                WHERE  PM.CD_PRE_MED    = IPM.CD_PRE_MED
                                                  AND TP.CD_TIP_PRESC   = IPM.CD_TIP_PRESC
                                                  AND PM.CD_ATENDIMENTO = A.CD_ATENDIMENTO
                                                  AND A.CD_PACIENTE     = P.CD_PACIENTE
                                                  AND PM.DH_CRIACAO    >= sysdate-2
                                                  AND TP.CD_TIP_PRESC   IN  (13662)
                                                  GROUP BY P.NM_PACIENTE
                                                  ORDER BY 2 DESC
                                          ) B

                                          WHERE A.NM_PACIENTE = B.NM_PACIENTE(+)
                                            AND (B.CD_ITEM IS NULL OR A.CRIACAO > B.DATA)
                                       

                                       ) H    





                                        ,(

                            SELECT ATENDIMENTO
                                  ,MAX(PROXIMA) PROXIMA
                                  ,MAX(SITUACAO)KEEP (DENSE_RANK LAST ORDER BY PROXIMA) SITUACAO
                             FROM (

                             SELECT ATENDIMENTO
                                   ,PROXIMA
                                                  ,CASE
                                                      WHEN (SYSDATE) > PROXIMA-(0.5/24) AND SYSDATE <= PROXIMA

                                                        THEN 'amarela'
                                                      WHEN (SYSDATE) < PROXIMA-(0.5/24)

                                                        THEN 'verde'
                                                      WHEN (SYSDATE) > PROXIMA

                                                        THEN 'vermelha'            
                                                   END SITUACAO

                                              FROM (


                                            SELECT ESCORE
                                                  ,DATA_COLETA
                                                  ,TP_ATENDIMENTO
                                                  ,ATENDIMENTO
                                                  ,CASE
                                                     WHEN ESCORE IN (0,1,2)
                                                       THEN DATA_COLETA+(4/24)
                                                     WHEN ESCORE IN (3,4)
                                                       THEN DATA_COLETA+(2/24)
                                                     WHEN ESCORE IN (5,6)
                                                       THEN DATA_COLETA+(1/24)
                                                     WHEN ESCORE IN (7,8,9,10,11,12,13,14,15)
                                                       THEN DATA_COLETA+(0.5/24)
                                                    END PROXIMA


                                              FROM



                                            (SELECT  
                                                   SUM(TEMP) + SUM(PA_SIS) + SUM(FREQ_RES) + SUM(FREQ_CARD) + SUM(NV_CON) ESCORE
                                                   ,DATA_COLETA
                                                   ,TP_ATENDIMENTO
                                                   ,ATENDIMENTO

                                              FROM (

                                            SELECT ICSV.CD_COLETA_SINAL_VITAL COD_COLETA
                                                   ,A.CD_ATENDIMENTO ATENDIMENTO            
                                                   ,P.NM_PACIENTE    PACIENTE
                                                   ,CSV.DATA_COLETA
                                                   ,A.TP_ATENDIMENTO
                                                   ,CASE
                                                      WHEN  ((SV.CD_SINAL_VITAL) = 1 AND (VALOR) <= 35)
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 1 AND (VALOR) BETWEEN 35.1 AND 37.8
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 1 AND (VALOR) > 37.8
                                                        THEN 2
                                                   END TEMP


                                                  ,CASE
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) < 70
                                                        THEN 3
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) BETWEEN 71 AND 80
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) BETWEEN 81 AND 100
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) BETWEEN 101 AND 199
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 4 AND (VALOR) >= 200
                                                        THEN 2
                                                   END PA_SIS


                                                   ,CASE
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) < 9
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) BETWEEN 10 AND 14
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) BETWEEN 15 AND 20
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) BETWEEN 21 AND 29
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 3 AND (VALOR) >= 30
                                                        THEN 3
                                                   END FREQ_RES
                                                    ,CASE
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) < 40
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 40 AND 50
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 51 AND 100
                                                        THEN 0
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 101 AND 110
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) BETWEEN 111 AND 120
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 2 AND (VALOR) > 120
                                                        THEN 3
                                                    END FREQ_CARD

                                                    ,CASE
                                                     WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 24
                                                        THEN 3
                                                      WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 25
                                                        THEN 2
                                                      WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 26
                                                        THEN 1
                                                      WHEN (SV.CD_SINAL_VITAL) = 79 AND (CD_UNIDADE_AFERICAO) = 23
                                                        THEN 0
                                                    END NV_CON
                                              FROM DBAMV.COLETA_SINAL_VITAL   CSV
                                                  ,DBAMV.ITCOLETA_SINAL_VITAL ICSV
                                                  ,DBAMV.SINAL_VITAL          SV
                                                  ,ATENDIME                   A
                                                  ,PACIENTE                   P
                                             WHERE CSV.CD_COLETA_SINAL_VITAL    =   ICSV.CD_COLETA_SINAL_VITAL
                                               AND SV.CD_SINAL_VITAL            =   ICSV.CD_SINAL_VITAL
                                               AND CSV.CD_ATENDIMENTO           =   A.CD_ATENDIMENTO
                                               AND P.CD_PACIENTE                =   A.CD_PACIENTE
                                               AND CSV.SN_FINALIZADO            =   'S'


                                            )
                                            GROUP BY DATA_COLETA,TP_ATENDIMENTO,ATENDIMENTO
                                            )

                                            WHERE ESCORE IS NOT NULL
                                              AND TP_ATENDIMENTO = 'I'
                                            

                                       )
                                   )

                            GROUP BY ATENDIMENTO         

                                        ) J      
                                        
                                    
                            
                            
                             ,(
                                      SELECT DISTINCT D.CD_ATENDIMENTO
                                        FROM DBAMV.PED_LAB   F
                                            ,DBAMV.ITPED_LAB G
                                            ,DBAMV.EXA_LAB   H
                                            ,DBAMV.ATENDIME   D              
                                       WHERE F.CD_PED_LAB     = G.CD_PED_LAB
                                         AND H.CD_EXA_LAB     = G.CD_EXA_LAB
                                         AND F.CD_ATENDIMENTO = D.CD_ATENDIMENTO       
                                         AND H.CD_EXA_LAB IN (1483,1484,1485,1486,1470,1471,1472,1473,1474,1475,1476,1477,1478,1479,1480,1481,1482)

                                         ) exa
                                   
                             ,(
                                     SELECT ATEND
       
                                            ,CASE
                                                WHEN (DATA_ATUAL) < PREV_ALTA-1
                                                  THEN 'verde'
                                                WHEN (DATA_ATUAL) = PREV_ALTA-1 OR (DATA_ATUAL) = PREV_ALTA
                                                  THEN 'amarela'
                                                WHEN (DATA_ATUAL) > PREV_ALTA
                                                  THEN 'vermelha'
                                             END STATUS   

                                        FROM (

                                      SELECT  e.cd_atendimento ATEND
                                             ,to_date(MAX(DBMS_LOB.SUBSTR(LO_VALOR)) KEEP (DENSE_RANK LAST ORDER BY DH_CRIACAO),'dd/mm/yyyy') prev_alta
                                             ,to_date(sysdate) DATA_ATUAL
                                        
                                        FROM  pw_editor_clinico           a
                                             ,dbamv.editor_documento      b 
                                             ,editor_registro_campo       c
                                             ,editor_campo                d
                                             ,dbamv.pw_documento_clinico  e
                                             
                                       WHERE a.cd_documento           =   b.cd_documento
                                         AND a.cd_editor_registro     =   c.cd_registro(+)
                                         AND c.cd_campo               =   d.cd_campo(+)
                                         AND a.cd_documento_clinico   =   e.cd_documento_clinico
                                         AND E.TP_STATUS              =   'FECHADO'
                                         and a.cd_documento           =   178
                                         and cd_metadado              =   50738
                                      --   AND TRUNC(E.DH_REFERENCIA) > '01/01/2016'
                                            AND E.CD_ATENDIMENTO NOT IN (352665)
                                         
                                         and DBMS_LOB.SUBSTR(LO_VALOR) is not null
                                         
                                       GROUP BY E.CD_ATENDIMENTO)
   
                                      
                                    ) PREV



                                      WHERE A.CD_ATENDIMENTO =  G.ATENDIME(+)
                                        AND A.NM_PACIENTE = PRESC.NM_PACIENTE(+)
                                        AND A.CD_ATENDIMENTO =  C.CD_ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO =  H.CD_ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO =  J.ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO = EXA.CD_ATENDIMENTO(+)
                                        AND A.CD_ATENDIMENTO = PREV.ATEND(+)
                                   GROUP BY A.NM_PACIENTE    ,A.CD_ATENDIMENTO
                                           ,A.DS_LEITO       ,A.NM_PRESTADOR
                                           ,A.DT_ATENDIMENTO ,C.STATUS
                                           ,G.STATUS_ISO     ,EXA.CD_ATENDIMENTO
                                           ,H.SITUACAO       ,PREV.STATUS
                                           ,J.SITUACAO  
                                           ,A.PLACE 
                                    ) A   


                       )Z 
                   ORDER BY 3
            )  GERAL              
          )           
                                             WHERE NUMERO BETWEEN 0 AND 20
                                                                      
                      ";
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