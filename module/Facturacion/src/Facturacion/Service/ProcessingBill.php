<?php
/**
 * Description of Processing Bill Service
 * @author Euler Nuñez
 */
// module/Facturacion/src/Facturacion/Service/ProcessingBill.php

namespace Facturacion\Service;
use Facturacion\Service\XLSXWriter;
use Buscador\Model\Service;

class ProcessingBill extends Service {

    protected $adapter;
    protected $filePath;
    protected $time;
    protected $periodo;
    protected $clientScopeFilter;
    protected $objPHPExcel;
    protected $cc;
    protected $aicc;
    protected $backbone;
    protected $backboneR;
    protected $backboneSan;
    protected $backboneS;
    protected $oec3;
    protected $oec3Ens;
    protected $months;
    
    protected $posts;

    public function __construct() {
        parent::__construct();
        $this->objPHPExcel = new \PHPExcel();
        $this->time = time();
        $this->clientScopeFilter = "";
        if('Cliente' == $this->userRole) {
            $this->clientScopeFilter = " AND id_cif_cliente ='" . $this->nif . "'";
        }
        
        $this->cc['xb'] = 2;
        $this->cc['xem'] = 3;
        $this->cc['xic'] = 4;
        $this->cc['xp'] = 5;
        $this->cc['xorgt'] = 13;
        
        $this->aicc['xb'] = 0.4;
        $this->aicc['xem'] = 0.4;
        $this->aicc['xic'] = 0.2;
        $this->aicc['xp'] = 0.0;
        $this->aicc['xorgt'] = 0.0;
        
        $this->backbone['xb'] = 0.69;
        $this->backbone['xem'] = 0.09;
        $this->backbone['xic'] = 0.01;
        $this->backbone['xp'] = 0.01;
        $this->backbone['xorgt'] = 0.2;
        
        $this->backboneR['xb'] = 0.4926;
        $this->backboneR['xem'] = 0.0432;
        $this->backboneR['xic'] = 0.0643;
        $this->backboneR['xp'] = 0.0143;
        $this->backboneR['xorgt'] = 0.3856;
     
        $this->backboneSan['xb'] = 0.735;
        $this->backboneSan['xem'] = 0.25;
        $this->backboneSan['xic'] = 0.015;
        $this->backboneSan['xp'] = 0.0;
        $this->backboneSan['xorgt'] = 0.0;
                
        $this->backboneS['xb'] = 0.735;
        $this->backboneS['xem'] = 0.25;
        $this->backboneS['xic'] = 0.015;
        $this->backboneS['xp'] = 0.0;
        $this->backboneS['xorgt'] = 0.0;

        $this->oec3['xb'] = 0.525;
        $this->oec3['xem'] = 0.22;
        $this->oec3['xic'] = 0.04;
        $this->oec3['xp'] = 0.015;
        $this->oec3['xorgt'] = 0.2;
        
        $this->oec3Ens['xb'] = 0.0;
        $this->oec3Ens['xem'] = 1.0;
        $this->oec3Ens['xic'] = 0.0;
        $this->oec3Ens['xp'] = 0.0;
        $this->oec3Ens['xorgt'] = 0.0;
        
        $this->months['01'] = 'gener';
        $this->months['02'] = 'febrer';
        $this->months['03'] = 'març';
        $this->months['04'] = 'abril';
        $this->months['05'] = 'maig';
        $this->months['06'] = 'juny';
        $this->months['07'] = 'juliol';
        $this->months['08'] = 'agost';
        $this->months['09'] = 'setembre';
        $this->months['10'] = 'octubre';
        $this->months['11'] = 'novembre';
        $this->months['12'] = 'desembre';
        
    }

    public function setAdapter($adapter) {
        $this->adapter = $adapter;
        return $this;
    }

    public function setFilePath($file) {
        $this->filePath = $file;
        return $this;
    }
    
    public function dataValidation($periodo) {

        $this->periodo = $periodo . '28';

        $statement = 
            "UPDATE `facturacion` 
                SET estado = 0 WHERE estado = 1 AND id_fecha_fact = $this->periodo";

        $adapter = $this->adapter->query($statement);
        $adapter->execute();

        $statement2 = 
            "UPDATE `billing` 
                SET estado = 0 WHERE estado = 1 AND id_fecha_fact = $this->periodo";

        $adapter2 = $this->adapter->query($statement2);
        $adapter2->execute();

        return $this;

    }
    
    
    

    public function fileUpload() {

        $statement = 
            "LOAD DATA
            INFILE '$this->filePath'
            INTO TABLE facturacion
            CHARACTER SET utf8
            FIELDS TERMINATED BY '\t'
            IGNORE 1 LINES
            (id_fecha_fact, id_cif_cliente, id_cargo, id_tipo_servicio,desc_tipo_servicio,id_multiconexion,@id_numero_comercial,id_codigo_concepto,
            desc_codigo_concepto,id_concepto_facturable,desc_concepto_facturable, f_unidades, f_inicio_periodo, f_fin_periodo, 
            @f_importe_total,@f_importe_estandard,@f_importe_distancia,@f_descuentos)
            SET id_numero_comercial =
                CASE
                    WHEN length(@id_numero_comercial) = 14 and substr(@id_numero_comercial, 1,5) = 'AD000'
                        THEN substr(@id_numero_comercial,6,9)
                    WHEN length(@id_numero_comercial) = 16
                        THEN concat(substr(@id_numero_comercial, 1,6),substr(@id_numero_comercial, 9,8))
                ELSE
                    @id_numero_comercial
                END,
            f_importe_total = replace(@f_importe_total,',','.'),
            f_importe_estandard = replace(@f_importe_estandard,',','.'),
            f_importe_distancia = replace(@f_importe_distancia,',','.'),
            f_descuentos = replace(@f_descuentos,',','.'),
            id = null,
            time ='$this->time',
            estado = 1";

        $adapter = $this->adapter->query($statement);
        $adapter->execute();

        return $this;

    }

    public function setDates() {
        
        $statement = 
            "UPDATE `facturacion` 
                SET f_inicio_periodo_date = f_inicio_periodo, 
                    f_fin_periodo_date = f_fin_periodo
                WHERE estado = 1";

        $adapter = $this->adapter->query($statement);
        $adapter->execute();
        
        return $this;

    }
    
    public function setDaysToPurchase() {
        
	$statement = 
	"UPDATE facturacion
	SET f_dias_fact =
	(SELECT
            CASE 
                WHEN (MONTH(F_INICIO_PERIODO_DATE)=MONTH(F_FIN_PERIODO_DATE)) THEN
                    CASE
                        when DAY(F_INICIO_PERIODO_DATE)=DAY(F_FIN_PERIODO_DATE) then 1
                        when DAY(F_INICIO_PERIODO_DATE)=1 AND DAY(F_FIN_PERIODO_DATE)=DATE_FORMAT(LAST_DAY(F_FIN_PERIODO_DATE),'%d') then 0 
                        when DAY(F_INICIO_PERIODO_DATE)=1 AND DAY(F_FIN_PERIODO_DATE) < DATE_FORMAT(LAST_DAY(F_FIN_PERIODO_DATE),'%d') 
                            then DAY(F_FIN_PERIODO_DATE)
                        when DAY(F_INICIO_PERIODO_DATE)>1 
                            then (DAY(F_FIN_PERIODO_DATE)-DAY(F_INICIO_PERIODO_DATE))+1			
                    END 
                WHEN (MONTH(F_INICIO_PERIODO_DATE) <> MONTH(F_FIN_PERIODO_DATE)) then
                    CASE 
                        when DAY(F_INICIO_PERIODO_DATE)=1 AND DAY(F_FIN_PERIODO_DATE)=DATE_FORMAT(LAST_DAY(F_FIN_PERIODO_DATE),'%d') then 0
                        when DAY(F_INICIO_PERIODO_DATE)=1 AND DAY(F_FIN_PERIODO_DATE)<DATE_FORMAT(LAST_DAY(F_FIN_PERIODO_DATE),'%d') 
                            then DATE_FORMAT(LAST_DAY(F_FIN_PERIODO_DATE),'%d')	
                        when DAY(F_INICIO_PERIODO_DATE)>1 AND DAY(F_FIN_PERIODO_DATE)=DATE_FORMAT(LAST_DAY(F_FIN_PERIODO_DATE),'%d') 
                            then (DATE_FORMAT(LAST_DAY(F_INICIO_PERIODO_DATE),'%d')-DAY(F_INICIO_PERIODO_DATE))+1	
                        when DAY(F_INICIO_PERIODO_DATE)>1 AND DAY(F_FIN_PERIODO_DATE)<DATE_FORMAT(LAST_DAY(F_FIN_PERIODO_DATE),'%d') 
                            then ((DATE_FORMAT(LAST_DAY(F_INICIO_PERIODO_DATE),'%d')-DAY(F_INICIO_PERIODO_DATE))+1) + DAY(F_FIN_PERIODO_DATE)
                    END 
            END 
	) WHERE estado = 1";

        $adapter = $this->adapter->query($statement);
        $adapter->execute();

        return $this;

    }
    
