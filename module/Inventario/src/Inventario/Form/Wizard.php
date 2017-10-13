<?php
/*
 * Description of Wizard Form
 * @author Euler Nunez
 * 
 */

namespace Inventario\Form;


use Zend\Form\Form;
#use Zend\Validator\Regex as RegexValidator;
#use Zend\Validator\EmailAddress as EmailAddress;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class Wizard extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter)
    {

        $this->adapter =$dbAdapter;
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
                    //'required'=>'required',
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
//                    'value_options' => array(
//                        #    'empty_option' => 'Seleccione ... ',
//                            '' => 'Seleccione una opción',
//                            '1' => 'Badalona',
//                            '2' => 'Rubi',
//                            '3' => 'Terrassa',
//                            '4' => 'Sabadell',
//                            '5' => 'Granollers'
//                    ),
                      'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForPoblacion(),  
                 
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
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForProvincia(),
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
            'name' => 'observacion',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes'=>array(
                'id' => 'observaciones',
                'class' => 'form-control input-sm',
                'rows' => 6,
                'placeholder' => 'Ingresa observaciones respecto a la sede ...',
            ),
            'options' => array(
                'label' => 'Observaciones',
            ),
        ));
        

        /* CIRCUITO */
        
        // main
        $this->add(array(
            'name' => 'cadministrativo',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nombre',
                ),
            'attributes' => 
                array(
                    'id' => 'cadministrativo',
                    'required'=>'required',
                    'aria-describedby' => 'cadministrativoHelp',
                    'placeholder' => 'Ingresar el administrativo',
                    'class' => 'form-control input-sm',
                    'maxlength' => 14
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'ctelefono',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Teléfono',
                    
                ),
            'attributes' => 
                array(
                    'id' => 'ctelefono',
                    'required'=>'required',
                    'class' => 'form-control input-sm',
                    'aria-describedby' => 'ctelefonoHelp',
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
    
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ccliente',
             'options' => array(
                    'label' => 'Cliente',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForCliente(),
             ),
            'attributes' => 
                array(
                    'id' => 'ccliente',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ctecnologia',
             'options' => array(
                    'label' => 'Tecnología',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTecnologia(),
             ),
            'attributes' => 
                array(
                    'id' => 'ctecnologia',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'cvelocidad',
             'options' => array(
                    'label' => 'Velocidad',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForVelocidad(),
             ),
            'attributes' => 
                array(
                    'id' => 'cvelocidad',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
            
        ));        

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ccriticidad',
             'options' => array(
                    'label' => 'Criticidad',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForCriticidad(),
             ),
            'attributes' => 
                array(
                    'id' => 'ccriticidad',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
            
        ));        

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'cfactura',
             'options' => array(
                    'label' => 'Factura',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForFactura(),
             ),
            'attributes' => 
                array(
                    'id' => 'cfactura',
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
            
        ));        

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'cestado',
             'options' => array(
                    'label' => 'Estado',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForEstado(),
             ),
            'attributes' => 
                array(
                    'id' => 'cestado',
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
            
        ));        
        
        
