<?php
/*
 * Description of Sede Form
 * @author Euler Nunez
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;


class Sede extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Sede");

        $this->add(array(
            'name' => 'oficina',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Oficina',
                ),
            'attributes' => 
                array(
                    'id' => 'oficina',
                    'required'=>'required',
                    'placeholder' => 'Nombre de la oficina',
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
                    'label' => 'Dirección',
                ),
            'attributes' => 
                array(
                    'id' => 'direccion',
                    'required'=>'required',
                    'placeholder' => 'Dirección',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        
    }
    
}
