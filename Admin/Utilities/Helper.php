<?php
require "../../Admin/fpdf182/fpdf.php";
class Helper
{
  public static function fileupload($filetoupload)
  {
    if (empty($filetoupload['name'])) {
      return false;
    }

    $ext = strtolower(pathinfo($filetoupload['name'], PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'];

    if (!in_array($ext, $allowed)) {
      return false;
    }

    if ($filetoupload['size'] > 10 * 1024 * 1024) {
      return false;
    }

    if (in_array($ext, ['pdf', 'doc', 'docx'])) {
      $target_dir = "../../Admin/uploads/resume/";
    } else {
      $target_dir = "../../Admin/uploads/";
    }

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($filetoupload['name']);

    if (file_exists($target_file)) {
      unlink($target_file);
    }

    return move_uploaded_file($filetoupload['tmp_name'], $target_file);
  }
  public static function feereceipt($collectfees)
  {

    //create pdf object
    $pdf = new FPDF('P', 'mm', 'A4');
    //add new page
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);

    //Cell(width , height , text , border , end line , [align] )

    $pdf->Image('../../Admin/media/logo.png', 5, 16, -300);
    $pdf->Cell(189, 10, '', 0, 1);

    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);

    $pdf->Cell(125, 7, 'DhawradHubballiTutor', 0, 0);
    $pdf->Cell(59, 7, 'Invoice', 0, 1); //end of line

    //set font to arial, regular, 12pt
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(130, 7, 'JP Nippani Complex,Gandhinagar', 0, 0);
    $pdf->Cell(59, 7, '', 0, 1); //end of line

    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(115, 7, 'Dharwad,580030', 0, 0);
    $pdf->Cell(25, 7, 'Date', 0, 0);
    $pdf->Cell(34, 7, "" . date("Y/m/d") . "", 0, 1); //end of line
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);

    $pdf->Cell(130, 7, 'Phone: +919741237334 ,+918007961759', 0, 0);
    $pdf->Cell(189, 10, '', 0, 1); //end of line

    //make a dummy empty cell as a vertical spacer
    $pdf->Cell(189, 10, '', 0, 1);
    //billing address
    $pdf->Cell(100, 5, 'Bill to', 0, 1); //end of line
    //add dummy cell at beginning of each line for indentation
    $pdf->Cell(10, 7, "" . $collectfees->get_name() . "", 0, 0);
    $pdf->Cell(90, 7, '', 0, 1);
    $pdf->Cell(10, 7, "" . $collectfees->get_coursesopted() . "", 0, 0);
    $pdf->Cell(90, 7, '', 0, 1);

    $pdf->Cell(10, 7, "" . $collectfees->get_phone() . "", 0, 0);
    $pdf->Cell(90, 7, '', 0, 1);

    //make a dummy empty cell as a vertical spacer
    $pdf->Cell(189, 10, '', 0, 1); //end of line

    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(105, 7, 'Description', 1, 0);
    $pdf->Cell(24, 7, 'Total Fees', 1, 0);
    $pdf->Cell(30, 7, 'Balance', 1, 0);
    $pdf->Cell(30, 7, 'Paid Amount', 1, 1); //end of line
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(105, 7, 'Fees paid towards ' . $collectfees->get_coursesopted() . ' ', 1, 0);
    $pdf->Cell(24, 7, "" . $collectfees->get_tfees() . "", 1, 0);
    $pdf->Cell(30, 7, "" . $collectfees->get_pendingfees() . "", 1, 0);
    $pdf->Cell(30, 7, "" . $collectfees->get_pfees() . "", 1, 1, 'R'); //end of line

    $pdf->Cell(129, 7, '', 1, 0);
    // $pdf->Cell(24 ,7,'',1,0);
    $pdf->Cell(30, 7, 'Subtotal', 1, 0);
    $pdf->Cell(30, 7, "" . $collectfees->get_pfees() . "", 1, 1, 'R');


    $pdf->Line(10, 50, 200, 50);
    $filename = "" . $collectfees->get_name() . date("Y-m-d") . ".pdf";

    $pdf->Cell(70, 119, ' Authorised Signatory', 0, 0);

