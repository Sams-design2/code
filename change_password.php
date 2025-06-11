<?php
session_start();
include('config/config.php');
include('config/code-generator.php');
$title ='One Solution POS- Reset Password';
require_once('partials/_head.php');

$msg ='';
if(isset($_GET['exp'])){
    $expire = $_GET['exp'];
    $current_time = date("y-m-d H:i:s");
    if($current_time >= $expire){
   ?>
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
    padding:30px;
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
  .sa{
      font-size:smaller;
  }
 }
  </style>
   <div class="big-wrapper light">
        <img src="img/shape.png" alt="" class="shape" />
  
<nav class="nav navbar sticky-top" > 
        <div class="logo">
                    <img src="logo.png" alt="Logo" />
                    <h3>1solution</h3>
                  </div>
     
        </nav>

<h1 class="text-center sa">  Reset Admin Password. </h1>
  <div class="containerx ">


<div class="modal-body">
    
<h1 class="text-center sa bg-danger text-white p-2"> Sorry, Your password reset link has expired 😔 </h1>
<small><a href='forgot_password.php'>Reset again</a></small>
      </div>
    </div>
<?php 
require_once('partials/_footer.php');
?>
      </div>
   <?php
    }else{
        
       $admin_email =mysqli_real_escape_string($mysqli,$_GET['203dkmj3jew99WE8EHDEN8u2qwjds8h']);

if (isset($_POST['change'])) {

 $new_password = sha1(mysqli_real_escape_string($mysqli,$_POST['admin_password']));
 $c_password = sha1(mysqli_real_escape_string($mysqli,$_POST['confirm_password']));
 if($new_password !== $c_password){
  $msg = "<div class='alert alert-danger'>Password does not match!</div>";
}elseif($new_password < 7){
  
  $msg = "<div class='alert alert-danger'>Weak Password</div>";
 }else{
  $newQuery = mysqli_query($mysqli , "UPDATE rpos_admin SET admin_password = '$new_password' WHERE admin_email ='$admin_email'");
  if($newQuery){
    $success = "Password Changed" && header("refresh:1; url=index.php");
  
  }
 }

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
.password1,.password,.password2, .password3{
    position: relative;
  }
  .fa-eye{
    position: absolute;
    top: 30%;
    right: 10%;
    opacity: 0.7;
    z-index: 100;
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
        Reset Admin Password.
        </h1>
<div class="containerx">
  <div class="card-body">
    <?php echo $msg; ?>
<form action="" method="post">

<div class="form-group mb-3">
  <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
    </div>
    
    <input class="form-control"  name="admin_password" placeholder="New password" id="password" required type="password">
    <i class="fas fa-eye" id="togglePassword"></i>
  </div>
</div>

<div class="form-group mb-3">
  <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
    </div>
    
    <input class="form-control"  name="confirm_password" placeholder="Confirm Password" id="password1" required type="password">
    <i class="fas fa-eye" id="togglePassword1"></i>
  </div>
</div>


<button type="submit" name="change" class="btn btn-success btn-block my-4">Enter</button>

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
   let pin =  document.getElementById("password");
   document.addEventListener("DOMContentLoaded", () =>{
pin.focus();
   }) 
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
</script>

</html>
<?php
       
    }
}
