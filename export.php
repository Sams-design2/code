<?php
// Database connection
// $servername = "your_database_host";
// $username = "your_database_username";
// $password = "your_database_password";
// $dbname = "your_database_name";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="export.csv"');
    $username="root";
    $password="";
    $servername="localhost";
    $dbname="u890055708_rposystem";
    // $mysqli=new mysqli($host,$dbuser, $dbpass, $db);
	$conn=mysqli_connect("localhost","root","","u890055708_rposystem");

// $conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['allExport'])) {
    // Retrieve form data
    $business_id = $_POST["business_id"];
    }
// Fetch data from the database
//$sql = "SELECT * FROM invoice_products ";
$sql = "SELECT * FROM invoice_products
        INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
        WHERE invoice_products.business_id = '$business_id'";
$result = $conn->query($sql);

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

// Set headers for download


// Close the database connection
$conn->close();
exit;
?>
