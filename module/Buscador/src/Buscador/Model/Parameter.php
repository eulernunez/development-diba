<?php
/**
 * Description of Parameter Table
 * @author Euler Nunez 
 */
// module/Buscador/src/Buscador/Model/Parameter.php

namespace Buscador\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;

class Parameter extends AbstractTableGateway {

    protected $table = 'parameter_search';
    protected $adapter;

    public function __construct(Adapter $adapter) {
        
        $this->adapter = $adapter;

    }

    public function getOptionsForParameter($id)
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT id, parameter FROM parameter_search WHERE section_id = '" . $id . "'");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['parameter'];
        }
        
        return $select;        
    }   


    
    
}