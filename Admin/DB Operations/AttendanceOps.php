<?php

require_once "../../DB Operations/dbconnection.php";

class AttendanceOps
{
    public static function findTrainerByFingerprint($fingerprintID, $branchID)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT
                id,
                BranchId,
                WorkingHours,
                ShiftID,
                Name
            FROM trainers
            WHERE FingerprintID='$fingerprintID'
            AND BranchId = '" . $connectionObj->real_escape_string($branchID) . "'
            LIMIT 1";

        $result = mysqli_query($connectionObj, $sql);

        if (mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return NULL;
    }

    public static function attendanceExists($trainerID, $attendanceDate)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT id
        FROM trainer_attendance
        WHERE TrainerID='$trainerID'
        AND AttendanceDate='$attendanceDate'";

        $result = mysqli_query($connectionObj, $sql);

        if ($result === false) {
            die("attendanceExists Query Error : " . $connectionObj->error . " | SQL: " . $sql);
        }

        return mysqli_num_rows($result) > 0;
    }

    public static function saveAttendance($attendance)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $punchIn  = $attendance->getPunchIn();
        $punchOut = $attendance->getPunchOut();

        $punchInSQL  = ($punchIn == "")  ? "NULL" : "'" . $connectionObj->real_escape_string($punchIn) . "'";
        $punchOutSQL = ($punchOut == "") ? "NULL" : "'" . $connectionObj->real_escape_string($punchOut) . "'";

        $sql = "INSERT INTO trainer_attendance
    (
        TrainerID,
        FingerprintID,
        BranchID,
        AttendanceDate,
        PunchIn,
        PunchOut,
        WorkingMinutes,
        RequiredMinutes,
        WorkingHours,
        LateMinutes,
        Late,
        OvertimeMinutes,
        ShortMinutes,
        Status,
        SourceFile
    )
    VALUES
    (
        '" . $attendance->getTrainerID() . "',
        '" . $attendance->getFingerprintID() . "',
        '" . $attendance->getBranchID() . "',
        '" . $attendance->getAttendanceDate() . "',
        $punchInSQL,
        $punchOutSQL,
        '" . $attendance->getWorkingMinutes() . "',
        '" . $attendance->getRequiredMinutes() . "',
        '" . $attendance->getWorkingHours() . "',
        '" . $attendance->getLateMinutes() . "',
        '" . $attendance->getLate() . "',
        '" . $attendance->getOvertimeMinutes() . "',
        '" . $attendance->getShortMinutes() . "',
        '" . $attendance->getStatus() . "',
        '" . $attendance->getSourceFile() . "'
    )";

        if ($connectionObj->query($sql) === TRUE) {
            return true;
        } else {
            die("Attendance Save Error : " . $connectionObj->error);
        }
    }

    public static function getSettings()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT * FROM attendance_settings LIMIT 1";
        $result = mysqli_query($connectionObj, $sql);

        return mysqli_fetch_assoc($result);
    }
    public static function getAttendanceByBranch($branchID)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT
                t.id AS TrainerID,
                t.Name AS TrainerName,
                ta.BranchID,
                ta.AttendanceDate,
                ta.PunchIn,
                ta.PunchOut,
                ta.WorkingHours,
                ta.Status,
                ta.Late,
                ta.LateMinutes
            FROM trainer_attendance ta
            INNER JOIN trainers t ON t.id = ta.TrainerID
            WHERE t.BranchId = '" . $connectionObj->real_escape_string($branchID) . "'
            ORDER BY ta.AttendanceDate ASC, t.Name ASC";

        $result = mysqli_query($connectionObj, $sql);
        if ($result === false) {
            die("getAttendanceByBranch Query Error : " . $connectionObj->error);
        }

        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    public static function getDailyAttendance($branchID, $date)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT
                t.id AS TrainerID,
                t.Name AS TrainerName,
                ta.PunchIn,
                ta.PunchOut,
                ta.WorkingHours,
                ta.Status,
                ta.Late,
                ta.LateMinutes,
                ta.OvertimeMinutes,
                ta.ShortMinutes
            FROM trainer_attendance ta
            INNER JOIN trainers t ON t.id = ta.TrainerID
            WHERE t.BranchId = '" . $connectionObj->real_escape_string($branchID) . "'
            AND ta.AttendanceDate = '" . $connectionObj->real_escape_string($date) . "'
            ORDER BY t.Name ASC";

        $result = mysqli_query($connectionObj, $sql);
        if ($result === false) {
            die("getDailyAttendance Query Error : " . $connectionObj->error);
        }

        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    public static function getMonthlyAttendance($branchID, $year, $month)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $monthStart = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
        $monthEnd   = date("Y-m-t", strtotime($monthStart));

        $sql = "SELECT
                t.id AS TrainerID,
                t.Name AS TrainerName,
                COUNT(CASE WHEN ta.Status = 'Present' THEN 1 END) AS PresentDays,
                COUNT(CASE WHEN ta.Status = 'Half Day' THEN 1 END) AS HalfDays,
                COUNT(CASE WHEN ta.Status = 'Absent' THEN 1 END) AS AbsentDays,
                COUNT(CASE WHEN ta.Status = 'Incomplete' THEN 1 END) AS IncompleteDays,
                COUNT(CASE WHEN ta.Late = 'Yes' THEN 1 END) AS LateDays,
                SUM(ta.WorkingHours) AS TotalHours,
                SUM(ta.OvertimeMinutes) AS TotalOvertimeMinutes,
                SUM(ta.LateMinutes) AS TotalLateMinutes
            FROM trainer_attendance ta
            INNER JOIN trainers t ON t.id = ta.TrainerID
            WHERE t.BranchId = '" . $connectionObj->real_escape_string($branchID) . "'
            AND ta.AttendanceDate BETWEEN '$monthStart' AND '$monthEnd'
            GROUP BY t.id, t.Name
            ORDER BY t.Name ASC";

        $result = mysqli_query($connectionObj, $sql);
        if ($result === false) {
            die("getMonthlyAttendance Query Error : " . $connectionObj->error);
        }

        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    public static function getDashboardSummary($branchID, $date)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT
                COUNT(CASE WHEN ta.Status = 'Present' THEN 1 END) AS PresentCount,
                COUNT(CASE WHEN ta.Status = 'Absent' THEN 1 END) AS AbsentCount,
                COUNT(CASE WHEN ta.Status = 'Half Day' THEN 1 END) AS HalfDayCount,
                COUNT(CASE WHEN ta.Status = 'Incomplete' THEN 1 END) AS IncompleteCount,
                COUNT(CASE WHEN ta.Late = 'Yes' THEN 1 END) AS LateCount,
                SUM(ta.OvertimeMinutes) AS TotalOvertimeMinutes
            FROM trainer_attendance ta
            INNER JOIN trainers t ON t.id = ta.TrainerID
            WHERE t.BranchId = '" . $connectionObj->real_escape_string($branchID) . "'
            AND ta.AttendanceDate = '" . $connectionObj->real_escape_string($date) . "'";

        $result = mysqli_query($connectionObj, $sql);
        if ($result === false) {
            die("getDashboardSummary Query Error : " . $connectionObj->error);
        }

        return mysqli_fetch_assoc($result);
    }

    public static function getMonthlyGraphData($branchID, $year, $month)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $monthStart = $year . "-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
        $monthEnd   = date("Y-m-t", strtotime($monthStart));

        $sql = "SELECT
                ta.AttendanceDate,
                COUNT(CASE WHEN ta.Status = 'Present' THEN 1 END) AS PresentCount,
                COUNT(CASE WHEN ta.Status = 'Absent' THEN 1 END) AS AbsentCount,
                COUNT(CASE WHEN ta.Late = 'Yes' THEN 1 END) AS LateCount
            FROM trainer_attendance ta
            INNER JOIN trainers t ON t.id = ta.TrainerID
            WHERE t.BranchId = '" . $connectionObj->real_escape_string($branchID) . "'
            AND ta.AttendanceDate BETWEEN '$monthStart' AND '$monthEnd'
            GROUP BY ta.AttendanceDate
            ORDER BY ta.AttendanceDate ASC";

        $result = mysqli_query($connectionObj, $sql);
        if ($result === false) {
            die("getMonthlyGraphData Query Error : " . $connectionObj->error);
        }

        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    public static function getTrainerCountByBranch($branchID)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT COUNT(*) AS TotalTrainers
            FROM trainers
            WHERE BranchId = '" . $connectionObj->real_escape_string($branchID) . "'";

        $result = mysqli_query($connectionObj, $sql);
        if ($result === false) {
            die("getTrainerCountByBranch Query Error : " . $connectionObj->error);
        }

        $row = mysqli_fetch_assoc($result);
        return (int) $row['TotalTrainers'];
    }


    public static function updateSettings($data)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $officeStart   = $connectionObj->real_escape_string($data['officestart']);
        $officeEnd     = $connectionObj->real_escape_string($data['officeend']);
        $graceMinutes  = (int) $data['graceminutes'];
        $fullDayHours  = (float) $data['fulldayhours'];
        $halfDayHours  = (float) $data['halfdayhours'];
        $midDayHours   = (float) $data['middayhours'];
        $status        = $connectionObj->real_escape_string($data['status']);

        // attendance_settings only ever has 1 row (id = 1), so check if it exists first
        $existing = self::getSettings();

        if ($existing == NULL) {
            $sql = "INSERT INTO attendance_settings
                (OfficeStart, OfficeEnd, GraceMinutes, FullDayHours, HalfDayHours, MidDayHours, Status)
                VALUES
                ('$officeStart', '$officeEnd', '$graceMinutes', '$fullDayHours', '$halfDayHours', '$midDayHours', '$status')";
        } else {
            $sql = "UPDATE attendance_settings SET
                OfficeStart   = '$officeStart',
                OfficeEnd     = '$officeEnd',
                GraceMinutes  = '$graceMinutes',
                FullDayHours  = '$fullDayHours',
                HalfDayHours  = '$halfDayHours',
                MidDayHours   = '$midDayHours',
                Status        = '$status'
                WHERE id = " . (int) $existing['id'];
        }

        if ($connectionObj->query($sql) === TRUE) {
            return true;
        } else {
            die("updateSettings Query Error : " . $connectionObj->error);
        }
    }
    public static function deleteAttendance($trainerID, $attendanceDate)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "DELETE FROM trainer_attendance
            WHERE TrainerID = '" . $connectionObj->real_escape_string($trainerID) . "'
            AND AttendanceDate = '" . $connectionObj->real_escape_string($attendanceDate) . "'";

        $result = mysqli_query($connectionObj, $sql);

        if ($result === false) {
            die("deleteAttendance Query Error : " . $connectionObj->error);
        }

        return true;
    }
    public static function getShifts()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT * FROM shifts WHERE Status = 'Active' ORDER BY StartTime ASC";
        $result = mysqli_query($connectionObj, $sql);

        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    public static function getSettingsFor($trainerID, $branchID, $shiftID)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $settings = null;

        // 1. Trainer-specific override
        $sql = "SELECT * FROM attendance_settings WHERE TrainerID = '" . (int)$trainerID . "' LIMIT 1";
        $result = mysqli_query($connectionObj, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $settings = mysqli_fetch_assoc($result);
        }

        // 2. Branch + Shift specific
        if ($settings === null && !empty($shiftID)) {
            $sql = "SELECT * FROM attendance_settings
                    WHERE BranchID = '" . (int)$branchID . "'
                    AND ShiftID = '" . (int)$shiftID . "'
                    AND TrainerID IS NULL LIMIT 1";
            $result = mysqli_query($connectionObj, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $settings = mysqli_fetch_assoc($result);
            }
        }

        // 3. Branch default (any shift)
        if ($settings === null) {
            $sql = "SELECT * FROM attendance_settings
                    WHERE BranchID = '" . (int)$branchID . "'
                    AND ShiftID IS NULL
                    AND TrainerID IS NULL LIMIT 1";
            $result = mysqli_query($connectionObj, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                $settings = mysqli_fetch_assoc($result);
            }
        }

        // 4. Global fallback
        if ($settings === null) {
            $sql = "SELECT * FROM attendance_settings
                    WHERE BranchID IS NULL AND ShiftID IS NULL AND TrainerID IS NULL LIMIT 1";
            $result = mysqli_query($connectionObj, $sql);
            $settings = mysqli_fetch_assoc($result);
        }

        // Trainer's own shift always defines actual OfficeStart/OfficeEnd
        if (!empty($shiftID)) {
            $shiftSql = "SELECT StartTime, EndTime FROM shifts WHERE id = '" . (int)$shiftID . "' LIMIT 1";
            $shiftResult = mysqli_query($connectionObj, $shiftSql);
            if ($shiftResult && mysqli_num_rows($shiftResult) > 0) {
                $shiftRow = mysqli_fetch_assoc($shiftResult);
                $settings['OfficeStart'] = $shiftRow['StartTime'];
                $settings['OfficeEnd']   = $shiftRow['EndTime'];
            }
        }

        return $settings;
    }

    public static function getAllSettingsProfiles()
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT
                    s.*,
                    b.BranchName AS BranchName,
                    sh.ShiftName AS ShiftName,
                    t.Name AS TrainerName
                FROM attendance_settings s
                LEFT JOIN branch b ON b.id = s.BranchID
                LEFT JOIN shifts sh ON sh.id = s.ShiftID
                LEFT JOIN trainers t ON t.id = s.TrainerID
                ORDER BY s.id ASC";

        $result = mysqli_query($connectionObj, $sql);

        $records = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }
        return $records;
    }

    public static function saveSettingsProfile($data)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $branchID  = !empty($data['branchid'])  ? (int)$data['branchid']  : "NULL";
        $shiftID   = !empty($data['shiftid'])   ? (int)$data['shiftid']   : "NULL";
        $trainerID = !empty($data['trainerid']) ? (int)$data['trainerid'] : "NULL";

        $officeStart  = $connectionObj->real_escape_string($data['officestart']);
        $officeEnd    = $connectionObj->real_escape_string($data['officeend']);
        $graceMinutes = (int) $data['graceminutes'];
        $fullDayHours = (float) $data['fulldayhours'];
        $halfDayHours = (float) $data['halfdayhours'];
        $midDayHours  = (float) $data['middayhours'];
        $status       = $connectionObj->real_escape_string($data['status']);

        $sql = "INSERT INTO attendance_settings
                (BranchID, ShiftID, TrainerID, OfficeStart, OfficeEnd, GraceMinutes, FullDayHours, HalfDayHours, MidDayHours, Status)
                VALUES
                ($branchID, $shiftID, $trainerID, '$officeStart', '$officeEnd', '$graceMinutes', '$fullDayHours', '$halfDayHours', '$midDayHours', '$status')";

        if ($connectionObj->query($sql) === TRUE) {
            return true;
        } else {
            die("saveSettingsProfile Query Error : " . $connectionObj->error);
        }
    }

    public static function deleteSettingsProfile($id)
    {
        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        if ((int)$id == 1) {
            return false;
        }

        $sql = "DELETE FROM attendance_settings WHERE id = '" . (int)$id . "'";
        $connectionObj->query($sql);
        return true;
    }
    public static function getAttendanceByBranchMonth(
        $branchID,
        $month,
        $year
    ) {

        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "SELECT
                t.id AS TrainerID,
                t.Name AS TrainerName,
                ta.BranchID,
                ta.AttendanceDate,
                ta.PunchIn,
                ta.PunchOut,
                ta.WorkingHours,
                ta.Status,
                ta.Late,
                ta.LateMinutes
            FROM trainer_attendance ta
            INNER JOIN trainers t
                ON t.id = ta.TrainerID
            WHERE
                t.BranchId = '" . $connectionObj->real_escape_string($branchID) . "'
                AND MONTH(ta.AttendanceDate) = '" . (int)$month . "'
                AND YEAR(ta.AttendanceDate) = '" . (int)$year . "'
            ORDER BY
                ta.AttendanceDate ASC,
                t.Name ASC";

        $result = mysqli_query($connectionObj, $sql);

        if ($result === false) {
            die("getAttendanceByBranchMonth Query Error : " . $connectionObj->error);
        }

        $records = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $records[] = $row;
        }

        return $records;
    }
    public static function deleteAttendanceByMonth(
        $branchID,
        $month,
        $year
    ) {

        $db = ConnectDb::getInstance();
        $connectionObj = $db->getConnection();

        $sql = "DELETE FROM trainer_attendance
            WHERE BranchID = '" . $connectionObj->real_escape_string($branchID) . "'
            AND MONTH(AttendanceDate) = '" . (int)$month . "'
            AND YEAR(AttendanceDate) = '" . (int)$year . "'";

        $result = mysqli_query($connectionObj, $sql);

        if ($result === false) {
            die("deleteAttendanceByMonth Query Error : " . $connectionObj->error);
        }

        return true;
    }
}
