<?php
class terms
{
    private $id;
    private $description;
    
    /**
     * Get the value of id
     */
    public function getid()
    {
        return $this->id;
    }
    
    /**
     * Set the value of id
     */
    public function setid($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * Get the value of description
     */
    public function getdescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setdescription($description)
    {
        $this->description = $description;

        return $this;
    }  
}
