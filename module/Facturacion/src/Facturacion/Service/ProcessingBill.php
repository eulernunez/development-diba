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

    public function __construct() {
        parent::__construct();
        $this->objPHPExcel = new \PHPExcel();
        $this->time = time();
        $this->clientScopeFilter = "";
        if('Cliente' == $this->userRole) {
            $this->clientScopeFilter = " AND id_cif_cliente ='" . $this->nif . "'";
        }
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
                
                if($contiguosTotal > $total) {
                    
                    $margen = (float)(($contiguosTotal - $total)*100/$total);
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

    
    
    
}