    public function setMonthsToPurchase() {
        
        
        $statement = 
            "UPDATE facturacion
            SET f_meses_fact =
            (SELECT
                CASE 
                    WHEN (MONTH(f_inicio_periodo_date) = MONTH(f_fin_periodo_date)) 
                        THEN
                            CASE
                                WHEN DAY(f_inicio_periodo_date) = DAY(f_fin_periodo_date) THEN 0
                                WHEN DAY(f_inicio_periodo_date) = 1 AND DAY(f_fin_periodo_date) = DATE_FORMAT(LAST_DAY(f_fin_periodo_date),'%d') THEN 1
                                WHEN DAY(f_inicio_periodo_date) = 1 AND DAY(f_fin_periodo_date) < DATE_FORMAT(LAST_DAY(f_fin_periodo_date),'%d') THEN 0
                                WHEN DAY(f_inicio_periodo_date) > 1 THEN 0
                            END
                    WHEN (MONTH(f_inicio_periodo_date) <> MONTH(f_fin_periodo_date)) 
                        THEN
                            CASE
                                WHEN DAY(f_inicio_periodo_date) = 1 AND DAY(f_fin_periodo_date) = DATE_FORMAT(LAST_DAY(f_fin_periodo_date),'%d') 
                                    THEN TIMESTAMPDIFF(MONTH,f_inicio_periodo_date,f_fin_periodo_date) + 1
                                WHEN DAY(f_inicio_periodo_date) = 1 AND DAY(f_fin_periodo_date) < DATE_FORMAT(LAST_DAY(f_fin_periodo_date),'%d') 
                                    THEN TIMESTAMPDIFF(MONTH,f_inicio_periodo_date,f_fin_periodo_date)
                                WHEN DAY(f_inicio_periodo_date) > 1 AND DAY(f_fin_periodo_date) = DATE_FORMAT(LAST_DAY(f_fin_periodo_date),'%d') 
                                    THEN 
                                        CASE
                                            WHEN DAY(f_inicio_periodo_date) = 31 THEN TIMESTAMPDIFF(MONTH,f_inicio_periodo_date,f_fin_periodo_date) + 1 /*OJO*/
                                            WHEN DAY(f_inicio_periodo_date) <> 31 THEN TIMESTAMPDIFF(MONTH,f_inicio_periodo_date,f_fin_periodo_date)
                                        END
                                WHEN DAY(f_inicio_periodo_date) > 1 AND DAY(F_FIN_PERIODO_DATE) < DATE_FORMAT(LAST_DAY(f_fin_periodo_date),'%d') 
                                    THEN 
                                        CASE
                                            WHEN (MONTH(f_fin_periodo_date) - MONTH(f_inicio_periodo_date))=1 THEN 0
                                            WHEN DAY(f_fin_periodo_date) >= DAY(f_inicio_periodo_date) THEN TIMESTAMPDIFF(MONTH,f_inicio_periodo_date,f_fin_periodo_date) - 1
                                            WHEN DAY(f_fin_periodo_date) < DAY(f_inicio_periodo_date) THEN TIMESTAMPDIFF(MONTH,f_inicio_periodo_date,f_fin_periodo_date)
                                        END
                            END
                END 
            ) WHERE estado = 1";

        $adapter = $this->adapter->query($statement);
        $adapter->execute();
        
        return $this;
        
    }
//round(((f_unidades * bb.f_precio_diario * aa.f_dias_fact)+(f_unidades*bb.f_precio_mensual*aa.f_meses_fact)),2)    
    public function billingLote3() {
        
	$statement = 
            "INSERT INTO billing 
                SELECT 
                    aa.id, 
                    aa.id_fecha_fact, 
                    aa.id_cif_cliente, 
                    cc.cif_titular_serv_lote, 
                    cc.desc_titular_serv_lote3, 
                    aa.desc_tipo_servicio, 
                    aa.id_multiconexion, 
                    aa.id_numero_comercial,
                    aa.id_codigo_concepto, 
                    aa.id_concepto_facturable, 
                    aa.desc_concepto_facturable, 
                    aa.f_unidades, 
                    aa.f_inicio_periodo, 
                    aa.f_fin_periodo, 
                    aa.f_importe_total,
                    bb.f_precio_mensual, 
                    bb.id_cod_cli, 
                    bb.desc_cod_cli, 
		    'LOTE3' lote, 
                    CASE
                        WHEN (aa.f_importe_total < 0)
                            THEN
                                -1 * (round(((f_unidades * (bb.f_precio_mensual/DAY(LAST_DAY(aa.f_inicio_periodo))) * aa.f_dias_fact)+(f_unidades*bb.f_precio_mensual*aa.f_meses_fact)),2))
                            ELSE
                                round(((f_unidades * (bb.f_precio_mensual/DAY(LAST_DAY(aa.f_inicio_periodo))) * aa.f_dias_fact)+(f_unidades*bb.f_precio_mensual*aa.f_meses_fact)),2)
                    END total,
                    aa.time,
                    aa.estado
                FROM
                    facturacion aa, 
                    catalogue bb, 
                    customers cc 
                WHERE aa.estado = 1 AND aa.id_concepto_facturable = bb.id_concepto_facturable AND aa.id_cif_cliente = cc.id_titular_serv_lote3";
        
        $adapter = $this->adapter->query($statement);
        $adapter->execute();
        
        return $this;

    }
    
    public function billingResto() {
        
	$statement = 
            "INSERT INTO billing 
        	SELECT
                    aa.id, 
                    aa.id_fecha_fact, 
                    aa.id_cif_cliente, 
                    cc.cif_titular_serv_lote, 
                    cc.desc_titular_serv_lote3, 
                    aa.desc_tipo_servicio, 
                    aa.id_multiconexion, 
                    aa.id_numero_comercial,
                    aa.id_codigo_concepto, 
                    aa.id_concepto_facturable, 
                    aa.desc_concepto_facturable, 
                    aa.f_unidades, 
                    aa.f_inicio_periodo, 
                    aa.f_fin_periodo, 
                    aa.f_importe_total,
                    '' f_precio, 
                    '' cod_cli, 
                    '' desc_cod_cli, 
                    'RESTO SERVICIOS' lote, 
                    ROUND(aa.f_importe_total,2) total,
                    aa.time,
                    aa.estado
                FROM
                    facturacion aa, 
                    customers cc 
                WHERE aa.estado = 1 AND aa.id_cif_cliente = cc.id_titular_serv_lote3 and aa.id_concepto_facturable 
                    NOT IN ( 
                            SELECT bb.id_concepto_facturable 
                            FROM catalogue bb
                            UNION ALL 
                            SELECT dd.id_concepto_facturable
                            FROM constraints dd
                            )";
        
        $adapter = $this->adapter->query($statement);
        $adapter->execute();

        return $this;
        
    }
    
    public function billingLote3Pending() {
        
        $statement = 
            "INSERT INTO billing 
                SELECT 
                    aa.id, 
                    aa.id_fecha_fact, 
                    aa.id_cif_cliente, 
                    cc.cif_titular_serv_lote, 
                    cc.desc_titular_serv_lote3, 
                    aa.desc_tipo_servicio, 
                    aa.id_multiconexion, 
                    aa.id_numero_comercial,
                    aa.id_codigo_concepto, 
                    aa.id_concepto_facturable, 
                    aa.desc_concepto_facturable, 
                    aa.f_unidades, 
                    aa.f_inicio_periodo, 
                    aa.f_fin_periodo, 
                    aa.f_importe_total,
                    bb.f_precio_mensual, 
                    bb.id_cod_cli, 
                    bb.desc_cod_cli, 
                    'LOTE3' lote, 
                    0 total,
                    aa.time,
                    aa.estado 
                FROM 
                    facturacion aa, 
                    constraints bb, 
                    customers cc 
                WHERE aa.estado = 1 AND aa.id_concepto_facturable = bb.id_concepto_facturable 
                    AND aa.id_cif_cliente = cc.id_titular_serv_lote3 
                    AND aa.id_numero_comercial 
                    IN  (
                        SELECT distinct a1.id_numero_comercial 
                        FROM billing a1, catalogue b1
                        WHERE a1.estado = 1 AND ltrim(rtrim(a1.id_concepto_facturable)) = ltrim(rtrim(b1.id_concepto_facturable))
                        )";

        $adapter = $this->adapter->query($statement);
        $adapter->execute();
        
        return $this;

    }
    
