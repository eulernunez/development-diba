<?php
/**
 * Ser
 * @author Euler Nunez 
 */
// module/Buscador/src/Buscador/Model/Parameter.php

namespace Buscador\Model;
use Zend\Session\Container;

abstract class  Service {
    
    protected $session; 
    protected $userRole;
    protected $nif;
    
    
    public function __construct() {
        $this->session = new Container('User');
        $this->userRole = $this->session->offsetGet('userRole');
        $this->nif = $this->session->offsetGet('firstName');        
    }
    
    
}