<?php
/**
 * Description of Cliente Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Cliente.php

namespace Inventario\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;

class Cliente extends AbstractTableGateway {

    protected $table = 'clientes';
    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    
    
    
    public function getNifForCliente($id)
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT nif FROM clientes WHERE id = '" . $id . "'");
        $result = [];
        foreach ($statement->execute() as $item) {
            $result = $item['nif'];
        }
        return $result;        
    }   
    
    

}