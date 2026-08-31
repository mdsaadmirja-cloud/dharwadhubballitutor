
<?php 
   class Admissions
  {
    private $Name;
    private $Phone;
    private $Email;
    private $DateofBirth;
    private $Qualification;
    private $Guardians_Name;
    private $Guardians_Phone;
    private $CoursesOpted;
    private $Address;
    private $AdhaarNo;
    private $Adhaarfile;
    private $Photofile;
    private $table_name="admissions";
  function set_name($name) {
    $this->Name = $name;
  }
  function get_name() {
    return $this->Name;
  }

  function set_phone($phone) {
    $this->Phone = $phone;
  }
  function get_phone() {
    return $this->Phone;
  }


  function set_email($email) {
    $this->Email = $email;
  }
  function get_email() {
    return $this->Email;
  }

  function set_dateofbirth($dateofbirth) {
    $this->DateofBirth = $dateofbirth;
  }
  function get_dateofbirth() {
    return $this->DateofBirth;
  }

  function set_qualification($qualification) {
    $this->Qualification = $qualification;
    
  }
  function get_qualification() {
    return $this->Qualification;
  }

  function set_guardiansname($guardiansname) {
    $this->Guardians_Name= $guardiansname;
  }
  function get_guardiansname() {
    return $this->Guardians_Name;
  }

  function set_guardiansphone($guardiansphone) {
    $this->Guardians_Phone= $guardiansphone;
  }
  function get_guardiansphone() {
    return $this->Guardians_Phone;
  }

  function set_coursesopted($coursesopted) {
    $this->CoursesOpted= $coursesopted;
  }
  function get_coursesopted() {
    return $this->CoursesOpted;
  }

  function set_address($address) {
    $this->Address = $address;
  }
  function get_address() {
    return $this->Address;
  }
  function set_adhaarno($adhaarno) {
    $this->AdhaarNo = $adhaarno;
  }
  function get_adhaarno() {
    return $this->AdhaarNo;
  }
  function set_adhaarfile($adhaarfile) {
    $this->AdhaarFile = $adhaarfile;
  }
  function get_adhaarfile() {
    return $this->AdhaarFile;
  }
  function set_photofile($photofile) {
    $this->PhotoFile = $photofile;
  }
  function get_photofile() {
    return $this->PhotoFile;
  }
 
 }
?>
