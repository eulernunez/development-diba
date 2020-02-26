<?php
/*
 * Description of Supply Form
 * @author Euler Nunez
 */

namespace Facturacion\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class InvoiceLote3 extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("InvoiceLote3");

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'organismo',
             'options' => array(
                    'label' => 'Organismo',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForOrganismo(),
             ),
            'attributes' => 
                array(
                    'id' => 'organismo',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'planta',
             'options' => array(
                    'label' => 'Planta',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForPlanta(),
             ),
            'attributes' => 
                array(
                    'id' => 'planta',
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
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForXarxa(),
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
             'name' => 'titular',
             'options' => array(
                    'label' => 'Titular',
                    'value_options' => array('0' => 'Seleccione una opción') +  $this->getOptionsForTitular(),
             ),
            'attributes' => 
                array(
                    'id' => 'titular',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'oficina',
             'options' => array(
                    'label' => 'Oficina',
                    'value_options' => array('0' => 'Seleccione una opción') + $this->getOptionsForOficina(),
             ),
            'attributes' => 
                array(
                    'id' => 'oficina',
                    'required'=> true,
                    'class' => 'form-control',
                    'title' => 'Seleccione la Oficina',
                    'data-header' => 'Seleccione la oficina',
                    'data-live-search' => 'true'
                ),
            'validators' => array('Int'),            
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'servicio',
             'options' => array(
                    'label' => 'Servicio',
                    'value_options' => $this->getOptionsForServicio()
             ),
            'attributes' => 
                array(
                    'id' => 'servicio',
                    'required'=> true,
                    'class' => 'form-control',
                    'title' => 'Seleccione el Servicio',
                    'data-header' => 'Seleccione el Servicio',
                    'data-live-search' => 'true'
                ),
            'validators' => array('Int'),            
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
            'name' => 'linea',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Número Comercial',
                ),
            'attributes' => 
                array(
                    'id' => 'linea',
                //    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'ip',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP Fija',
                ),
            'attributes' => 
                array(
                    'id' => 'ip',
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
             'name' => 'clave',
             'options' => array(
                    'label' => 'Clave Cobro',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForClave(),
             ),
            'attributes' => 
                array(
                    'id' => 'clave',
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
        
        
    }
    
    
    public function getOptionsForOrganismo()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, organismo FROM organismos');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['organismo'];
        }
        return $select;        
    }
    
    public function getOptionsForPlanta()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, planta FROM plantas');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['planta'];
        }
        return $select;        
    }
    
    public function getOptionsForXarxa()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, xarxa FROM xarxas');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['xarxa'];
        }
        return $select;        
    }

    public function getOptionsForTitular()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, titular FROM titulares');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['titular'];
        }
        return $select;        
    }

    public function getOptionsForOficina()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT id, oficina AS dato FROM sedes_lote3");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['dato'];
        }
        return $select;        
    }
    
    public function getOptionsForServicio()
    {
        $dbAdapter = $this->adapter;
        $statement = 
            $dbAdapter->query("SELECT id, CONCAT(codigo_servicio,' ',servicio,' ',descripcion,' ',descripcion_detallada,'  €  ',precio) AS dato FROM servicios_lote3");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['dato'];
        }
        return $select;
    }

    public function getOptionsForClave()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, clave FROM clave_cobros');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['clave'];
        }
        return $select;
    }
    
    public function getOptionsForEstado()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, estado FROM estados_lote3');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['estado'];
        }
        return $select;
    }
    
    
}    