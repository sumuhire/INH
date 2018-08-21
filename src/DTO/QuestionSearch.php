<?php

namespace App\DTO;

class QuestionSearch
{
    public $title;

    public $target_department;
    public $search;
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
}