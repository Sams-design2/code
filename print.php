<?php 
ob_start();
session_start();
require ("fpdf/fpdf.php");
require ("word.php");
include('config/config.php');
include('config/checklogin.php');
$title = "Print Reciept";
check_login();


//customer and invoice details
$info=[
  "customer"=>"",
  "invoice_no"=>"",
  "invoice_date"=>"",
  "total_amt"=>"",
  "words"=>"",
];

//Select Invoice Details From Database
$sql="select * from invoice where order_code ='{$_GET["SID"]}'";
// var_dump($sql);
$res = $con->query($sql);
if($res->num_rows > 0) {

  $row = $res->fetch_assoc(); 
  
  // Get the current tax information
  $tax_array = []; // An associative array with the tax name and its respective percentageg
  $total_tax = 0;

  $get_tax_percentages = "SELECT tax_name, tax_percentage FROM tax";
  $query = $con->query($get_tax_percentages);
  while ($tax = mysqli_fetch_assoc($query)) {
    $tax_name = $tax["tax_name"];
    $tax_percentage = $tax["tax_percentage"];

    // Add to the tax_array associative array
    $tax_array[$tax_name] = $tax_percentage;

    // Increment the total_tax by the tax_percentage
    $total_tax += $tax["tax_percentage"];
  }

  $total_tax = number_format($total_tax, 2);

  $grand_total = $row['GRAND_TOTAL'] + (($total_tax / 100) * $row['GRAND_TOTAL']);

	  $info=[
		"customer"=>$row["cname"],
		"invoice_no"=>$row["order_code"],
		"invoice_date"=>date("d-m-y g:i",strtotime($row["created_at"])),
		"total_amt"=>number_format($row["GRAND_TOTAL"],2),
		"grandd" => number_format($grand_total,2)
	  ];
  }

  // var_dump($info);
  
  //invoice Products
  $products_info=[];
  
  //Select Invoice Product Details From Database
  $sql="SELECT * FROM invoice_products where SID ='{$_GET["SID"]}'";
  // var_dump($sql);
  $res=$con->query($sql);
  if($res->num_rows>0){
	  while($row=$res->fetch_assoc()){
		   $products_info[]=[
			"name"=>$row["PNAME"],
			"price"=>$row["PRICE"],
			"qty"=>$row["QTY"],
			"total"=>number_format($row["TOTAL"]),
		   ];
	  }
  }
  // SALES ATTENDANT
  $business_id = $_SESSION['business_id'];
    // $login_id = $_SESSION['login_id'];
    $ret = "SELECT * FROM  rpos_admin  WHERE business_id = '$business_id'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
while ($admin = $res->fetch_object()) {
  $infoz=[
    "sales_person" =>$admin->admin_name,
	  ];
}
    // PAYMENT METHOD
    $ql = "SELECT pay_method  FROM rpos_payments WHERE SID = '{$_GET["SID"]}'";
    // var_dump($ql);
    $rr = mysqli_query($mysqli,$ql);
    $check = mysqli_num_rows($rr);
    // var_dump($check>0);
    if($check > 0){
  while ($row = mysqli_fetch_assoc($rr)) {
    global  $infow;
    $infow=[
      "pay_method" =>$row['pay_method'],
      ];
  }
}
    // COMPANY INFO
  $ql = "SELECT *  FROM company_info  WHERE business_id = '$business_id'";
    // var_dump($ql);
  $rr = mysqli_query($mysqli,$ql);
  
  $check = mysqli_num_rows($rr);
  // var_dump($check>0);
  if($check > 0) {
        while ($row = mysqli_fetch_assoc($rr)) {
        global $infoy;
        
          $infoy=[
            "company" =>$row['company'],
            "address" =>$row['address'],
            "city" =>$row['city'],
            "phone" =>$row['phone'],
            ];
        }

    } else {
      $infoy = [
        "company" => "1Solutions",
        "address" => "Accra",
        "city" => "Accra",
        "phone" => "N/A"
      ];
    }
  // else{
  //   return 'bussiness id not found';
  // }
