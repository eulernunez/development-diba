<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Facturacion\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Facturacion\Form\Invoice;
use Facturacion\Form\InvoiceLote3;
use Facturacion\Form\Auxiliar;
use Facturacion\Form\Planta;

# HACK TO RESOLVE PROBLEM
use Zend\Session\Container;

class BillingController extends AbstractActionController
{

    protected $processingBillService;
    protected $periodo;
    
    public function setProcessingBillService($service) {
        $this->processingBillService = $service;
        return $this;
    }

    protected function attachDefaultListeners() {

        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach('dispatch', array($this, 'preDispatch'), 50);
        //$events->attach('dispatch', array($this, 'postDispatch'), -100);

    }

    public function preDispatch () {
        # HACK TO RESOLVE PROBLEM
        $session = new Container('User');
        if(empty($session->offsetGet('userRole'))&&empty($session->offsetGet('firstName'))) {
            return $this->redirect()->toRoute('users',
                array('controller'=> 'User',
                      'action' => 'logout'));
        }
    }

    public function loadAction()
    {

        //die('Hi!, 3ur3ka');
        return [];

    }

    public function processAction()
    {

        $posts = (array)$this->request->getPost();
        echo('POSTS: <pre>' . print_r($posts, true) . '</pre>');

        $this->periodo = $posts['limite'];
        echo('PERIODO@controller: <pre>' . print_r($this->periodo, true) . '</pre>');
        //$uploadDir =  realpath(dirname(__DIR__) . './Doc' ) . '/';
        //$uploadDir = "C:\diba\upload\\"; # ANALYSIS
        $uploadDir = "C:/diba/upload/";

        $fileTypes = array('txt');
        $verifyToken = md5('unique_salt' . $posts['timestamp']);

        if (!empty($_FILES) && $posts['token'] == $verifyToken) {

            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $uploadDir . $_FILES['Filedata']['name'];

            $fileParts = pathinfo($_FILES['Filedata']['name']);
            if (in_array(strtolower($fileParts['extension']), $fileTypes)) {
                move_uploaded_file($tempFile, $targetFile);
                $this->processingBillService->setFilePath($targetFile)
                    ->dataValidation($this->periodo)
                    ->fileUpload()->setDates()->setDaysToPurchase()
                    ->setDaysToPurchase()->setMonthsToPurchase()
                    ->billingLote3()
                    ->billingResto()
                    ->billingLote3Pending()
                    ->billingRestoPending();
                    //->invoiceDownload();
            } else {
                echo 'Invalid file type.';
                //die('DOS');
            }

        }

        $viewmodel = new ViewModel();
        $viewmodel->setTerminal(true);
        
        return $viewmodel;

//        return [];
        
//        $this->redirect()->toRoute('invoice-download');
        
    }

