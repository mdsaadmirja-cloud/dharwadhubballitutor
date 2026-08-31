<?php 

class Company{
    private $id;
    private $date;
    private $name;
    private $email;
    private $phone;
    private $location;
    private $approachedby;
    private $companyname;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     */
    public function setDate($date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     */
    public function setPhone($phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set the value of location
     */
    public function setLocation($location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of approachedby
     */
    public function getApproachedby()
    {
        return $this->approachedby;
    }

    /**
     * Set the value of approachedby
     */
    public function setApproachedby($approachedby): self
    {
        $this->approachedby = $approachedby;

        return $this;
    }

    

    /**
     * Get the value of companyname
     */
    public function getCompanyname()
    {
        return $this->companyname;
    }

    /**
     * Set the value of companyname
     */
    public function setCompanyname($companyname): self
    {
        $this->companyname = $companyname;

        return $this;
    }

    
}

?>
