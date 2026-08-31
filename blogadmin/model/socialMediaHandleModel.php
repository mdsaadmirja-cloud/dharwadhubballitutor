<?php
class socialMediaHandle{
    private $Id;
    private $Name;
    private $Handle;
    private $icon;
    private $CreatedBy;
    private $CreatedOn;
    private $ModifiedBy;
    private $ModifiedOn;
    

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
    public function setId($Id)
    {
        $this->Id = $Id;

        return $this;
    }

    /**
     * Get the value of Name
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * Set the value of Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * Get the value of Handle
     */
    public function getHandle()
    {
        return $this->Handle;
    }

    /**
     * Set the value of Handle
     */
    public function setHandle($Handle)
    {
        $this->Handle = $Handle;

        return $this;
    }

    /**
     * Get the value of CreatedBy
     */
    public function getCreatedBy()
    {
        return $this->CreatedBy;
    }

    /**
     * Set the value of CreatedBy
     */
    public function setCreatedBy($CreatedBy)
    {
        $this->CreatedBy = $CreatedBy;

        return $this;
    }

    /**
     * Get the value of CreatedOn
     */
    public function getCreatedOn()
    {
        return $this->CreatedOn;
    }

    /**
     * Set the value of CreatedOn
     */
    public function setCreatedOn($CreatedOn)
    {
        $this->CreatedOn = $CreatedOn;

        return $this;
    }

    /**
     * Get the value of ModifiedBy
     */
    public function getModifiedBy()
    {
        return $this->ModifiedBy;
    }

    /**
     * Set the value of ModifiedBy
     */
    public function setModifiedBy($ModifiedBy)
    {
        $this->ModifiedBy = $ModifiedBy;

        return $this;
    }

    /**
     * Get the value of ModifiedOn
     */
    public function getModifiedOn()
    {
        return $this->ModifiedOn;
    }

    /**
     * Set the value of ModifiedOn
     */
    public function setModifiedOn($ModifiedOn)
    {
        $this->ModifiedOn = $ModifiedOn;

        return $this;
    }

    /**
     * Get the value of icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }
}
?>