    public function billingRestoPending() {

        try{

            $statement = 
                "INSERT INTO billing 
                    SELECT 
                        aa.id, 
                        aa.id_fecha_fact, 
                        aa.id_cif_cliente, 
                        cc.cif_titular_serv_lote, 
                        cc.desc_titular_serv_lote3, 
                        aa.desc_tipo_servicio, 
                        aa.id_multiconexion, 
                        aa.id_numero_comercial,
                        aa.id_codigo_concepto, 
                        aa.id_concepto_facturable, 
                        aa.desc_concepto_facturable, 
                        aa.f_unidades, 
                        aa.f_inicio_periodo, 
                        aa.f_fin_periodo, 
                        aa.f_importe_total,
                        '' f_precio, 
                        '' cod_cli, 
                        '' desc_cod_cli, 
                        'RESTO SERVICIOS' lote, 
                        round(aa.f_importe_total,2) total,
                        aa.time,
                        aa.estado 
                    FROM 
                        facturacion aa, 
                        constraints bb, 
                        customers cc 
                    WHERE aa.estado = 1 AND aa.id_concepto_facturable = bb.id_concepto_facturable 
                        and aa.id_cif_cliente = cc.id_titular_serv_lote3 
                        and aa.id_numero_comercial 
                        NOT IN 
                        (
                        SELECT distinct a1.id_numero_comercial 
                        FROM 
                            billing a1, 
                            catalogue b1
                        WHERE a1.estado = 1 AND ltrim(rtrim(a1.id_concepto_facturable)) = ltrim(rtrim(b1.id_concepto_facturable))
                        )";

            $adapter = $this->adapter->query($statement);
            $adapter->execute();
            
        } catch (\Exception $e) {

            echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');

        }

        return $this;

    }

    public function invoiceDownload($periodo) {
        

//        $statement = 
//            "SELECT
//                id,
//                id_fecha_fact,
//                id_lcif_cliente,
//                id_cif_cliente,
//                desc_nom_ent,
//                desc_tipo_servicio,
//                id_multiconexion,
//                id_numero_comercial,
//                id_codigo_concepto,
//                id_concepto_facturable,
//                desc_concepto_facturable,
//                f_unidades,
//                f_inicio_periodo_fact,
//                f_fin_periodo_fact,
//                replace(f_precio_mensual_lote3,'.',',') f_precio_mensual_lote3,
//                id_cod_cli,
//                desc_servicio_lote3,
//                desc_lote,
//                replace(f_total_sin_iva,'.',',') f_total_sin_iva
//            FROM billing WHERE estado=1";
//
//        $adapter = $this->adapter->query($statement);
//
//        $header = array();
//        $header[] 
//            = array(
//                '0' => 'ID',
//                '1' => utf8_decode('FECHA FACTURACIÓN'),
//                '2' => 'LCIF',
//                '3' => 'CIF',
//                '4' => 'ENTIDAD',
//                '5' => 'TIPO SERVICIO',
//                '6' => utf8_decode('MULTICONEXIÓN'),
//                '7' => 'NRO COMERCIAL',
//                '8' => 'COD CONCEPTO',
//                '9' => 'COD CONCEPTO FACTURABLE',
//                '10' => 'CONCEPTO FACTURABLE',
//                '11' => 'UNIDADES',
//                '12' => 'INICIO',
//                '13' => 'FIN',
//                '14' => 'PRECIO MENSUAL',
//                '15' => 'COD CLIENTE',
//                '16' => 'SERVICIO LOTE3',
//                '17' => utf8_decode('DESCRIPCIÓN'),
//                '18' => 'TOTAL SIN UIVA'
//            ); 
//
//        $invoices = array();
//        
//        foreach ($adapter->execute() as $item) {
//
//            $idConcepto = (string)( '' . $item['id_concepto_facturable'] . '');
//            $invoices[] 
//                = array(
//                    '0' => strip_tags($item['id']),
//                    '1' => utf8_decode(strip_tags($item['id_fecha_fact'])),
//                    '2' => utf8_decode(strip_tags($item['id_lcif_cliente'])),
//                    '3' => utf8_decode(strip_tags($item['id_cif_cliente'])),
//                    '4' => strip_tags($item['desc_nom_ent']),
//                    '5' => strip_tags($item['desc_tipo_servicio']),
//                    '6' => $item['id_multiconexion'], 
//                    '7' => utf8_decode(strip_tags($item['id_numero_comercial'])),
//                    '8' => utf8_decode(strip_tags($item['id_codigo_concepto'])),
//                    '9' => strval($idConcepto),
//                    '10' => $item['desc_concepto_facturable'],
//                    '11' => $item['f_unidades'],
//                    '12' => $item['f_inicio_periodo_fact'],
//                    '13' => $item['f_fin_periodo_fact'],
//                    '14' => $item['f_precio_mensual_lote3'],
//                    '15' => $item['id_cod_cli'],
//                    '16' => $item['desc_servicio_lote3'],
//                    '17' => $item['desc_lote'],
//                    '18' => $item['f_total_sin_iva']
//                );
//        }
//
//        header('Content-Type: text/csv; charset=utf-8');                                        #CSV
//        header('Content-Disposition: attachment; filename=' . time() .  '-INVOICES.csv');       #CSV
//
//        $output = fopen('php://output', 'w');
//
//        $excels = array_merge($header, $invoices);
//
//        foreach($excels as $line) {
//            fputcsv($output, $line, ';');                                                       #CSV
//        }
//
//        fclose($output);

        //exit(); OJO
        
        

        // OTHER OPTION
        $this->periodo = $periodo . '28';
        
        $header = array(
            'ID'=>'0',
            'FECHA FAC'=>'string',
            'L_CIF'=>'string', 
            'CIF CLIENTE'=>'string', 
            'NOM ENS'=>'string', 
            'TIPO SERVICIO'=>'string', 
            'MULTICONEXION'=>'string', 
            'NUM COMERCIAL'=>'string',
            'COD CONCEPTO'=>'string', 
            'CONC FACT'=>'string', 
            'DESCRIPCIÓN CONCEPTO FAC'=>'string',
            'UNIDADES'=>'0',
            'INI PERIODO'=>'string',
            'FIN PERIODO'=>'string',
            'PRE MEN LOT3'=>'#,##0.00',
            'COD_CLI'=>'string',
            'DESCRIPCIÓN CONCURSO'=>'string',
            'LOTE'=>'string',
            'TOTAL SIN IVA'=>'#,##0.00');
        
        $filename = time() .  "-INVOICES.xlsx";
        header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $statement = 
            "SELECT
                id,
                id_fecha_fact,
                id_lcif_cliente,
                id_cif_cliente,
                desc_nom_ent,
                desc_tipo_servicio,
                id_multiconexion,
                id_numero_comercial,
                id_codigo_concepto,
                id_concepto_facturable,
                desc_concepto_facturable,
                f_unidades,
                f_inicio_periodo_fact,
                f_fin_periodo_fact,
                f_precio_mensual_lote3,
                id_cod_cli,
                desc_servicio_lote3,
                desc_lote,
                f_total_sin_iva
            FROM billing WHERE estado=1 AND id_fecha_fact = '" . $this->periodo ."'";
        
        $adapter = $this->adapter->query($statement);
        
        $writer = new XLSXWriter();
        $writer->setAuthor('Application Web'); 
        $writer->writeSheetHeader('ENTIDADES', $header);
        
        foreach ($adapter->execute() as $item) {

            $item['f_precio_mensual_lote3'] = 1*$item['f_precio_mensual_lote3'];
            $item['f_total_sin_iva'] = 1*$item['f_total_sin_iva'];
            $writer->writeSheetRow('ENTIDADES', $item);

        }
        
        $writer->writeToStdOut();

    }

    
    public function getInvoices() {

        $statement = 
            "SELECT
                id,
                id_fecha_fact,
                id_lcif_cliente,
                id_cif_cliente,
                desc_nom_ent,
                desc_tipo_servicio,
                id_multiconexion,
                id_numero_comercial,
                id_codigo_concepto,
                id_concepto_facturable,
                desc_concepto_facturable,
                f_unidades,
                f_inicio_periodo_fact,
                f_fin_periodo_fact,
                f_precio_mensual_lote3,
                id_cod_cli,
                desc_servicio_lote3,
                desc_lote,
                f_total_sin_iva
            FROM billing WHERE estado=1";

        //die('<pre>' . print_r($statement, true) . '</pre>');
        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();
        
        return $this->convertedObjects($result);

    }
    
