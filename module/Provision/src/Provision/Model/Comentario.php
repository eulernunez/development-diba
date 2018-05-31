<?php

/**
 * Description of Comentario Table
 * @author Euler Nunez 
 */
// module/Provision/src/Provision/Model/Comentario.php

namespace Provision\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Comentario extends AbstractTableGateway {

    protected $table = 'comentarios';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function saveComentario(Entity\Comentario $comment) {
        
        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(
            'asunto' => $comment->getAsunto(),
            'comentario' => $comment->getComment(),
            'tramitacion_id' => $comment->getTramitacionId(),
            'comentarista_id' => $comment->getComentaristaId()    
        );

        
        $id = (int) $comment->getId();
        
        if ($id == 0) {
            $data['created'] = date("Y-m-d H:i:s");
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            if (!$this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            return $id;
        }

        else return false;
        
    }
    
    
    
}