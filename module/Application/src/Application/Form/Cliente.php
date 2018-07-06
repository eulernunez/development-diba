<?php
/*
 * Description of Cliente Form
 * @author Euler Nunez
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;


class Cliente extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Cliente");

        $this->add(array(
            'name' => 'cliente',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Cliente',
                ),
            'attributes' => 
                array(
                    'id' => 'cliente',
                    'required'=>'required',
                    'placeholder' => 'Título del cliente',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'nif',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nif',
                ),
            'attributes' => 
                array(
                    'id' => 'nif',
                    'required'=>'required',
                    'placeholder' => 'NIF',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        
    }
    
}