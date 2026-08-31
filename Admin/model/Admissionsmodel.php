<?php

class Admissions
{
  private $id;
  private $createddate;
  private $Name;
  private $Phone;
  private $Email;
  private $DateofBirth;
  private $Gender;
  private $Qualification;
  private $Guardians_Name;
  private $Guardians_Phone;
  private $CoursesOpted;
  private $Address;
  private $AdhaarNo;
  private $AdhaarFile;
  private $PhotoFile;
  private $Resume;
  private $Courseid;
  private $Branch;
  private $Admissionstatus;
  private $CancellationReason;
  private $table_name = "admissions";
  private $enqueryId;
  private $modifiedDate;

  function set_CancellationReason($Reason)
  {
    $this->CancellationReason = $Reason;
  }
  function get_CancellationReason()
  {
    return $this->CancellationReason;
  }


  function set_Admissionstatus($status)
  {
    $this->Admissionstatus = $status;
  }
  function get_Admissionstatus()
  {
    return $this->Admissionstatus;
  }

  function set_id($id)
  {
    $this->id = $id;
  }
  function get_id()
  {
    return $this->id;
  }


  function set_enqueryId($enqueryId)
  {
    $this->enqueryId = $enqueryId;
  }
  function get_enqueryId()
  {
    return $this->enqueryId;
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

  function set_dateofbirth($dateofbirth)
  {
    $this->DateofBirth = $dateofbirth;
  }
  function get_dateofbirth()
  {
    return $this->DateofBirth;
  }

  function set_gender($gender)
  {
    $this->Gender = $gender;
  }
  function get_gender()
  {
    return $this->Gender;
  }

  function set_qualification($qualification)
  {
    $this->Qualification = $qualification;
  }
  function get_qualification()
  {
    return $this->Qualification;
  }

  function set_guardiansname($guardiansname)
  {
    $this->Guardians_Name = $guardiansname;
  }
  function get_guardiansname()
  {
    return $this->Guardians_Name;
  }

  function set_guardiansphone($guardiansphone)
  {
    $this->Guardians_Phone = $guardiansphone;
  }
  function get_guardiansphone()
  {
    return $this->Guardians_Phone;
  }

  function set_coursesopted($coursesopted)
  {
    $this->CoursesOpted = $coursesopted;
  }
  function get_coursesopted()
  {
    return $this->CoursesOpted;
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
    if (empty($this->AdhaarFile)) {
      return "";
    }
    return $this->AdhaarFile;
  }
  function set_photofile($photofile)
  {
    $this->PhotoFile = $photofile;
  }
  function get_photofile()
  {
    if (empty($this->PhotoFile)) {
      return "";
    }
    return $this->PhotoFile;
  }
  function set_resume($resume)
  {
    $this->Resume = $resume;
  }
  function get_resume()
  {
    if (empty($this->Resume)) {
      return "";
    }
    return $this->Resume;
  }
  function set_courseid($courseid)
  {
    $this->Courseid = $courseid;
  }
  function get_courseid()
  {
    return $this->Courseid;
  }
  function set_branch($branch)
  {
    $this->Branch = $branch;
  }

  function get_branch()
  {
    return $this->Branch;
  }

  /**
   * Get the value of modifiedDate
   */
  public function getModifiedDate()
  {
    return $this->modifiedDate;
  }

  /**
   * Set the value of modifiedDate
   */
  public function setModifiedDate($modifiedDate): self
  {
    $this->modifiedDate = $modifiedDate;

    return $this;
  }

  /**
   * Get the value of createddate
   */
  public function getCreateddate()
  {
    return $this->createddate;
  }

  /**
   * Set the value of createddate
   */
  public function setCreateddate($createddate): self
  {
    $this->createddate = $createddate;

    return $this;
  }
  
}
