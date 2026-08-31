<?php

class CourseModal
{
  private $coursemodalId;
  private $Name;
  private $Description;


  /**
   * Get the value of Name
   */
  public function getName()
  {
    return $this->Name;
  }

  /**
   * Set the value of Name
   */
  public function setName($Name): self
  {
    $this->Name = $Name;

    return $this;
  }

  /**
   * Get the value of Description
   */
  public function getDescription()
  {
    return $this->Description;
  }

  /**
   * Set the value of Description
   */
  public function setDescription($Description): self
  {
    $this->Description = $Description;

    return $this;
  }

  /**
   * Get the value of coursemodalId
   */
  public function getCoursemodalId()
  {
    return $this->coursemodalId;
  }

  /**
   * Set the value of coursemodalId
   */
  public function setCoursemodalId($coursemodalId): self
  {
    $this->coursemodalId = $coursemodalId;

    return $this;
  }
}