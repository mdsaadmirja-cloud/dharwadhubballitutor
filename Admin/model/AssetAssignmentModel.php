<?php

class AssetAssignmentModel
{
    private $asset_id;
    private $employee_id;
    private $assigned_date;
    private $expected_return;
    private $remarks;
    private $assigned_by;

    public function get_asset_id()
    {
        return $this->asset_id;
    }

    public function set_asset_id($asset_id)
    {
        $this->asset_id = $asset_id;
    }

    public function get_employee_id()
    {
        return $this->employee_id;
    }

    public function set_employee_id($employee_id)
    {
        $this->employee_id = $employee_id;
    }

    public function get_assigned_date()
    {
        return $this->assigned_date;
    }

    public function set_assigned_date($assigned_date)
    {
        $this->assigned_date = $assigned_date;
    }

    public function get_expected_return()
    {
        return $this->expected_return;
    }

    public function set_expected_return($expected_return)
    {
        $this->expected_return = $expected_return;
    }

    public function get_remarks()
    {
        return $this->remarks;
    }

    public function set_remarks($remarks)
    {
        $this->remarks = $remarks;
    }

    public function get_assigned_by()
    {
        return $this->assigned_by;
    }

    public function set_assigned_by($assigned_by)
    {
        $this->assigned_by = $assigned_by;
    }
}