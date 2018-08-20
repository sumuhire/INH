<?php

namespace App\DTO;

class QuestionSearch
{
    public $title;
    public $search;
    public $targetDepartment;
    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
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
     * Get the value of targetDepartment
     */ 
    public function getTargetDepartment()
    {
        return $this->targetDepartment;
    }
    /**
     * Set the value of targetDepartment
     *
     * @return  self
     */ 
    public function setTargetDepartment($targetDepartment)
    {
        $this->targetDepartment = $targetDepartment;
        return $this;
    }
}