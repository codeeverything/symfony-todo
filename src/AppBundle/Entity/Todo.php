<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="todos")
 */
class Todo extends BaseEntity {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    /**
     * @ORM\Column(type="string")
     */
    public $name;
    
    /**
     * @ORM\Column(type="boolean")
     */
    public $completed;
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function setCompleted($completed) {
        $this->completed = $completed;
    }
    
    public function getCompleted() {
        return $this->completed;
    }
}