<?php
class Business
{
    private $business_name;
    private $business_address;
    private $business_contact;
    private $business_tag;
    private $business_email;
    private $business_id;
    private $business_GSTIN;
    private $business_logoImage;
    private $business_aboutBusiness;

    /**
     * Get the value of business_name
     */
    public function getBusinessName()
    {
        return $this->business_name;
    }

    /**
     * Set the value of business_name
     */
    public function setBusinessName($business_name)
    {
        $this->business_name = $business_name;

        return $this;
    }

    /**
     * Get the value of business_address
     */
    public function getBusinessAddress()
    {
        return $this->business_address;
    }

    /**
     * Set the value of business_address
     */
    public function setBusinessAddress($business_address)
    {
        $this->business_address = $business_address;

        return $this;
    }

    /**
     * Get the value of business_contact
     */
    public function getBusinessContact()
    {
        return $this->business_contact;
    }

    /**
     * Set the value of business_contact
     */
    public function setBusinessContact($business_contact)
    {
        $this->business_contact = $business_contact;

        return $this;
    }

    /**
     * Get the value of business_tag
     */
    public function getBusinessTag()
    {
        return $this->business_tag;
    }

    /**
     * Set the value of business_tag
     */
    public function setBusinessTag($business_tag)
    {
        $this->business_tag = $business_tag;

        return $this;
    }

    /**
     * Get the value of business_email
     */
    public function getBusinessEmail()
    {
        return $this->business_email;
    }

    /**
     * Set the value of business_email
     */
    public function setBusinessEmail($business_email)
    {
        $this->business_email = $business_email;

        return $this;
    }

    /**
     * Get the value of business_id
     */
    public function getBusinessId()
    {
        return $this->business_id;
    }

    /**
     * Set the value of business_id
     */
    public function setBusinessId($business_id)
    {
        $this->business_id = $business_id;

        return $this;
    }

    /**
     * Get the value of business_GSTIN
     */
    public function getBusinessGSTIN()
    {
        return $this->business_GSTIN;
    }

    /**
     * Set the value of business_GSTIN
     */
    public function setBusinessGSTIN($business_GSTIN)
    {
        $this->business_GSTIN = $business_GSTIN;

        return $this;
    }

    /**
     * Get the value of business_logoImage
     */
    public function getBusinessLogoImage()
    {
        return $this->business_logoImage;
    }

    /**
     * Set the value of business_logoImage
     */
    public function setBusinessLogoImage($business_logoImage)
    {
        $this->business_logoImage = $business_logoImage;

        return $this;
    }

    /**
     * Get the value of business_aboutBusiness
     */
    public function getBusinessAboutBusiness()
    {
        return $this->business_aboutBusiness;
    }

    /**
     * Set the value of business_aboutBusiness
     */
    public function setBusinessAboutBusiness($business_aboutBusiness)
    {
        $this->business_aboutBusiness = $business_aboutBusiness;

        return $this;
    }
}
