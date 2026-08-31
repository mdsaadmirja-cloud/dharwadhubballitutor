<?php
class notification
{
    public $Id;
    public $Message;
    public $Status;
    public $Category;


    /**
     * Get the value of Id
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * Set the value of Id
     */
    public function setId($Id): self
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Get the value of Message
     */
    public function getMessage()
    {
        return $this->Message;
    }

    /**
     * Set the value of Message
     */
    public function setMessage($Message): self
    {
        $this->Message = $Message;

        return $this;
    }

    /**
     * Get the value of Status
     */
    public function getStatus()
    {
        return $this->Status;
    }

    /**
     * Set the value of Status
     */
    public function setStatus($Status): self
    {
        $this->Status = $Status;

        return $this;
    }
  

    /**
     * Get the value of Category
     */
    public function getCategory()
    {
        return $this->Category;
    }

    /**
     * Set the value of Category
     */
    public function setCategory($Category): self
    {
        $this->Category = $Category;

        return $this;
    }
}
