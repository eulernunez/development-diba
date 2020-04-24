<?php
/*
 * Description of Planta Form
 * @author Euler Nunez
 */

namespace Facturacion\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class Planta extends Form
{
    
    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Planta");
    
        $this->add(array(
            'name' => 'sede',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Sede',
                ),
            'attributes' => 
                array(
                    'id' => 'sede',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'direccion',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Direccion',
                ),
            'attributes' => 
                array(
                    'id' => 'direccion',
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
             'name' => 'cif',
             'options' => array(
                    'label' => 'Cif',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForCifs(),
             ),
            'attributes' => 
                array(
                    'id' => 'cif',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'cliente',
             'options' => array(
                    'label' => 'Cliente',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForClientes(),
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
             'name' => 'xarxa',
             'options' => array(
                    'label' => 'Xarxa',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForXarxas(),
             ),
            'attributes' => 
                array(
                    'id' => 'xarxa',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));        
        
       $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'poblacion',
             'options' => array(
                    'label' => 'Poblacion',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForPoblaciones(),
             ),
            'attributes' => 
                array(
                    'id' => 'poblacion',
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
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForServicios(),
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
             'name' => 'tipo',
             'options' => array(
                    'label' => 'Tipo',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTipos(),
             ),
            'attributes' => 
                array(
                    'id' => 'Tipo',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
            'name' => 'caudal',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Caudal',
                ),
            'attributes' => 
                array(
                    'id' => 'caudal',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'velocidad',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'velocidad',
                ),
            'attributes' => 
                array(
                    'id' => 'velocidad',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
       
        $this->add(array(
            'name' => 'administrativo',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Administrativo',
                ),
            'attributes' => 
                array(
                    'id' => 'administrativo',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'backup',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Administrativo Backup',
                ),
            'attributes' => 
                array(
                    'id' => 'backup',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'factura',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Factura',
                ),
            'attributes' => 
                array(
                    'id' => 'factura',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'modelo',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Modelo',
                ),
            'attributes' => 
                array(
                    'id' => 'modelo',
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
             'name' => 'propiedad',
             'options' => array(
                    'label' => 'Propiedad',
                    'value_options' => array(
                            '2' => 'Seleccione una opción',
                            '0' => 'No',
                            '1' => 'Si',
                            '3' => 'ND'
                    ),
             ),
            'attributes' => 
                array(
                    'id' => 'propiedad',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
             
            'validators' => array('Int'),            
            
        ));        

         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ipfija',
             'options' => array(
                    'label' => 'IP Fija',
                    'value_options' => array(
                            '2' => 'Seleccione una opción',
                            '0' => 'No',
                            '1' => 'Si',
                            '3' => 'ND'
                    ),
             ),
            'attributes' => 
                array(
                    'id' => 'ipfija',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
             
            'validators' => array('Int'),            
            
        ));        

        $this->add(array(
            'name' => 'direccionip',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Dirección IP',
                ),
            'attributes' => 
                array(
                    'id' => 'direccionip',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
         
        $this->add(array(
            'name' => 'iplan',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP Lan',
                ),
            'attributes' => 
                array(
                    'id' => 'iplan',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        
        $this->add(array(
            'name' => 'observaciones',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes'=>array(
                'id' => 'observaciones',
                //'required'=>'required',
                'class' => 'form-control input-sm',
                'rows' => 11,
                'placeholder' => 'Ingresa información ...',
            ),
            'options' => array(
                'label' => 'Observaciones',
            ),
        ));
        
        
        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'estado',
             'options' => array(
                    'label' => 'Estado',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForEstados(),
             ),
            'attributes' => 
                array(
                    'id' => 'estado',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
       
       
       
    }
    

    public function getOptionsForCifs()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, cif FROM pl_cifs');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['cif'];
        }
        return $select;        
    }    
    
    public function getOptionsForClientes()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, cliente FROM pl_clientes');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['cliente'];
        }
        return $select;        
    }    
    
    public function getOptionsForXarxas()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, xarxa FROM pl_xarxas');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['xarxa'];
        }
        return $select;        
    }    
    
    public function getOptionsForPoblaciones()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, poblacion FROM pl_poblaciones');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['poblacion'];
        }
        return $select;        
    }    
    
    public function getOptionsForServicios()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, servicio FROM pl_servicios');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['servicio'];
        }
        return $select;        
    }    
    
    public function getOptionsForTipos()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, tipo FROM pl_tipos');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['tipo'];
        }
        return $select;        
    }    
    
    public function getOptionsForEstados()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, estado FROM pl_estados');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['estado'];
        }
        return $select;        
    }    
    
}