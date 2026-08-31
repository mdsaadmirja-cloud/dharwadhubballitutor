<?php

class Courses
{
  private $id;
  private $CName;
  private $Ctype;
  private $Cduration;

  private $Csyllabus;
  private $table_name = "course";

  function set_id($id)
  {
    $this->id = $id;
  }
  function get_id()
  {
    return $this->id;
  }

  function set_cname($cname)
  {
    $this->CName = $cname;
  }
  function get_cname()
  {
    return $this->CName;
  }

  function set_ctype($ctype)
  {
    $this->Ctype = $ctype;
  }
  function get_ctype()
  {
    return $this->Ctype;
  }

  function set_cduration($cduration)
  {
    $this->Cduration = $cduration;
  }
  function get_cduration()
  {
    return $this->Cduration;
  }
}
