<?php
class facebook
{ 
    private $Id;
    private $PluginCode;
    

    /**
     * Get the value of PluginCode
     */
    public function getPluginCode()
    {
        return $this->PluginCode;
    }

    /**
     * Set the value of PluginCode
     */
    public function setPluginCode($PluginCode): self
    {
        $this->PluginCode = $PluginCode;

        return $this;
    }

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
}