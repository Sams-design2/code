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
    //return $employee_id;
    }





$sql = "SELECT * FROM invoice_products WHERE staff_number = '$employee_id'";
$result = $con->query($sql);

// Output CSV file
$output = fopen('php://output', 'w');

// Add headers
fputcsv($output, array('Time of Order', 'Staff ID', 'Bussiness ID', 'Product Name', 'Price', 'Quantity', 'Order Status', 'Total'));

// Check if there are rows in the result set
if ($result->num_rows > 0) {
    // Fetch and output each row
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, array( $row['created_at'], $row['staff_number'], $row['business_id'], $row['PNAME'], $row['PRICE'], $row['QTY'], $row['order_status'], $row['TOTAL']));
    }
}

// Close the output file
fclose($output);



exit();