<?php 
ob_start();
session_start();
$title = 'One Solution POS- Get Started';
include('config/config.php');
include('config/code-generator.php');
include('config/checklogin.php');
include('partials/_head.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="partials/style.css">
    <style>
      .light {
  --mainColor: #64bcf4;
  --hoverColor: #5bacdf;
  --backgroundColor: #f1f8fc;
  --darkOne: #312f3a;
  --darkTwo: #45424b;
  --lightOne: #919191;
  --lightTwo: #aaa;
}
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
}
.containerx{
  width: 600px;
    margin:0 auto;
    padding:50px;
    background-color: white;
    align-items: center;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
  }
  .form-group{
    margin-bottom:30px;
}
@media (max-width:600px){
  .containerx{
  width: 400px;

    padding:15px;
    justify-content: center;
    background-color: white;
    align-items: center;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    border-radius: 5px;
  }
  .tt{
      font-size:20px;
  }
 }
</style>
<body class="">
  <div class="big-wrapper light">
      <img src="img/shape.png" alt="" class="shape" />
<nav class="nav navbar sticky-top" > 
        <div class="logo">
                    <img src="logo.png" alt="Logo" />
                    <h3>1solution</h3>
                  </div>
      
        </nav>
  <h1 class="text-dark tt text-center" > Register as Administrator for free</h1>

   
  <div class="containerx">
  <?php 
  $msg = '';
if (isset($_POST["submit"])) {
  $admin_id = $_POST["admin_id"];
  $business_id = $_POST["business_id"];
  $admin_name = $_POST["admin_name"];
  $admin_email = ($_POST["admin_email"]);
  $admin_password = sha1(mysqli_real_escape_string($mysqli,$_POST["admin_password"]));
  $admin_pincode = (mysqli_real_escape_string($mysqli,$_POST["admin_pincode"]));
 $passwordRepeat = sha1(mysqli_real_escape_string($mysqli,$_POST["repeat_password"]));
  $pincodeRepeat = (mysqli_real_escape_string($mysqli,$_POST["repeat_pincode"]));
  


  $errors = array();
  
  if (empty($admin_name) OR empty($admin_email) OR empty($admin_password) OR empty($passwordRepeat)OR empty($admin_pincode) OR empty($pincodeRepeat)) {
   array_push($errors,"All fields are required");
  }
  if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
   array_push($errors, "Email is not valid");
  }
  if (strlen($admin_password)<8) {
   array_push($errors,"Password must be at least 8 charactes long");
  }
  if ($admin_password!==$passwordRepeat) {
   array_push($errors,"Password does not match");
  }
  if ($admin_pincode!==$pincodeRepeat) {
    array_push($errors,"Pincode does not match");
  }
  
  $sql = "SELECT * FROM rpos_admin WHERE admin_email = '$admin_email' AND status ='1'";
  $result = mysqli_query($mysqli, $sql);
  $rowCount = mysqli_num_rows($result);
  if ($rowCount>0) {
   array_push($errors,"Email already registered!");
  }
  if (count($errors)>0) {
    foreach ($errors as  $error) {
       echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
       '.$error.'
      
     </div>';
   }}

else{

   //Insert Captured information to a database table
   $postQuery = "INSERT INTO rpos_admin (admin_id,admin_name,admin_email, admin_password, admin_pincode,business_id) VALUES(?,?,?,?,?,?)";
   $postStmt = $mysqli->prepare($postQuery);
   //bind paramaters
   $rc = $postStmt->bind_param('ssssss',$admin_id, $admin_name, $admin_email, $admin_password, $admin_pincode,$business_id);
   $postStmt->execute();
  
   if ($postStmt) {

     

require 'vendor/autoload.php';


$mail = new PHPMailer(true);

try {
                     
    $mail->isSMTP();        
    $mail->SMTPOptions = array(
      'ssl' => array(
      'verify_peer' => false,
      'verify_peer_name' => false,
      'allow_self_signed' => true
      )
      );
    $mail->SMTPSecure = "ssl";                                   
    $mail->Host       = "smtp.titan.email";                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = "no-reply@onesolutionpos.com";
    $mail->Password   = "Enter2net$";                           
    $mail->Port       = 465;


    //Recipients
    $mail->setFrom('no-reply@onesolutionpos.com', 'One solution POS');
    $mail->addAddress($admin_email, $admin_name);     
            
    $mail->addReplyTo('no-reply@onesolutionpos.com', 'no-reply');

$wel = str_shuffle("1234567890!@#$%^&*()_+qwertyuiopasdfghjklzxcvbnm<>QWERTYUIOPASDFGHJKLZXCVBNM") . $customerID . $beta .rand(99999,11111);

$expiry_time  = date("y-m-d H:i:s", time() + 60 * 1440);


$business_id= base64_encode(urlencode($business_id));
 

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Onesolution POS-Comfirm Account Registration.';
    $mail->Body    = "Hello $admin_name,<br><br>Please Click on the button below to verify your account.<br>Make sure to complete your account registration by fill out your business Details. <br> <br> <button style='background-color:rgba(20,20,250,0.5); font-size:14px; max-width:60%; padding:10px; color:white !important;border-radius:8px'> <a target = '_blank' style='color:white;text-decoration:none;' href='https://www.onesolutionpos.com/pos/admin/business.php?business_auth00=$business_id&hexCwjsjsoklaj2982ehj2snszQoqj8OI829311!Code&name=$admin_name&exd=$expiry_time'> Click here to verify </a> </button><br><br> Please note that this link expires after 24 hours <br>Thank you for choosing One Solution POS. <br><br> With Warm Regards,<br> The One Solution Team. 

    ";
    

    $mail->send();
  $msg = '<div class="alert alert-primary">An email has been sent to your email address</div>';
} catch (Exception $e) {
  $msg = '<div class="alert alert-danger">Something Went Wrong</div>';
}
    //  $success = "Registered Successfully!" && header("refresh:1; url=business.php?business_auth00=$business_id");
   } else {
     $err = "Please Try Again Or Try Later";
    }
 }
}