    public function convertedObjects($result)
    {

        $entities = array();

        foreach ($result as $row) {
            
            $entity = new \stdClass;
            $entity->id = $row['id'];
            $entity->periodo = $row['id_fecha_fact'];
            $entity->lCif = $row['id_lcif_cliente'];
            $entity->cif = $row['id_cif_cliente'];
            $entity->entidades = $row['desc_nom_ent'];
            $entity->tipoServicio = $row['desc_tipo_servicio'];
            $entity->telefono = $row['id_numero_comercial'];
            
//            $entity->codConcepto = $row['id_codigo_concepto'];
//            $entity->concFact = $row['id_concepto_facturable'];
            $entity->conceptoFacturable = $row['desc_concepto_facturable']; //Nombre sede
            $entity->unidades = $row['f_unidades'];
            $entity->inicio = $row['f_inicio_periodo_fact'];
            $entity->fin = $row['f_fin_periodo_fact'];
            $entity->precioMensualLote3 = $row['f_precio_mensual_lote3'];
            $entity->totalSinIva = $row['f_total_sin_iva'];
            $entities[$row['id']] = $entity;

        }

        
        
        
        
        return $entities;

    }

    
    public function getInvoice($id) {

        $datos = array();
        
        $statement = 
            "SELECT *
            FROM billing WHERE estado=1 AND id='" . $id . "'";

        $adapter = $this->adapter->query($statement);
        
        $invoice = array();
        foreach ($adapter->execute() as $item) {
            $invoice[] = $item;
        }

        $datos['factura'] = $invoice;

        return $datos;

    }

    public function getLote3Invoice($id) {

        $datos = array();
        
        $statement = 
            "SELECT *
            FROM factura_lote3 WHERE activo = 1 AND id='" . $id . "'";

        $adapter = $this->adapter->query($statement);
        
        $invoice = array();
        foreach ($adapter->execute() as $item) {
            $invoice[] = $item;
        }

        $datos['factura'] = $invoice;

        return $datos;

    }
    
    
    
    public function getTotalByEntities($periodo) {
        
         $this->periodo = $periodo;
        
        $statement = 
            "SELECT
                b.id,
                b.id_titular_serv id_titular_serv,
                b.desc_titular_serv_lote3 desc_titular_serv_lote3,
                round(sum(total_lote3),2) total_lote3,
                round(sum(total_resto_servicios),2) total_resto_servicios
            FROM
                (
		SELECT
                    id,
                    id_cif_cliente id_titular_serv, 
                    desc_nom_ent desc_titular_serv_lote3, 
                    CASE 
                                    WHEN desc_lote = 'LOTE3' 
                                    THEN f_total_sin_iva ELSE 0 
                    END total_lote3,
                    CASE
                                    WHEN desc_lote = 'RESTO SERVICIOS'
                                    THEN f_total_sin_iva ELSE 0 
                    END total_resto_servicios  
		FROM billing
		WHERE estado=2" . $this->clientScopeFilter . " AND DATE_FORMAT(id_fecha_fact,'%Y%m')= '". $this->periodo . "'
                ) b
            GROUP BY
		id_titular_serv,
		desc_titular_serv_lote3";
        
        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();

        return $result;

    }
    
    
    public function getSubTotalByPhone() {
        
        $statement = 
            "SELECT
                b.id,
                b.id_titular_serv id_titular_serv,
                b.desc_titular_serv_lote3 desc_titular_serv_lote3,
                b.id_telefono id_telefono,
                round(sum(total_lote3),2) total_lote3,
                round(sum(total_resto_servicios),2) total_resto_servicios
                FROM
                    (
                    SELECT
                        id,
                        id_cif_cliente id_titular_serv, 
                        desc_nom_ent desc_titular_serv_lote3,
                        id_numero_comercial id_telefono,
                        CASE 
                            WHEN desc_lote = 'LOTE3' 
                            THEN f_total_sin_iva ELSE 0 
                        END total_lote3,
                        CASE
                            WHEN desc_lote = 'RESTO SERVICIOS'
                            THEN f_total_sin_iva ELSE 0 
                        END total_resto_servicios  
                    FROM billing
                    WHERE estado=2" . $this->clientScopeFilter . " AND DATE_FORMAT(id_fecha_fact,'%Y%m')= '". $this->periodo . "'
                    ) b
                GROUP BY
                    id_titular_serv,
                    desc_titular_serv_lote3,
                    id_telefono";
        
        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();

        return $result;
    
    }
   
    public function getDetails() {
        
        $statement = 
            "SELECT
                id,
                id_cif_cliente id_titular_serv, 
                desc_nom_ent desc_titular_serv_lote3,
                id_numero_comercial id_telefono, 
                desc_concepto_facturable desc_servicio_estandard,
                desc_servicio_lote3,
                case when desc_lote = 'LOTE3' then f_total_sin_iva else 0 end lote3, 
                case when desc_lote = 'RESTO SERVICIOS' then f_total_sin_iva else 0 end resto_servicios  
            FROM billing 
            WHERE estado=2" . $this->clientScopeFilter . " AND DATE_FORMAT(id_fecha_fact,'%Y%m') = '". $this->periodo . "'
            ORDER BY id_numero_comercial";

        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();

        return $result;

    }

    public function setPostParams($posts) {
        $this->posts = $posts;
        return $this;
    }
    
    
    
    
    public function updateInvoice() {

        $invoice = new \Facturacion\Model\Entity\Invoice();
        $id = (int)$this->posts['invoiceId'];
        $invoice->setId($id);
        $invoice->setOptions($this->posts);

        $handler = new \Facturacion\Model\Invoice($this->adapter);
        $invoiceId = $handler->saveInvoice($invoice);
        
        return $invoiceId;
    }

    public function deleteInvoice() {

        $invoice = new \Facturacion\Model\Entity\Invoice();
        $invoiceId = (int)$this->posts['id'];
        $invoice->setId($invoiceId);
        
        //echo('@Service: <pre>' . print_r($invoice, true) . '</pre>');
        
        $handler = new \Facturacion\Model\Invoice($this->adapter);
        $result = $handler->deleteInvoice($invoice);
        
        return $result;
        
    }
    

    public function saveInvoice() {

        $invoice = new \Facturacion\Model\Entity\Invoice();
        $invoice->setOptions($this->posts);

        $handler = new \Facturacion\Model\Invoice($this->adapter);
        $invoiceId = $handler->saveInvoice($invoice);

        return $invoiceId;

    }
    
    public function invoiceValidate() {

        $statement = 
            "UPDATE `facturacion` 
                SET estado = 2 
                WHERE estado = 1";

        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();
        
        $statement2 = 
            "UPDATE `billing` 
                SET estado = 2 
                WHERE estado = 1";

        $adapter2 = $this->adapter->query($statement2);
        $result2 = $adapter2->execute();

        if($result && $result2) {
            return true;
        }
        
        return false;

    }
    
    
    
    public function constructHeader() {

        $header = array(
            'CIF',
            'Razón Social',
            'Clave de cobro',
            'Contacto',
            'Domicilio',
            'Localidad',
            'Distrito Postal',
            'SumaDeTOTAL SIN IVA',
            'LOTE',
            'CIF CLIENTE',
            'NOM ENS'
        );

        $row = 1;
        $col = 'A';	
        foreach($header as $item) {		
            $this->objPHPExcel->getActiveSheet()
                    ->getStyle($col.$row,$item)
                    ->getFont()->setBold(true);
            $this->objPHPExcel->getActiveSheet()
                    ->getStyle($col.$row,$item)->getFill()
                    ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $this->objPHPExcel->getActiveSheet()
                    ->getStyle($col.$row,$item)->getFill()
                    ->getStartColor()->setARGB('E3E3E3');
            $this->objPHPExcel->getActiveSheet()
                    ->setCellValue($col.$row,$item);
            $col++;
        }
        
        return $this->objPHPExcel;
    }
    
