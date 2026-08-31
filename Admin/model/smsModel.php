<?php

require "../Utilities/textlocal.class.php";
class Sms
{   
   
    private $username ;
    private $APIkey ;
    private $key ;
    private $test = "1";
    private $sender ;
    private $numbers;
    private $message;
    private $Id;
    private $ModifiedOn;
    private $CreatedOn;
    private $ModifiedBy;
    private $CreatedBy;


    /**
     * Get the value of numbers
     */
    public function getNumbers()
    {
        return $this->numbers;
    }

    /**
     * Set the value of numbers
     */
    public function setNumbers($numbers)
    {
        $this->numbers = $numbers;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getusername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */
    public function setusername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of APIkey
     */
    public function getAPIkey()
    {
        return $this->APIkey;
    }

    /**
     * Set the value of APIkey
     */
    public function setAPIkey($APIkey): self
    {
        $this->APIkey = $APIkey;

        return $this;
    }

    /**
     * Get the value of key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set the value of key
     */
    public function setKey($key): self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get the value of test
     */
    public function gettest()
    {
        return $this->test;
    }

    /**
     * Set the value of test
     */
    public function settest($test): self
    {
        $this->test = $test;

        return $this;
    }

    /**
     * Get the value of sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set the value of sender
     */
    public function setSender($sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    

    public function sendSMS()
    {
        $Textlocal = new Textlocal($this->getusername(), $this->getkey(), false);
     error_log($this->getusername());
     error_log($this->numbers);
     
        $numbers= array($this->numbers);
        $sender=$this->sender;
        $message = $this->message;
        error_log($message);
        $response = $Textlocal->sendSms($numbers, $message, $sender);
        error_log($response);
 
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
    public function setModifiedOn($ModifiedOn): self
    {
        $this->ModifiedOn = $ModifiedOn;

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
