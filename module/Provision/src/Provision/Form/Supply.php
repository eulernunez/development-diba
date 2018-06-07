<?php
/*
 * Description of Supply Form
 * @author Euler Nunez
 */

namespace Provision\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class Supply extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Supply");

        $this->add(array(
            'name' => 'solicitante',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Solicitante',
                ),
            'attributes' => 
                array(
                    'id' => 'solicitante',
                    'required'=>'required',
                    'placeholder' => 'Ingresa el nombre del solicitante',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'asunto',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Asunto',
                ),
            'attributes' => 
                array(
                    'id' => 'asunto',
                    'required'=>'required',
                    'placeholder' => 'Ingresa el asunto',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'linea',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Adm/Tel/Ext',
                ),
            'attributes' => 
                array(
                    'id' => 'linea',
                    //'required'=>'required',
                    'aria-describedby' => 'lineaHelp',
                    'placeholder' => 'Ingresa la línea',
                    'class' => 'form-control input-sm',
                    'maxlength' => 14,
                    'pattern' => '^[0-9]{1,}$'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'midas',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Midas',
                ),
            'attributes' => 
                array(
                    'id' => 'midas',
                    //'required'=>'required',
                    'aria-describedby' => 'midasHelp',
                    'placeholder' => 'Ingresa el midas',
                    'class' => 'form-control input-sm',
                    'maxlength' => 14,
                    'pattern' => '^[0-9]{1,}$'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'inicio',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Fecha de solicitud',
                ),
            'attributes' => 
                array(
                    'id' => 'inicio',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'odinvoz',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Odin Voz',
                ),
            'attributes' => 
                array(
                    'id' => 'odinvoz',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'bj',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'BJ',
                ),
            'attributes' => 
                array(
                    'id' => 'bj',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'odindatos',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Odin Datos',
                ),
            'attributes' => 
                array(
                    'id' => 'odindatos',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'sg',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'SG',
                ),
            'attributes' => 
                array(
                    'id' => 'sg',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'atlas',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Atlas',
                ),
            'attributes' => 
                array(
                    'id' => 'atlas',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'visord',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Visord',
                ),
            'attributes' => 
                array(
                    'id' => 'visord',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'cliente',
             'options' => array(
                    'label' => 'Entidades',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForCliente(),
             ),
            'attributes' => 
                array(
                    'id' => 'cliente',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'sede',
             'options' => array(
                    'label' => 'Sedes',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForSede(),
             ),
            'attributes' => 
                array(
                    'id' => 'sede',
                //    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'lote',
             'options' => array(
                    'label' => 'Lote',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForLote(),
             ),
            'attributes' => 
                array(
                    'id' => 'lote',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'servicio',
             'options' => array(
                    'label' => 'Servicio',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForServicio(),
             ),
            'attributes' => 
                array(
                    'id' => 'servicio',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'peticion',
             'options' => array(
                    'label' => 'Peticion',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForPeticion(),
             ),
            'attributes' => 
                array(
                    'id' => 'peticion',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'tramitador',
             'options' => array(
                    'label' => 'Tramitador',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTramitador(),
             ),
            'attributes' => 
                array(
                    'id' => 'tramitador',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
        // Comment
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'comentarista',
             'options' => array(
                    'label' => 'Tramitador',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTramitador(),
             ),
            'attributes' => 
                array(
                    'id' => 'comentarista',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
        // Finish
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'comentaristaf',
             'options' => array(
                    'label' => 'Tramitador',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTramitador(),
             ),
            'attributes' => 
                array(
                    'id' => 'comentaristaf',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
        // Reopen
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'comentaristar',
             'options' => array(
                    'label' => 'Tramitador',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTramitador(),
             ),
            'attributes' => 
                array(
                    'id' => 'comentaristar',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'comentaristaa',
             'options' => array(
                    'label' => 'Tramitador',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTramitador(),
             ),
            'attributes' => 
                array(
                    'id' => 'comentaristaa',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'comentaristad',
             'options' => array(
                    'label' => 'Tramitador',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTramitador(),
             ),
            'attributes' => 
                array(
                    'id' => 'comentaristad',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'estado',
             'options' => array(
                    'label' => 'Estado',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForEstado(),
             ),
            'attributes' => 
                array(
                    'id' => 'estado',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
            'name' => 'descripcion',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes'=>array(
                'id' => 'descripcion',
                 'required'=>'required',
                'class' => 'form-control input-sm',
                'rows' => 6,
                'placeholder' => 'Ingresa la descripción ...',
            ),
            'options' => array(
                'label' => 'Descripción',
            ),
        ));

    }


    public function getOptionsForCliente()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, cliente FROM clientes');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['cliente'];
        }
        return $select;        
    }   

    public function getOptionsForSede()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, nombre FROM sedes');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['nombre'];
        }
        return $select;
    }

    public function getOptionsForLote()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, lote FROM lotes');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['lote'];
        }
        return $select;
    }

    public function getOptionsForServicio()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, servicio FROM servicios');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['servicio'];
        }
        return $select;        
    }   
    
    public function getOptionsForPeticion()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, peticion FROM peticiones');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['peticion'];
        }
        return $select;        
    }
    
    public function getOptionsForTramitador()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, tramitador FROM tramitadores');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['tramitador'];
        }
        return $select;        
    }
    
    public function getOptionsForEstado()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, estados FROM estado_tramites WHERE visible = 1');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['estados'];
        }
        return $select;
    }

}