    public function getSumatoriItems($periodo, $filter) {
        
                $query = "
            SELECT
                a.id_cif,
                a.desc_razon_social, 
                a.desc_clave_cobro,
                a.desc_contacto, 
                a.desc_domicilio,
                a.desc_localidad, 
                a.desc_distrito_postal,
		replace(round(sum(b.f_total_sin_iva),2),'.',',') f_total_sin_iva,
		b.desc_lote,
		b.id_cif_cliente,
		b.desc_nom_ent
                FROM 
                    billing AS b LEFT JOIN address AS a ON b.id_cif_cliente = a.id_cif 
                WHERE b.id_fecha_fact = '" . $periodo . "'
                GROUP BY
                    a.id_cif,
                    a.desc_razon_social, 
                    a.desc_clave_cobro,
                    a.desc_clave_cobro,
                    a.desc_contacto, 
                    a.desc_domicilio,
                    a.desc_localidad, 
                    a.desc_distrito_postal,
                    b.desc_lote,
                    b.id_cif_cliente, 
                    b.desc_nom_ent
                HAVING (b.desc_lote='" . $filter . "')";
        
        $adapter = $this->adapter->query($query);
        
        $row = 2;
        foreach ($adapter->execute() as $item) {
            $col = 'A';
            foreach ($item as $value) {
		$this->objPHPExcel->getActiveSheet()
                            ->setCellValueExplicit($col.$row,$value, \PHPExcel_Cell_DataType::TYPE_STRING);
		$col++;
            }
            $row++;
        }

        return $this->objPHPExcel;

    }


    /*  Exportation excel file 
     *  $objPHPExcel = new \PHPExcel();
     * 
     * 
     */
    public function sumatoriaLotes($periodo) {

        $this->objPHPExcel->getSecurity()->setLockWindows(false)
                    ->setLockStructure(false);

        $this->objPHPExcel = $this->constructHeader();
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->objPHPExcel->getActiveSheet()->setTitle('LOTE3');
        $this->objPHPExcel = $this->getSumatoriItems($periodo, 'LOTE3');

        $this->objPHPExcel->createSheet(1); 
        $this->objPHPExcel->setActiveSheetIndex(1);
        $this->objPHPExcel->getActiveSheet()->setTitle('RESTO SERVICIO');
        $this->objPHPExcel = $this->constructHeader();
        $this->objPHPExcel = $this->getSumatoriItems($periodo, 'RESTO SERVICIOS');
        
        $this->objPHPExcel->setActiveSheetIndex(0);
        
        $filename = 'SumatorisPerLot_' . $periodo . '.xlsx';
        
        header("Content-Type: text/html;charset=utf-8");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-disposition: attachment; filename=$filename");
        header('Cache-Control: max-age=0');

        try {
            
            $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
            $objWriter->save('php://output'); 

        } catch (Exception $e) {

            echo 'Excepción capturada: ' .  $e->getMessage();

        }

        exit;

    }

    /*
     * @Eureka
     */
    public function comparisonProcess($percent, $periodo) {
        
        $information = array();
        
        $percent = (float)$percent;
        $month = (int)substr($periodo, 4, 2);
        $year = (int)substr($periodo, 0, 4);
    
        if($month > 1) {
           $contiguosMonth =  $month - 1;
           if($contiguosMonth < 10) {
               $contiguosMonth = '0' . (string)$contiguosMonth;
           }
           $contiguosYear = $year;
        } else if($month == 1) {
            $contiguosMonth = 12;
            $contiguosYear = $year - 1;
        }
        
        $contiguosPeriodo = (string)$contiguosYear . '' . (string)$contiguosMonth;

        $information['periodoContiguo'] = $contiguosPeriodo;
        
        $contiguosRecords = $this->getTotalByEntities($contiguosPeriodo);
        $records = $this->getTotalByEntities($periodo);
        
        $contiguos = array();
        $current = array();
        $result = array();
        
        foreach ($contiguosRecords as $item) {
            $item['periodo'] = $contiguosPeriodo;
            $contiguos[$item['id_titular_serv']] = $item;
        }
        
        foreach ($records as $item) {
            $item['periodo'] = $periodo;
            $current[$item['id_titular_serv']] = $item;
        }

        $total = 0.00;
        $contiguosTotal = 0.00;
        $margen = 0.00;

        foreach ($current as $item) {
            
            if(isset($contiguos[$item['id_titular_serv']])) {

                $key = $contiguos[$item['id_titular_serv']];
                $item['id_continuo'] = $key['id'];
                $item['periodo_continuo'] = $key['periodo'];
                $item['total_lote3_continuo'] = $key['total_lote3'];
                $item['total_resto_servicios_continuo'] = $key['total_resto_servicios'];
                
                $total = (float)$item['total_lote3'] + (float)$item['total_resto_servicios'];
                $contiguosTotal = (float)$item['total_lote3_continuo'] + (float)$item['total_resto_servicios_continuo'];
                
                $item['total'] = $total;
                $item['total_continuo'] = $contiguosTotal;
                $item['percent'] = $percent;
                
                if( $total > $contiguosTotal) {
                    
                    $margen = (float)((($total * 100)/$contiguosTotal) - 100) ;
                    $item['margen'] = $margen;
                    // Set key = $item['id_titular_serv']  
                    if($margen >= $percent) {
                        $result[$item['id_titular_serv']] = $item;
                    }

                }

            }

            
        }

        $information['result'] = $result;

        return $information;

    }

