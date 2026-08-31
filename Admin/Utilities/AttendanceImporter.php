<?php

require_once dirname(__DIR__) . "/Libraries/SimpleXLS.php";
require_once dirname(__DIR__) . "/DB Operations/AttendanceOps.php";
require_once dirname(__DIR__) . "/model/AttendanceModel.php";

use Shuchkin\SimpleXLS;

class AttendanceImporter
{

    public static function importAttendance($filePath, $branchID, $reportType, $fileName)
    {
        if (!file_exists($filePath)) {
            die("Attendance file not found.");
        }

        if (!$xls = SimpleXLS::parse($filePath)) {
            die(SimpleXLS::parseError());
        }

        $sheetNames = $xls->sheetNames();

        $saved = [];
        $skipped = [];

        foreach ($sheetNames as $index => $sheetName) {

            $sheetName = trim($sheetName);

            // Skip non-card sheets
            if (stripos($sheetName, "Schedule") !== false) {
                continue;
            }

            if (
                stripos($sheetName, "Att.") !== false ||
                stripos($sheetName, "Exception") !== false
            ) {
                continue;
            }

            // Card Report Sheet
            if (preg_match('/^\d+(?:\.\d+)*$/', $sheetName)) {

                $result = self::importCardReport(
                    $xls->rows($index),
                    $branchID,
                    $fileName
                );

                if (!empty($result['saved'])) {
                    $saved = array_merge($saved, $result['saved']);
                }

                if (!empty($result['skipped'])) {
                    $skipped = array_merge($skipped, $result['skipped']);
                }
            }
        }

        return [
            'saved'   => $saved,
            'skipped' => $skipped
        ];
    }
    private static function importScheduleReport($rows, $branchID, $fileName)
    {
        echo "<h2>Schedule Report Summary</h2>";

        $headerRow = null;

        foreach ($rows as $index => $row) {
            if (
                isset($row[0]) && trim($row[0]) == "ID" &&
                isset($row[1]) && trim($row[1]) == "Name"
            ) {
                $headerRow = $index;
                break;
            }
        }

        if ($headerRow === null) {
            echo "<p style='color:red'>Schedule header not found, skipping.</p>";
            return;
        }

        $dayColumns = [];
        foreach ($rows[$headerRow] as $col => $value) {
            if ($col >= 3 && trim((string)$value) !== "" && is_numeric($value)) {
                $dayColumns[] = $col;
            }
        }

        $totalDays = count($dayColumns);
        $dataStart = $headerRow + 2; // skip the day-of-week row (WED, THU...)

        for ($i = $dataStart; $i < count($rows); $i++) {

            $row = $rows[$i];

            if (empty($row[0]))
                continue;

            $fingerprintID = trim($row[0]);
            $employeeName  = trim($row[1]);
            $department    = trim($row[2]);

            $presentDays = 0;

            foreach ($dayColumns as $col) {
                $cell = isset($row[$col]) ? trim((string)$row[$col]) : "";
                if ($cell !== "") {
                    $presentDays++;
                }
            }

            echo "<hr><b>$employeeName</b> (ID: $fingerprintID, $department)<br>";
            echo "Present Days : $presentDays / $totalDays<br>";
        }
    }
    private static function importCardReport($rows, $branchID, $fileName)
    {
        // Read report month/year
        $reportDate = self::getReportMonthYear($rows);

        $reportMonth = $reportDate['month'];
        $reportYear  = $reportDate['year'];

        // These arrays will be filled by processEmployeeBlock()
        $saved   = [];
        $skipped = [];

        // Employee Block 1 (Columns A–O)
        self::processEmployeeBlock(
            $rows,
            0,
            $reportMonth,
            $reportYear,
            $branchID,
            $fileName,
            $saved,
            $skipped
        );

        self::processEmployeeBlock(
            $rows,
            15,
            $reportMonth,
            $reportYear,
            $branchID,
            $fileName,
            $saved,
            $skipped
        );

        self::processEmployeeBlock(
            $rows,
            30,
            $reportMonth,
            $reportYear,
            $branchID,
            $fileName,
            $saved,
            $skipped
        );

        return [
            'saved'   => $saved,
            'skipped' => $skipped
        ];
    }
    private static function processEmployeeBlock(
        $rows,
        $startCol,
        $reportMonth,
        $reportYear,
        $branchID,
        $fileName,
        &$saved,
        &$skipped
    ) {

        /*
    |--------------------------------------------------------------------------
    | Employee Details
    |--------------------------------------------------------------------------
    */

        $employeeName  = trim((string)($rows[2][$startCol + 9] ?? ""));
        $fingerprintID = trim((string)($rows[3][$startCol + 9] ?? ""));

        if ($fingerprintID == "") {
            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Trainer Lookup
    |--------------------------------------------------------------------------
    */

        $trainer = AttendanceOps::findTrainerByFingerprint(
            $fingerprintID,
            $branchID
        );

        if ($trainer == NULL) {

            $skipped[] = [
                'fingerprintID' => $fingerprintID,
                'employeeName'  => $employeeName,
                'reason'        => 'Trainer Not Found'
            ];

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | Attendance Rows
    |--------------------------------------------------------------------------
    */

        for ($row = 11; $row <= 41; $row++) {

            $weekDate = trim((string)($rows[$row][$startCol] ?? ""));

            if ($weekDate == "") {
                continue;
            }

            /*
        |--------------------------------------------------------------------------
        | Day Number
        |--------------------------------------------------------------------------
        */

            if (!preg_match('/^(\d+)/', $weekDate, $match)) {
                continue;
            }

            $day = (int)$match[1];

            /*
        |--------------------------------------------------------------------------
        | Build Punch List
        |--------------------------------------------------------------------------
        */

            $punches = [];

            for ($c = 1; $c <= 6; $c++) {

                $value = trim((string)($rows[$row][$startCol + $c] ?? ""));

                if ($value == "") {
                    continue;
                }

                if (strcasecmp($value, "Absent") == 0) {
                    continue;
                }

                if (!preg_match('/^(?:[01]?\d|2[0-3]):[0-5]\d$/', $value)) {
                    continue;
                }

                $punches[] = $value;
            }

            /*
        |--------------------------------------------------------------------------
        | Convert Punches To Old Format
        |--------------------------------------------------------------------------
        */

            $cell = implode("\n", $punches);
            /*
        |--------------------------------------------------------------------------
        | Send To Existing Card Report Processor
        |--------------------------------------------------------------------------
        */

            self::processCardDay(
                $trainer,
                $branchID,
                $employeeName,
                $fingerprintID,
                $day,
                $reportMonth,
                $reportYear,
                $cell,
                $saved,
                $skipped,
                $fileName
            );
        }
    }

    private static function processCardDay(
        $trainer,
        $branchID,
        $employeeName,
        $fingerprintID,
        $day,
        $reportMonth,
        $reportYear,
        $cell,
        &$saved,
        &$skipped,
        $fileName
    ) {

        // Empty Cell = Absent
        if (trim($cell) == "") {

            $maxDays = cal_days_in_month(
                CAL_GREGORIAN,
                (int)$reportMonth,
                (int)$reportYear
            );

            if ($day > $maxDays) {
                return;
            }

            $attendanceDate = date(
                "Y-m-d",
                strtotime(
                    $reportYear . "-" .
                        $reportMonth . "-" .
                        str_pad($day, 2, "0", STR_PAD_LEFT)
                )
            );

            $record = [
                'fingerprintID'   => $fingerprintID,
                'employeeName'    => $employeeName,
                'trainerID'       => $trainer['id'],
                'branchID'        => $branchID,
                'attendanceDate'  => $attendanceDate,
                'punchIn'         => "",
                'punchOut'        => "",
                'workedMinutes'   => 0,
                'requiredMinutes' => 0,
                'lateMinutes'     => 0,
                'overtimeMinutes' => 0,
                'shortMinutes'    => 0,
                'status'          => "Absent",
                'sourceFile'      => $fileName
            ];

            if (!AttendanceOps::attendanceExists(
                $trainer['id'],
                $attendanceDate
            )) {

                self::saveRecord($record);
                $saved[] = $record;
            }

            return;
        }

        /*
     * Convert cell into punches
     */

        $cell = str_replace("\r", "\n", $cell);

        $lines = explode("\n", $cell);

        $punches = [];

        foreach ($lines as $line) {

            $line = trim($line);

            if ($line == "") {
                continue;
            }

            // Accept only HH:MM format (24-hour)
            if (!preg_match('/^(?:[01]?\d|2[0-3]):[0-5]\d$/', $line)) {
                continue;
            }

            $punches[] = $line;
        }

        $punchCount = count($punches);

        /*
     * Date
     */

        $attendanceDate = date(
            "Y-m-d",
            strtotime(
                $reportYear . "-" .
                    $reportMonth . "-" .
                    str_pad($day, 2, "0", STR_PAD_LEFT)
            )
        );

        /*
     * First Punch
     */

        $firstPunch = "";

        if ($punchCount > 0) {
            $firstPunch = $punches[0];
        }

        /*
     * Last Punch
     */

        $lastPunch = "";

        if ($punchCount > 1) {
            $lastPunch = $punches[$punchCount - 1];
        }

        /*
     * Remaining calculations
     */

        self::calculateAttendance(
            $trainer,
            $branchID,
            $employeeName,
            $fingerprintID,
            $attendanceDate,
            $firstPunch,
            $lastPunch,
            $punchCount,
            $saved,
            $skipped,
            $fileName
        );
    }

    /*
     * ==========================================================
     * CARD REPORT CALCULATION ENGINE
     * ==========================================================
     *
     * Applies the punch-count business rules against a single
     * trainer/day, using AttendanceOps::getSettingsFor() to resolve
     * OfficeStart, GraceMinutes, FullDayHours, HalfDayHours and
     * MidDayHours (Trainer -> Branch -> Shift precedence is handled
     * inside AttendanceOps, this method never hardcodes hours).
     *
     * Punch Rules
     * -----------
     * 0 punches  -> Absent (handled in processCardDay before this
     *               method is called, kept here as well for safety
     *               in case a cell reduces to 0 punches after trim)
     * 1 punch    -> Incomplete, Check In only
     * 2 punches  -> Check In = first, Check Out = second, full calc
     * 3+ punches -> Check In = first, Check Out = last, middle
     *               punches ignored, hours calculated normally,
     *               Status forced to Incomplete
     */
    private static function calculateAttendance(
        $trainer,
        $branchID,
        $employeeName,
        $fingerprintID,
        $attendanceDate,
        $firstPunch,
        $lastPunch,
        $punchCount,
        &$saved,
        &$skipped,
        $fileName
    ) {

        $trainerID      = $trainer['id'];
        $trainerShiftID = isset($trainer['ShiftID']) ? $trainer['ShiftID'] : null;

        // Never overwrite an existing attendance row here — same
        // guard used by the exception-report flow / forceSaveSkipped.
        if (AttendanceOps::attendanceExists($trainerID, $attendanceDate)) {

            $skipped[] = [
                'fingerprintID'   => $fingerprintID,
                'employeeName'    => $employeeName,
                'trainerID'       => $trainerID,
                'branchID'        => $branchID,
                'attendanceDate'  => $attendanceDate,
                'punchIn'         => $firstPunch,
                'punchOut'        => $lastPunch,
                'workedMinutes'   => 0,
                'requiredMinutes' => 0,
                'lateMinutes'     => 0,
                'overtimeMinutes' => 0,
                'shortMinutes'    => 0,
                'status'          => '',
                'sourceFile'      => $fileName,
                'reason'          => 'Already Exists'
            ];
            return;
        }

        // Settings are resolved per Trainer -> Branch -> Shift,
        // exactly as configured in Attendance Settings. Nothing here
        // is hardcoded.
        $resolvedSettings = AttendanceOps::getSettingsFor($trainerID, $branchID, $trainerShiftID);

        $officeStart     = $resolvedSettings['OfficeStart'];
        $graceMinutes    = (int) $resolvedSettings['GraceMinutes'];
        $requiredMinutes = (float) $resolvedSettings['FullDayHours'] * 60;
        $halfDayMinutes  = (float) $resolvedSettings['HalfDayHours'] * 60;
        $midDayMinutes   = (float) $resolvedSettings['MidDayHours'] * 60; // reserved per settings schema

        $punchInFormatted  = "";
        $punchOutFormatted = "";
        $workedMinutes   = 0;
        $lateMinutes     = 0;
        $overtimeMinutes = 0;
        $shortMinutes    = 0;
        $status          = "";

        $graceEndTime = strtotime($attendanceDate . " " . $officeStart) + ($graceMinutes * 60);

        if ($punchCount == 0) {

            // Defensive fallback - processCardDay already routes true
            // empty cells to the Absent branch before reaching here.
            $status = "Absent";
        } elseif ($punchCount == 1) {

            // 1 Punch -> Incomplete, Check In only, Check Out empty
            $inTime = strtotime($attendanceDate . " " . $firstPunch);

            $punchInFormatted  = date("Y-m-d H:i:s", $inTime);
            $punchOutFormatted = "";

            $status = "Incomplete";

            $lateMinutes = max(0, round(($inTime - $graceEndTime) / 60));
        } else {

            // 2 punches -> Check In = first, Check Out = second
            // 3+ punches -> Check In = first, Check Out = last,
            //               middle punches are ignored
            $inDateTime = DateTime::createFromFormat(
                "Y-m-d H:i",
                $attendanceDate . " " . $firstPunch
            );

            $outDateTime = DateTime::createFromFormat(
                "Y-m-d H:i",
                $attendanceDate . " " . $lastPunch
            );

            if (!$inDateTime || !$outDateTime) {
                $skipped[] = [
                    'fingerprintID' => $fingerprintID,
                    'employeeName'  => $employeeName,
                    'reason'        => 'Invalid Punch Time'
                ];
                return;
            }

            $inTime  = $inDateTime->getTimestamp();
            $outTime = $outDateTime->getTimestamp();

            // Overnight Shift Support
            if ($outTime < $inTime) {
                $outTime = strtotime("+1 day", $outTime);
            }

            $punchInFormatted  = date("Y-m-d H:i:s", $inTime);
            $punchOutFormatted = date("Y-m-d H:i:s", $outTime);

            $workedMinutes = round(($outTime - $inTime) / 60);

            $lateMinutes     = max(0, round(($inTime - $graceEndTime) / 60));
            $overtimeMinutes = max(0, $workedMinutes - $requiredMinutes);

            if ($workedMinutes >= $requiredMinutes) {

                // Worked >= FullDay
                $status       = "Present";
                $shortMinutes = 0;
            } elseif ($workedMinutes > $halfDayMinutes) {

                // HalfDay < Worked < FullDay
                $status       = "Present";
                $shortMinutes = $requiredMinutes - $workedMinutes;
            } elseif ($workedMinutes == $halfDayMinutes) {

                // Worked == HalfDay
                $status       = "Half Day";
                $shortMinutes = $requiredMinutes - $workedMinutes;
            } else {

                // Worked < HalfDay - never show Absent here, display
                // the actual worked hours instead (e.g. "03:25")
                $shortMinutes = $requiredMinutes - $workedMinutes;

                $hrs  = floor($workedMinutes / 60);
                $mins = $workedMinutes % 60;

                $status = sprintf("%02d:%02d", $hrs, $mins);
            }

            // 3 or more punches always render as Incomplete, even
            // though the worked-hours math above is unaffected.
            if ($punchCount >= 3) {
                $status = "Incomplete";
            }
        }

        $record = [
            'fingerprintID'   => $fingerprintID,
            'employeeName'    => $employeeName,
            'trainerID'       => $trainerID,
            'branchID'        => $branchID,
            'attendanceDate'  => $attendanceDate,
            'punchIn'         => $punchInFormatted,
            'punchOut'        => $punchOutFormatted,
            'workedMinutes'   => $workedMinutes,
            'requiredMinutes' => $requiredMinutes,
            'lateMinutes'     => $lateMinutes,
            'overtimeMinutes' => $overtimeMinutes,
            'shortMinutes'    => $shortMinutes,
            'status'          => $status,
            'sourceFile'      => $fileName
        ];

        self::saveRecord($record);
        $saved[] = $record;
    }



    private static function importExceptionReport($rows, $branchID, $fileName)
    {


        $headerRow = null;

        $headerRow = null;

        foreach ($rows as $index => $row) {

            $foundID = false;
            $foundName = false;

            foreach ($row as $cell) {

                $cell = strtoupper(trim((string)$cell));

                if ($cell == "ID") {
                    $foundID = true;
                }

                if ($cell == "NAME") {
                    $foundName = true;
                }
            }

            if ($foundID && $foundName) {
                $headerRow = $index;
                break;
            }
        }

        $dayColumns = [];

        foreach ($rows[$headerRow] as $col => $value) {

            $value = trim((string)$value);

            if (
                is_numeric($value) &&
                (int)$value >= 1 &&
                (int)$value <= 31
            ) {

                $dayColumns[$col] = (int)$value;
            }
        }

        $dataStart = $headerRow + 2;

        $saved   = [];
        $skipped = [];

        for ($i = $dataStart; $i < count($rows); $i++) {

            $row = $rows[$i];

            if (empty($row[0]))
                continue;

            $fingerprintID  = trim($row[0]);
            $employeeName   = trim($row[1]);
            $attendanceDate = trim($row[3]);

            $punchInRaw  = trim($row[4]);
            $punchOutRaw = trim($row[5]);

            if ($attendanceDate == "")
                continue;

            $punchIn  = ($punchInRaw  == "") ? "" : date("Y-m-d H:i:s", strtotime($attendanceDate . " " . $punchInRaw));
            $punchOut = ($punchOutRaw == "") ? "" : date("Y-m-d H:i:s", strtotime($attendanceDate . " " . $punchOutRaw));

            $trainer = AttendanceOps::findTrainerByFingerprint($fingerprintID, $branchID);

            if ($trainer == NULL) {
                $skipped[] = [
                    'fingerprintID'   => $fingerprintID,
                    'employeeName'    => $employeeName,
                    'trainerID'       => null,
                    'branchID'        => $branchID,
                    'attendanceDate'  => $attendanceDate,
                    'punchIn'         => $punchIn,
                    'punchOut'        => $punchOut,
                    'workedMinutes'   => 0,
                    'requiredMinutes' => 0,
                    'lateMinutes'     => 0,
                    'overtimeMinutes' => 0,
                    'shortMinutes'    => 0,
                    'status'          => '',
                    'sourceFile'      => $fileName,
                    'reason'          => 'Trainer Not Found'
                ];
                continue;
            }

            $trainerID       = $trainer['id'];
            $trainerShiftID  = $trainer['ShiftID'];

            $resolvedSettings = AttendanceOps::getSettingsFor($trainerID, $branchID, $trainerShiftID);
            $officeStart      = $resolvedSettings['OfficeStart'];
            $graceMinutes     = (int) $resolvedSettings['GraceMinutes'];
            $requiredMinutes  = (float) $resolvedSettings['FullDayHours'] * 60;
            $halfDayMinutes   = $requiredMinutes / 2;
            $midDayMinutes    = (float) $resolvedSettings['MidDayHours'] * 60;

            $workedMinutes   = 0;
            $lateMinutes     = 0;
            $overtimeMinutes = 0;
            $shortMinutes    = 0;
            $status          = "";

            if ($punchInRaw == "" && $punchOutRaw == "") {
                $status = "Absent";
            } elseif ($punchInRaw != "" && $punchOutRaw == "") {
                $status = "Incomplete";
            } elseif ($punchInRaw == "" && $punchOutRaw != "") {
                $status = "Incomplete";
            } else {
                $inTime  = strtotime($attendanceDate . " " . $punchInRaw);
                $outTime = strtotime($attendanceDate . " " . $punchOutRaw);

                $workedMinutes = round(($outTime - $inTime) / 60);

                if ($workedMinutes >= $requiredMinutes) {

                    // Present
                    $status = "Present";
                    $overtimeMinutes = $workedMinutes - $requiredMinutes;
                } elseif ($workedMinutes > $halfDayMinutes) {

                    // More than 5 hours
                    $status = "Present";

                    $shortMinutes = $requiredMinutes - $workedMinutes;
                    $lateMinutes = max(0, $shortMinutes - $graceMinutes);
                } elseif ($workedMinutes == $halfDayMinutes) {

                    // Exactly 5 hours
                    $status = "Half Day";

                    $shortMinutes = $requiredMinutes - $workedMinutes;
                    $lateMinutes = max(0, $shortMinutes - $graceMinutes);
                } else {

                    // Less than 5 hours
                    $status = "Worked";

                    $shortMinutes = $requiredMinutes - $workedMinutes;
                    $lateMinutes = max(0, $shortMinutes - $graceMinutes);
                }
            }

            $record = [
                'fingerprintID'   => $fingerprintID,
                'employeeName'    => $employeeName,
                'trainerID'       => $trainerID,
                'branchID'        => $branchID,
                'attendanceDate'  => $attendanceDate,
                'punchIn'         => $punchIn,
                'punchOut'        => $punchOut,
                'workedMinutes'   => $workedMinutes,
                'requiredMinutes' => $requiredMinutes,
                'lateMinutes'     => $lateMinutes,
                'overtimeMinutes' => $overtimeMinutes,
                'shortMinutes'    => $shortMinutes,
                'status'          => $status,
                'sourceFile'      => $fileName
            ];

            if (AttendanceOps::attendanceExists($trainerID, $attendanceDate)) {
                $record['reason'] = 'Already Exists';
                $skipped[] = $record;
                continue;
            }

            self::saveRecord($record);

            $record['reason'] = 'Saved';
            $saved[] = $record;
        }

        return [
            'saved'   => $saved,
            'skipped' => $skipped
        ];
    }


    private static function saveRecord($record)
    {
        $model = new AttendanceModel();
        $model->setTrainerID($record['trainerID']);
        $model->setFingerprintID($record['fingerprintID']);
        $model->setBranchID($record['branchID']);
        $model->setAttendanceDate($record['attendanceDate']);
        $model->setPunchIn($record['punchIn']);
        $model->setPunchOut($record['punchOut']);
        $model->setWorkingMinutes($record['workedMinutes']);
        $model->setRequiredMinutes($record['requiredMinutes']);
        $model->setLateMinutes($record['lateMinutes']);
        $model->setOvertimeMinutes($record['overtimeMinutes']);
        $model->setShortMinutes($record['shortMinutes']);
        $model->setStatus($record['status']);
        $model->setSourceFile($record['sourceFile']);
        $hours = floor($record['workedMinutes'] / 60);
        $minutes = $record['workedMinutes'] % 60;

        $model->setWorkingHours(sprintf("%02d:%02d", $hours, $minutes));
        $model->setLate($record['lateMinutes'] > 0 ? "Yes" : "No");

        AttendanceOps::saveAttendance($model);
    }

    public static function forceSaveSkipped($record)
    {
        if (empty($record['trainerID'])) {
            return false; // Trainer Not Found rows can never be saved — no valid trainer to link
        }

        AttendanceOps::deleteAttendance($record['trainerID'], $record['attendanceDate']);
        self::saveRecord($record);

        return true;
    }
    private static function getReportMonthYear($rows)
    {
        foreach ($rows as $row) {

            foreach ($row as $cell) {

                $cell = trim((string)$cell);

                /*
             * Card Report
             * Example:
             * 2026-07-01 ~ 2026-07-31
             */
                if (preg_match('/(\d{4})-(\d{2})-\d{2}/', $cell, $m)) {

                    return [
                        "month" => $m[2],
                        "year"  => $m[1]
                    ];
                }

                /*
             * Old Format
             * Example:
             * July 2026
             */
                if (preg_match('/([A-Za-z]+)\s+(\d{4})/', $cell, $m)) {

                    return [
                        "month" => date("m", strtotime($m[1])),
                        "year"  => $m[2]
                    ];
                }
            }
        }

        return [
            "month" => date("m"),
            "year"  => date("Y")
        ];
    }
}
