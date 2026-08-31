<?php
class Expense { 

    private $id;
    private $Date;
    private $name;
    private $Description;
    private $Amount;
    private $Category;
    private $Receipts;
     

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
     * Get the value of Date
     */
    public function getDate()
    {
        return $this->Date;
    }

    /**
     * Set the value of Date
     */
    public function setDate($Date): self
    {
        $this->Date = $Date;

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

    /**
     * Get the value of Receipts
     */
    public function getReceipts()
    {
        return $this->Receipts;
    }

    /**
     * Set the value of Receipts
     */
    public function setReceipts($Receipts): self
    {
        $this->Receipts = $Receipts;

        return $this;
    }

    /**
     * Get the value of Amount
     */
    public function getAmount()
    {
        return $this->Amount;
    }

    /**
     * Set the value of Amount
     */
    public function setAmount($Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }
}



?>