    public function invoiceExport($periodo, $result) {

        $this->objPHPExcel->getSecurity()->setLockWindows(false)
                ->setLockStructure(false);

        $this->objPHPExcel = $this->constructInvoiceHeader();
        $this->objPHPExcel->setActiveSheetIndex(0);
        $this->objPHPExcel->getActiveSheet()->setTitle('FACTURA-' . $periodo);
        $this->objPHPExcel = $this->getInvoiceItems($result);

        $this->objPHPExcel->setActiveSheetIndex(0);
        
        $filename = 'Factura_' . $periodo . '.xlsx';

        header("Content-Type: text/html;charset=utf-8");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-disposition: attachment; filename=$filename");
        header('Cache-Control: max-age=0');

        try {
            
            $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel2007');
            $objWriter->save('php://output'); 

        } catch (Exception $e) {

            echo 'Excepción capturada: ' .  $e->getMessage();

        }

        exit;

    }
    

        
    public function constructInvoiceHeader() {

        $header = array(
            'ENTIDAD',
            'LÍNEA',
            'DETALLE TELEFONICA',
            'DETALLE CONCURSO',
            'LOTE3',
            'RESTO',
            'TOTAL'
        );

        $row = 1;
        $col = 'A';	
        foreach($header as $item) {		
            $this->objPHPExcel->getActiveSheet()
                    ->getStyle($col.$row,$item)
                    ->getFont()->setBold(true);
            $this->objPHPExcel->getActiveSheet()
                    ->getStyle($col.$row,$item)->getFill()
                    ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $this->objPHPExcel->getActiveSheet()
                    ->getStyle($col.$row,$item)->getFill()
                    ->getStartColor()->setARGB('E3E3E3');
            $this->objPHPExcel->getActiveSheet()
                    ->setCellValue($col.$row,$item);
            $col++;
        }
        
        return $this->objPHPExcel;
    }
        
    
    public function getInvoiceItems($result) {
        
        
        $row = 2;
        foreach ($result as $item) {
            $col = 'A';
            foreach ($item as $value) {
                if($col<'E'){
		$this->objPHPExcel->getActiveSheet()
                            ->setCellValueExplicit($col.$row,$value, \PHPExcel_Cell_DataType::TYPE_STRING);
                } else {
                    $this->objPHPExcel->getActiveSheet()
                        ->setCellValueExplicit($col.$row,$value, \PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    if(empty($item['DETALLE_ESTANDAR'])){
                        $this->objPHPExcel->getActiveSheet()
                            ->getStyle($col.$row,$item)
                            ->getFont()->setBold(true);
                    }
                }
                $col++;
            }
            $row++;
        }

        return $this->objPHPExcel;

    }


    public function saveLote3Invoice() {

        $invoiceLote3 = new \Facturacion\Model\Entity\InvoiceLote3();
        $invoiceLote3->setOptions($this->posts);

        $handler = new \Facturacion\Model\InvoiceLote3($this->adapter);
        $invoiceId = $handler->saveLote3Invoice($invoiceLote3);

        return $invoiceId;

    }

    public function getLote3Invoices() {

        $statement =
            "SELECT f.*,
                o.id AS organismoId, o.organismo,
                p.id AS plantaId, p.planta,
                x.id AS xarxaId, x.xarxa,
                c.id AS claveId, c.clave,
                s.id AS oficinaId, s.nombre,
                sr.id AS servicioId, sr.codigo_servicio, sr.servicio, sr.descripcion_detallada, sr.precio,
                e.id AS estadoId, e.estado
                    FROM factura_lote3 AS f
                        LEFT JOIN organismos AS o ON f.organismo = o.id
                        LEFT JOIN plantas AS p ON f.planta = p.id
                        LEFT JOIN xarxas AS x ON f.xarxa = x.id
                        LEFT JOIN clave_cobros AS c ON f.clave = c.id
                        LEFT JOIN sedes AS s ON f.oficina = s.id
                        LEFT JOIN servicios_lote3 AS sr ON f.servicio = sr.id
                        LEFT JOIN estados_lote3 AS e ON f.estado = e.id
                        WHERE f.estado = 1 AND f.activo = 1
                        ORDER BY f.creacion DESC"; 
        
        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();
        
        return $this->convertedInvoiceObjects($result);

    }
    
    /*CHECK */
    public function convertedInvoiceObjects($result)
    {

        $entities = array();

        foreach ($result as $row) {
            
            $entity = new \stdClass;
            $entity->id = $row['id'];
            $entity->organismo = ($row['organismoId']=='1')?'DIBA':'ORGT';
            $entity->planta = $row['planta'];
            $entity->xarxa = $row['xarxa'];
            $entity->clave = $row['clave'];
            $entity->nombre = $row['nombre']; //Nombre sede
            $entity->codigoservicio = $row['codigo_servicio'];
            $entity->servicio = $row['servicio'];
            $entity->descripciondetallada = $row['descripcion_detallada'];
            $entity->precio = $row['precio'];
            $entity->administrativo = $row['administrativo'];
            $entity->linea = $row['linea'];
            $entity->ip = $row['ip'];
            
            $entities[$row['id']] = $entity;
        }
        //die('<pre>' . print_r($entities, true) . '</pre>');                    
        return $entities;

    }

    public function templateExport()
    {

        //die('$post in templateExport <pre>' . print_r($this->posts, true) . '</pre>');
        //$templatePath = "C:\\xampp\htdocs\development\module\Facturacion\src\Facturacion\Template\\Template_XIC.xls";
        //die('DIR: <pre>' . print_r(dirname(__DIR__), true) . '</pre>');

        $centroCoste = (string)$this->posts['centrocostes'];
        $periodo = (string)$this->posts['periodo'];
        $templatePath = dirname(__DIR__) . "\Template\\Template_" . strtoupper($centroCoste) .  ".xls"; 
        
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        
        $objPHPExcel = $objReader->load($templatePath);
            
        $cc = $this->cc[$centroCoste];
        
        //$objPHPExcel->getActiveSheet()->setCellValue('D1', \PHPExcel_Shared_Date::PHPToExcel(time()));
        $objPHPExcel->getActiveSheet()->setCellValue('G1', $periodo);

        $total = 0;
        
        // Calculate SERVEI Accés a Internet Independent: Accés a la ADSL = 2
        $adsl = 2;    
        $adslAccessData = $this->getServicesAccessData($adsl, $periodo, $cc, 1); // XIC = 4
        
        $baseRow = 12;
        foreach($adslAccessData as $r => $access) {
            $row = $baseRow + $r;
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $access['servicio'])
                                          ->setCellValue('D'.$row, $access['descripcion'])
                                          ->setCellValue('E'.$row, $access['Unidad'])
                                          ->setCellValue('F'.$row, $access['precio'])
                                          ->setCellValue('G'.$row, $access['Total']);
            $total = $total + $access['Total'];
        }
        $objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
        
        $adslAccessData2 = $this->getServicesAccessData($adsl, $periodo, $cc, 2); // XIC = 4
        
        $baseRow2 = $row + 2;
        foreach($adslAccessData2 as $r => $access2) {
            $row = $baseRow2 + $r;
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $access2['servicio'])
                                          ->setCellValue('D'.$row, $access2['descripcion'])
                                          ->setCellValue('E'.$row, $access2['Unidad'])
                                          ->setCellValue('F'.$row, $access2['precio'])
                                          ->setCellValue('G'.$row, $access2['Total']);
            $total = $total + $access2['Total'];
        }

