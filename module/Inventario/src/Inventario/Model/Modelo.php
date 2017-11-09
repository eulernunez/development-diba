<?php
/**
 * Description of Modelos Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Modelo.php

namespace Inventario\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;

// TEST

class Modelo extends AbstractTableGateway {

    
    protected $table = 'modelos';
    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getOptionsForModelos($id)
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT id, modelo FROM modelos WHERE fabricante_id = '" . $id . "'");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['modelo'];
        }
        return $select;
    }

}