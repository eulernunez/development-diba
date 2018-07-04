<?php
/**
 * Description of Maintenance Service
 * @author Euler Nuñez
 */
// module/Provision/src/Application/Model/Maintenance/MaintenanceService.php

namespace Application\Model\Maintenance;


class MaintenanceService {

    protected $adapter;
    protected $posts;

    public function setAdapter($adapter) {
        $this->adapter = $adapter;
        return $this;
    }

    public function setPostParams($posts) {
        $this->posts = $posts;
        return $this;
    }
    
    public function saveTableMaintenance() {
        
        $postEntity = $this->posts['entity'];
        $class = "\Application\Model\Entity" .  $postEntity;
        $entity = new $class();
        $entity->setOptions($this->posts);
        $class2 = "\Application\Model" .  $postEntity;
        $table = new $class2($this->adapter);
        $result = $table->save($entity);
        
        return $result;
        
    }

}