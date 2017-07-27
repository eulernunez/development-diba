<?php
/*
 * Description of SedesTable
 *
 * @author Euler Nunez
 * 
 */

namespace Inventario\Form;


use Zend\Form\Form;
#use Zend\Validator\Regex as RegexValidator;
#use Zend\Validator\EmailAddress as EmailAddress;


class Wizard extends Form
{

    public function __construct($name = null)
    {

        parent::__construct("Wizard");
        $this->setAttribute('method', 'post');        
        
        /* SEDE */
        $this->add(array(
            'name' => 'nombre',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nombre',
                ),
            'attributes' => 
                array(
                    'id' => 'nombre',
                    'required'=>'required',
                    'placeholder' => 'Ingresa el nombre de la sede',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'idescat',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Idescat',
                    
                ),
            'attributes' => 
                array(
                    'id' => 'idescat',
                    'required'=>'required',
                    'aria-describedby' => 'idescatHelp',
                    'class' => 'form-control input-sm',
                    
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
    
        
        $this->add(array(
            'name' => 'direccion',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes'=>array(
                'id' => 'direccion',
                'required'=>'required',
                'class' => 'form-control input-sm',
                'placeholder' => 'Ingresa la dirección de la sede ...',
                'rows' => 3,
            ),
            'options' => array(
                'label' => 'Dirección',
            ),
        ));
        
        
         $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'poblacion',
             #'required' => true,
             'options' => array(
                    'label' => 'Población',
                    'value_options' => array(
                        #    'empty_option' => 'Seleccione ... ',
                            '' => 'Seleccione una opción',
                            '0' => 'Badalona',
                            '1' => 'Rubi',
                            '2' => 'Terrassa',
                            '3' => 'Sabadell',
                            '4' => 'Granollers'
                    ),
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
             'name' => 'provincia',
             #'required' => true,
             'options' => array(
                    'label' => 'Provincia',
                    'value_options' => array(
                        #    'empty_option' => 'Seleccione ... ',
                            '' => 'Seleccione una opción',
                            '0' => 'Barcelona',
                            '1' => 'Tarragona',
                            '2' => 'Lérida',
                            '3' => 'Gerona'
                    ),
             ),
            'attributes' => 
                array(
                    'id' => 'provincia',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
            
        ));        
         
         
        $this->add(array(
            'name' => 'horario',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nombre',
                ),
            'attributes' => 
                array(
                    'id' => 'horario',
                    'required'=>'required',
                    'aria-describedby' => 'horarioHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'contacto',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Contacto',
                    'aria-describedby' => 'contactoHelp',
                ),
            'attributes' => 
                array(
                    'id' => 'contacto',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));


        $this->add(array(
            'name' => 'telefono',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Teléfono',
                    'aria-describedby' => 'telefonoHelp',
                ),
            'attributes' => 
                array(
                    'id' => 'telefono',
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
                'class' => 'form-control input-sm',
                'rows' => 6,
            ),
            'options' => array(
                'label' => 'Observaciones',
            ),
        ));
        

        /* CIRCUITO */
        
        // administrativo
        $this->add(array(
            'name' => 'administrativo',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nombre',
                ),
            'attributes' => 
                array(
                    'id' => 'administrativo',
                    'required'=>'required',
                    'aria-describedby' => 'administrativoHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        
        
    } 
    
    
}    