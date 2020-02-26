<?php
/*
 * Description of Auxiliar Form
 * @author Euler Nunez
 */

namespace Facturacion\Form;

use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Adapter;

class Auxiliar extends Form
{
    
    protected $adapter;
    
    public function __construct(AdapterInterface $dbAdapter) {

        $this->adapter =$dbAdapter;
        parent::__construct("InvoiceLote3");

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'periodo',
             'options' => array(
                    'label' => 'Período',
                    'value_options' => array('' => 'Seleccione un período') +  $this->getOptionsForPeriodo(),
             ),
            'attributes' => 
                array(
                    'id' => 'periodo',
                    'required'=> true,
                    'class' => 'form-control',
                ),
            'validators' => array('Int'),            
        ));
    
    }


    public function getOptionsForPeriodo()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT id, periodo FROM periodos ORDER BY id DESC");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['periodo']] = $item['periodo'];
        }
        return $select;
    }

}
