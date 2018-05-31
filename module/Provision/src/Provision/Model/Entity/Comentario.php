<?php
/**
 * Description of Comentario
 * @author Euler Nuñez
 */

// module/Provision/src/Provision/Model/Entity/Comentario.php

namespace Provision\Model\Entity;

class Comentario {

    protected $id;
    protected $asunto;
    protected $comment;
    protected $created;
    
    protected $tramitacionId;
    protected $comentaristaId;
    
    
    public function __construct(array $options = null) {
        
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    
    
    public function getAsunto() {
        return $this->asunto;
    }

    public function setAsunto($asunto) {
        $this->asunto = $asunto;
        return $this;
    }
    
    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
        return $this;
    }
    
    public function getCreated() {
        return $this->created;
    }

    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }
    
    public function getTramitacionId() {
        return $this->tramitacionId;
    }

    public function setTramitacionId($tramitacionId) {
        $this->tramitacionId = $tramitacionId;
        return $this;
    }

    public function getComentaristaId() {
        return $this->comentaristaId;
    }

    public function setComentaristaId($comentaristaId) {
        $this->comentaristaId = $comentaristaId;
        return $this;
    }

}