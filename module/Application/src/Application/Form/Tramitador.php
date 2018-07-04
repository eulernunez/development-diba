<?php
/*
 * Description of Tramitador Form
 * @author Euler Nunez
 */

namespace Application\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;


class Tramitador extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Tramitador");

        $this->add(array(
            'name' => 'tramitador',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Solicitante',
                ),
            'attributes' => 
                array(
                    'id' => 'tramitador',
                    'required'=>'required',
                    'placeholder' => 'Nombre completo',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

    }
    
}