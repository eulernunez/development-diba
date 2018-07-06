<?php
/*
 * Description of Estado [tramites] Form
 * @author Euler Nunez
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;


class Estado extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Estado");

        $this->add(array(
            'name' => 'estado',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Estado',
                ),
            'attributes' => 
                array(
                    'id' => 'estado',
                    'required'=>'required',
                    'placeholder' => 'Título del estado',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'visible',
             'options' => array(
                    'label' => 'Situación',
                    'value_options' => array(
                                        '1' => 'Abierta',
                                        '2' => 'Cerrada')
             ),
            'attributes' => 
                array(
                    'id' => 'visible',
                    'required'=> true,
                    'value' => '1',
                    'class' => 'form-control input-sm',
                    'enable' => false
                ),

            'validators' => array('Int'),            
        ));
        
    }
    
}