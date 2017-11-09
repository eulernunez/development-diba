<?php
/**
 * Description of Sede
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Circuito.php

namespace Inventario\Model\Entity;

class Circuito {

    protected $id;
    protected $cadministrativo;
    protected $xcadministrativo;
    protected $ctelefono;
    protected $cibenet;
    protected $ccliente;
    protected $ctecnologia;
    protected $cvelocidad;
    protected $ccriticidad;
    protected $cfactura;
    protected $cestado;
    protected $cgestionado;
    protected $cbackup;
    
    protected $sedeId;
    
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

    
    public function getCadministrativo() {
        return $this->cadministrativo;
    }

    public function setCadministrativo($cadministrativo) {
        $this->cadministrativo = $cadministrativo;
        return $this;
    }
    
    public function getXcadministrativo() {
        return $this->xcadministrativo;
    }

    public function setXcadministrativo($xcadministrativo) {
        $this->xcadministrativo = $xcadministrativo;
        return $this;
    }
    
    
    public function getCtelefono() {
        return $this->ctelefono;
    }

    public function setCtelefono($ctelefono) {
        $this->ctelefono = $ctelefono;
        return $this;
    }

    public function getCibenet() {
        return $this->cibenet;
    }

    public function setCibenet($cibenet) {
        $this->cibenet = $cibenet;
        return $this;
    }
    
    
    public function getCcliente() {
        return $this->ccliente;
    }

    public function setCcliente($ccliente) {
        $this->ccliente = $ccliente;
        return $this;
    }

    public function getCtecnologia() {
        return $this->ctecnologia;
    }

    public function setCtecnologia($ctecnologia) {
        $this->ctecnologia = $ctecnologia;
        return $this;
    }

    public function getCvelocidad() {
        return $this->cvelocidad;
    }

    public function setCvelocidad($cvelocidad) {
        $this->cvelocidad = $cvelocidad;
        return $this;
    }

    public function getCcriticidad() {
        return $this->ccriticidad;
    }

    public function setCcriticidad($ccriticidad) {
        $this->ccriticidad = $ccriticidad;
        return $this;
    }
    
    public function getCfactura() {
        return $this->cfactura;
    }

    public function setCfactura($cfactura) {
        $this->cfactura = $cfactura;
        return $this;
    }

    public function getCestado() {
        return $this->cestado;
    }

    public function setCestado($cestado) {
        $this->cestado = $cestado;
        return $this;
    }
    
    public function getCgestionado() {
        return $this->cgestionado;
    }

    public function setCgestionado($cgestionado) {
        $this->cgestionado = $cgestionado;
        return $this;
    }
    
    public function getCbackup() {
        return $this->cbackup;
    }

    public function setCbackup($cbackup) {
        $this->cbackup = $cbackup;
        return $this;
    }
    
    public function getSedeId() {
        return $this->sedeId;
    }

    public function setSedeId($sedeId) {
        $this->sedeId = $sedeId;
        return $this;
    }
    
    
}

