<?php  
   class Fees implements JsonSerializable
  {
    private $StudentName;
    private $Address;
    private $CourseOpted;
    private $Phone;
    private $Admissionid;
    private $Courseid;
    private $TotalFees;
    private $PaidFees;
    private $PendingFees;
    private $Feesplan;
    private $DueDate;
    private $PaymentMode;
    private $PaymentDescription;
    private $Modified_Date;
    private $feesreceipt;
    private $table_name="fees";
    private $Admissionstatus;
    private $CancellationReason;
    private $GstRate;
    private $GstAmount;
    private $BaseAmount;

   function set_CancellationReason($Reason) {
      $this->CancellationReason = $Reason;
    }
    
    function get_CancellationReason() {
      return $this->CancellationReason;
    }

    
    function set_Admissionstatus($status) {
      $this->Admissionstatus = $status;
    }
    function get_Admissionstatus() {
      return $this->Admissionstatus;
    }
    
    function set_admitid($admitid) {
      $this->Admissionid = $admitid;
     }
     function get_admitid() {
      return $this->Admissionid;
     }
     
     function set_courseid($courseid) {
      $this->Courseid = $courseid;
     }
     function get_courseid() {
      return $this->Courseid;
     }

   function set_tfees($tfees) {
    $this->TotalFees = $tfees;
   }
   function get_tfees() {
    return $this->TotalFees;
   }

   function set_pfees($pfees) {
    $this->PaidFees = $pfees;
   }
   function get_pfees() {
    return $this->PaidFees;
   }

   function set_pendingfees($pendingfees) {
    $this->PendingFees = $pendingfees;
   }
   function get_pendingfees() {
    return $this->PendingFees;
   }

   function set_name($name) {
    $this->StudentName = $name;
   }
   function get_name() {
    return $this->StudentName;
   }

   function set_coursesopted($coursesopted) {
    $this->CourseOpted = $coursesopted;
   }
   function get_coursesopted() {
    return $this->CourseOpted;
   }

   function set_phone($phone) {
    $this->Phone= $phone;
   }
   function get_phone() {
    return $this->Phone;
   }

   function set_feesplan($feesplan) {
    $this->Feesplan = $feesplan;
   }
   function get_feesplan() {
    return $this->Feesplan;
  }

  function set_duedate($duedate) {
    $this->DueDate = $duedate;
  }
  function get_duedate() {
    if($this->DueDate==""){
      return NULL;
    }else{
     return $this->DueDate;
    }
  }

  function set_pmode($pmode) {
    $this->PaymentMode = $pmode;
  }
  function get_pmode() {
    return $this->PaymentMode;
  }

  function set_pdescription($pdescription) {
    $this->PaymentDescription = $pdescription;
    
  }
  function get_pdescription() {
    return $this->PaymentDescription;
  }
  function set_modifieddate($modifieddate) {
    $this->Modified_Date = $modifieddate;
    
  }
  function get_modifieddate() {
    return $this->Modified_Date;
  }

  function set_feereceipt($feereceipt) {
    $this->feesreceipt = $feereceipt;
  }
  function get_feereceipt() {
    return $this->feesreceipt;
  }

  function set_gstrate($gstrate) {
    $this->GstRate = $gstrate;
  }
  function get_gstrate() {
    return $this->GstRate;
  }

  function set_gstamount($gstamount) {
    $this->GstAmount = $gstamount;
  }
  function get_gstamount() {
    return $this->GstAmount;
  }

  function set_baseamount($baseamount) {
    $this->BaseAmount = $baseamount;
  }
  function get_baseamount() {
    return $this->BaseAmount;
  }
 

    public function getAddress()
    {
        return $this->Address;
    }

    public function setAddress($Address)
    {
        $this->Address = $Address;

        return $this;
    }

    public function jsonSerialize():mixed
    {
        return 
            [
                'admitid' => $this->Admissionid,
                'pfees' => $this->PaidFees,
                'pendingfees' => $this->PendingFees,
                'pmode' => $this->PaymentMode,
                'duedate' => $this-> DueDate,
                'modifieddate'=>$this->Modified_Date,
                'gstrate' => $this->GstRate,
                'gstamount' => $this->GstAmount,
                'baseamount' => $this->BaseAmount,
            ];
        
    }


}
?>