<?php

/**
 * ClientProjectModel.php
 * POJO for a single Client Project record.
 */

class ClientProject
{
    private $id;
    private $client_id;
    private $branch_id;
    private $branch_name;
    private $project_name;
    private $project_type;
    private $technology;
    private $description;
    private $start_date;
    private $expected_delivery;
    private $completed_date;
    private $budget;
    private $advance_amount;
    private $pending_amount;
    private $priority;        // 'Low' | 'Medium' | 'High'
    private $project_status;  // 'Planning' | 'Development' | 'Testing' | 'Completed' | 'Maintenance' | 'Cancelled'
    private $created_at;

    // Populated via JOIN when listing projects across clients
    private $company_name;
    private $client_name;
    private $progress;
    private $remarks;

    public function get_id()
    {
        return $this->id;
    }
    public function set_id($id)
    {
        $this->id = $id;
    }

    public function get_client_id()
    {
        return $this->client_id;
    }
    public function set_client_id($client_id)
    {
        $this->client_id = $client_id;
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

    public function get_project_name()
    {
        return $this->project_name;
    }
    public function set_project_name($project_name)
    {
        $this->project_name = $project_name;
    }

    public function get_project_type()
    {
        return $this->project_type;
    }
    public function set_project_type($project_type)
    {
        $this->project_type = $project_type;
    }

    public function get_technology()
    {
        return $this->technology;
    }
    public function set_technology($technology)
    {
        $this->technology = $technology;
    }

    public function get_description()
    {
        return $this->description;
    }
    public function set_description($description)
    {
        $this->description = $description;
    }

    public function get_start_date()
    {
        return $this->start_date;
    }
    public function set_start_date($start_date)
    {
        $this->start_date = $start_date;
    }

    public function get_expected_delivery()
    {
        return $this->expected_delivery;
    }
    public function set_expected_delivery($expected_delivery)
    {
        $this->expected_delivery = $expected_delivery;
    }

    public function get_completed_date()
    {
        return $this->completed_date;
    }
    public function set_completed_date($completed_date)
    {
        $this->completed_date = $completed_date;
    }

    public function get_budget()
    {
        return $this->budget;
    }
    public function set_budget($budget)
    {
        $this->budget = $budget;
    }

    public function get_advance_amount()
    {
        return $this->advance_amount;
    }
    public function set_advance_amount($advance_amount)
    {
        $this->advance_amount = $advance_amount;
    }

    public function get_pending_amount()
    {
        return $this->pending_amount;
    }
    public function set_pending_amount($pending_amount)
    {
        $this->pending_amount = $pending_amount;
    }

    public function get_priority()
    {
        return $this->priority;
    }
    public function set_priority($priority)
    {
        $this->priority = $priority;
    }

    public function get_project_status()
    {
        return $this->project_status;
    }
    public function set_project_status($project_status)
    {
        $this->project_status = $project_status;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }
    public function set_created_at($created_at)
    {
        $this->created_at = $created_at;
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
    public function get_progress()
    {
        return $this->progress;
    }
    public function set_progress($progress)
    {
        $this->progress = $progress;
    }

    public function get_remarks()
    {
        return $this->remarks;
    }
    public function set_remarks($remarks)
    {
        $this->remarks = $remarks;
    }
}
