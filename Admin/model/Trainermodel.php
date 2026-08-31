
<?php
class Trainer
{
  private $id;
  private $Name;
  private $Phone;
  private $Email;
  private $Qualification;
  private $Coursesassigned = array();
  private $Address;
  private $AdhaarNo;
  private $AdhaarFile;
  private $PhotoFile;
  private $Resume;
  private $StaffCode;
  private $BranchId;
  private $Department;
  private $Designation;
  private $FingerprintID;
  private $JoiningDate;
  private $Status;
  private $workinghours;
  private $Created_Date;
  private $bank_name;
  private $account_holder_name;
  private $account_number;
  private $account_type;
  private $ifsc_code;
  private $branch_name;
  private $bank_address;
  private $table_name = "trainers";

  function set_id($id)
  {
    $this->id = $id;
  }
  function get_id()
  {
    return $this->id;
  }

  function set_name($name)
  {
    $this->Name = $name;
  }
  function get_name()
  {
    return $this->Name;
  }

  function set_phone($phone)
  {
    $this->Phone = $phone;
  }
  function get_phone()
  {
    return $this->Phone;
  }


  function set_email($email)
  {
    $this->Email = $email;
  }
  function get_email()
  {
    return $this->Email;
  }

  function set_qualification($qualification)
  {
    $this->Qualification = $qualification;
  }
  function get_qualification()
  {
    return $this->Qualification;
  }

  function set_coursesassigned($coursesassigned)
  {
    $this->Coursesassigned = $coursesassigned;
  }
  function get_coursesassigned()
  {
    return $this->Coursesassigned;
  }

  function set_address($address)
  {
    $this->Address = $address;
  }
  function get_address()
  {
    return $this->Address;
  }
  function set_adhaarno($adhaarno)
  {
    $this->AdhaarNo = $adhaarno;
  }
  function get_adhaarno()
  {
    return $this->AdhaarNo;
  }
  function set_adhaarfile($adhaarfile)
  {
    $this->AdhaarFile = $adhaarfile;
  }
  function get_adhaarfile()
  {
    return $this->AdhaarFile;
  }
  function set_photofile($photofile)
  {
    $this->PhotoFile = $photofile;
  }
  function get_photofile()
  {
    return $this->PhotoFile;
  }
  function set_resume($resume)
  {
    $this->Resume = $resume;
  }
  function get_resume()
  {
    return $this->Resume;
  }
  function set_staffcode($staffcode)
  {
    $this->StaffCode = $staffcode;
  }
  function get_staffcode()
  {
    return $this->StaffCode;
  }
  function set_branchid($branchid)
  {
    $this->BranchId = $branchid;
  }
  function get_branchid()
  {
    return $this->BranchId;
  }
  function set_department($department)
  {
    $this->Department = $department;
  }
  function get_department()
  {
    return $this->Department;
  }
  function set_designation($designation)
  {
    $this->Designation = $designation;
  }
  function get_designation()
  {
    return $this->Designation;
  }
  function set_fingerprintid($fingerprintid)
  {
    $this->FingerprintID = $fingerprintid;
  }
  function get_fingerprintid()
  {
    return $this->FingerprintID;
  }
  function set_joiningdate($joiningdate)
  {
    $this->JoiningDate = $joiningdate;
  }
  function get_joiningdate()
  {
    return $this->JoiningDate;
  }
  function set_status($status)
  {
    $this->Status = $status;
  }
  function get_status()
  {
    return $this->Status;
  }
  public function set_workinghours($workinghours)
  {
    $this->workinghours = $workinghours;
  }
  public function get_workinghours()
  {
    return $this->workinghours;
  }
  private $ShiftID;

  public function set_shiftid($shiftid)
  {
    $this->ShiftID = $shiftid;
  }
  public function get_shiftid()
  {
    return $this->ShiftID;
  }
  function set_created_date($created_date)
  {
    $this->Created_Date = $created_date;
  }
  function get_created_date()
  {
    return $this->Created_Date;
  }
  public function set_bank_name($value)
  {
    $this->bank_name = $value;
  }

  public function get_bank_name()
  {
    return $this->bank_name;
  }

  public function set_account_holder_name($value)
  {
    $this->account_holder_name = $value;
  }

  public function get_account_holder_name()
  {
    return $this->account_holder_name;
  }

  public function set_account_number($value)
  {
    $this->account_number = $value;
  }

  public function get_account_number()
  {
    return $this->account_number;
  }

  public function set_account_type($value)
  {
    $this->account_type = $value;
  }

  public function get_account_type()
  {
    return $this->account_type;
  }

  public function set_ifsc_code($value)
  {
    $this->ifsc_code = $value;
  }

  public function get_ifsc_code()
  {
    return $this->ifsc_code;
  }

  public function set_branch_name($value)
  {
    $this->branch_name = $value;
  }

  public function get_branch_name()
  {
    return $this->branch_name;
  }

  public function set_bank_address($value)
  {
    $this->bank_address = $value;
  }

  public function get_bank_address()
  {
    return $this->bank_address;
  }
}

?>
