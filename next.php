<?php
ob_start();
session_start();
$title ='One Solution POS- Reset Password';
$business_id = $_GET['id'];

include('config/config.php');

require_once('partials/_head.php');
?>


<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <meta charset="UTF-8">
  <title>Four Digit Code</title>

   <link rel="stylesheet" href="partials/style.css">
<style>
.big-wrapper {
  position: relative;
  padding: 1.7rem 0 2rem;
  width: 100%;
  min-height: 100vh;
  overflow: hidden;
 
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}
.shape {
  position: absolute;
  z-index: 0;
  width: 500px;
  bottom: -180px;
  left: -15px;
  opacity: 0.1;
}

      body{
          background-color:#f1f8fc ;
          margin:0;
  padding: 0;
}
.containerx{
  width: 500px ;
    margin:0 auto;
    padding:30px;
    background-color: white;
    align-items: center;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    border-radius: 5px;
  }
  .form-group{
    margin-bottom:30px;
}
 .sd{

background-color:rgba(10, 0, 100, 0.5) ;
border-radius: 4px;
 }
 @media (max-width:600px){
  .containerx{
  width: 90%;
    margin:10px auto;
    padding:15px;
    background-color: white;
    align-items: center;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    border-radius: 5px;
  } 
}
</style>
</head>
<body class="">
  <h1 class="text-center " ><?php echo $_GET['name'] ?>'s Report</h1>
  
  <div class="row containerx  align-items-center">
<ul class='col-md-4'>
  <h4>Products</h4>
<?php 
$qq = mysqli_query($mysqli,"SELECT * FROM rpos_products WHERE business_id='$business_id'");
$run = mysqli_num_rows($qq);
if($run > 0){
 while($row = mysqli_fetch_assoc($qq)){
?>
<li><?= $row["prod_name"] ?></li>
<?php
 } 
}
?>
</ul>
<ul class='col-md-4'>
  <h4>Workers</h4>
<?php 
$qq = mysqli_query($mysqli,"SELECT * FROM rpos_staff WHERE business_id='$business_id'");
$run = mysqli_num_rows($qq);
if($run > 0){
 while($row = mysqli_fetch_assoc($qq)){
?>
<li><?= $row["staff_name"] ?></li>
<?php
 } 
}
?>
</ul>
<ul class='col-md-4'>
  <h4>Total Sales</h4>
<?php 
$query = "SELECT SUM(pay_amt) FROM `rpos_payments` WHERE  business_id = '$business_id'";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($sales);
$stmt->fetch();
$stmt->close();

?>
<p><?= number_format($sales) ?></p>
<?php

?>
</ul>
</div>
</body>

</html>