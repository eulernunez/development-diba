<?php
/**
 * Description of Velocidad Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Velocidad.php

namespace Inventario\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;

class Velocidad extends AbstractTableGateway {

    protected $table = 'velocidades';
    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getOptionsForVelocidad($id)
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT id, velocidad FROM velocidades WHERE tecnologia_id = '" . $id . "'");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['velocidad'];
        }
        return $select;
    }

}