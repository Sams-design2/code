<?php
// Database connection
// $servername = "your_database_host";
// $username = "your_database_username";
// $password = "your_database_password";
// $dbname = "your_database_name";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="export.csv"');
include('config/config.php');


// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_POST['employeeID'])) {
    // Retrieve form data
    $employee_id = $_POST["employee_id"];
    $business_id = $_POST["business_id"];
    //return $employee_id;
    }





// $sql = "SELECT * FROM invoice_products WHERE staff_number = '$employee_id'";
if(empty($employee_id)){
    $sql =  "SELECT * FROM invoice_products
    INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
    WHERE invoice_products.business_id = '$business_id'";
      
}
else{
    $sql =  "SELECT * FROM invoice_products
    INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
    WHERE invoice_products.business_id = '$business_id' AND rpos_payments.sales_person ='$employee_id'";
      
}

       


$result = $con->query($sql);

// Output CSV file
$output = fopen('php://output', 'w');

// Add headers
fputcsv($output, array('Time of Order', 'Sales Person','Product Name','Price', 'Pay Code','Pay Method','Total'));

// Add data rows
if ($result->num_rows > 0) {
    // Fetch and output each row
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, array(date('d/M/Y g:i', strtotime($row['created_at'])),$row['sales_person'], $row['PNAME'], $row['PRICE'],$row['pay_code'], $row['pay_method'], $row['TOTAL']));
    }
}

fclose($output);



exit();