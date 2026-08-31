<?php

require_once __DIR__ . "/../model/ChatbotLeadModel.php";
require_once "../../DB Operations/dbconnection.php";

class DBchatbotlead
{
    private static function conn()
    {
        $db = ConnectDb::getInstance();
        return $db->getConnection();
    }

    public static function ensureSchema()
    {
        $connectionObj = self::conn();

        $hasStatus = mysqli_query($connectionObj, "SHOW COLUMNS FROM chatbot_leads LIKE 'status'");
        if ($hasStatus && mysqli_num_rows($hasStatus) == 0) {
            mysqli_query(
                $connectionObj,
                "ALTER TABLE chatbot_leads
                 ADD COLUMN status ENUM('new','followup','converted','spam')
                 NOT NULL DEFAULT 'new' AFTER source"
            );
        }

        $hasNotes = mysqli_query($connectionObj, "SHOW COLUMNS FROM chatbot_leads LIKE 'notes'");
        if ($hasNotes && mysqli_num_rows($hasNotes) == 0) {
            mysqli_query(
                $connectionObj,
                "ALTER TABLE chatbot_leads
                 ADD COLUMN notes TEXT NULL AFTER status"
            );
        }
    }

    private static function hydrate($row)
    {
        return new ChatbotLead(
            $row['id'],
            $row['name'],
            $row['phone'],
            $row['interest'],
            $row['source'],
            $row['created_at'],
            $row['status'],
            $row['notes']
        );
    }

    public static function getAllLeads()
    {
        $connectionObj = self::conn();

        $leads = [];

        $sql = "SELECT id,name,phone,interest,source,created_at,status,notes
                FROM chatbot_leads
                ORDER BY created_at DESC";

        $result = mysqli_query($connectionObj, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $leads[] = self::hydrate($row);
            }
        }

        return $leads;
    }

    public static function getLeadById($id)
    {
        $connectionObj = self::conn();

        $id = (int)$id;

        $sql = "SELECT id,name,phone,interest,source,created_at,status,notes
                FROM chatbot_leads
                WHERE id=$id
                LIMIT 1";

        $result = mysqli_query($connectionObj, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return self::hydrate(mysqli_fetch_assoc($result));
        }

        return null;
    }

    public static function updateStatus($id, $status)
    {
        $connectionObj = self::conn();

        $id = (int)$id;

        $allowed = ['new', 'followup', 'converted', 'spam'];

        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $status = mysqli_real_escape_string($connectionObj, $status);

        $sql = "UPDATE chatbot_leads
                SET status='$status'
                WHERE id=$id";

        return mysqli_query($connectionObj, $sql);
    }

    public static function updateNotes($id, $notes)
    {
        $connectionObj = self::conn();

        $id = (int)$id;

        $notes = mysqli_real_escape_string($connectionObj, $notes);

        $sql = "UPDATE chatbot_leads
                SET notes='$notes'
                WHERE id=$id";

        return mysqli_query($connectionObj, $sql);
    }

    public static function updateLead($id, $name, $phone, $interest)
    {
        $connectionObj = self::conn();

        $id = (int)$id;

        $name = mysqli_real_escape_string($connectionObj, $name);
        $phone = mysqli_real_escape_string($connectionObj, $phone);
        $interest = mysqli_real_escape_string($connectionObj, $interest);

        $sql = "UPDATE chatbot_leads
            SET
                name='$name',
                phone='$phone',
                interest='$interest'
            WHERE id='$id'";

        if (mysqli_query($connectionObj, $sql)) {
            return true;
        } else {
            die("Update Error : " . mysqli_error($connectionObj));
        }
    }

    public static function insertLead($name, $phone, $interest, $source)
    {
        $connectionObj = self::conn();

        $name = mysqli_real_escape_string($connectionObj, $name);
        $phone = mysqli_real_escape_string($connectionObj, $phone);
        $interest = mysqli_real_escape_string($connectionObj, $interest);
        $source = mysqli_real_escape_string($connectionObj, $source);

        $sql = "INSERT INTO chatbot_leads
                (name,phone,interest,source,created_at,status)
                VALUES
                ('$name','$phone','$interest','$source',NOW(),'new')";

        if (mysqli_query($connectionObj, $sql)) {
            return mysqli_insert_id($connectionObj);
        }

        return false;
    }

    public static function deleteLead($id)
    {
        $connectionObj = self::conn();

        $id = (int)$id;

        $sql = "DELETE FROM chatbot_leads WHERE id=$id";

        return mysqli_query($connectionObj, $sql);
    }
    public static function moveToEnquiry($id)
    {
        $connectionObj = self::conn();

        $lead = self::getLeadById($id);

        if (!$lead) {
            return false;
        }

        $phone = mysqli_real_escape_string($connectionObj, $lead->get_phone());

        // Duplicate check
        $check = mysqli_query(
            $connectionObj,
            "SELECT id FROM candidates WHERE Phone='$phone' AND status=1 LIMIT 1"
        );

        if (mysqli_num_rows($check) > 0) {
            return false;
        }

        $name     = mysqli_real_escape_string($connectionObj, $lead->get_name());
        $interest = mysqli_real_escape_string($connectionObj, $lead->get_interest());
        $source   = mysqli_real_escape_string($connectionObj, $lead->get_Source());

        $sql = "INSERT INTO candidates
            (
                Name,
                Email,
                Phone,
                Qualification,
                source,
                branch,
                Trainings,
                Internship,
                Demo,
                Services,
                status
            )
            VALUES
            (
                '$name',
                '',
                '$phone',
                '',
                '$source',
                'Online',
                '$interest',
                '',
                '',
                '',
                1
            )";

        if (mysqli_query($connectionObj, $sql)) {
            mysqli_query(
                $connectionObj,
                "UPDATE chatbot_leads
             SET status='converted'
             WHERE id=" . $id
            );

            return true;
        }

        die(mysqli_error($connectionObj));
    }
}
