
<?php
class Registration
{
  private $id;
  private $Name;
  private $Email;
  private $Phone;
  private $Trainings;
  private $source;
  private $branch;
  private $Internship;
  private $Services;
  private $Demo;
  private $Qualification;
  private $enq_createdon;
  private $table_name = "candidates";

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

  function set_email($email)
  {
    $this->Email = $email;
  }
  function get_email()
  {
    return $this->Email;
  }

  function set_phone($phone)
  {
    $this->Phone = $phone;
  }
  function get_phone()
  {
    return $this->Phone;
  }

  function set_trainings($trainings)
  {
    $this->Trainings = $trainings;
  }
  function get_trainings()
  {
    return $this->Trainings;
  }
  function set_internship($internship)
  {
    $this->Internship = $internship;
  }
  function get_internship()
  {
    return $this->Internship;
  }
  function set_services($services)
  {
    $this->Services = $services;
  }
  function get_services()
  {
    return $this->Services;
  }
  function set_demo($demo)
  {
    $this->Demo = $demo;
  }
  function get_demo()
  {
    return $this->Demo;
  }
  function set_qualification($qualification)
  {
    $this->Qualification = $qualification;
    echo $this->Qualification;
  }
  function get_qualification()
  {
    return $this->Qualification;
  }

  function set_enqcreatedon($enq_createdon)
  {
    $this->enq_createdon = $enq_createdon;
  }
  function get_enqcreatedon()
  {
    return $this->enq_createdon;
  }

  /**
   * Get the value of source
   */
  public function get_Source()
  {
    return $this->source;
  }

  /**
   * Set the value of source
   */
  public function set_Source($source)
  {
    $this->source = $source;

    return $this;
  }

  /**
   * Get the value of branch
   */
  public function getBranch()
  {
    return $this->branch;
  }

  /**
   * Set the value of branch
   */
  public function setBranch($branch)
  {
    $this->branch = $branch;

    return $this;
  }
}
?>
