<?php
/*
 * Description of Invoice Form
 * @author Euler Nunez
 */

namespace Facturacion\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;


class Invoice extends Form
{

    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("Invoice");

        // id_fecha_fact
        $this->add(array(
            'name' => 'periodo',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Período',
                ),
            'attributes' => 
                array(
                    'id' => 'periodo',
                    'required'=>'required',
                    'readonly'=>'true',
                    //'placeholder' => 'Ingresa el nombre del solicitante',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'idLcifCliente',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'LCIF',
                ),
            'attributes' => 
                array(
                    'id' => 'idLcifCliente',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'idCifCliente',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'CIF',
                ),
            'attributes' => 
                array(
                    'id' => 'idCifCliente',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'descNomEnt',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Entidad',
                ),
            'attributes' => 
                array(
                    'id' => 'descNomEnt',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'descTipoServicio',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Servicio',
                ),
            'attributes' => 
                array(
                    'id' => 'descTipoServicio',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'idMulticonexion',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'ID Multiconexión',
                ),
            'attributes' => 
                array(
                    'id' => 'idMulticonexion',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'idNumeroComercial',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Número comercial',
                ),
            'attributes' => 
                array(
                    'id' => 'idNumeroComercial',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        // CODIGO CONCEPTO CP Cuotas Períodicas
        $this->add(array(
            'name' => 'idCodigoConcepto',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Código concepto',
                ),
            'attributes' => 
                array(
                    'id' => 'idCodigoConcepto',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'idConceptoFacturable',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'ID concepto facturable',
                ),
            'attributes' => 
                array(
                    'id' => 'idConceptoFacturable',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'descConceptoFacturable',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Concepto facturable',
                ),
            'attributes' => 
                array(
                    'id' => 'descConceptoFacturable',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'unidades',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Unidades',
                ),
            'attributes' => 
                array(
                    'id' => 'unidades',
                    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'inicioPeriodoFact',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Fecha inicio',
                ),
            'attributes' => 
                array(
                    'id' => 'inicioPeriodoFact',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'finPeriodoFact',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Fecha fin',
                ),
            'attributes' => 
                array(
                    'id' => 'finPeriodoFact',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'importeTotalEquipo',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Importe total equipo',
                ),
            'attributes' => 
                array(
                    'id' => 'importeTotalEquipo',
                    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'precioMensualLote3',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Importe mensual lote3',
                ),
            'attributes' => 
                array(
                    'id' => 'precioMensualLote3',
                    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

        $this->add(array(
            'name' => 'totalSinIva',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Total(s/ IVA)',
                ),
            'attributes' => 
                array(
                    'id' => 'totalSinIva',
                    'required'=>'required',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        /* UZ */
        $this->add(array(
            'name' => 'idCodCli',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'ID código cliente',
                ),
            'attributes' => 
                array(
                    'id' => 'idCodCli',
                    //'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'descServicioLote3',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Servicio Lote 3',
                ),
            'attributes' => 
                array(
                    'id' => 'descServicioLote3',
                    //'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));
        
        $this->add(array(
            'name' => 'descLote',
            'type' => 'Zend\Form\Element\Text',
            'options' => 
                array(
                    'label' => 'Descripción Lote',
                ),
            'attributes' => 
                array(
                    'id' => 'descLote',
                    'required'=>'required',
                    'readonly'=>'true',
                    'class' => 'form-control input-sm'
                ),
            'filters' => array(
                 array('name' => 'Zend\Filter\StringTrim'),
                 array('name' => 'Zend\Filter\StringToLower'),
             )
        ));

    }

}