<?php

class ProjectPayment
{
    private $id;
    private $project_id;

    private $project_name;
    private $client_name;
    private $company_name;
    private $branch_name;

    private $payment_date;
    private $amount;

    private $payment_mode;
    private $payment_type;

    private $transaction_no;
    private $remarks;

    private $created_at;

    // ID
    public function get_id()
    {
        return $this->id;
    }

    public function set_id($id)
    {
        $this->id = $id;
    }

    // Project
    public function get_project_id()
    {
        return $this->project_id;
    }

    public function set_project_id($project_id)
    {
        $this->project_id = $project_id;
    }

    public function get_project_name()
    {
        return $this->project_name;
    }

    public function set_project_name($project_name)
    {
        $this->project_name = $project_name;
    }

    // Company
    public function get_company_name()
    {
        return $this->company_name;
    }

    public function set_company_name($company_name)
    {
        $this->company_name = $company_name;
    }

    // Client
    public function get_client_name()
    {
        return $this->client_name;
    }

    public function set_client_name($client_name)
    {
        $this->client_name = $client_name;
    }

    // Branch
    public function get_branch_name()
    {
        return $this->branch_name;
    }

    public function set_branch_name($branch_name)
    {
        $this->branch_name = $branch_name;
    }

    // Payment Date
    public function get_payment_date()
    {
        return $this->payment_date;
    }

    public function set_payment_date($payment_date)
    {
        $this->payment_date = $payment_date;
    }

    // Amount
    public function get_amount()
    {
        return $this->amount;
    }

    public function set_amount($amount)
    {
        $this->amount = $amount;
    }

    // Payment Mode
    public function get_payment_mode()
    {
        return $this->payment_mode;
    }

    public function set_payment_mode($payment_mode)
    {
        $this->payment_mode = $payment_mode;
    }

    // Payment Type
    public function get_payment_type()
    {
        return $this->payment_type;
    }

    public function set_payment_type($payment_type)
    {
        $this->payment_type = $payment_type;
    }

    // Transaction No
    public function get_transaction_no()
    {
        return $this->transaction_no;
    }

    public function set_transaction_no($transaction_no)
    {
        $this->transaction_no = $transaction_no;
    }

    // Remarks
    public function get_remarks()
    {
        return $this->remarks;
    }

    public function set_remarks($remarks)
    {
        $this->remarks = $remarks;
    }

    // Created At
    public function get_created_at()
    {
        return $this->created_at;
    }

    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
    }
}