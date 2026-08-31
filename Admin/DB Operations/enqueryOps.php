<?php
// require "../Admin/session.php";
require_once "../../DB Operations/dbconnection.php";
require_once "../../Admin/model/EnqueryModel.php";

class DBenquery
{
    /*
     */
    public static function getAllEnquery()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM candidates where status = 1 order by id DESC LIMIT 500";
        $result = mysqli_query($connectionObj, $sql);
        $enquirylist = [];
        if (mysqli_num_rows($result) > 0) {
 while ($row = mysqli_fetch_assoc($result)) {
                $enqueryModel = new enquery();
                $enqueryModel->set_enqcreatedon(date('d-m-y', strtotime($row["Modified_Date"])));
                $enqueryModel->set_Id($row["id"]);
                $enqueryModel->set_name($row["Name"]);
                $enqueryModel->set_email($row["Email"]);
                $enqueryModel->set_phone($row["Phone"]);
                $enqueryModel->set_Source($row["source"]);
                $enqueryModel->setBranch($row["branch"]);
                $enqueryModel->set_enqueryFor($row["Trainings"]);
                $sql = "SELECT followup_enq_id,followupDate,status FROM `enquiry_followup` WHERE followup_enq_id ='" . $enqueryModel->get_Id() . "' ORDER BY followup_id DESC LIMIT 1 ";
                //error_log($sql);
                $followup = mysqli_query($connectionObj, $sql);
                $row2 = mysqli_fetch_assoc($followup);
                $count = mysqli_num_rows($followup);
                if ($count > 0) {
                    $enqueryModel->set_followenqid($row2["followup_enq_id"]);
                    $enqueryModel->set_followupDate((date('d-m-y', strtotime($row2["followupDate"])) == '01-01-70') ? "" : date('d-m-y', strtotime($row2["followupDate"])));
                    $enqueryModel->set_status($row2["status"]);

                }
                array_push($enquirylist, $enqueryModel);
            }
        } else {
            echo "0 results";
        }
        return $enquirylist;
    }

    public static function getTodaysFollowUps()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM `enquiry_followup` AS ef Join  `candidates` as c  ON  ef.followup_enq_id=c.id WHERE followupDate=CURRENT_DATE";
        //error_log($sql);
        $result = mysqli_query($connectionObj, $sql);
        $enquirylist = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $enqueryModel = new enquery();
                $enqueryModel->set_enqcreatedon(date('d-m-y', strtotime($row["Modified_Date"])));
                $enqueryModel->set_Id($row["id"]);
                $enqueryModel->set_name($row["Name"]);
                $enqueryModel->set_email($row["Email"]);
                $enqueryModel->set_phone($row["Phone"]);
                $enqueryModel->set_Source($row["source"]);
                $enqueryModel->setBranch($row["branch"]);
                $enqueryModel->set_enqueryFor($row["Trainings"]);
                $sql = "SELECT followup_enq_id,followupDate,status FROM `enquiry_followup` WHERE followup_enq_id ='" . $enqueryModel->get_Id() . "' ORDER BY followup_id DESC LIMIT 1 ";
                //error_log($sql);
                $followup = mysqli_query($connectionObj, $sql);
                $row2 = mysqli_fetch_assoc($followup);
                $count = mysqli_num_rows($followup);
                if ($count > 0) {
                    $enqueryModel->set_followenqid($row2["followup_enq_id"]);
                    $enqueryModel->set_followupDate((date('d-m-y', strtotime($row2["followupDate"])) == '01-01-70') ? "" : date('d-m-y', strtotime($row2["followupDate"])));
                    $enqueryModel->set_status($row2["status"]);

                }
                array_push($enquirylist, $enqueryModel);
            }
        } else {
            echo "0 results";
        }
        return $enquirylist;
    }
    public static function getAllEnqueryBySection($enqueryFor)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM candidates  WHERE status = 1 and " . $enqueryFor . "!='' order by id DESC";
        //error_log($sql);
        $result = mysqli_query($connectionObj, $sql);
        $enquirylist = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $enqueryModel = new enquery();
                $enqueryModel->set_enqcreatedon(date('d-m-y', strtotime($row["Modified_Date"])));
                $enqueryModel->set_Id($row["id"]);
                $enqueryModel->set_name($row["Name"]);
                $enqueryModel->set_email($row["Email"]);
                $enqueryModel->set_phone($row["Phone"]);
                $enqueryModel->set_Source($row["source"]);
                $enqueryModel->setBranch($row["branch"]);
                $enqueryModel->set_enqueryFor($row["$enqueryFor"]);
                $sql = "SELECT followup_enq_id,followupDate,status FROM `enquiry_followup` WHERE followup_enq_id ='" . $enqueryModel->get_Id() . "' ORDER BY followup_id DESC LIMIT 1 ";
                //error_log($sql);
                $followup = mysqli_query($connectionObj, $sql);
                $row2 = mysqli_fetch_assoc($followup);
                $count = mysqli_num_rows($followup);
                if ($count > 0) {
                    $enqueryModel->set_followenqid($row2["followup_enq_id"]);
                    $enqueryModel->set_followupDate((date('d-m-y', strtotime($row2["followupDate"])) == '01-01-70') ? "" : date('d-m-y', strtotime($row2["followupDate"])));
                    $enqueryModel->set_status($row2["status"]);

                }
                array_push($enquirylist, $enqueryModel);
            }
        } else {
            echo "0 results";
        }
        return $enquirylist;
    }
    public static function insert($registrationObj)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "insert into candidates (`Name`, `Email`, `Phone`,`source`, `branch`,`Trainings`,`Internship`,`Services`,`Demo`,`Qualification`) 
                values ('" . $registrationObj->get_name() .
            "','" . $registrationObj->get_email() .
            "','" . $registrationObj->get_phone() .
            "','" . $registrationObj->get_Source() .
            "','" . $registrationObj->getbranch() .
            "','" . $registrationObj->get_trainings() .
            "', '" . $registrationObj->get_internship() .
            "','" . $registrationObj->get_services() .
            "','" . $registrationObj->get_demo() .
            "','" . $registrationObj->get_qualification() .
            "')";

        //error_log($sql);
        if ($connectionObj->query($sql) === true) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }
    public static function update($reg)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "update candidates SET Name='" . $reg->get_name() . "',
          Email='" . $reg->get_email() . "',
          Phone='" . $reg->get_phone() . "',
          source='" . $reg->get_Source() . "',
          Trainings= '" . $reg->get_trainings() . "',
          Internship='" . $reg->get_internship() . "',
          Services='" . $reg->get_services() . "',
          Demo='" . $reg->get_demo() . "',
          Qualification='" . $reg->get_qualification() . "',
          branch ='" . $reg->getBranch() . "'
          where id='" . $reg->get_id() . "' ";

        //error_log($sql);
        if ($connectionObj->query($sql) === TRUE) {
        } else {
            echo "Error: " . $sql . "<br>" . $connectionObj->error;
        }
    }
    public static function delete($registrationObj)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "DELETE FROM candidates WHERE Id='" . $registrationObj . "'";
        //error_log($sql);
        if ($connectionObj->query($sql) === true) {
        }
    }
    public static function getEnqueryById($id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();
        $sql = "SELECT * FROM candidates  WHERE status = 1 AND id=" . $id;
        $result = mysqli_query($connectionObj, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $enqueryModel = new enquery();
            $enqueryModel->set_enqcreatedon(date('d-m-y', strtotime($row["Modified_Date"])));
            $enqueryModel->set_Id($row["id"]);
            $enqueryModel->set_name($row["Name"]);
            $enqueryModel->set_email($row["Email"]);
            $enqueryModel->set_phone($row["Phone"]);
            $enqueryModel->set_Source($row["source"]);
            $enqueryModel->setBranch($row["branch"]);
            if ($row["Trainings"] != "") {
                $enqueryModel->setEnqType("Trainings");
                $enqueryModel->set_enqueryFor($row["Trainings"]);
            } else if ($row["Internship" != ""]) {
                $enqueryModel->setEnqType("Internship");
                $enqueryModel->set_enqueryFor($row["Internship"]);
            } else if ($row["Demo" != ""]) {
                $enqueryModel->setEnqType("Demo");
                $enqueryModel->set_enqueryFor($row["Demo"]);
            } else if ($row["Services" != ""]) {
                $enqueryModel->setEnqType("Services");
                $enqueryModel->set_enqueryFor($row["Services"]);
            }

            $sql = "SELECT followup_enq_id,followupDate,status FROM `enquiry_followup` WHERE followup_enq_id ='" . $enqueryModel->get_Id() . "' ORDER BY followup_id DESC LIMIT 1 ";
            //error_log($sql);
            $followup = mysqli_query($connectionObj, $sql);
            $row2 = mysqli_fetch_assoc($followup);
            $count = mysqli_num_rows($followup);
            if ($count > 0) {
                $enqueryModel->set_followenqid($row2["followup_enq_id"]);
                $enqueryModel->set_followupDate((date('d-m-y', strtotime($row2["followupDate"])) == '01-01-70') ? "" : date('d-m-y', strtotime($row2["followupDate"])));
                $enqueryModel->set_status($row2["status"]);

            }
            $enqueryModel;
        } else {
            echo "0 results";
        }
        return $enqueryModel;
    }
}