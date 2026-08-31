<?php
require_once "../../DB Operations/dbconnection.php";
    class DBservice
    {
      public static function insert($serviceObj)
      {
          $db=ConnectDb::getInstance();
          $connectionObj=$db->getConnection();
        
          $sql = "insert into services (`Candidateid`,`Name`, `Phone`, `Email`,`Services`,`TotalAmount`,`PaidAmount`,`PendingAmount`,`PaymentMode`,`paymentreceipt`) 
                values ('".$serviceObj->get_candidateid()."','".$serviceObj->get_name()."','".$serviceObj->get_phone()."','".$serviceObj->get_email()."','".$serviceObj->get_services()."','".$serviceObj->get_totalamt()."','".$serviceObj->get_paidamt()."','".$serviceObj->get_pendingamt()."','".$serviceObj->get_paymentmode()."','".$serviceObj->get_paymentreceipt()."')";
                echo $sql;
          if ($connectionObj->query($sql) === true) {
          } else {
              echo "Error: " . $sql . "<br>" . $connectionObj->error;
          }
      }

      public static function paymentdetails($viewObj)
      {
        $db=ConnectDb::getInstance();
        $connectionObj=$db->getConnection();
        
           $sql="Select C.id,C.Phone,C.Name,C.Email, C.Services , TotalAmount, SUM(PaidAmount) as PaidAmount, SUM(Pendingamount) as PendingAmount from candidates as C 
           LEFT JOIN services as S on C.id=S.Candidateid 
           where C.id=(".$viewObj.") GROUP BY Name,Services,TotalAmount";
            $view= new Services();
            $result=mysqli_query($db->getConnection(), $sql);
           
            if (mysqli_num_rows($result) > 0){
              while($row = mysqli_fetch_assoc($result)) {
                $view->set_name($row['Name']);
                $view->set_email($row['Email']);
                $view->set_phone($row['Phone']);
                $view->set_candidateid($row['id']);
                $view->set_services($row['Services']);
                $view->set_totalamt($row['TotalAmount']);
                $view->set_paidamt($row['PaidAmount']);
                $view->set_pendingamt($row['PendingAmount']);
                $view->set_pendingamt($row['TotalAmount']-$row["PaidAmount"]);
              }
            }else {
              $view=NULL;
            }
            return $view;
    
          }

          public static function viewpaymentdetails($viewObj)
       {
          $db=ConnectDb::getInstance();
          $connectionObj=$db->getConnection();
          $sql = "select Modified_Date,PaidAmount,PendingAmount,PaymentMode,paymentreceipt from services where Candidateid=$viewObj";
        
          $result = mysqli_query($db->getConnection(), $sql);
          $paymentdetails=[];
          if (mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_assoc($result)) {
          $view= new Services();
          $view->set_modifieddate($row['Modified_Date']);
          $view->set_paidamt($row['PaidAmount']);
          $view->set_pendingamt($row['PendingAmount']);
          $view->set_paymentmode($row['PaymentMode']);
        
          $view->set_paymentreceipt($row['paymentreceipt']);
         array_push($paymentdetails,$view);
        
         }    
         } else {
            echo "No entries ";
         } 
         return $paymentdetails;
       }
      
                
    }