// var_dump($infoy);
  
  class PDF extends FPDF
  {
    
    function headz($infoy) {
      //  var_dump($infoy);
      //Display Company Info
      // global $infoy;
      $this->SetFont('Arial','B',7);
      $this->Cell(62,8,$infoy["company"],1,1 ,'C');
      $this->SetFont('Arial','',8);
      $this->Cell(60,5,$infoy["address"],0,1,'C');
      $this->Cell(60,5,$infoy["city"],0,1, 'C');
      $this->Cell(60,5,$infoy["phone"],0,1, 'C');
      
    //   //Display INVOICE text
    //   $this->SetY(15);
    //   $this->SetX(-40);
    //   $this->SetFont('Arial','B',18);
    //   $this->Cell(50,10,"RECEIPT",0,1);
      
      //Display Horizontal line
      $this->Line(0,35,210,35);
      $this->ln(2);
    }
    
    function body($info,$products_info, $tax_array){
      
      //Billing Details
    
      $this->SetFont('Arial','B',8);
      $this->Cell(50,7,"BILL TO: " .$info["customer"] ,0,1, 'L');

      //Display Invoice no
    
      $this->Cell(50,7,"RECIEPT ID : #".$info["invoice_no"], 0,1, 'L');
      
      //Display Invoice date
      $this->Cell(50,7,"PURCHASE DATE : ".$info["invoice_date"],0,1, 'L');

  //Display Horizontal line
      $this->Line(0,76,210,76);
      $this->ln(2);
      
      //Display Table headings
      $this->SetY(80);
      
      $this->SetX(2);
      $this->SetFont('Arial','B',7);
      $this->Cell(57,9,"ITEM(S)",1,0);
      $this->Cell(15,9,"PRICE",1,0,"C");
      $this->Cell(10,9,"QTY",1,0,"C");
      $this->Cell(15,9,"TOTAL",1,1,"C");
      $this->SetFont('Arial','B',7);


      // // Define a width and height for the MultiCell
      // $width = 35;
      // $height = 9;

      // // Use MultiCell for the "ITEM(S)" cell   
      // $this->MultiCell($width, $height, "ITEM(S)", 1);

      // // Move to the next cell
      // $this->SetX($this->GetX() + $width);

      
      //Display table product rows
      foreach($products_info as $row){
           $this->SetX(2);
         
        $this->Cell(57,9,$row["name"],"LR",0);
        // $this->Cell(35, 9, $row["name"], 1, 1);

        $this->Cell(15,9,$row["price"],"R",0,"C");
        $this->Cell(10,9,$row["qty"],"R",0,"C");
        $this->Cell(15,9,$row["total"],"R",1,"C");
       
        
        $this->Ln(8); // Add space of 8 units (adjust the number as needed)
      }
      //Display table total row
      // After drawing the table contents, add a line to close the table
      

      $this->SetX(2);
      $this->SetFont('Arial','B',7);
      $this->Cell(50,5,"SUB TOTAL",1,0,"R");
      $this->Cell(25,5, 'GHC '.$info["total_amt"],1,1,"R");
      
      //Display table empty rows
      //Display table total rows
      foreach($tax_array as $tax_name => $tax_percentage) {
        $this->SetX(2);
        $this->SetFont('Arial','B',7);
        $this->Cell(50,5,$tax_name,1,0,"R");
        $this->Cell(25,5, $tax_percentage.'%',1,1,"R"); 
      }
      
      
      //Display table total row
         $this->SetX(2);
      $this->SetFont('Arial','B',7);
      $this->Cell(50,8,"GRAND TOTAL",1,0,"R");
      $this->Cell(25,8, 'GHC '.$info["grandd"],1,1,"R");
      
      //Display amount in words
      // $this->SetY(225);
      // $this->SetX(10);
      // $this->SetFont('Arial','B',12);
      // $this->Cell(0,9,"Amount in Words ",0,1);
      // $this->SetFont('Arial','',12);
      // $this->Cell(0,9,$info["words"],0,1);
      
    }
  function bodyz( $infoz)
  {
      //Display Sales person
      $this->SetFont('Arial','B',8);

      $this->SetY(62);
      $this->SetX(-78);
      $this->Cell(50,7,"SALES PERSON : ".$infoz["sales_person"],0,1,"C");
  }
  function bodyw($infow)
  {
      //Display Payment method
      $this->SetY(55);
    $this->SetX(-77);

    $this->SetFont('Arial','B',8);
    $this->Cell(50,7,"PAYMENT METHOD: " .$infow["pay_method"],0,1,"C");

  }

    function Footer(){
      
      //set footer position
      // $this->SetY(-50);
      // $this->SetFont('Arial','B',12);
      // $this->Cell(0,10,"for ABC COMPUTERS",0,1,"R");
      // $this->Ln(15);
      // $this->SetFont('Arial','',12);
      // $this->Cell(0,10,"Authorized Signature",0,1,"R");
      // $this->SetFont('Arial','',10);
      
      //Display Footer Text
      // $this->Cell(0,10,"This is a computer generated invoice",0,1,"C");
      
    }
    
  }
  //Create A4 Page with Portrait 
  $pdf=new PDF("P","mm",array(100,200));
  $pdf->AddPage();
  // $pdf->Line(10, $pdf->GetY(), $totalTableWidth = 180 + 10, $pdf->GetY());
  global $infoy;
  $pdf->Headz($infoy);
  $pdf->body($info,$products_info, $tax_array);
$pdf->bodyz($infoz);
$pdf->bodyw($infow);
  $pdf->Output();
?>