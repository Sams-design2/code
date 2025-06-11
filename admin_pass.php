<?php
ob_start();
session_start();
$title ='One Solution POS- Reset Password';

include('config/config.php');
include('config/checklogin.php');
check_login();
$business_id = $_SESSION['business_id'];


if (isset($_POST['pincode'])) {
  $admin_pin = (($_POST['admin_pincode']));

//search into db
$sql = "SELECT * FROM rpos_admin WHERE admin_pincode = '$admin_pin' AND business_id = '$business_id' ";
//condition and implementation
$result = mysqli_query($mysqli, $sql);
if($result){

        $num = mysqli_num_rows($result);
        if($num > 0){
   header("location:dashboard.php");
        }

 else {
$err = "password incorrect";
}
}

}
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
  width: 500px;
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
  width: 400px;
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
     
      <div class="big-wrapper light">
          <img src="img/shape.png" alt="" class="shape" />
   <nav class="nav navbar px-2 align-items-center" > 
        <div class="logo">
                    <img src="logo.png" alt="Logo" />
                    <h3>1solution</h3>
                  </div>
    <div class="sd">
                  <h1 class="badge text-white"> Welcome Back,<span style="text-transform:uppercase ;"> <?php 
                 $ql = "SELECT admin_name FROM rpos_admin WHERE business_id = '$business_id'";
                 $rr = mysqli_query($mysqli,$ql);
                 $check = mysqli_num_rows($rr);
                 if($check > 0){
                     if($row = mysqli_fetch_assoc($rr)){
                   echo $row['admin_name'];
                 
                        
                     }
                 }
                 ?>
                 </span>
                 </h1>
                 </div>
        </nav>
  
        <h1 class="text-center">
        Enter 4 digit Code
        </h1>
<div class="containerx">
  <div class="card-body">
<form action="" method="post">
<!-- Footer -->


<div class="form-group">
  <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
    </div>
    <input inputmode="numeric" type="password" name="admin_pincode" maxlength="4" class="form-control" id="pincode" placeholder="Enter 4-digit code" required/>
  </div>
</div>
<button type="submit" name="pincode"  class="btn btn-success btn-block my-4">Enter</button>

</form>
</div>
</div>
<?php
  require_once('partials/_footer.php');
  ?>
</div>



<script  src="script.js"></script>
<script>
  let pin =  document.getElementById("pincode");
   document.addEventListener("DOMContentLoaded", () =>{
pin.focus();
   }) 
  </script>

</body>

</html>