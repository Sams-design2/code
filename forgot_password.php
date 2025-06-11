<?php
session_start();
include('config/config.php');
include('config/code-generator.php');
$title ='One Solution POS- Forgot Password';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$msg ='';
// if the Enter button is clicked
if(isset($_POST['change'])){
  // for extra security we call out the email using this mysqli function
$admin_email = mysqli_real_escape_string($mysqli, $_POST['admin_email']); 
// write our query to check if the email exist in our database
$changeQuery = mysqli_query($mysqli ,"SELECT * FROM rpos_admin WHERE admin_email = '$admin_email'");

while($row = mysqli_fetch_assoc($changeQuery)){

  $admin_name = $row['admin_name'];
  $admin_pincode = $row['admin_pincode'];
}
//now lets check 
$check = mysqli_num_rows($changeQuery);
// so if the email exists in the database
if($check > 0){
// send the change password link to the user using smtp

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
$new = $alpha . $mpesaCode;
$auth =  $customerID . $beta . $checksum ;
 $expiry_time  = date("y-m-d H:i:s", time() + 60 * 5);

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Onesolution POS- Change Admin Password.';
    $mail->Body = "
    Hello $admin_name,<br><br>There was recently a request to change the password on your account.<br> If you requested this password change, please click on the button below to set a new password.<br><br>  <button style='background-color:rgba(20,20,250,0.5); font-size:14px; max-width:60%; padding:10px; color:white !important;border-radius:8px'> <a target = '_blank' style='color:white;text-decoration:none;' href='https://www.onesolutionpos.com/pos/admin/change_password.php?changeauth00=$auth&203dkmj3jew99WE8EHDEN8u2qwjds8h=$admin_email&code=$new&exp=$expiry_time'> Click here to Change password </a> </button> <br><br> Kindly ignore this message if you didn't want to change your password.<br> Incase you forgot your Admin pincode too, here is it <strong>$admin_pincode</strong> <br><br> Thank you for choosing One Solution POS. <br><br> With Warm Regards,<br> The One Solution Team.";
    

    $mail->send();
    if($mail->send()){
    $msg = '<div class="alert alert-primary">An email has been sent to your email address</div>';
    
  }else {
    # code...
    $msg = '<div class="alert alert-danger">Something Went Wrong</div>';
  }
} catch (Exception $e) {
    echo "<div class='alert-danger alert' >
    Something Went wrong.
    </div>";
}
    //  $success = "Registered Successfully!" && header("refresh:1; url=business.php?business_auth00=$business_id");
   } else {
     $err = "Your account hasn't been registered!";
    }

}
else{

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
 
 @media (max-width:600px){
  .containerx{
  width: 400px;
    margin:0 auto;
    padding:15px;
    background-color: white;
    align-items: center;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    border-radius: 5px;
  } 
 }
</style>
<body>
<div class="big-wrapper light">
        <img src="img/shape.png" alt="" class="shape" />
        <nav class="nav navbar " > 
        <div class="logo">
                    <img src="logo.png" alt="Logo" />
                    <h3>1solution</h3>
                  </div>
      
        </nav>
        <h1 class="text-center">
        Change Admin Password.
        </h1>
<div class="containerx">
  <div class="card-body">
    <?php echo $msg; ?>
<form action="" method="post">

<div class="form-group mb-3">
  <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
    </div>
    
    <input class="form-control" autofocus value="<?php if(isset($_POST['login'])){echo $_POST['admin_email'];} ?>"  name="admin_email" placeholder="Enter your email" id="a_email" required type="email">
  </div>
</div>


<button type="submit" name="change" class="btn btn-success btn-block my-4">Enter</button>
<small><i class="bi bi-arrow-left" ></i><a href="index.php">&nbsp; Go back</a></small>
</form>
</div>
</div>
<?php
  require_once('partials/_footer.php');
  ?>
</div>










  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>
<script>
   let pin =  document.getElementById("a_email");
   document.addEventListener("DOMContentLoaded", () =>{
pin.focus();
   }) 
  
</script>

</html>