<?php
session_start();
include('config/config.php');
//login 

$business_id =  $_SESSION['business_id'];

if(!isset($business_id)){
  header("location:initial.php");
}
if (isset($_POST['pincode'])) {

  $staff_pincode = mysqli_real_escape_string($mysqli,$_POST['staff_pincode']); //double encrypt to increase security
  $stmt = $mysqli->prepare("SELECT  staff_pincode, staff_id, staff_number FROM   rpos_staff WHERE ( staff_pincode =? AND business_id = '$business_id')"); //sql to log in user
  $stmt->bind_param('s', $staff_pincode); //bind fetched parameters
  $stmt->execute(); //execute bind 
  $stmt->bind_result( $staff_pincode, $staff_id, $staff_number); //bind result
  $rs = $stmt->fetch();
  $_SESSION['staff_id'] = $staff_id;
  $_SESSION['staff_number'] = $staff_number;
  
  if ($rs) {
    //if its sucessfull
    header("location:invo.php");
  } else {
    $err = "Incorrect Authentication Credentials ";
  }
}
if(isset($_POST['sess']))
{
  session_destroy();
  header('location:initial.php');
}
$qq = mysqli_query($mysqli, "SELECT * FROM rpos_staff  WHERE business_id = '$business_id'");
if(mysqli_num_rows($qq) <= 0){
$errr = '<div class="alert alert-danger">Kindly Add a Cashier</div>';
}
require_once('partials/_head.php');
?>





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
    z-index: ;
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
   <body>
     
      <div class="big-wrapper light">
          <img src="img/shape.png" alt="" class="shape" />
    <nav class="nav navbar px-2" > 
        <div class="logo">
                    <img src="logo.png" alt="Logo" />
                    <h3>1solution</h3>
                  </div>
                      <div>
                        <a class="btn btn-white  form-control" href="../admin/index.php"><i class="bi bi-arrow-left"></i>Login As Admin </a>
                        <form action="" method="post">
<button type="submit" name="sess"  class="btn btn-danger form-control mt-2">End session</button>
</form>
                      </div>   
                    </nav>
                    <h1 class="badge mb-2"> You're logged in under Admin<span class="text-danger" style="text-transform:uppercase;font-size:14px"> <?php 
                 $ql = "SELECT admin_name FROM rpos_admin WHERE business_id = '$business_id'";
                 $rr = mysqli_query($mysqli,$ql);
                 $check = mysqli_num_rows($rr);
                 if($check > 0){
                     if($row = mysqli_fetch_assoc($rr)){
                   echo $row['admin_name'];
                 
                        
                     }
                 }
                 ?>
                 </h1>
        <h1 class="text-center">
          Login As Cashier.
        </h1>
        <div></div>
        <div class="containerx">
  <?php if (isset($errr)){ echo $errr; } ?>
  <div class="card-body">
<form action="" method="post">
<!-- Footer -->


<div class="">
  <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
    </div>
    <input  type="password" inputmode="numeric" name="staff_pincode" maxlength="4" class="form-control" id="pincode" placeholder="Enter 4-digit code" required/>
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
  <?php
  require_once('partials/_scripts.php');
  ?>
  <script>
  let pin =  document.getElementById("pincode");
   document.addEventListener("DOMContentLoaded", () =>{
pin.focus();
   }) 
  </script>
</body>

</html>