<?php
class enquery
{
    private $id;
    private $name;
    private $email;
    private $phone;
    private $source;
    private $qualification;
    private $enqType;
    private $enqueryFor;
    private $Modified_Date;
    private $enq_createdon;
    private $status;
    private $followup_enq_id;
    private $followup_comments;
    private $followup_by;
    private $followupDate;
    private $Status;
    private $followup_createdon;
    private $branch;
    public function set_Id($IdValue)
    {
        $this->id = $IdValue;
    }

    public function get_Id()
    {
        return $this->id;
    }
    public function set_name($nameValue)
    {
        $this->name = $nameValue;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_email($emailValue)
    {
        $this->email = $emailValue;
    }

    public function get_email()
    {
        return $this->email;
    }

    public function set_phone($phoneValue)
    {
        $this->phone = $phoneValue;
    }

    public function get_phone()
    {
        return $this->phone;
    }
    public function set_qualification($qualificationValue)
    {
        $this->qualification = $qualificationValue;
    }
    public function get_qualification()
    {
        return $this->qualification;
    }
    public function set_enqueryFor($enqueryForvalue)
    {
        $this->enqueryFor = $enqueryForvalue;
    }
    public function get_enqueryFor()
    {
        return $this->enqueryFor;
    }

    public function set_modifieddate($modifieddateValue)
    {
        $this->Modified_Date = $modifieddateValue;
    }
    public function get_modifieddate()
    {
        return $this->Modified_Date;
    }

    function set_enqcreatedon($enqcreatedonValue)
    {
        $this->enq_createdon = $enqcreatedonValue;
    }
    function get_enqcreatedon()
    {
        return $this->enq_createdon;
    }



    /**
     * Get the value of followup_enq_id
     */
    public function getFollowupEnqId()
    {
        return $this->followup_enq_id;
    }

    /**
     * Set the value of followup_enq_id
     */
    public function setFollowupEnqId($followup_enq_id)
    {
        $this->followup_enq_id = $followup_enq_id;

        return $this;
    }

    function set_followupOn($followupOn)
    {
        $this->followup_on = $followupOn;
    }
    function get_followupOn()
    {
        return $this->followup_on;
    }
    function set_followid($followid)
    {
        $this->followupid = $followid;
    }
    function get_followid()
    {
        return $this->followupid;
    }

    function set_followenqid($followenqid)
    {
        $this->followup_enq_id = $followenqid;
    }
    function get_followenqid()
    {
        return $this->followup_enq_id;
    }

    function set_followcomment($followcomment)
    {
        $this->followup_comments = $followcomment;
    }
    function get_followcomment()
    {
        return $this->followup_comments;
    }

    function set_followupBy($followupBy)
    {
        $this->followup_by = $followupBy;
    }
    function get_followupBy()
    {
        return $this->followup_by;
    }

    function get_followupDate()
    {
        return $this->followupDate;
    }

    function set_followupDate($followupDate)
    {
        $this->followupDate = $followupDate;

        return $this;
    }

 

    /**
     * Get the value of followup_createdon
     */
    public function getFollowupCreatedon()
    {
        return $this->followup_createdon;
    }

    /**
     * Set the value of followup_createdon
     */
    public function setFollowupCreatedon($followup_createdon)
    {
        $this->followup_createdon = $followup_createdon;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of Status
     */
    public function get_Status()
    {
        return $this->Status;
    }

    /**
     * Set the value of Status
     */
    public function set_Status($Status)
    {
        $this->Status = $Status;

        return $this;
    }
      

    /**
     * Get the value of source
     */
    public function get_Source()
    {
        return $this->source;
    }

    /**
     * Set the value of source
     */
    public function set_Source($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get the value of branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Set the value of branch
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get the value of enqType
     */
    public function getEnqType()
    {
        return $this->enqType;
    }

    /**
     * Set the value of enqType
     */
    public function setEnqType($enqType)
    {
        $this->enqType = $enqType;

        return $this;
    }
}
