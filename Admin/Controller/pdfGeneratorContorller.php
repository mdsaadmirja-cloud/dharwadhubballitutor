<?php
require_once("../vendor/autoload.php");
require_once("../model/Feesmodel.php");
require_once("../DB Operations/FeesOps.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $waterMarked = $_POST['waterMarked'];
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'showWatermarkText' => $waterMarked,
        'orientation' => 'P',
        'autoPadding' => 'true',
        'collapseBlockMargins' => 'false',
        'defaultheaderline' => '2',
    ]);
    $fileType = $_POST['fileType'];
    $fileName = $_POST['fileName'];
    if ($waterMarked == 'true') {
        $mpdf->WriteHTML('<watermarktext content="ACE DECORS" alpha="0.1" />');
    }
    $mpdf->WriteHTML($_POST['html'], \Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->Output('../uploads/' . $fileType . '/' . $fileName . '.pdf', \Mpdf\Output\Destination::FILE);
error_log($fileType);
   
    if($fileType=='studentpayment'){
        $Fees= new Fees();
        $Fees->set_admitid($_POST['admitID']);
        $Fees->set_modifiedby($_POST['modifiedby']);
    $Fees->set_feereceipt($fileName.'.pdf');
    DBFees::updateFileName($Fees);

    }
  
}