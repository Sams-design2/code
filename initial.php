<?php
session_start();
include('config/config.php');
$title ='One Solution POS- Cashier Initial Login';
//login 
// Check if the user is already logged in
if (isset($_SESSION['business_id'])) {
  // User is already logged in, redirect to the desired page
  header("Location: index.php");
  exit();
}
if (isset($_POST['login'])) {
  $admin_email = $_POST['admin_email'];
  $admin_password = sha1(mysqli_real_escape_string($mysqli,$_POST['admin_password'])); 
  $stmt = $mysqli->prepare("SELECT  admin_email,admin_name, admin_password, business_id, status FROM   rpos_admin WHERE (admin_email =? AND admin_password =?)"); //sql to log in user
  $stmt->bind_param('ss',  $admin_email, $admin_password); //bind fetched parameters
  $stmt->execute(); //execute bind 
  $stmt->bind_result($admin_email, $admin_password,$admin_name, $business_id,$status); //bind result
  $rs = $stmt->fetch();
  $_SESSION['business_id'] = $business_id;
  $stat = $status;
  $name = $admin_name;
$business_id = $_SESSION['business_id'];
  
    
  if(!$rs){
    
      $err = "Incorrect Authentication Credentials ";
  }
  elseif($stat === 0){
        $err = 'This email address hasn\'t been verified';
  }
   
       else{
        header("location:index.php");
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
    z-index: 100;
}

  #password{
    position: relative;
  }
  #togglePassword{
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
      <div>
        <a class="btn btn-white" href="../admin/index.php">Retrun to Admin<i class="bi bi-arrow-right"></i> </a>
      </div>
        </nav>
        
        <h1 class="text-center">
         Welcome Cashier.
        </h1>
       
<div class="containerx">
  <div class="card-body">
<form action="" method="post">
<div class="mb-2" style="style='width:100px; display:flex; flex-direction:column;align-items:center justify-content:center'">
        <small class="text-center" > You're required to login with your <span class="text-danger" > Admin's credential </span>because it's your first time logging In onto this device.</small>
        </div>
<!-- Footer -->
<div class="form-group mb-3">
  <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
    </div>
    
    <input class="form-control" value="<?php if(isset($_POST['login'])){echo $_POST['admin_email'];} ?>" required name="admin_email" placeholder="Email" id="a_email" type="email">
  </div>
</div>

<div class="form-group">
  <div class="input-group input-group-alternative">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
    </div>
    <input class="form-control" id="password" required name="admin_password" placeholder="Password" type="password">
<i id="togglePassword" class="fas fa-eye"></i>
  </div>
</div>


<button type="submit" name="login"  class="btn btn-success btn-block my-4">Log In</button>
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
 
   
   </script>
</html>