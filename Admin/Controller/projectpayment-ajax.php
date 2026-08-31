<?php

require_once "../../DB Operations/dbconnection.php";
require_once "../DB Operations/ProjectPaymentOps.php";

if (!isset($_GET['project_id'])) {
    exit;
}

$projectId = (int)$_GET['project_id'];

$data = DBprojectpayment::getProjectPaymentSummary($projectId);

header("Content-Type: application/json");

echo json_encode($data);