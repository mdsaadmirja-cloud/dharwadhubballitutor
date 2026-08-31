<?php 
require "Admin/fpdf182/fpdf.php";

$pdf = new FPDF('P','mm','A4');


         //add new page
         $pdf->AddPage();
         $pdf->SetFont('Arial','B',18);

        //Cell(width , height , text , border , end line , [align] )
       
        
        $pdf->Image('Admin/media/logo.png',5,16,-300);
        $pdf->Cell(189 ,10,'',0,1);
     
        $pdf->Cell(12 ,5,"",0,0);
        $pdf->Cell(12,5,"",0,0);
       
        $pdf->Cell(125 ,7,'DhawradHubballiTutor',0,0);
        $pdf->Cell(59 ,7,'Invoice',0,1);//end of line

        //set font to arial, regular, 12pt
        $pdf->SetFont('Arial','',12);
        $pdf->Cell(12 ,5,"",0,0);
        $pdf->Cell(12 ,5,"",0,0);
        $pdf->Cell(130 ,7,'JP Nippani Complex,Gandhinagar',0,0);
        $pdf->Cell(59 ,7,'',0,1);//end of line

        $pdf->Cell(12,5,"",0,0);
        $pdf->Cell(12 ,5,"",0,0);
        $pdf->Cell(115 ,7,'Dharwad,580030',0,0);
        $pdf->Cell(25 ,7,'Date',0,0);
        $pdf->Cell(34 ,7,"".date("Y/m/d")."",0,1);//end of line
        $pdf->Cell(12 ,5,"",0,0);
        $pdf->Cell(12 ,5,"",0,0);

        $pdf->Cell(130 ,7,'Phone: +919741237334 ,+918007961759',0,0);
        $pdf->Cell(189 ,10,'',0,1);//end of line

    //make a dummy empty cell as a vertical spacer
        $pdf->Cell(189 ,10,'',0,1);
    //billing address
        $pdf->Cell(100 ,5,'Bill to',0,1);//end of line

    //add dummy cell at beginning of each line for indentation
       $pdf->Cell(10 ,5,"",0,0);
       $pdf->Cell(90 ,5,'',0,1);
       $pdf->Cell(10 ,5,'',0,0);
       $pdf->Cell(90 ,5,"",0,1);

       $pdf->Cell(10 ,5,'',0,0);
       $pdf->Cell(90 ,5,"",0,1);

    //make a dummy empty cell as a vertical spacer
      $pdf->Cell(189 ,10,'',0,1);//end of line

      $pdf->SetFont('Arial','B',12);

      $pdf->Cell(105 ,7,'Description',1,0);
      $pdf->Cell(24 ,7,'Total Fees',1,0);
      $pdf->Cell(30 ,7,'Balance',1,0);
      $pdf->Cell(30 ,7,'Paid Amount',1,1);//end of line

      $pdf->SetFont('Arial','',12);

      $pdf->Cell(105 ,7,'Fees paid towards Web Designing and Development  ',1,0);
      $pdf->Cell(24 ,7,'',1,0);
      $pdf->Cell(30 ,7,'',1,0);
      $pdf->Cell(30 ,7,'',1,1,'R');//end of line
      
      $pdf->Cell(129 ,7,'',1,0);
      // $pdf->Cell(24 ,7,'',1,0);
      $pdf->Cell(30 ,7,'Subtotal',1,0);
  
      $pdf->Cell(30 ,7,'',1,1,'R');

     

      $filename='filename1.pdf';

         //output the result
        $pdf->Output('C:/wamp64/www/dharwadhubballitutor/Admin/uploads/Fee Receipts/'.$filename,'F');

        ?>