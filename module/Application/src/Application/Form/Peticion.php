<?php
/*
 * Description of Peticion Form
 * @author Euler Nunez
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;


class Peticion extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Peticion");

        $this->add(array(
            'name' => 'peticion',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Trámite',
                ),
            'attributes' => 
                array(
                    'id' => 'peticion',
                    'required'=>'required',
                    'placeholder' => 'Tipo de trámite',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        
        
    }
    
}