?>
<style>
  .password1,.password,.password2, .password3{
    position: relative;
  }
  .fa-eye{
    position: absolute;
    top: 30%;
    right: 10%;
    opacity: 0.7;
  }
</style>

  <form action="" method="post">
    <?= $msg ?>
            <div class="form-group">
              <input type="text" class="form-control" name="admin_name" autofocus required value="<?php if(isset($_POST['admin_name'])){echo $_POST['admin_name'];}?>"
              placeholder="Admin Name:">
              <input type="hidden" value="<?=$customerID ?>" name="admin_id">
              <input type="hidden" value="<?=$beta ?>" name="business_id">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" required name="admin_email" value="<?php if(isset($_POST['admin_email'])){echo $_POST['admin_email'];}?>" placeholder="Email:">
            </div>
           <div class="row">
            <div class="form-group col-md-6">
                <input type="password" class="form-control"  id="password3" name="admin_password" required placeholder="Password:">
              <i class="fas fa-eye" id="togglePassword3"></i>
                
                
            </div>
            <div class="form-group col-md-6">
                <input type="password" class="form-control"  id="password1" name="repeat_password" required placeholder="Confirm Password:">
              <i class="fas fa-eye" id="togglePassword1"></i>
                
            </div>
            </div>
           <div class="row">

            <div class="form-group col-md-6">
                <input type="password"  class="form-control" id='password2' maxlength="4" required name="admin_pincode" placeholder="pincode:">
              <i class="fas fa-eye" id="togglePassword2"></i>
                
            </div>
            <div class="form-group col-md-6">
                <input type="password" class="form-control" id="password"  maxlength="4" required name="repeat_pincode" placeholder="Confirm Pincode:">
              <i class="fas fa-eye" id="togglePassword"></i>
              
            </div>
           </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-success btn-block" value="Register" name="submit">
            </div>
        </form>
        <div>
        <div class="py-4" ><p>Already Registered ? <a style="text-decoration: underline; color: blue; "href="index.php">Login Here</a></p></div>
      </div>
    </div>
<?php 

require_once('partials/_footer.php');
?>
    </div>

</body>
<script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("fa-eye-slash");
        });
        const togglePassword1 = document.querySelector("#togglePassword1");
        const password1 = document.querySelector("#password1");

        togglePassword1.addEventListener("click", function () {
            // toggle the type attribute
            const type = password1.getAttribute("type") === "password" ? "text" : "password";
            password1.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("fa-eye-slash");
        });
        const togglePassword2 = document.querySelector("#togglePassword2");
        const password2 = document.querySelector("#password2");

        togglePassword2.addEventListener("click", function () {
            // toggle the type attribute
            const type = password2.getAttribute("type") === "password" ? "text" : "password";
            password2.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("fa-eye-slash");
        });
        const togglePassword3 = document.querySelector("#togglePassword3");
        const password3 = document.querySelector("#password3");

        togglePassword3.addEventListener("click", function () {
            // toggle the type attribute
            const type = password3.getAttribute("type") === "password" ? "text" : "password";
            password3.setAttribute("type", type);
            
            // toggle the icon
            this.classList.toggle("fa-eye-slash");
        });

        // prevent form submit
  
    </script>
</html>