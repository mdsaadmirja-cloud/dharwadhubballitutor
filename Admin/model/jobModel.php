<?php

class Job
{
  private $jobid;
  private $Name;
  private $Description;
  private $id;
  private $JobLocation;
  private $RemoteWork;

  /**
   * Get the value of jobid
   */
  public function getJobid()
  {
    return $this->jobid;
  }

  /**
   * Set the value of jobid
   */
  public function setJobid($jobid): self
  {
    $this->jobid = $jobid;

    return $this;
  }

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
   * Get the value of id
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId($id): self
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of JobLocation
   */
  public function getJobLocation()
  {
    return $this->JobLocation;
  }

  /**
   * Set the value of JobLocation
   */
  public function setJobLocation($JobLocation): self
  {
    $this->JobLocation = $JobLocation;

    return $this;
  }

  /**
   * Get the value of RemoteWork
   */
  public function getRemoteWork()
  {
    return $this->RemoteWork;
  }

  /**
   * Set the value of RemoteWork
   */
  public function setRemoteWork($RemoteWork): self
  {
    $this->RemoteWork = $RemoteWork;

    return $this;
  }
}

