<?php
ob_start();
session_start();
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');
include('../admin/models/Product.php');

check_login();
$staff_number = $_SESSION['staff_number'];

if (isset($_POST['pay'])) {
  //Prevent Posting Blank Values

  if (empty($_POST["pay_code"]) || empty($_POST["pay_amt"]) || empty($_POST['pay_method'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $business_id = $_SESSION['business_id'];
    $pay_code = $_POST['pay_code'];
    $SID = $_GET['SID'];
    $pay_amt  = $_POST['pay_amt'];
    $pay_method = $_POST['pay_method'];
    $pay_id = $_POST['pay_id'];
    $sales_person = $_POST['sales_person'];
    $order_status = $_GET['order_status'];
    $products = json_decode($_GET['products'], true);

    // Make sure the products parameter gotten from the GET request is valid
    if (!is_array($products)) {
      $err = "Please Try Again Or Try Later";
    } else {
      // Make sure the products exist
      $any_error = false;
      foreach ($products as $product_id => $ordered_quantity) {
        $product = new Product($con, $business_id, $product_id);
        if (!$product->checkProductExists()) {
          $any_error = true;
        }
      }

      if ($any_error === true) {
        $err = "Please Try Again Or Try Later";
      } else {
        //Insert Captured information to a database table
        $postQuery = "INSERT INTO rpos_payments (pay_id, pay_code, SID, pay_amt, pay_method,staff_number,business_id,sales_person) VALUES(?,?,?,?,?,?,?,?)";
        $upQry = "UPDATE invoice_products SET order_status =? WHERE business_id =? AND SID =?";

        $postStmt = $mysqli->prepare($postQuery);
        $upStmt = $mysqli->prepare($upQry);
        //bind paramaters

        $rc = $postStmt->bind_param('ssssssss', $pay_id, $pay_code, $SID, $pay_amt, $pay_method,$staff_number,$business_id,$sales_person);
        $rc = $upStmt->bind_param('sss', $order_status, $business_id, $SID);
        $postStmt->execute();
        $upStmt->execute();
        //declare a varible which will be passed to alert function
        if ($upStmt && $postStmt) {
          // Subtract each ordered quantity from the corresponding product's stock
          foreach ($products as $product_id => $ordered_quantity) {
            $product = new Product($con, $business_id, $product_id);
            $product->reduceStock($ordered_quantity);
          }

          echo '
          <h2 class="alert alert-success text-center">
          <i class="fas fa-check-circle text-success" style="font-size:20px"></i> ORDER PAID SUCCESSFULLY! Do you want to print the receipt? 
          <br><br>  
          <a target="_blank" class="badge badge-success px-3 py-2 print-receipt-link" data-sid="'.$SID.'" href ="invo.php">Yes, Print</a> OR <a class="badge badge-warning px-3 py-2" href ="invo.php">No, I\'m done</a>
          </h2>';
        } else {
          $err = "Please Try Again Or Try Later";
        }
      }
    }
  }
}
if(isset($_POST['draft'])){
  $success = "Order Drafted" && header("refresh:1; url=payments.php");
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
    $total = $_GET['pay'];
    $SID = $_GET['SID'];
    $ret = "SELECT * FROM  invoice_products WHERE SID ='$SID' GROUP BY SID";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($order = $res->fetch_object()) {
       

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
            <?php
              $staff_id = $_SESSION['staff_id'];
    // $login_id = $_SESSION['login_id'];
    $ret = "SELECT * FROM  rpos_staff  WHERE staff_id = '$staff_id'";
    $stmt = $mysqli->prepare($ret);
    $stmt->execute();
    $res = $stmt->get_result();
while ($staff = $res->fetch_object()) {
 
    $sales_person = $staff->staff_name;
	
}
            ?>
            <div class="card-body">
              <form method="post"  enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Payment ID</label>
                    <input type="text" name="pay_id" readonly value="<?php echo $payid;?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>Payment Code</label>
                    <input type="text" name="pay_code" value="<?php echo $mpesaCode; ?>" class="form-control" value="">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Amount ₵ </label>
  
             <input type="text" name="pay_amt" readonly  value="<?php echo $total ?>" class="form-control">
                    
                  </div>
                  <div class="col-md-6">
                    <label>Payment Method</label>
                    <select class="form-control" name="pay_method">
                        <option selected>Cash</option>
                        <option>Mobile Money</option>
                        <option>Credit Card</option>
                        <option>Bank Transfer</option>
                    </select>
                  </div>
                </div>
                <br>
                <input type='text' name = 'sales_person' value ='<?= $sales_person; ?>'>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" <?php  if (isset($upStmt) && isset($postStmt)) { echo 'disabled';} else{echo '';}  ?> name="pay" value="Pay Order" id="popup-button" class="btn btn-success" value="">
                    <input type="submit" name="draft"  <?php if (isset($upStmt) && isset($postStmt)) { echo 'disabled';} else{echo '';} ?> value="Draft Order" class="btn btn-info" value="">
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
  require_once('partials/_scripts.php'); }
  ?>
</body>

</html>