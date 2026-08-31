<?php

require "../session.php";
require_once "../DB Operations/AssetOps.php";

$id = intval($_GET['id']);

$data = DBasset::getAssetById($id);

echo json_encode($data);