    public function process333Action()
    {

        $header = array();
        $header[] 
            = array(
                #'0' => utf8_decode('CÓDIGO'),
                '0' => 'NIF',
                '1' => 'ENTIDAD',
                '2' => 'SERVICIO',
                '3' => 'SOLICITANTE',
                '4' => 'FECHA SOLICITUD',
                '5' => 'FECHA FIN',
                '6' => utf8_decode('LÍNEA'),
                '7' => utf8_decode('PETICIÓN'),
                #'9' => 'TRAMITADOR',
                #'10' => 'SEDE',
                '8' => 'ASUNTO',
                '9' => 'ESTADO',
                '10' => 'FECHA DE PROCESO',
                '11' => 'D [Total]',
                '12' => 'H [Total]',
                '13' => ':m [Total]',
                '14' => ':s [Total]',
                '15' => 'D [Parado]',
                '16' => 'H [Parado]',
                '17' => ':m [Parado]',
                '18' => ':s [Parado]',
                '19' => 'D [Real]',
                '20' => 'H [Real]',
                '21' => ':m [Real]',
                '22' => ':s [Real]'
                ); 

        $supplies = array();
        
//        foreach ($data as $item) {
//
//            $linea = strip_tags($item->linea);
//            $line = (empty($linea)) ? '': '> '. $linea;
//            $supplies[] 
//                = array(
//                    #'0' => '# ' . $item->id,
//                    '0' => strip_tags($item->cif),
//                    '1' => utf8_decode(strip_tags($item->entitat)),
//                    '2' => utf8_decode(strip_tags($item->servicio)),
//                    '3' => utf8_decode(strip_tags($item->solicitante)),
//                    '4' => strip_tags($item->datecreated),
//                    '5' => strip_tags($item->fin),
//                    '6' => strval($linea), #$line,
//                    '7' => utf8_decode(strip_tags($item->peticion)),
//                    #'8' => utf8_decode(strip_tags($item->tramitador)),
//                    #'10' => utf8_decode(strip_tags($item->sede)),
//                    '8' => utf8_decode(strip_tags($item->asunto)),
//                    '9' => utf8_decode(strip_tags($item->estado)),
//                    '10' => $item->currentdate,
//                    '11' => $item->d . 'D',
//                    '12' => $item->h . 'H' ,
//                    '13' => $item->m . 'm',
//                    '14' => $item->s . 's',
//                    '15' => $item->dstop . 'D',
//                    '16' => $item->hstop . 'H' ,
//                    '17' => $item->mstop . 'm',
//                    '18' => $item->sstop . 's',
//                    '19' => $item->dreal . 'D',
//                    '20' => $item->hreal . 'H' ,
//                    '21' => $item->mreal . 'm',
//                    '22' => $item->sreal . 's'
//                );
//
//        }

        header('Content-Type: text/csv; charset=utf-8');                                      #CSV
        header('Content-Disposition: attachment; filename=' . time() .  '-PROVISIONES.csv');  #CSV

        $output = fopen('php://output', 'w');

        $excels = array_merge($header, $supplies);

        foreach($excels as $line) {
            fputcsv($output, $line, ';');                                                     #CSV
        }

        fclose($output);

        exit();

    }

    /* 
     * Download initial document FACT-CHECK
     */
    public function invoiceDownloadAction()
    {

        $posts = (array)$this->request->getPost();
        $periodo = $posts['intervalo'];

//        die('$periodo: <pre>' . print_r($periodo, true) . '</pre>');
//        $periodo = (int)$this->params()->fromRoute('periodo');

        $handler = 
            $this->processingBillService->invoiceDownload($periodo);

        $viewmodel = new ViewModel();
        $viewmodel->setTerminal(true);

        return $viewmodel;

// REDIRECT
//          $this->redirect()->toRoute('billing-load');
//          $this->redirect()->refresh();

    }        
            
    public function listAction() {
        //getFormalities
        $facturas = $this->processingBillService->getInvoices();
        $largePeriodo = array_shift($facturas);
        $smallPeriodo = (int)substr($largePeriodo, 0, 6);
        
        //die('<pre>' . print_r($smallPeriodo, true) . '</pre>');

        if(is_array($facturas)) { 

            $viewmodel = 
                    new ViewModel(
                            array('facturas' => $facturas,
                                  'periodo' => $smallPeriodo));
            return $viewmodel;

        }

        
    }
    
    
    public function browserAction() {
        
        
        
    }
    
    public function tableTreeAction() {

        $totals = $this->processingBillService->getTotalByEntities();
        $subTotals = $this->processingBillService->getSubTotalByPhone();
        $details = $this->processingBillService->getDetails();
//        $str = 'Barcelona-ESBCN-Spain';
//        preg_match('/-(.*?)-/', $str, $a);
//        $resultado = $a[0];
//        $a = explode("-",$str);
//        $resultado = $a[2];
        
        $subTotalsByPhone = array();
        foreach ($subTotals as $item) {
            $subTotalsByPhone[$item['id_titular_serv']][] = $item;
        }

        $detailsGroupByPhone = array();
        foreach ($details as $item) {
            $detailsGroupByPhone[$item['id_telefono']][] = $item;
        }
        
//        echo('<pre>' . print_r($detailsGroupByPhone, true) . '</pre>');
//        die('DEAD');
        $viewmodel = 
            new ViewModel(
                    array('totals' => $totals,
                          'subTotalsByPhone'  => $subTotalsByPhone,
                          'detailsGroupByPhone' => $detailsGroupByPhone)
                        );

        return $viewmodel;

    }
    
    
    public function invoiceAction() {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $id = (int)$this->params()->fromRoute('id');

        $information = $this->processingBillService->getInvoice($id);
        $form = new Invoice($dbAdapter);

        $viewmodel = 
            new ViewModel(
                    array('information' => $information,
                          'form' => $form));

        return $viewmodel;

    }
    
