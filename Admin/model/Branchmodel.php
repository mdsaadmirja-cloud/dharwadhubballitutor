<?php

class Branch
{
    private $id;
    private $branchname;
    private $branchcode;
    private $address;
    private $phone;
    private $status;

    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_branchname()
    {
        return $this->branchname;
    }

    public function set_branchname($branchname)
    {
        $this->branchname = $branchname;
    }

    public function get_branchcode()
    {
        return $this->branchcode;
    }

    public function set_branchcode($branchcode)
    {
        $this->branchcode = $branchcode;
    }

    public function get_address()
    {
        return $this->address;
    }

    public function set_address($address)
    {
        $this->address = $address;
    }

    public function get_phone()
    {
        return $this->phone;
    }

    public function set_phone($phone)
    {
        $this->phone = $phone;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function set_status($status)
    {
        $this->status = $status;
    }
}