    $pdf->Ln(10);
    $pdf->Cell(140, 7, '', 0, 0);

    $pdf->Cell(70, 100, 'Student Signature', 0, 0, 'C');


    //output the result
    $pdf->Output('../../Admin/uploads/Fee Receipts/' . $filename, 'F');
  }


  public static function paymentreceipt($collectamt)
  {

    //create pdf object
    $pdf = new FPDF('P', 'mm', 'A4');
    //add new page
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 14);

    //Cell(width , height , text , border , end line , [align] )

    $pdf->Image('../../Admin/media/logo.png', 5, 16, -300);
    $pdf->Cell(189, 10, '', 0, 1);

    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);

    $pdf->Cell(125, 7, 'DhawradHubballiTutor', 0, 0);
    $pdf->Cell(59, 7, 'Invoice', 0, 1); //end of line

    //set font to arial, regular, 12pt
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(130, 7, 'JP Nippani Complex,Gandhinagar', 0, 0);
    $pdf->Cell(59, 7, '', 0, 1); //end of line

    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(115, 7, 'Dharwad,580030', 0, 0);
    $pdf->Cell(25, 7, 'Date', 0, 0);
    $pdf->Cell(34, 7, "" . date("Y/m/d") . "", 0, 1); //end of line
    $pdf->Cell(12, 5, "", 0, 0);
    $pdf->Cell(12, 5, "", 0, 0);

    $pdf->Cell(130, 7, 'Phone: +919741237334 ,+918007961759', 0, 0);
    $pdf->Cell(189, 10, '', 0, 1); //end of line

    //make a dummy empty cell as a vertical spacer
    $pdf->Cell(189, 10, '', 0, 1);
    //billing address
    $pdf->Cell(100, 5, 'Bill to', 0, 1); //end of line
    //add dummy cell at beginning of each line for indentation
    $pdf->Cell(10, 7, "" . $collectamt->get_name() . "", 0, 0);
    $pdf->Cell(90, 7, '', 0, 1);
    $pdf->Cell(10, 7, "" . $collectamt->get_services() . "", 0, 0);
    $pdf->Cell(90, 7, '', 0, 1);

    $pdf->Cell(10, 7, "" . $collectamt->get_phone() . "", 0, 0);
    $pdf->Cell(90, 7, '', 0, 1);

    //make a dummy empty cell as a vertical spacer
    $pdf->Cell(189, 10, '', 0, 1); //end of line

    $pdf->SetFont('Arial', 'B', 12);

    $pdf->Cell(105, 7, 'Description', 1, 0);
    $pdf->Cell(30, 7, 'TotalAmount', 1, 0);
    $pdf->Cell(24, 7, 'Balance', 1, 0);
    $pdf->Cell(30, 7, 'Paid Amount', 1, 1); //end of line
    $pdf->SetFont('Arial', '', 12);

    $pdf->Cell(105, 7, 'Fees paid towards ' . $collectamt->get_services() . ' ', 1, 0);
    $pdf->Cell(24, 7, "" . $collectamt->get_totalamt() . "", 1, 0);
    $pdf->Cell(30, 7, "" . $collectamt->get_pendingamt() . "", 1, 0);
    $pdf->Cell(30, 7, "" . $collectamt->get_paidamt() . "", 1, 1, 'R'); //end of line

    $pdf->Cell(129, 7, '', 1, 0);
    // $pdf->Cell(24 ,7,'',1,0);
    $pdf->Cell(30, 7, 'Subtotal', 1, 0);
    $pdf->Cell(30, 7, "" . $collectamt->get_paidamt() . "", 1, 1, 'R');


    $pdf->Line(10, 50, 200, 50);
    $filename = "" . $collectamt->get_name() . date("Y-m-d") . ".pdf";

    $pdf->Cell(70, 119, ' Authorised Signatory', 0, 0);

    $pdf->Ln(10);
    $pdf->Cell(140, 7, '', 0, 0);

    $pdf->Cell(70, 100, 'Student Signature', 0, 0, 'C');


    //output the result
    $pdf->Output('../../Admin/uploads/Payment Receipts/' . $filename, 'F');
  }
}
