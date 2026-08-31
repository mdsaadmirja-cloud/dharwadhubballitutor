<?php
class Testimonials implements JsonSerializable
{
    private $Id;
    private $Name;
    private $Description;
    private $Image;
    private $ImageAlternateText;
    private $ModifiedDate;
    private $createdDate;
    private $Modifiedby;
    private $createdby;
    private $RateNow;
    public function jsonSerialize()
    {
        return [
            'Id' => $this->Id,
            'Name' => $this->Name,
            'Description' => $this->Description,
            'ImageAlternateText' => $this->ImageAlternateText,
            'RateNow' => $this->RateNow,
        ];
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
    public function setName($Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * Get the value of Description
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * Set the value of Description
     */
    public function setDescription($Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * Get the value of Image
     */
    public function getImage()
    {
        return $this->Image;
    }

    /**
     * Set the value of Image
     */
    public function setImage($Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    /**
     * Get the value of createdDate
     */
    public function getcreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set the value of createdDate
     */
    public function setcreatedDate($createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get the value of ImageAlternateText
     */
    public function getImageAlternateText()
    {
        return $this->ImageAlternateText;
    }

    /**
     * Set the value of ImageAlternateText
     */
    public function setImageAlternateText($ImageAlternateText): self
    {
        $this->ImageAlternateText = $ImageAlternateText;

        return $this;
    }

    /**
     * Get the value of ModifiedDate
     */
    public function getModifiedDate()
    {
        return $this->ModifiedDate;
    }

    /**
     * Set the value of ModifiedDate
     */
    public function setModifiedDate($ModifiedDate): self
    {
        $this->ModifiedDate = $ModifiedDate;

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

   /** Get the value of RateNow
     */
    public function getRateNow()
    {
        return $this->RateNow;
    }

    /**
     * Set the value of RateNow
     */
    public function setRateNow($RateNow): self
    {
        $this->RateNow = $RateNow;

        return $this;
    }

    /**
     * Get the value of Modifiedby
     */
    public function getModifiedby()
    {
        return $this->Modifiedby;
    }

    /**
     * Set the value of Modifiedby
     */
    public function setModifiedby($Modifiedby): self
    {
        $this->Modifiedby = $Modifiedby;

        return $this;
    }

    /**
     * Get the value of createdby
     */
    public function getcreatedby()
    {
        return $this->createdby;
    }

    /**
     * Set the value of createdby
     */
    public function setcreatedby($createdby): self
    {
        $this->createdby = $createdby;

        return $this;
    }
}
