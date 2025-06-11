<?php 
ob_start();
session_start();
$title ='One Solution POS- Business Details';

include('config/config.php');
include('config/code-generator.php');
include('config/checklogin.php');

 require_once('partials/_head.php'); 
 

if(isset($_GET['name']) && isset($_GET['exd']) &&  $_GET['business_auth00']){
     $business_id = base64_decode(urldecode($_GET['business_auth00']));
     $name = $_GET['name'];
      $expire = $_GET['exd'];
      $current_time = date("y-m-d H:i:s");
      if($current_time >= $expire){
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
<body class="">
<div class="big-wrapper light">
        <img src="img/shape.png" alt="" class="shape" />
  
<nav class="nav navbar sticky-top" > 
        <div class="logo">
                    <img src="logo.png" alt="Logo" />
                    <h3>1solution</h3>
                  </div>
     
        </nav>

<h1 class="text-center sa"> Admin Account Verification </h1>
  <div class="containerx ">


<div class="modal-body">
    
<h1 class="text-center sa bg-danger text-white p-2"> Sorry, Your account verification link has expired 😔 </h1>
<small><a href='get-started.php'>Register again</a></small>
      </div>
    </div>
<?php 

require_once('partials/_footer.php');
?>
      </div>
</body>
</html>
<?php
      
      }
else
{ 

   
  
  
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
  <div class="d-flex justify-content-center align-items-center" >
<p class="px-2" ><strong class='text-success'>You're almost there, <?php echo $name ?> 🔥</strong><br>
  Now,tell us more about your business!
</p>
</div>

  <div class="containerx ">
  <?php 

 $msg= '';
if (isset($_POST['info-save'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["company"]) || empty($_POST["address"]) || empty($_POST['city']) || empty($_POST['phone'])) {
    $err = "Blank Values Not Accepted";
  } else {
      
        
    $company = $_POST['company'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $phone = $_POST['phone'];
    $status = "1";


    //Insert Captured information to a database table
    $mquery = mysqli_query($mysqli, "SELECT * FROM company_info WHERE status = '1' AND business_id = '$business_id'");
    $check = mysqli_num_rows($mquery);
    $postQuery = "INSERT  company_info SET  company =?, address =?, city =?, phone =?, business_id =?, status =?";
        $upQry = "UPDATE rpos_admin SET status =? WHERE business_id =?";
        
    $postStmt = $mysqli->prepare($postQuery);
        $upStmt = $mysqli->prepare($upQry);
    //bind 
    $rc = $postStmt->bind_param('ssssss', $company, $address, $city, $phone, $business_id, $status);
    $rc = $upStmt->bind_param('ss', $status, $business_id);
    
    $postStmt->execute();
     $upStmt->execute();
    //declare a varible which will be passed to alert function
    if (!$postStmt) {
      $err = "Please Try Again Or Try Later";
      
    }elseif($check > 0){
        $msg="<div class='alert alert-danger'><strong>Details already submitted!</strong></div>";
    }
    else {
      $success = "You are all Set!" && header("refresh:1; url=index.php");
    }
  }
}
    

require_once('partials/_head.php');
?>

<div class="modal-body">
  <form action="" method="post">
<?= $msg; ?>
          <div>
<label for="company">Company Name</label>
<input type="text"  class="form-control mb-2" required placeholder="One Solution Gh" name="company">
</div>
<div>
<label for="address">Company Address</label>
<input type="text" class="form-control mb-2" required  placeholder=" Main Street"  name="address">
<input type="hidden" value = "<?= $business_id ?>"  name="business_id">
</div>



<div>
<label for="city">City</label>
<input type="text" class="form-control mb-2"placeholder="Accra" required   name="city">
</div>

<div>
<label  for="phone">Phone Number</label>
<input class="form-control mb-2" placeholder="+233 xxx-xxxx-xxx" required type="text"name="phone">
</div>
       
      
     
    
     <input type="submit" name="info-save" class="btn-success btn-block btn mt-3"value="Procceed">
        
        </form>
        <div>
        </div>
      </div>
    </div>
<?php 

require_once('partials/_footer.php');
?>
      </div>
</body>
</html>
<?php
}
}