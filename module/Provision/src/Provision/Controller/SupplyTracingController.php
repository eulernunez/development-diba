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
        $json = $posts['data'];
        $data = json_decode($json);
        
        $header = array();
        $header[] 
            = array(
                '0' => utf8_decode('CÓDIGO'),
                '1' => 'NIF',
                '2' => 'ENTIDAD',
                '3' => 'SERVICIO',
                '4' => 'SOLICITANTE',
                '5' => 'FECHA SOLICITUD',
                '6' => 'FECHA FIN',
                '7' => utf8_decode('LÍNEA'),
                '8' => utf8_decode('PETICIÓN'),
                '9' => 'TRAMITADOR',
                '10' => 'SEDE',
                '11' => 'ASUNTO',
                '12' => 'ESTADO',
                '13' => 'FECHA ACTUAL',
                '14' => utf8_decode('DÍAS'),
                '15' => 'HORAS',
                '16' => 'MINUTOS',
                '17' => 'SEGUNDOS'
            ); 

        $supplies = array();
        
        foreach ($data as $item) {
            
            $linea = strip_tags($item->linea);
            $line = (empty($linea)) ? '': '> '. $linea;
            $supplies[] 
                = array(
                    '0' => '# ' . $item->id,
                    '1' => strip_tags($item->cif),
                    '2' => utf8_decode(strip_tags($item->entitat)),
                    '3' => utf8_decode(strip_tags($item->servicio)),
                    '4' => utf8_decode(strip_tags($item->solicitante)),
                    '5' => strip_tags($item->datecreated),
                    '6' => strip_tags($item->fin),
                    '7' => $line,
                    '8' => utf8_decode(strip_tags($item->peticion)),
                    '9' => utf8_decode(strip_tags($item->tramitador)),
                    '10' => utf8_decode(strip_tags($item->sede)),
                    '11' => utf8_decode(strip_tags($item->asunto)),
                    '12' => utf8_decode(strip_tags($item->estado)),
                    '13' => $item->currentdate,
                    '14' => $item->d . 'D',
                    '15' => $item->h . 'H' ,
                    '16' => $item->m . 'm',
                    '17' => $item->s . 's'
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
            fputcsv($output, $line, ';');                                                       #CSV
//            fputcsv($output, $line, "\t");
        }

        fclose($output);

        exit();

    }
    

}
