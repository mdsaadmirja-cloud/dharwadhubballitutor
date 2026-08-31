<?php

class VendorModel
{
    private $id;
    private $name;
    private $contact_person;
    private $phone;
    private $email;
    private $gst_number;
    private $address;
    private $city;
    private $state;
    private $pincode;
    private $branch;
    private $notes;
    private $status;
    private $created_by;
    private $created_at;
    private $updated_at;

    // ID
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Vendor Name
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    // Contact Person
    public function getContactPerson()
    {
        return $this->contact_person;
    }

    public function setContactPerson($contact_person)
    {
        $this->contact_person = $contact_person;
    }

    // Phone
    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    // Email
    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    // GST
    public function getGstNumber()
    {
        return $this->gst_number;
    }

    public function setGstNumber($gst_number)
    {
        $this->gst_number = $gst_number;
    }

    // Address
    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    // City
    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    // State
    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    // Pincode
    public function getPincode()
    {
        return $this->pincode;
    }

    public function setPincode($pincode)
    {
        $this->pincode = $pincode;
    }

    // Branch
    public function getBranch()
    {
        return $this->branch;
    }

    public function setBranch($branch)
    {
        $this->branch = $branch;
    }

    // Notes
    public function getNotes()
    {
        return $this->notes;
    }

    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    // Status
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    // Created By
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    public function setCreatedBy($created_by)
    {
        $this->created_by = $created_by;
    }

    // Created At
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    // Updated At
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;
    }
}