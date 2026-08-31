<?php

class AttendanceModel
{
    private $TrainerID;
    private $FingerprintID;
    private $BranchID;
    private $AttendanceDate;
    private $PunchIn;
    private $PunchOut;
    private $WorkingMinutes;
    private $RequiredMinutes;
    private $LateMinutes;
    private $OvertimeMinutes;
    private $ShortMinutes;
    private $Status;
    private $SourceFile;

    public function setTrainerID($v)
    {
        $this->TrainerID = $v;
    }
    public function getTrainerID()
    {
        return $this->TrainerID;
    }

    public function setFingerprintID($v)
    {
        $this->FingerprintID = $v;
    }
    public function getFingerprintID()
    {
        return $this->FingerprintID;
    }

    public function setBranchID($v)
    {
        $this->BranchID = $v;
    }
    public function getBranchID()
    {
        return $this->BranchID;
    }

    public function setAttendanceDate($v)
    {
        $this->AttendanceDate = $v;
    }
    public function getAttendanceDate()
    {
        return $this->AttendanceDate;
    }

    public function setPunchIn($v)
    {
        $this->PunchIn = $v;
    }
    public function getPunchIn()
    {
        return $this->PunchIn;
    }

    public function setPunchOut($v)
    {
        $this->PunchOut = $v;
    }
    public function getPunchOut()
    {
        return $this->PunchOut;
    }

    public function setWorkingMinutes($v)
    {
        $this->WorkingMinutes = $v;
    }
    public function getWorkingMinutes()
    {
        return $this->WorkingMinutes;
    }

    public function setRequiredMinutes($v)
    {
        $this->RequiredMinutes = $v;
    }
    public function getRequiredMinutes()
    {
        return $this->RequiredMinutes;
    }

    public function setLateMinutes($v)
    {
        $this->LateMinutes = $v;
    }
    public function getLateMinutes()
    {
        return $this->LateMinutes;
    }

    public function setOvertimeMinutes($v)
    {
        $this->OvertimeMinutes = $v;
    }
    public function getOvertimeMinutes()
    {
        return $this->OvertimeMinutes;
    }

    public function setShortMinutes($v)
    {
        $this->ShortMinutes = $v;
    }
    public function getShortMinutes()
    {
        return $this->ShortMinutes;
    }

    public function setStatus($v)
    {
        $this->Status = $v;
    }
    public function getStatus()
    {
        return $this->Status;
    }

    public function setSourceFile($v)
    {
        $this->SourceFile = $v;
    }
    public function getSourceFile()
    {
        return $this->SourceFile;
    }

    private $WorkingHours;
    private $Late;

    public function setWorkingHours($v)
    {
        $this->WorkingHours = $v;
    }
    public function getWorkingHours()
    {
        return $this->WorkingHours;
    }

    public function setLate($v)
    {
        $this->Late = $v;
    }
    public function getLate()
    {
        return $this->Late;
    }
}
