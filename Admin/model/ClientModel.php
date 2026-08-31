<?php

/**
 * ClientModel.php
 * POJO for a single Client record.
 * Follows the same get_/set_ convention used in Trainer/Vendor models.
 */

class Client
{
    private $id;
    private $client_code;
    private $branch_id;
    private $branch_name;
    private $company_name;
    private $client_name;
    private $mobile;
    private $alternate_mobile;
    private $email;
    private $website;
    private $gst_number;
    private $address;
    private $city;
    private $state;
    private $pincode;
    private $industry;
    private $status;      // 'Active' | 'Inactive'
    private $notes;
    private $created_at;
    private $updated_at;

    // Not persisted directly on this table - populated by ClientOps
    // when building list/profile views (COUNT of client_projects).
    private $total_projects = 0;

    public function get_id()
    {
        return $this->id;
    }
    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_client_code()
    {
        return $this->client_code;
    }
    public function set_client_code($client_code)
    {
        $this->client_code = $client_code;
    }
    public function get_branch_id()
    {
        return $this->branch_id;
    }

    public function set_branch_id($branch_id)
    {
        $this->branch_id = $branch_id;
    }

    public function get_branch_name()
    {
        return $this->branch_name;
    }

    public function set_branch_name($branch_name)
    {
        $this->branch_name = $branch_name;
    }

    public function get_company_name()
    {
        return $this->company_name;
    }
    public function set_company_name($company_name)
    {
        $this->company_name = $company_name;
    }

    public function get_client_name()
    {
        return $this->client_name;
    }
    public function set_client_name($client_name)
    {
        $this->client_name = $client_name;
    }

    public function get_mobile()
    {
        return $this->mobile;
    }
    public function set_mobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function get_alternate_mobile()
    {
        return $this->alternate_mobile;
    }
    public function set_alternate_mobile($alternate_mobile)
    {
        $this->alternate_mobile = $alternate_mobile;
    }

    public function get_email()
    {
        return $this->email;
    }
    public function set_email($email)
    {
        $this->email = $email;
    }

    public function get_website()
    {
        return $this->website;
    }
    public function set_website($website)
    {
        $this->website = $website;
    }

    public function get_gst_number()
    {
        return $this->gst_number;
    }
    public function set_gst_number($gst_number)
    {
        $this->gst_number = $gst_number;
    }

    public function get_address()
    {
        return $this->address;
    }
    public function set_address($address)
    {
        $this->address = $address;
    }

    public function get_city()
    {
        return $this->city;
    }
    public function set_city($city)
    {
        $this->city = $city;
    }

    public function get_state()
    {
        return $this->state;
    }
    public function set_state($state)
    {
        $this->state = $state;
    }

    public function get_pincode()
    {
        return $this->pincode;
    }
    public function set_pincode($pincode)
    {
        $this->pincode = $pincode;
    }

    public function get_industry()
    {
        return $this->industry;
    }
    public function set_industry($industry)
    {
        $this->industry = $industry;
    }

    public function get_status()
    {
        return $this->status;
    }
    public function set_status($status)
    {
        $this->status = $status;
    }

    public function get_notes()
    {
        return $this->notes;
    }
    public function set_notes($notes)
    {
        $this->notes = $notes;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }
    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
    }

    public function get_updated_at()
    {
        return $this->updated_at;
    }
    public function set_updated_at($updated_at)
    {
        $this->updated_at = $updated_at;
    }

    public function get_total_projects()
    {
        return $this->total_projects;
    }
    public function set_total_projects($total_projects)
    {
        $this->total_projects = $total_projects;
    }
}
