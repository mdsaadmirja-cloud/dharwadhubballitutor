<?php

require_once "../../DB Operations/dbconnection.php";
require_once "../model/Branchmodel.php";

class DBbranch
{
    public static function selectbranch()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT * FROM branch
                WHERE Status='Active'
                ORDER BY BranchName";

        $result = mysqli_query($connectionObj, $sql);

        $branchlist = array();

        if ($result) {

            while ($row = mysqli_fetch_assoc($result)) {

                $branch = new Branch();

                $branch->set_id($row['id']);
                $branch->set_branchname($row['BranchName']);
                $branch->set_branchcode($row['BranchCode']);
                $branch->set_address($row['Address']);
                $branch->set_phone($row['Phone']);
                $branch->set_status($row['Status']);

                $branchlist[] = $branch;
            }
        }

        return $branchlist;
    }
}