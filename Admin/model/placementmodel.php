<?php
class Placement
{
    private $Id;
    private $Admissionid;
    private $Assistedon;
    private $Companyname;
    private $jobpost;
    private $Result;
    private $Reason;
    private $Rejectedby;
    private $offerletter;
    private $CreatedOn;
    private $Modifiedon;
    private $Createdby;
    private $Modifiedby;


    /**
     * Get the value of Assistedon
     */
    public function getAssistedon()
    {
        return $this->Assistedon;
    }

    /**
     * Set the value of Assistedon
     */
    public function setAssistedon($Assistedon): self
    {
        $this->Assistedon = $Assistedon;

        return $this;
    }

    /**
     * Get the value of Companyname
     */
    public function getCompanyname()
    {
        return $this->Companyname;
    }

    /**
     * Set the value of Companyname
     */
    public function setCompanyname($Companyname): self
    {
        $this->Companyname = $Companyname;

        return $this;
    }

    /**
     * Get the value of jobpost
     */
    public function getJobpost()
    {
        return $this->jobpost;
    }

    /**
     * Set the value of jobpost
     */
    public function setJobpost($jobpost): self
    {
        $this->jobpost = $jobpost;

        return $this;
    }

    /**
     * Get the value of Result
     */
    public function getResult()
    {
        return $this->Result;
    }

    /**
     * Set the value of Result
     */
    public function setResult($Result): self
    {
        $this->Result = $Result;

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
    public function setCreatedOn($CreatedOn): self
    {
        $this->CreatedOn = $CreatedOn;

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
     * Get the value of Modifiedon
     */
    public function getModifiedon()
    {
        return $this->Modifiedon;
    }

    /**
     * Set the value of Modifiedon
     */
    public function setModifiedon($Modifiedon): self
    {
        $this->Modifiedon = $Modifiedon;

        return $this;
    }

    /**
     * Get the value of Createdby
     */
    public function getCreatedby()
    {
        return $this->Createdby;
    }

    /**
     * Set the value of Createdby
     */
    public function setCreatedby($Createdby): self
    {
        $this->Createdby = $Createdby;

        return $this;
    }

    /**
     * Get the value of Reason
     */
    public function getReason()
    {
        return $this->Reason;
    }

    /**
     * Set the value of Reason
     */
    public function setReason($Reason): self
    {
        $this->Reason = $Reason;

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



    /**
     * Get the value of Admissionid
     */
    public function getAdmissionid()
    {
        return $this->Admissionid;
    }

    /**
     * Set the value of Admissionid
     */
    public function setAdmissionid($Admissionid): self
    {
        $this->Admissionid = $Admissionid;

        return $this;
    }

    /**
     * Get the value of offerletter
     */
    public function getofferletter()
    {
        return $this->offerletter;
    }

    /**
     * Set the value of offerletter
     */
    public function setofferletter($offerletter): self
    {
        $this->offerletter = $offerletter;

        return $this;
    }

    /**
     * Get the value of Rejectedby
     */
    public function getRejectedby()
    {
        return $this->Rejectedby;
    }

    /**
     * Set the value of Rejectedby
     */
    public function setRejectedby($Rejectedby): self
    {
        $this->Rejectedby = $Rejectedby;

        return $this;
    }
}