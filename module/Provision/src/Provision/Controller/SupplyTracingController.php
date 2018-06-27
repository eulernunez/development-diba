<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Provision\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Provision\Form\Supply;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Driver\Pdo\Resul;


class SupplyTracingController extends AbstractActionController
{

    protected $supplyTracingService;
    
    public function setSupplyTracingService($service) {
        $this->supplyTracingService = $service;
        return $this;
    }
    
    public function indexAction()
    {
        return [];
    }
    
    public function openAction()
    {

        $tramites = $this->supplyTracingService->getFormalities();
        
        if(is_array($tramites)) { 

            $viewmodel = 
                    new ViewModel(
                            array('tramites' => $tramites));
            return $viewmodel;

        }
  
    }

    public function closeAction()
    {

        $visible = 2;
        $tramites = $this->supplyTracingService->getFormalities($visible);
        
        if(is_array($tramites)) { 

            $viewmodel = 
                    new ViewModel(
                            array('tramites' => $tramites));
            return $viewmodel;

         }
        
        
    }        
    
    public function supplyAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $id = $this->params()->fromRoute('id');
        $visible = (int)$this->params()->fromRoute('visible');
        if('0' == $visible ) {
            $visible = 1;
        }

        $information = $this->supplyTracingService->getFormality($id, $visible);
        $form = new Supply($dbAdapter);
        //die('$information: <pre>' . print_r($information, true) . '</pre>');

        $viewmodel = 
            new ViewModel(
                    array('information' => $information,
                          'form' => $form));

        return $viewmodel;
    }


    public function watchStoppingAction()
    {

        $posts = (array)$this->request->getPost();

        
        $id = (int)$posts['id'];
        //$paradaId = (int)$posts['parada'];
        
        /* Update el registro con la fecha y motivo en TABLE [paradas] */
        
        
        
        $this->supplyTracingService->setPostParams($posts);
        $result = $this->supplyTracingService->watchStopping();
        
        $information = $this->supplyTracingService->getFormality($id);
        //die('$information: <pre>' . print_r($information, true) . '</pre>');
        $viewmodel = new ViewModel(array(
                                    'information' => $information));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }

    public function restartWatchAction()
    {

        $posts = (array)$this->request->getPost();

        $id = (int)$posts['id'];


        $this->supplyTracingService->setPostParams($posts);
        $result = $this->supplyTracingService->restartWatch();
        $information = $this->supplyTracingService->getFormality($id);

        $viewmodel = new ViewModel(array(
                                    'information' => $information));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }
    
    public function createAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Supply($dbAdapter);
        
        $date = new \DateTime();
        
        $dateTime = $date->format('d/m/Y H:i:s');
        
        $viewmodel = new ViewModel(array(
                                    'form' => $form,
                                    'dateTime' => $dateTime));
        return  $viewmodel;

    }
    
    public function saveSupplyAction()
    {

        $posts = (array)$this->request->getPost();
        
        $this->supplyTracingService->setPostParams($posts);
        $supplyId = $this->supplyTracingService->saveSupply();
        
        //die('<pre>' . print_r($supplyId, true) . '</pre>');        
                
        
        
        //It's good
        $this->redirect()->toRoute('open-supply-tracing');

    }

    public function updateSupplyAction()
    {
        
        $posts = (array)$this->request->getPost();
        $this->supplyTracingService->setPostParams($posts);
        $supplyId = $this->supplyTracingService->updateSupply();
        
        //die('<pre>' . print_r($posts, true) . '</pre>');
        $this->redirect()->toRoute('open-supply-tracing');

    }        
    
    public function supplyFinishAction()
    {
        
        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['finishComment'];
        
        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->finishSupply();
        
        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;


    }        
    
    public function supplyRemoveAction()
    {
        
        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['removeComment'];
        
        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->removeSupply();
        
        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
        
    }        
    
    public function supplyDeleteAction()
    {

        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['deleteComment'];
        $comentaristaId = (int)$posts['comentarista'];

        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->deleteSupply();
        $comentarista = $this->supplyTracingService->getComentarista($comentaristaId);

        $created = date("Y-m-d H:i:s");
        $viewmodel = new ViewModel(
                        array('comment' => $comment,
                              'comentarista' => $comentarista,
                              'created' => $created));
        $viewmodel->setTerminal(true);

        return $viewmodel;
   
    }

    public function addCommentAction()
    {
        
        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['addComment'];
        
        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->addComment();
        
        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }

    public function supplyReopenAction()
    {

        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['reopenComment'];

        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->reopenSupply();

        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function exportDataAction()
    {

        $posts = (array)$this->request->getPost();
        
        if(!isset($posts['data'])) {
            $this->supplyTracingService->setPostParams($posts);
            $data = (array)$this->supplyTracingService->getSuppliesToReport();
        } elseif (isset($posts['data'])) {
            $json = $posts['data'];
            $data = json_decode($json);
        }
        //die('<pre>' . print_r($data, true) . '</pre>');

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
        
        foreach ($data as $item) {

            $linea = strip_tags($item->linea);
            $line = (empty($linea)) ? '': '> '. $linea;
            $supplies[] 
                = array(
                    #'0' => '# ' . $item->id,
                    '0' => strip_tags($item->cif),
                    '1' => utf8_decode(strip_tags($item->entitat)),
                    '2' => utf8_decode(strip_tags($item->servicio)),
                    '3' => utf8_decode(strip_tags($item->solicitante)),
                    '4' => strip_tags($item->datecreated),
                    '5' => strip_tags($item->fin),
                    '6' => strval($linea), #$line,
                    '7' => utf8_decode(strip_tags($item->peticion)),
                    #'8' => utf8_decode(strip_tags($item->tramitador)),
                    #'10' => utf8_decode(strip_tags($item->sede)),
                    '8' => utf8_decode(strip_tags($item->asunto)),
                    '9' => utf8_decode(strip_tags($item->estado)),
                    '10' => $item->currentdate,
                    '11' => $item->d . 'D',
                    '12' => $item->h . 'H' ,
                    '13' => $item->m . 'm',
                    '14' => $item->s . 's',
                    '15' => $item->dstop . 'D',
                    '16' => $item->hstop . 'H' ,
                    '17' => $item->mstop . 'm',
                    '18' => $item->sstop . 's',
                    '19' => $item->dreal . 'D',
                    '20' => $item->hreal . 'H' ,
                    '21' => $item->mreal . 'm',
                    '22' => $item->sreal . 's'
                );

            }

//        $file = 'attachment; filename=' . time() .  '-PROVISIONES.csv';
//        $this->getResponse()->getHeaders()->addHeaders(
//            array(
//                'Content-Type' => 'text/csv',
//                'charset' => 'utf-8',
//                'Content-Disposition' => $file
//        ));

        header('Content-Type: text/csv; charset=utf-8');                                      #CSV
        header('Content-Disposition: attachment; filename=' . time() .  '-PROVISIONES.csv');  #CSV

//        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
//        header('Content-Disposition: attachment; filename=' . time() .  '-PROVISIONES.xls');
        
        $output = fopen('php://output', 'w');

        $excels = array_merge($header, $supplies);
        
        foreach($excels as $line) {
            fputcsv($output, $line, ';');                                                     #CSV
//            fputcsv($output, $line, "\t");
        }

        fclose($output);

        exit();

    }
    

    public function customExportAction() {

        // On the supply service develop business logic
        // END >> Create the report to export
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Supply($dbAdapter);
        
        
        $viewmodel = new ViewModel(array(
                                    'form' => $form ));
        return  $viewmodel;        


    }

}
