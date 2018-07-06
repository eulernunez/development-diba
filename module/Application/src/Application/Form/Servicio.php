<?php
/*
 * Description of Servicio Form
 * @author Euler Nunez
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;


class Servicio extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Servicio");

        $this->add(array(
            'name' => 'servicio',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Servicio',
                ),
            'attributes' => 
                array(
                    'id' => 'servicio',
                    'required'=>'required',
                    'placeholder' => 'Título del servicio',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        
        
    }
    
}