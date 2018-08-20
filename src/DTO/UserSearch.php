<?php
namespace App\DTO;

class UserSearch
{

    public $search;

    public $role;
   
    /**
     * Get the value of search
     */ 
    public function getSearch()
    {
        return $this->search;
    }
    /**
     * Set the value of search
     *
     * @return  self
     */ 
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * Get the value of project
     */
    public function getRole()
    {
        return $this->role;
    }
    /**
     * Set the value of project
     *
     * @return  self
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }
}

?>