//        $this->add(array(
//            'type' => 'Zend\Form\Element\Checkbox',
//            'name' => 'cgestionado',
//            'options' => array(
//                'label' => 'Gestionado',
////                'checked_value' => '1',
////                'unchecked_value' => '2',
////                'use_hidden_element' => true,
//             ),
//            'attributes' => 
//                array(
//                    'id' => 'cgestionado',
//                    'class' => 'form-control input-sm',
//                )
//        ));
        
        
        // backup
        $this->add(array(
            'name' => 'bcadministrativo',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nombre',
                ),
            'attributes' => 
                array(
                    'id' => 'bcadministrativo',
                    'aria-describedby' => 'bcadministrativoHelp',
                    'placeholder' => 'Ingresar el administrativo backup',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'bctelefono',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Teléfono',
                ),
            'attributes' => 
                array(
                    'id' => 'bctelefono',
                    'class' => 'form-control input-sm',
                    'aria-describedby' => 'bctelefonoHelp',
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
    
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'bccliente',
             'options' => array(
                    'label' => 'Cliente',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForCliente(),
             ),
            'attributes' => 
                array(
                    'id' => 'bccliente',
                    'class' => 'form-control input-sm',
                ),
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'bctecnologia',
             'options' => array(
                    'label' => 'Tecnología',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForTecnologia(),
             ),
            'attributes' => 
                array(
                    'id' => 'bctecnologia',
                    'class' => 'form-control input-sm',
                ),
        ));        

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'bcvelocidad',
             'options' => array(
                    'label' => 'Velocidad',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForVelocidad(),
             ),
            'attributes' => 
                array(
                    'id' => 'bcvelocidad',
                    'class' => 'form-control input-sm',
                )
        ));        
    
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'bcestado',
             'options' => array(
                    'label' => 'Estado',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForEstado(),
             ),
            'attributes' => 
                array(
                    'id' => 'bcestado',
                    'class' => 'form-control input-sm',
                )
        ));        
        
        
        /* EQUIPO */

        // main
        
        
        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'eservicio',
             'options' => array(
                    'label' => 'Servicio',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForServicio(),
             ),
            'attributes' => 
                array(
                    'id' => 'eservicio',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
            'name' => 'enemonico',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nemónico',
                ),
            'attributes' => 
                array(
                    'id' => 'enemonico',
                   # 'required'=>'required',
                    'aria-describedby' => 'enemonicoHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'eipgestion',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP Gestión',
                ),
            'attributes' => 
                array(
                    'id' => 'eipgestion',
                    #'required'=>'required',
                    'aria-describedby' => 'eipgestionHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'enivel',
             'options' => array(
                    'label' => 'Nivel',
                    'value_options' => array('1' => 'Nivel 1',
                                             '2' => 'Nivel 2',
                                             '3' => 'Nivel 3',
                                             '4' => 'Nivel 4',
                                             '5' => 'Nivel 5'),
             ),
            'attributes' => 
                array(
                    'id' => 'enivel',
                    'required'=> true,
                    'value' => '1',
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'enemonicon1',
             'options' => array(
                    'label' => 'Nemónico Nivel 1',
                    'value_options' => array('1' => 'NA'),
             ),
            'attributes' => 
                array(
                    'id' => 'enemonicon1',
                    'required'=> true,
                    'value' => '1',
                    'class' => 'form-control input-sm',
                    'enable' => false
                ),

            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'efabricante',
             'options' => array(
                    'label' => 'Fabricante',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForFabricante(),
             ),
            'attributes' => 
                array(
                    'id' => 'efabricante',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'emodelo',
             'options' => array(
                    'label' => 'Modelo',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForModelo(),
             ),
            'attributes' => 
                array(
                    'id' => 'emodelo',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        $this->add(array(
            'name' => 'eserie',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'N/S',
                ),
            'attributes' => 
                array(
                    'id' => 'eserie',
                    'aria-describedby' => 'eserieHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'eubicacion',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Ubicación',
                ),
            'attributes' => 
                array(
                    'id' => 'eubicacion',
                    'aria-describedby' => 'eubicacionHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'elogosalta',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Pedido LOGOS Alta',
                ),
            'attributes' => 
                array(
                    'id' => 'elogosalta',
                    'aria-describedby' => 'elogosaltaHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        
        $this->add(array(
            'name' => 'econtacto',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Contacto',
                ),
            'attributes' => 
                array(
                    'id' => 'econtacto',
                    'aria-describedby' => 'econtactoHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'etelefono',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Teléfono',
                ),
            'attributes' => 
                array(
                    'id' => 'etelefono',
                    'aria-describedby' => 'etelefonoHelp',
                    'placeholder' => 'Ingresa el teléfono del contacto del equipo ...',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'ehorario',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Horario',
                ),
            'attributes' => 
                array(
                    'id' => 'ehorario',
                    'aria-describedby' => 'ehorarioHelp',
                    'placeholder' => 'Ingresa el horario del contacto del equipo ...',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'eestado',
             'options' => array(
                    'label' => 'Estado',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForEstado(),
             ),
            'attributes' => 
                array(
                    'id' => 'eestado',
                    'class' => 'form-control input-sm',
                )
            ));        
        
        $this->add(array(
            'name' => 'eobservacion',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes'=>array(
                'id' => 'eobservacion',
                'class' => 'form-control input-sm',
                'rows' => 4,
                'placeholder' => 'Ingresa observaciones respecto al equipo ...',
            ),
            'options' => array(
                'label' => 'Observaciones',
            ),
        ));
        
        /* Equipo Backup */

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'beservicio',
             'options' => array(
                    'label' => 'Servicio',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForServicio(),
             ),
            'attributes' => 
                array(
                    'id' => 'beservicio',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        $this->add(array(
            'name' => 'benemonico',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nemónico',
                ),
            'attributes' => 
                array(
                    'id' => 'benemonico',
                   # 'required'=>'required',
                    'aria-describedby' => 'benemonicoHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'beipgestion',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP Gestión',
                ),
            'attributes' => 
                array(
                    'id' => 'beipgestion',
                    #'required'=>'required',
                    'aria-describedby' => 'beipgestionHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'benivel',
             'options' => array(
                    'label' => 'Nivel',
                    'value_options' => array('1' => 'Nivel 1',
                                             '2' => 'Nivel 2',
                                             '3' => 'Nivel 3',
                                             '4' => 'Nivel 4',
                                             '5' => 'Nivel 5'),
             ),
            'attributes' => 
                array(
                    'id' => 'benivel',
                    'required'=> true,
                    'value' => '1',
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'benemonicon1',
             'options' => array(
                    'label' => 'Nemónico Nivel 1',
                    'value_options' => array('1' => 'NA'),
             ),
            'attributes' => 
                array(
                    'id' => 'benemonicon1',
                    'required'=> true,
                    'value' => '1',
                    'class' => 'form-control input-sm',
                    'enable' => false
                ),

            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'befabricante',
             'options' => array(
                    'label' => 'Fabricante',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForFabricante(),
             ),
            'attributes' => 
                array(
                    'id' => 'befabricante',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'bemodelo',
             'options' => array(
                    'label' => 'Modelo',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForModelo(),
             ),
            'attributes' => 
                array(
                    'id' => 'bemodelo',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        $this->add(array(
            'name' => 'beserie',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'N/S',
                ),
            'attributes' => 
                array(
                    'id' => 'beserie',
                    'aria-describedby' => 'beserieHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        /* Other side */
        $this->add(array(
            'name' => 'beubicacion',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Ubicación',
                ),
            'attributes' => 
                array(
                    'id' => 'eubicacion',
                    'aria-describedby' => 'eubicacionHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'belogosalta',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Pedido LOGOS Alta',
                ),
            'attributes' => 
                array(
                    'id' => 'elogosalta',
                    'aria-describedby' => 'elogosaltaHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'beobservacion',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes'=>array(
                'id' => 'eobservacion',
                'class' => 'form-control input-sm',
                'rows' => 4,
                'placeholder' => 'Ingresa observaciones respecto al equipo ...',
            ),
            'options' => array(
                'label' => 'Observaciones',
            ),
        ));
        
        
        
        

        /* EQUIPO NO GESTIONADO */

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'engservicio',
             'options' => array(
                    'label' => 'Servicio',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForServicio(),
             ),
            'attributes' => 
                array(
                    'id' => 'engservicio',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'engpropiedad',
             'options' => array(
                    'label' => 'Propiedad Router',
                    'value_options' => array(   '' => 'Seleccione una opción',
                                                '1' => 'Telefónica',
                                                '2' => 'Cliente'),
             ),
            'attributes' => 
                array(
                    'id' => 'engpropiedad',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));                
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'engtipoip',
             'options' => array(
                    'label' => 'Tipo IP',
                    'value_options' => array(   '' => 'Seleccione una opción',
                                                '1' => 'Dinámico',
                                                '2' => 'Estático'),
             ),
            'attributes' => 
                array(
                    'id' => 'engtipoip',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));                
        
        
        $this->add(array(
            'name' => 'engip',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP',
                ),
            'attributes' => 
                array(
                    'id' => 'engip',
                    'required'=>'required',
                    'aria-describedby' => 'engipHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'engred',
             'options' => array(
                    'label' => 'Red',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForRed(),
             ),
            'attributes' => 
                array(
                    'id' => 'engred',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'enguso',
             'options' => array(
                    'label' => 'Usos',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForUso(),
             ),
            'attributes' => 
                array(
                    'id' => 'enguso',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        ////////// Other side
        $this->add(array(
            'name' => 'engcontacto',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Contacto',
                ),
            'attributes' => 
                array(
                    'id' => 'engcontacto',
                    'aria-describedby' => 'engcontactoHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'engtelefono',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Teléfono',
                ),
            'attributes' => 
                array(
                    'id' => 'engtelefono',
                    'aria-describedby' => 'engtelefonoHelp',
                    'placeholder' => 'Ingresa el teléfono del contacto del equipo ...',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'enghorario',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Horario',
                ),
            'attributes' => 
                array(
                    'id' => 'enghorario',
                    'aria-describedby' => 'enghorarioHelp',
                    'placeholder' => 'Ingresa el horario del contacto del equipo ...',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'engobservacion',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes'=>array(
                'id' => 'engobservacion',
                'class' => 'form-control input-sm',
                'rows' => 4,
                'placeholder' => 'Ingresa observaciones respecto al equipo ...',
            ),
            'options' => array(
                'label' => 'Observaciones',
            ),
        ));

    
        /* IP WAN */
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ipwrpv',
             'options' => array(
                    'label' => 'RPV',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForRpv(),
             ),
            'attributes' => 
                array(
                    'id' => 'ipwrpv',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ipwrouting',
             'options' => array(
                    'label' => 'Routing',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForRouting(),
             ),
            'attributes' => 
                array(
                    'id' => 'ipwrouting',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),
        ));

        $this->add(array(
            'name' => 'ipwvlanedc',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Vlan EDC',
                ),
            'attributes' => 
                array(
                    'id' => 'ipwvlanedc',
                   # 'required'=>'required',
                    'aria-describedby' => 'enemonicoHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ipwvlannacional',
             'options' => array(
                    'label' => 'Vlan Nacional',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForVlanNacional(),
             ),
            'attributes' => 
                array(
                    'id' => 'ipwvlannacional',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),
        ));
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ipwred',
             'options' => array(
                    'label' => 'Red',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForRed(),
             ),
            'attributes' => 
                array(
                    'id' => 'ipwred',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'ipwuso',
             'options' => array(
                    'label' => 'Usos',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForUso(),
             ),
            'attributes' => 
                array(
                    'id' => 'ipwuso',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        // Other side
        $this->add(array(
            'name' => 'ipwipwanedc',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP WAN EDC',
                ),
            'attributes' => 
                array(
                    'id' => 'ipwipwanedc',
                    'aria-describedby' => 'ipwipwanedcHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'ipwmascara',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Máscara',
                ),
            'attributes' => 
                array(
                    'id' => 'ipwmascara',
                    'aria-describedby' => 'ipwmascaraHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'ipwpeppal',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'PE Ppal',
                ),
            'attributes' => 
                array(
                    'id' => 'ipwpeppal',
                    'aria-describedby' => 'ipwpeppalHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'ipwpebackup',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'PE Backup',
                ),
            'attributes' => 
                array(
                    'id' => 'ipwpebackup',
                    'aria-describedby' => 'ipwpebackupHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        
        /* IP LAN */
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'iplrpv',
             'options' => array(
                    'label' => 'RPV',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForRpv(),
             ),
            'attributes' => 
                array(
                    'id' => 'iplrpv',
                    'required'=> false,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));

        $this->add(array(
            'name' => 'iplalias',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Alias',
                ),
            'attributes' => 
                array(
                    'id' => 'iplalias',
                   # 'required'=>'required',
                    'aria-describedby' => 'iplaliasHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'iplvlan',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Vlan',
                ),
            'attributes' => 
                array(
                    'id' => 'iplvlan',
                   # 'required'=>'required',
                    'aria-describedby' => 'iplvlanHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'ipliplan',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP Lan',
                ),
            'attributes' => 
                array(
                    'id' => 'ipliplan',
                   # 'required'=>'required',
                    'aria-describedby' => 'ipliplanHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        /* Other side*/
        $this->add(array(
            'name' => 'iplmascara',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Máscara',
                ),
            'attributes' => 
                array(
                    'id' => 'iplmascara',
                    'aria-describedby' => 'iplmascaraHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'iplnat',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Nat',
                ),
            'attributes' => 
                array(
                    'id' => 'iplnat',
                    'aria-describedby' => 'iplnatHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
            )
        ));

        $this->add(array(
            'name' => 'iplinterfaz',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Interfaz',
                ),
            'attributes' => 
                array(
                    'id' => 'iplinterfaz',
                    'aria-describedby' => 'iplinterfazHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
            )
        ));

        
        /* HARDWARE ADICIONAL */
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'hatipo',
             'options' => array(
                    'label' => 'Tipo',
                    'value_options' => array('' => 'Seleccione una opción') 
                        +  $this->getOptionsForHardwareAdicional(),
             ),
            'attributes' => 
                array(
                    'id' => 'hatipo',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'hafabricante',
             'options' => array(
                    'label' => 'Fabricante',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForFabricante(),
             ),
            'attributes' => 
                array(
                    'id' => 'hafabricante',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'hamodelo',
             'options' => array(
                    'label' => 'Modelo',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForModelo(),
             ),
            'attributes' => 
                array(
                    'id' => 'hamodelo',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        

        // Other side
        $this->add(array(
            'name' => 'haserie',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'N/S',
                ),
            'attributes' => 
                array(
                    'id' => 'haserie',
                    'aria-describedby' => 'haserieHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'haalias',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Alias',
                ),
            'attributes' => 
                array(
                    'id' => 'haalias',
                    'aria-describedby' => 'haaliasHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        
        /* HARDWARE ESPECIAL */
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'rpvtarjeta',
             'options' => array(
                    'label' => 'Tarjeta',
                    'value_options' => array('' => 'Seleccione una opción') 
                        +  $this->getOptionsForTarjeta(),
             ),
            'attributes' => 
                array(
                    'id' => 'rpvtarjeta',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'rpvfabricante',
             'options' => array(
                    'label' => 'Fabricante',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForFabricante(),
             ),
            'attributes' => 
                array(
                    'id' => 'rpvfabricante',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),            
        ));        
        
        /* Other side */
        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'rpvmodelo',
             'options' => array(
                    'label' => 'Modelo',
                    'value_options' => array('' => 'Seleccione una opción') +  $this->getOptionsForModelo(),
             ),
            'attributes' => 
                array(
                    'id' => 'rpvamodelo',
                    #'required'=> true,
                    'class' => 'form-control input-sm',
                ),

            'validators' => array('Int'),  
        ));

        $this->add(array(
            'name' => 'rpvserie',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'N/S',
                ),
            'attributes' => 
                array(
                    'id' => 'rpvserie',
                    'aria-describedby' => 'rpvserieHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'rpvalias',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Alias',
                ),
            'attributes' => 
                array(
                    'id' => 'rpvalias',
                    'aria-describedby' => 'rpvaliasHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        /* Other field set IP LAN*/
        /* Side a */
        $this->add(array(
            'name' => 'rpvvlan',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Vlan',
                ),
            'attributes' => 
                array(
                    'id' => 'rpvvlan',
                    'aria-describedby' => 'rpvvlanHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'rpviplan1',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP Lan 1',
                ),
            'attributes' => 
                array(
                    'id' => 'rpviplan1',
                    'aria-describedby' => 'rpviplan1Help',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'rpviplan2',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP Lan 2',
                ),
            'attributes' => 
                array(
                    'id' => 'rpviplan2',
                    'aria-describedby' => 'rpviplan2Help',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        /* Side b */

        $this->add(array(
            'name' => 'rpvmascara',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Máscara',
                ),
            'attributes' => 
                array(
                    'id' => 'rpvmascara',
                    'aria-describedby' => 'rpvmascaraHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'rpvinterfaz1',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Interfaz 1',
                ),
            'attributes' => 
                array(
                    'id' => 'rpvinterfaz1',
                    'aria-describedby' => 'rpvinterfaz1Help',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'rpvinterfaz2',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Interfaz 2',
                ),
            'attributes' => 
                array(
                    'id' => 'rpvinterfaz2',
                    'aria-describedby' => 'rpvinterfaz2Help',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        /* Multicast*/
        /* Side a */
        $this->add(array(
            'name' => 'redwantunelgreppal',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Red Wan Tunel GRE PPAL',
                ),
            'attributes' => 
                array(
                    'id' => 'redwantunelgreppal',
                    'aria-describedby' => 'redwantunelgreppalHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));


        $this->add(array(
            'name' => 'iploppbackgre',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP LoppBack GRE',
                ),
            'attributes' => 
                array(
                    'id' => 'iploppbackgre',
                    'aria-describedby' => 'iploppbackgreHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'ipedctunel1',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP EDC Tunel 1',
                ),
            'attributes' => 
                array(
                    'id' => 'ipedctunel1',
                    'aria-describedby' => 'iploppbackgreHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'ipasrppaltuneloficina',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP ASR PPAL Tunel Oficina',
                ),
            'attributes' => 
                array(
                    'id' => 'ipasrppaltuneloficina',
                    'aria-describedby' => 'ipasrppaltuneloficinaHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));        

        $this->add(array(
            'name' => 'interfaztunelasrppal',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Interfaz Tunel ASR PPAL',
                ),
            'attributes' => 
                array(
                    'id' => 'interfaztunelasrppal',
                    'aria-describedby' => 'interfaztunelasrppalHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        /* Side b */
        $this->add(array(
            'name' => 'redwantunelgrebck',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Red Wan Tunel GRE BCK',
                ),
            'attributes' => 
                array(
                    'id' => 'redwantunelgrebck',
                    'aria-describedby' => 'redwantunelgrebckHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));        
        
        $this->add(array(
            'name' => 'iprp',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP RP',
                ),
            'attributes' => 
                array(
                    'id' => 'iprp',
                    'aria-describedby' => 'iprpHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'ipedctunel2',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP EDC Tunel 2',
                ),
            'attributes' => 
                array(
                    'id' => 'ipedctunel2',
                    'aria-describedby' => 'ipedctunel2Help',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));        
        
        $this->add(array(
            'name' => 'ipasrbcktuneloficina',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'IP ASR BCK Tunel Oficina',
                ),
            'attributes' => 
                array(
                    'id' => 'ipasrbcktuneloficina',
                    'aria-describedby' => 'ipasrbcktuneloficinaHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'interfaztunelasrbck',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Interfaz Tunel ASR BCK',
                ),
            'attributes' => 
                array(
                    'id' => 'interfaztunelasrbck',
                    'aria-describedby' => 'interfaztunelasrbckHelp',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        

    } 
    

    // Other methods
    
    public function getOptionsForPoblacion()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, poblacion FROM poblaciones');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['poblacion'];
        }
        return $select;        
    }   
    
    public function getOptionsForProvincia()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, provincia FROM provincias');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['provincia'];
        }
        return $select;        
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
    
    public function getOptionsForTecnologia()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, tecnologia FROM tecnologias');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['tecnologia'];
        }
        return $select;        
    }   

    public function getOptionsForVelocidad()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, velocidad FROM velocidades');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['velocidad'];
        }
        return $select;        
    }   

    public function getOptionsForCriticidad()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, criticidad FROM criticidades');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['criticidad'];
        }
        return $select;        
    }   
    
    public function getOptionsForFactura()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, factura FROM facturas');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['factura'];
        }
        return $select;        
    }   

    public function getOptionsForEstado()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, estado FROM estados');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['estado'];
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
    
    public function getOptionsForFabricante()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, fabricante FROM fabricantes');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['fabricante'];
        }
        return $select;        
    }

    public function getOptionsForModelo()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, modelo FROM modelos');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['modelo'];
        }
        return $select;
    }

    public function getOptionsForRed()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, red FROM redes');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['red'];
        }
        return $select;
    }
    
    public function getOptionsForUso()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, uso FROM usos');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['uso'];
        }
        return $select;
    }
    
    public function getOptionsForRpv()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, rpv FROM rpvs');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['rpv'];
        }
        return $select;
    }


    public function getOptionsForRouting()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, routing FROM routings');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['routing'];
        }
        return $select;
    }        

    public function getOptionsForVlanNacional()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, vlan FROM vlan_nacionales');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['vlan'];
        }
        return $select;
    }        
    
    
    public function getOptionsForHardwareAdicional() 
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, tipo FROM tipo_hardware_adicional');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['tipo'];
        }
        return $select;
    }
    
    public function getOptionsForTarjeta()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, tarjeta FROM tarjetas');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['tarjeta'];
        }
        return $select;
    }

}    