        $objPHPExcel->getActiveSheet()->removeRow($baseRow2-1,1);

        
        // Calculate SERVEI de connectivitat de dades: Accés a la VPN = 1
        $vpn = 1;
        $vpnAccessData = $this->getServicesAccessData($vpn, $periodo, $cc, 1); // XIC = 4
        //$baseRow = 17;
        $baseRow3 = $row + 3;
        foreach($vpnAccessData as $r => $access) {
            $row = $baseRow3 + $r;
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $access['servicio'])
                                          ->setCellValue('D'.$row, $access['descripcion'])
                                          ->setCellValue('E'.$row, $access['Unidad'])
                                          ->setCellValue('F'.$row, $access['precio'])
                                          ->setCellValue('G'.$row, $access['Total']);
            $total = $total + $access['Total'];
        }
        $objPHPExcel->getActiveSheet()->removeRow($baseRow3-1,1);
                
        $vpnAccessData2 = $this->getServicesAccessData($vpn, $periodo, $cc, 2); // XIC = 4 Ampliado
        $baseRow4 = $row + 2;
        foreach($vpnAccessData2 as $r => $access2) {
            $row = $baseRow4 + $r;
            $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $access2['servicio'])
                                          ->setCellValue('D'.$row, $access2['descripcion'])
                                          ->setCellValue('E'.$row, $access2['Unidad'])
                                          ->setCellValue('F'.$row, $access2['precio'])
                                          ->setCellValue('G'.$row, $access2['Total']);
            $total = $total + $access2['Total'];
        }

        $objPHPExcel->getActiveSheet()->removeRow($baseRow4-1,1);


        if($cc == 2 || $cc == 3 || $cc == 4 || $cc == 13 ) {
            $row = $row + 2;
            $parameters = $this->calculateAiccCoste($periodo, 'AICC-' . strtoupper($centroCoste)); // AICC-XIC/AICC-XEM/AICC-XB/AICC-XORGT 
            $codigo = $parameters['0']['servicio'];
            $descripcion = $parameters['0']['descripcion_detallada'];
            $precio = $parameters['0']['precio'];
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, 1);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $precio);
            $total = $total + $precio;
            
                // BEGIN: Formate special ti XORGT
                if($cc == 13) {
                    $row = $row + 1; 
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
                    $parameters = $this->calculateAiccCoste($periodo, 'ORGT-H'); // REVIEW
                    $codigo = $parameters['0']['servicio'];
                    $descripcion = $parameters['0']['descripcion_detallada'];
                    $precio = $parameters['0']['precio'];
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, 1);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $precio);
                    $total = $total + $precio;
                    
                    $row = $row + 1; 
                    $objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
                    $parameters = $this->calculateAiccCoste($periodo, 'OTROS');
                    $codigo = $parameters['0']['servicio'];
                    $descripcion = $parameters['0']['descripcion_detallada'];
                    $precio = $parameters['0']['precio'];
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, 1);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $precio);
                    $total = $total + $precio;
                    
                }
                // END
            
            $row = $row + 3;
            
        } else {
            $row = $row + 2;
        }    
        
        
        // Calculate AICC
        $aicc = $this->aicc[$centroCoste];
        $parameters = $this->proportionalityCalculate($periodo, 10);   // AICC
        $codigo = $parameters['0']['servicio'];
        $descripcion = $parameters['0']['descripcion'];
        $precio = $parameters['0']['precio'];
        
        if($aicc > 0) {
            $objPHPExcel->getActiveSheet()->setCellValue('C' .$row, $codigo);
            $objPHPExcel->getActiveSheet()->setCellValue('D' .$row, $descripcion);
            $objPHPExcel->getActiveSheet()->setCellValue('F' .$row, $precio * $aicc); // 20% AICC to XIC, 40% AICC to XEM, 40% AICC to XB
            $total = $total + $precio * $aicc;
            $row = $row + 1;
        }
        
        // Calculate BACKBONE
        $backbone = $this->backbone[$centroCoste];
        $parameters = $this->proportionalityCalculate($periodo, 6); // Backbone
        $codigo = $parameters['0']['servicio'];
        $descripcion = $parameters['0']['descripcion'];
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio * $backbone); // See top
        $total = $total + $precio * $backbone;
        
        // Calculate BACKBONE-R
        $row = $row + 1;
        $backboneR = $this->backboneR[$centroCoste];
        $parameters = $this->proportionalityCalculate($periodo, 7); // Backbone - R
        $codigo = $parameters['0']['servicio'];
        $descripcion = $parameters['0']['descripcion'];
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio * $backboneR); // See top
        $total = $total + $precio * $backboneR;
        
        // Calculate BACKBONE-SAN
        $row = $row + 1;
        $backboneSan = $this->backboneSan[$centroCoste];
        $parameters = $this->proportionalityCalculate($periodo, 9); // Backbone -SAN
        $codigo = $parameters['0']['servicio'];
        $descripcion = $parameters['0']['descripcion'];
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio * $backboneSan); // See top
        $total = $total + $precio * $backboneSan;

        // Calculate BACKBONE-S
        $row = $row + 1;
        $backboneS = $this->backboneS[$centroCoste];
        $parameters = $this->proportionalityCalculate($periodo, 8); // Backbone - S
        $codigo = $parameters['0']['servicio'];
        $descripcion = $parameters['0']['descripcion'];
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio * $backboneS); // See top
        $total = $total + $precio * $backboneS;
        
        // Calculate OEC3
        $row = $row + 1;
        $oec3 = $this->oec3[$centroCoste];
        $parameters = $this->proportionalityCalculate($periodo, 11); // OEC3
        $codigo = $parameters['0']['servicio'];
        $descripcion = $parameters['0']['descripcion'];
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio * $oec3); // See top
        $total = $total + $precio * $oec3;
        
        // Calculate OEC3 ENS
        $row = $row + 1;
        $oec3Ens = $this->oec3Ens[$centroCoste];
        $parameters = $this->proportionalityCalculate($periodo, 12); // OEC3 ENS
        $codigo = $parameters['0']['servicio'];
        $descripcion = $parameters['0']['descripcion'];
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $codigo);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $descripcion);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $precio * $oec3Ens); // See top
        $total = $total + $precio * $oec3Ens;        
        
        // Calculate TOTALES
        $objPHPExcel->getActiveSheet()->setCellValue('C5', $total);
        $objPHPExcel->getActiveSheet()->setCellValue('D5', $total * 0.21);
        $objPHPExcel->getActiveSheet()->setCellValue('E5', $total * 1.21);
        
        try {
                $filename = "FACTURA_" . strtoupper($centroCoste) . "_" . $periodo .  ".xls";
                header("Content-Type: text/html;charset=utf-8");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-disposition: attachment; filename=$filename");
                header('Cache-Control: max-age=0');
                
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
        }   catch (Exception $e) {
            echo 'Excepción capturada: ' .  $e->getMessage();
        }

        exit;

    }

    
    /*
     * Methods to calcuilate and fill template
     * 
     */
    
    public function calculateAiccCoste($periodo, $centrocoste) { // AICC-XIC
        
        $statement =
            "SELECT s.servicio, s.descripcion_detallada, SUM(s.precio) AS precio 
            FROM factura_lote3 AS f INNER JOIN servicios_lote3 AS s ON f.servicio = s.id 
            WHERE s.servicio = '" . $centrocoste . "' AND f.periodo = '" . $periodo . "' GROUP BY f.xarxa";
        
        $adapter = $this->adapter->query($statement);
        $objResult = $adapter->execute();
        
        $result = array();
        foreach ($objResult as $item) {
            $result[] = $item;  
        }
        
        return $result;
        
    }
    
    
    public function proportionalityCalculate($periodo, $xarxa) {
        
        $statement =
            "SELECT s.servicio, s.descripcion, SUM(s.precio) AS precio  
            FROM factura_lote3 AS f 
            INNER JOIN servicios_lote3 AS s ON f.servicio = s.id 
            WHERE f.xarxa = " . $xarxa . " AND f.periodo = '" . $periodo . "' GROUP BY f.xarxa";
        
        $adapter = $this->adapter->query($statement);
        $objResult = $adapter->execute();
        
        $result = array();
        foreach ($objResult as $item) {
            $result[] = $item;  
        }
        
        return $result;
        
    }
    
    
    public function getServicesAccessData($planta, $periodo, $centrocoste, $ampliado)
    {
        
        $statement =
            "SELECT s.codigo_servicio, s.servicio, s.descripcion, COUNT(s.servicio) AS Unidad, s.precio,
                SUM(s.precio) AS Total, s.estado AS Flag 
                FROM factura_lote3 AS f INNER JOIN servicios_lote3 AS s ON f.servicio = s.id
                WHERE f.planta = '" . $planta . "' AND  f.xarxa = '" . $centrocoste . "' AND f.periodo = '" . $periodo . "' AND s.estado = '" 
                . $ampliado . "'  GROUP BY s.servicio ORDER BY s.servicio ASC";

        //die('$statement: <pre>' . print_r($statement, true) . '</pre>');
        
        $adapter = $this->adapter->query($statement);
        $objResult = $adapter->execute();
        
        $result = array();
        foreach ($objResult as $item) {
            $result[] = $item;  
        }
        
        return $result;
        
        
    }
    
    public function globalTemplateExport()
    {

        $periodo = (string)$this->posts['periodo'];
        $templatePath = dirname(__DIR__) . "\Template\\Template_Global.xls"; 
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        $objPHPExcel = $objReader->load($templatePath);
            
        //$objPHPExcel->getActiveSheet()->setCellValue('D1', \PHPExcel_Shared_Date::PHPToExcel(time()));
        $month = $this->getMonths($periodo);
        $msg = 'RESUM FACTURACIÓ DIPUTACIÓ BCN ' . $month . ' 2020';
        $objPHPExcel->getActiveSheet()->setCellValue('B2', $msg);
        //$objPHPExcel->getActiveSheet()->setCellValue('P1', $periodo);
        
        $vpn = 1;
        $adsl = 2;
        $xic = 4;
        $vpnXic = $this->getSubTotalByCostCenter($vpn, $xic, $periodo);
        $adslXic = $this->getSubTotalByCostCenter($adsl, $xic, $periodo);
        $xem = 3;
        $vpnXem = $this->getSubTotalByCostCenter($vpn, $xem, $periodo);
        $adslXem = $this->getSubTotalByCostCenter($adsl, $xem, $periodo);
        $xb = 2;
        $vpnXb = $this->getSubTotalByCostCenter($vpn, $xb, $periodo);
        $adslXb = $this->getSubTotalByCostCenter($adsl, $xb, $periodo);
        $xp = 5;
        $vpnXp = $this->getSubTotalByCostCenter($vpn, $xp, $periodo);
        $adslXp = $this->getSubTotalByCostCenter($adsl, $xp, $periodo);
                
        $objPHPExcel->getActiveSheet()->setCellValue('E6', $vpnXic['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E7', $adslXic['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E8', $vpnXem['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E9', $adslXem['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E10', $vpnXb['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E11', $adslXb['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E12', $vpnXp['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E13', $adslXp['0']['SubTotal']);

        // Backbone
        $parameters = $this->proportionalityCalculate($periodo, 6); // Backbone
        $precio = $parameters['0']['precio'];
        $backboneXic = $this->backbone['xic'];
        $backboneXem = $this->backbone['xem'];
        $backboneXb = $this->backbone['xb'];
        $backboneXp = $this->backbone['xp'];
        $backboneXorgt = $this->backbone['xorgt'];
        
        $objPHPExcel->getActiveSheet()->setCellValue('F6', $precio * $backboneXic);
        $objPHPExcel->getActiveSheet()->setCellValue('F8', $precio * $backboneXem);
        $objPHPExcel->getActiveSheet()->setCellValue('F10', $precio * $backboneXb);
        $objPHPExcel->getActiveSheet()->setCellValue('F12', $precio * $backboneXp);
        
        $objPHPExcel->getActiveSheet()->setCellValue('F18', $precio * $backboneXorgt);
        
        
        // Backbone-R
        $parameters = $this->proportionalityCalculate($periodo, 7); // Backbone - R
        $precio = $parameters['0']['precio'];
        $backboneRXic = $this->backboneR['xic'];
        $backboneRXem = $this->backboneR['xem'];
        $backboneRXb = $this->backboneR['xb'];
        $backboneRXp = $this->backboneR['xp'];
        $backboneRXorgt = $this->backboneR['xorgt'];
        
        $objPHPExcel->getActiveSheet()->setCellValue('G6', $precio * $backboneRXic);
        $objPHPExcel->getActiveSheet()->setCellValue('G8', $precio * $backboneRXem);
        $objPHPExcel->getActiveSheet()->setCellValue('G10', $precio * $backboneRXb);
        $objPHPExcel->getActiveSheet()->setCellValue('G12', $precio * $backboneRXp);
        
        $objPHPExcel->getActiveSheet()->setCellValue('G18', $precio * $backboneRXorgt);
        
        // Backbone-San
        $parameters = $this->proportionalityCalculate($periodo, 9); // Backbone - SAN
        $precio = $parameters['0']['precio'];
        $backboneSanXic = $this->backboneSan['xic'];
        $backboneSanXem = $this->backboneSan['xem'];
        $backboneSanXb = $this->backboneSan['xb'];
        $backboneSanXp = $this->backboneSan['xp'];
        $backboneSanXorgt = $this->backboneSan['xorgt'];
        
        $objPHPExcel->getActiveSheet()->setCellValue('H6', $precio * $backboneSanXic);
        $objPHPExcel->getActiveSheet()->setCellValue('H8', $precio * $backboneSanXem);
        $objPHPExcel->getActiveSheet()->setCellValue('H10', $precio * $backboneSanXb);
        $objPHPExcel->getActiveSheet()->setCellValue('H12', $precio * $backboneSanXp);
        
        $objPHPExcel->getActiveSheet()->setCellValue('H18', $precio * $backboneSanXorgt);
        
        // Backbone-S
        $parameters = $this->proportionalityCalculate($periodo, 8); // Backbone - S
        $precio = $parameters['0']['precio'];
        $backboneSXic = $this->backboneS['xic'];
        $backboneSXem = $this->backboneS['xem'];
        $backboneSXb = $this->backboneS['xb'];
        $backboneSXp = $this->backboneS['xp'];
        $backboneSXorgt = $this->backboneS['xorgt'];
        
        $objPHPExcel->getActiveSheet()->setCellValue('I6', $precio * $backboneSXic);
        $objPHPExcel->getActiveSheet()->setCellValue('I8', $precio * $backboneSXem);
        $objPHPExcel->getActiveSheet()->setCellValue('I10', $precio * $backboneSXb);
        $objPHPExcel->getActiveSheet()->setCellValue('I12', $precio * $backboneSXp);
        
        $objPHPExcel->getActiveSheet()->setCellValue('I18', $precio * $backboneSXorgt);


        // Fraction INET AICC-XIC, AICC-XEM, AICC-XB y AICC-XORGT
        $parameters = $this->calculateAiccCoste($periodo, 'AICC-XIC');
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('J6', $precio);
        $parameters = $this->calculateAiccCoste($periodo, 'AICC-XEM');
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('J8', $precio);
        $parameters = $this->calculateAiccCoste($periodo, 'AICC-XB');
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('J10', $precio);

        $parameters = $this->calculateAiccCoste($periodo, 'AICC-XORGT');
        $precio = $parameters['0']['precio'];
        $objPHPExcel->getActiveSheet()->setCellValue('J18', $precio);


        // OEC3
        $parameters = $this->proportionalityCalculate($periodo, 11); // OEC3
        $precio = $parameters['0']['precio'];
        $oec3Xic = $this->oec3['xic'];
        $oec3Xem = $this->oec3['xem'];
        $oec3Xb = $this->oec3['xb'];
        $oec3Xp = $this->oec3['xp'];
        $oec3Xorgt = $this->oec3['xorgt'];
        
        $objPHPExcel->getActiveSheet()->setCellValue('K6', $precio * $oec3Xic);
        $objPHPExcel->getActiveSheet()->setCellValue('K8', $precio * $oec3Xem);
        $objPHPExcel->getActiveSheet()->setCellValue('K10', $precio * $oec3Xb);
        $objPHPExcel->getActiveSheet()->setCellValue('K12', $precio * $oec3Xp);
        
        $objPHPExcel->getActiveSheet()->setCellValue('K18', $precio * $oec3Xorgt);

        // OEC3-ENS
        $parameters = $this->proportionalityCalculate($periodo, 12); // OEC3-ENS
        $precio = $parameters['0']['precio'];
        $oec3EnsXic = $this->oec3Ens['xic'];
        $oec3EnsXem = $this->oec3Ens['xem'];
        $oec3EnsXb = $this->oec3Ens['xb'];
        $oec3EnsXp = $this->oec3Ens['xp'];
        $oec3EnsXorgt = $this->oec3Ens['xorgt'];

        $objPHPExcel->getActiveSheet()->setCellValue('L6', $precio * $oec3EnsXic);
        $objPHPExcel->getActiveSheet()->setCellValue('L8', $precio * $oec3EnsXem);
        $objPHPExcel->getActiveSheet()->setCellValue('L10', $precio * $oec3EnsXb);
        $objPHPExcel->getActiveSheet()->setCellValue('L12', $precio * $oec3EnsXp);
        
        $objPHPExcel->getActiveSheet()->setCellValue('L18', $precio * $oec3EnsXorgt);

        
        // AICC
        $parameters = $this->proportionalityCalculate($periodo, 10); // AICC
        $precio = $parameters['0']['precio'];
        $aiccXic = $this->aicc['xic'];
        $aiccXem = $this->aicc['xem'];
        $aiccXb = $this->aicc['xb'];
        $aiccXp = $this->aicc['xp'];
        $aiccXorgt = $this->aicc['xorgt'];
        
        $objPHPExcel->getActiveSheet()->setCellValue('M6', $precio * $aiccXic);
        $objPHPExcel->getActiveSheet()->setCellValue('M8', $precio * $aiccXem);
        $objPHPExcel->getActiveSheet()->setCellValue('M10', $precio * $aiccXb);
        $objPHPExcel->getActiveSheet()->setCellValue('M12', $precio * $aiccXp);
        
        $objPHPExcel->getActiveSheet()->setCellValue('M18', $precio * $aiccXorgt);

        ////////////////////////////////////
        //// XORGT
        ////////////////////////////////////
        
        $vpnXorgt = $this->getSubTotalXorgtCostCenter(14, 1, $periodo);
        $adslXorgt = $this->getSubTotalXorgtCostCenter(15, 2, $periodo);

        $objPHPExcel->getActiveSheet()->setCellValue('E18', $vpnXorgt['0']['SubTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E19', $adslXorgt['0']['SubTotal']);
        
        
        try {
                $filename = "RESUMEN_GLOBAL_" . $periodo .  ".xls";
                header("Content-Type: text/html;charset=utf-8");
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header("Content-disposition: attachment; filename=$filename");
                header('Cache-Control: max-age=0');
                
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save('php://output');
        }   catch (Exception $e) {

            echo 'Excepción capturada: ' .  $e->getMessage();

        }

        exit;

    }


    public function getSubTotalByCostCenter($planta, $xarxa, $periodo) {
        
        $statement = "SELECT f.xarxa AS CentroCosto,  SUM(s.precio) AS SubTotal 
                        FROM factura_lote3 AS f INNER JOIN servicios_lote3 AS s ON f.servicio = s.id 
			WHERE f.xarxa = " . $xarxa . " AND f.planta = " . $planta . " AND f.periodo = '" . $periodo . "'";
        
        $adapter = $this->adapter->query($statement);
        $objResult = $adapter->execute();
        
        $result = array();
        foreach ($objResult as $item) {
            $result[] = $item;  
        }

        return $result;

    }
    
    
        public function getSubTotalXorgtCostCenter($xarxa, $planta, $periodo) {
        
        $statement = "SELECT f.xarxa AS CentroCosto,  SUM(s.precio) AS SubTotal 
                        FROM factura_lote3 AS f INNER JOIN servicios_lote3 AS s ON f.servicio = s.id 
			WHERE (f.xarxa = 13 OR f.xarxa = $xarxa ) AND f.planta = " . $planta . " AND f.periodo = '" . $periodo . "'";
        
        $adapter = $this->adapter->query($statement);
        $objResult = $adapter->execute();
        
        $result = array();
        foreach ($objResult as $item) {
            $result[] = $item;  
        }

        return $result;

    }

    public function getMonths($periodo) {
 
        $month = substr($periodo, 4, 2);
        
        return strtoupper($this->months[$month]);

    }
    
    
}