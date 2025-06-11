<?php
session_start();
ob_start();
$title ='One Solution POS-Admin Dashboard';

include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
$business_id = $_SESSION['business_id'];
//Add Staff
if (isset($_POST['addStaff'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["staff_number"]) || empty($_POST["staff_name"]) || empty($_POST['staff_email']) || empty($_POST['staff_pincode'])) {
    $err = "Blank Values Not Accepted";
  
  } else {
    
    $staff_number = $_POST['staff_number'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $staff_pincode = ($_POST['staff_pincode']);
    $newq = "SELECT staff_pincode FROM rpos_staff where staff_pincode ='$staff_pincode' AND business_id = '$business_id'";
    $res = mysqli_query($mysqli, $newq);
    if(mysqli_num_rows($res) >0 ){
      $err = "Opps! This Pincode Is owned by another colleague,Kindly try again";
    }
else{
    //Insert Captured information to a database table
    $postQuery = "INSERT INTO rpos_staff (staff_number, staff_name, staff_email, staff_pincode,business_id) VALUES(?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('sssss', $staff_number, $staff_name, $staff_email, $staff_pincode,$business_id);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Staff Added" && header("refresh:1; url=hrm.php");
    } else {
      $err = "Please Try Again Or Try Later";
    }
  }
}


}

require_once('partials/_head.php');
?>

<body>
  <!-- Sidenav -->
  <?php
  require_once('partials/_sidebar.php');
  ?>
  <!-- Main content -->
  <div class="main-content">
    <!-- Top navbar -->
    <?php
    require_once('partials/_topnav.php');
    ?>
    <!-- Header -->
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;" class="header  pb-8 pt-5 pt-md-8">
    <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--8">
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Please Fill All Fields</h3>
            </div>
            <div class="card-body">
              <form method="POST">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Staff Number</label>
                    <input type="text" name="staff_number" class="form-control" value="<?php echo $alpha; ?>-<?php echo $beta; ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Staff Name</label>
                    <input type="text" name="staff_name" class="form-control" value="<?php if(isset($_POST['addStaff'])){echo $_POST['staff_name'];} ?>">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Staff Email</label>
                    <input type="email" name="staff_email" class="form-control" value="<?php if(isset($_POST['addStaff'])){echo $_POST['staff_email'];} ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Staff Pincode</label>
                    <input type="password" maxlength="4" name="staff_pincode" class="form-control" value="">
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addStaff" value="Add Staff" class="btn btn-success" value="">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer -->
      <?php
      require_once('partials/_footer.php');
      ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>