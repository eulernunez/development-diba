<?php
/**
 * Description of Supply Tracing Service
 * @author Euler Nuñez
 */
// module/Provision/src/Provision/Model/SupplyService/SupplyTracingService.php

namespace Provision\Model\SupplyTracing;


class SupplyTracingService {

    protected $adapter;
    protected $params;

    public function setAdapter($adapter) {
        $this->adapter = $adapter;
        return $this;
    }

    public function getFormalities() {

        $statement =
            "SELECT * ";
         
        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();

        return $result;

    }
    
    
    
}