<?php
class template
{ 
    private $sender = "DHTENQ";
    private $message;
    private $messageId;
    private $CreatedBy;
    private $ModifiedBy;


    /**
     * Get the value of sender
     */
    public function getsender()
    {
        return $this->sender;
    }

    /**
     * Set the value of sender
     */
    public function setsender($sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getmessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     */
    public function setmessage($message): self
    {
        $this->message = $message;

        return $this;
    }
    public function sendSMS()
    {
        $Textlocal = new Textlocal('atharshaikh1@gmail.com', '650d05aea27f2bad20a99358b460a1980388a591d2cebaa63d04e1f03ca83605', false);
     
        $numbers = array(8007961759);
        $sender = 'DHTENQ';
        $message = $this->message;
        error_log($message);
        $response = $Textlocal->sendSms($numbers, $message, $sender);
        error_log($response);
 
    }

    /**
     * Get the value of messageId
     */
    public function getmessageId()
    {
        return $this->messageId;
    }

    /**
     * Set the value of messageId
     */
    public function setmessageId($messageId): self
    {
        $this->messageId = $messageId;

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
    public function setCreatedBy($CreatedBy): self
    {
        $this->CreatedBy = $CreatedBy;

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
    public function setModifiedBy($ModifiedBy): self
    {
        $this->ModifiedBy = $ModifiedBy;

        return $this;
    }
}