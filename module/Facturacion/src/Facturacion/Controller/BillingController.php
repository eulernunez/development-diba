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
use Zend\Session\Container;

class BillingController extends AbstractActionController
{

    protected $processingBillService;
    protected $periodo;
    
    public function setProcessingBillService($service) {
        $this->processingBillService = $service;
        return $this;
    }

    public function loadAction()
    {
        
        $session = new Container('User');
        $userRole = $session->offsetGet('userRole');
        $nif = $session->offsetGet('firstName');
        $result = array(
            'Role' => $userRole,
            'Nif' => $nif);

        die('<pre>' . print_r($result,true) . '</pre>');

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
        
        //die('<pre>' . print_r($facturas, true) . '</pre>');
        
        if(is_array($facturas)) { 

            $viewmodel = 
                    new ViewModel(
                            array('facturas' => $facturas));
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
    
}