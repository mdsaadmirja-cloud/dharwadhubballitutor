<?php 
   class Sanitization 
  { 
    public Static function test_input($data){
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      $data = htmlspecialchars_decode($data);
      $data = str_replace("'","''",$data);
      return $data;
    }
  }

?>