    public function updateInvoiceAction() {

        $posts = (array)$this->request->getPost();
        $this->processingBillService->setPostParams($posts);
        $invoiceId = $this->processingBillService->updateInvoice();

        if($invoiceId) {
            $this->redirect()->toRoute('list-invoices');
        }

    }

    public function invoiceDeleteAction()
    {

        $posts = (array)$this->request->getPost();
        $this->processingBillService->setPostParams($posts);

        //echo('ProcessingBillService:<pre>' . print_r($this->processingBillService, true) . '</pre>');        
        
        $invoiceId = $this->processingBillService->deleteInvoice(); // deleteSupply

        if($invoiceId) {
            $this->redirect()->toRoute('list-invoices');
        }
//        $viewmodel = new ViewModel(array());
//        $viewmodel->setTerminal(true);
//        return $viewmodel;

    }
    
    
    public function invoiceCreateAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Invoice($dbAdapter);

        $viewmodel = new ViewModel(array(
                                    'form' => $form
                                    ));
        return  $viewmodel;

    }
    
    public function saveInvoiceAction()
    {

        $posts = (array)$this->request->getPost();

        $this->processingBillService->setPostParams($posts);
        $invoiceId = $this->processingBillService->saveInvoice();

        //It's good
        if($invoiceId) {
            $this->redirect()->toRoute('list-invoices');
        }

    }

    
    public function invoiceValidationAction()
    {

        $posts = (array)$this->request->getPost();
        //die('<pre>' . print_r($posts, true) . '</pre>');

        $result = $this->processingBillService->invoiceValidate();

        //It's good
        if($result) {
            $this->redirect()->toRoute('invoice-filter');
        }

    }

    public function invoiceFilterAction() {

        $viewmodel = 
            new ViewModel(
                    array());

        return $viewmodel;

    }
    
    public function invoiceFilterResultAction() {

        $params = $this->getRequest()->getQuery()->toArray();
        $periodo = (string)$params['periodo'];


        if(empty($periodo)) {
            
            die('DEAD -  3ur3ka');

        }
        
        $totals = $this->processingBillService->getTotalByEntities($periodo);
        $subTotals = $this->processingBillService->getSubTotalByPhone();
        $details = $this->processingBillService->getDetails();
        
        $subTotalsByPhone = array();
        foreach ($subTotals as $item) {
            $subTotalsByPhone[$item['id_titular_serv']][] = $item;
        }

        $detailsGroupByPhone = array();
        foreach ($details as $item) {
            $detailsGroupByPhone[$item['id_telefono']][] = $item;
        }
        
        $viewmodel = 
            new ViewModel(
                    array('totals' => $totals,
                          'subTotalsByPhone'  => $subTotalsByPhone,
                          'detailsGroupByPhone' => $detailsGroupByPhone)
                        );

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function uiSummationAction() {
        
        $viewmodel = 
            new ViewModel(
                    array());

        return $viewmodel;

    }
    
    public function lotSummationAction() {
        
        $posts = (array)$this->request->getPost();
        
        if(empty($posts['periodo'])) {
            die('DEAD');
            
        } else {
            $periodo = (string)$posts['periodo'] . '28';
            $handler = 
                $this->processingBillService->sumatoriaLotes($periodo);
            
        }
        
    }
    
    public function invoiceFilterExportAction() {
        
        $posts = (array)$this->request->getPost();
        
        if(empty($posts['periodo'])) {
            die('DEAD');
        } else {
            
            $periodo = (string)$posts['periodo'];

            $totals = $this->processingBillService->getTotalByEntities($periodo);
            $subTotals = $this->processingBillService->getSubTotalByPhone();
            $details = $this->processingBillService->getDetails();

            $subTotalsByPhone = array();
            foreach ($subTotals as $item) {
                $subTotalsByPhone[$item['id_titular_serv']][] = $item;
            }

            $detailsGroupByPhone = array();
            foreach ($details as $item) {
                $detailsGroupByPhone[$item['id_telefono']][] = $item;
            }            

            $result = array();
            $element = array();
            $subElement = array();
            $thirdElement = array();

            foreach($totals as $item){
                $element['ENTIDAD'] = $item['id_titular_serv'] . ' - ' . $item['desc_titular_serv_lote3'];
                $element['LINEA'] = '';
                $element['DETALLE_ESTANDAR'] = '';
                $element['DETALLE_LOTE3'] = '';
                $element['LOTE3'] = $item['total_lote3'];
                $element['RESTO'] = $item['total_resto_servicios'];
                $element['TOTAL'] = (float)$item['total_lote3'] + (float)$item['total_resto_servicios'];
                $result[] = $element;
                unset($element);
                foreach($subTotalsByPhone[$item["id_titular_serv"]] as $phone){
                    $subElement['ENTIDAD'] = '';
                    $subElement['LINEA'] = $phone['id_telefono'];
                    $subElement['DETALLE_ESTANDAR'] = '';
                    $subElement['DETALLE_LOTE3'] = '';
                    $subElement['LOTE3'] = $phone['total_lote3'];
                    $subElement['RESTO'] = $phone['total_resto_servicios'];
                    $subElement['TOTAL'] = (float)$phone['total_lote3'] + (float)$phone['total_resto_servicios'];
                    $result[] = $subElement;
                    unset($subElement);
                    foreach($detailsGroupByPhone[$phone["id_telefono"]] as $detail) {
                        $thirdElement['ENTIDAD'] = '';
                        $thirdElement['LINEA'] = '';
                        $thirdElement['DETALLE_ESTANDAR'] = $detail['desc_servicio_estandard'];
                        $thirdElement['DETALLE_LOTE3'] = $detail['desc_servicio_lote3'];
                        $thirdElement['LOTE3'] = $detail['lote3'];
                        $thirdElement['RESTO'] = $detail['resto_servicios'];
                        $thirdElement['TOTAL'] = (float)$detail['lote3'] + (float)$detail['resto_servicios'];
                        $result[] = $thirdElement;
                        unset($thirdElement);
                    }
                }
            }
        
            $handler = 
            $this->processingBillService->invoiceExport($periodo, $result);
            
        }
        
    }
    
    public function invoiceComparisonAction() {

        $viewmodel = 
            new ViewModel(
                    array());

        return $viewmodel;

    }
    
    public function comparisonBetweenInmediatePeriodsAction() {
        
        $params = $this->getRequest()->getQuery()->toArray();
        
        $percent = (int)$params['percent'];
        $periodo = (string)$params['periodo'];
        
        $records = array();
        
        if($periodo != '0'){
            $records = $this->processingBillService->comparisonProcess($percent, $periodo);
        }

        //die('TABLE: <pre>' . print_r($records['result'], true) . '</pre>');
        if(is_array($records['result'])) {

            $viewmodel = 
                    new ViewModel(
                            array(
                                'records' => $records['result'],
                                'periodo' => $periodo,
                                'periodocontiguo' => $records['periodoContiguo'],
                                ));
            $viewmodel->setTerminal(true);

            return $viewmodel;

        }

    }
    
    public function lote3SignUpAction() {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new InvoiceLote3($dbAdapter);
        $periodo = (int)$this->params()->fromRoute('periodo');
        
        $date = new \DateTime();

        $dateTime = $date->format('d/m/Y H:i:s');

        $viewmodel = new ViewModel(array(
                                    'form' => $form,
                                    'dateTime' => $dateTime,
                                    'periodo' => $periodo));
        return  $viewmodel;

    }
    
    public function lote3BillingSaveAction()
    {
        
        $posts = (array)$this->request->getPost();
        
        //die('SAVE POST: <pre>' . print_r($posts, true) . '</pre>');
        $this->processingBillService->setPostParams($posts);
        $invoiceId = $this->processingBillService->saveLote3Invoice();

        //It's good
        $this->redirect()->toRoute('lote3-invoice-list');

    }

    public function lote3InvoiceListAction()
    {

        $lote3Invoices = $this->processingBillService->getLote3Invoices();

        $periodo = array_shift($lote3Invoices);
        
        $isValilatedPeriod = $this->processingBillService->checkPeriodo($periodo);

        if(is_array($lote3Invoices)) { 

            $viewmodel = 
                    new ViewModel(
                            array('lote3Invoices' => $lote3Invoices,
                                  'periodo' => $periodo,
                                  'isValilatedPeriod' => $isValilatedPeriod));
            return $viewmodel;

        }
  
  
    }
    
    public function plantasListAction()
    {
        
        $plantas = $this->processingBillService->getPlantas();

        if(is_array($plantas)) { 

            $viewmodel = 
                    new ViewModel(
                            array('plantas' => $plantas));
            return $viewmodel;

        }
  
  
    }
    
    public function exportTemplateUiAction() {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Auxiliar($dbAdapter);
        
        $viewmodel = 
            new ViewModel(
                    array('form' => $form));

        return $viewmodel;

    }
    
    public function exportGlobalUiAction() {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Auxiliar($dbAdapter);

        $viewmodel = 
            new ViewModel(
                    array('form' => $form));

        return $viewmodel;

    }
    
    public function templateExportAction()
    {
        
        $posts = (array)$this->request->getPost();
        
        $this->processingBillService->setPostParams($posts)->templateExport();
        
        
    }
    
    public function globalTemplateExportAction()
    {
        
        $posts = (array)$this->request->getPost();
        
        $this->processingBillService->setPostParams($posts)->globalTemplateExport();
        
    }

    public function invoiceUpdateAction() {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $id = (int)$this->params()->fromRoute('id');

        $information = $this->processingBillService->getLote3Invoice($id);

        //die('<pre>' . print_r($information, true) . '</pre>');
        
        $form = new InvoiceLote3($dbAdapter);

        $viewmodel = 
            new ViewModel(
                    array('information' => $information,
                          'form' => $form));

        return $viewmodel;

    }

    public function plantaUpdateAction() {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $id = (int)$this->params()->fromRoute('id');
        
        $information = $this->processingBillService->getPlanta($id);

        $form = new Planta($dbAdapter);
        
        //die('<pre>' . print_r($form, true) . '</pre>');

        $viewmodel = 
            new ViewModel(
                    array('information' => $information,
                          'form' => $form));

        return $viewmodel;

    }
    
    public function invoiceLote3UpdateAction() {


        $posts = (array)$this->request->getPost();
        
        $this->processingBillService->setPostParams($posts);
        $invoiceId = $this->processingBillService->updateLote3Invoice();

        if($invoiceId) {
            $this->redirect()->toRoute('lote3-invoice-list');
        }

    }

    public function plantaUpdateConfirmAction() {

        $posts = (array)$this->request->getPost();
        $this->processingBillService->setPostParams($posts);
        $plantaId = $this->processingBillService->plantaUpdateConfirm();

        if($plantaId) {
            $this->redirect()->toRoute('plantas-list');
        }

    }
    
    public function invoicePeriodUiAction() {

        $viewmodel = 
            new ViewModel(
                    array());

        return $viewmodel;

    }
    
    public function invoicePeriodGettingAction() {
        
        $params = $this->getRequest()->getQuery()->toArray();
        $periodo = (string)$params['periodo'];
         
        $result = $this->processingBillService->invoicePeriodExecute($periodo);

        $viewmodel = 
            new ViewModel(
                    array());
        
         $viewmodel->setTerminal(true);

            return $viewmodel;

    }

    public function lote3InvoiceValidationAction() {
        
        $posts = (array)$this->request->getPost();
        
        $result = $this->processingBillService->Lote3InvoiceValidate($posts['data']);
        
        //It's good
        if($result) {
            $this->redirect()->toRoute('export-global-ui');
        }
        
        
    }
    
}