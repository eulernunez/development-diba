<?php
/*
 * Description of Buscador Form
 * @author Euler Nunez
 * 
 */

namespace Buscador\Form;


use Zend\Form\Form;
use Zend\Db\Adapter\AdapterInterface;



class Search extends Form
{

    protected $adapter;
    protected $sectionId;

    public function __construct(AdapterInterface $dbAdapter, $sectionId = null)
    {

        $this->sectionId = $sectionId;
        $this->adapter =$dbAdapter;

        parent::__construct("Search");
        $this->setAttribute('method', 'get');

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'section',
            'options' => array(
                'label' => 'Sección',
                'value_options' => array('0' => 'Todos') +  $this->getOptionsForSection(),
             ),
            'attributes' => 
                array(
                    'id' => 'section',
                    'required'=> true,
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),
        ));

        $this->add(array(
             'type' => 'Zend\Form\Element\Select',
             'name' => 'parameters',
             'options' => array(
                    'label' => 'Parámetros',
                   'value_options' => $this->getOptionsForParameter(),   
             ),
            'attributes' => 
                array(
                    'id' => 'parameters',
                    'class' => 'form-control input-sm',
                ),
            'validators' => array('Int'),            
        ));        

        
        
        
    }

    public function getOptionsForSection()
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query('SELECT id, section FROM section_search');
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['section'];
        }
        return $select;        
    }   

    public function getOptionsForParameter()
    {
        $id = (int)$this->getSectionId();
        
        $select = [];
        if($id>0) {
            $dbAdapter = $this->adapter;
            $statement = $dbAdapter->query("SELECT id, parameter FROM parameter_search WHERE section_id = '" . $id . "'");
            
            foreach ($statement->execute() as $item) {
                $select[$item['id']] = $item['parameter'];
            }
        } else {
            $select = array('0' => 'Todos');
        }
        return $select;        
    }   
    
    public function getSectionId()
    {
        if(isset($this->sectionId)) {
            return $this->sectionId;
        } else {
            return 0;
        }
    }

    
}