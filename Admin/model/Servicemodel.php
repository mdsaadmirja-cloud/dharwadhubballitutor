<?php

      class Services
      {
          private $id;
          private $Name;
          private $Email;
          private $Phone;
          private $Services;
          private $TotalAmount;
          private $PaidAmount;
          private $PendingAmount;
          private $PaymentMode;
          private $paymentreceipt;
          private $Candidateid;
          private $table_name="services";

          public function set_id($id)
          {
              $this->id = $id;
          }
          public function get_id()
          {
              return $this->id;
          }
          public function set_name($name)
          {
              $this->Name = $name;
          }
          public function get_name()
          {
              return $this->Name;
          }
        
          public function set_phone($phone)
          {
              $this->Phone = $phone;
          }
          public function get_phone()
          {
              return $this->Phone;
          }
        
        
          public function set_email($email)
          {
              $this->Email = $email;
          }
          public function get_email()
          {
              return $this->Email;
          }

           
          public function set_services($services)
          {
              $this->Services = $services;
          }
          public function get_services()
          {
              return $this->Services;
          }

          function set_totalamt($totalamt) {
            $this->TotalAmount = $totalamt;
           }
           function get_totalamt() {
            return $this->TotalAmount;
           }
        
           function set_paidamt($paidamt) {
            $this->PaidAmount = $paidamt;
           }
           function get_paidamt() {
            return $this->PaidAmount;
           }
        
           function set_pendingamt($pendingamt) {
            $this->PendingAmount = $pendingamt;
           }
           function get_pendingamt() {
            return $this->PendingAmount;
           }


           function set_paymentmode($paymentmode) {
            $this->PaymentMode = $paymentmode;
           }
           function get_paymentmode() {
            return $this->PaymentMode;
           }

           function set_modifieddate($modifieddate) {
            $this->Modified_Date = $modifieddate;
          }
          function get_modifieddate() {
            return $this->Modified_Date;
          }

          function set_paymentreceipt($paymentreceipt) {
            $this->paymentreceipt = $paymentreceipt;
          }
          function get_paymentreceipt() {
            return $this->paymentreceipt;
          }

          function set_candidateid($candidateid) {
            $this->Candidateid = $candidateid;
           }
           function get_candidateid() {
            return $this->Candidateid;
           }
           
         
      }
        ?>