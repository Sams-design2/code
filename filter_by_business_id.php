<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
    $business_id = $_SESSION['business_id'] ;
require_once('partials/_analytics.php');
$title ='One Solution POS- Admin Dashboard';
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
          <!-- Card stats -->
          <div class="row">
    
          
           
              <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
			    <!-- <a style="text-decoration: none;"  href=""> -->
                 
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Filter BY Bussiness ID</h5>
                      <!-- <span class="h2 font-weight-bold mb-0"><?php //echo $orders; ?></span> -->
                      <form method="POST"action="filter_by_business_id.php">
                        <div style="display:flex">
                      <select name="cars" id="cars" style="border-radius:10px;width:150px">
                            <option value="">All Businesses</option>
                            <?php

    
$ret = "SELECT * FROM  invoice_products  GROUP BY SID";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
while ($order = $res->fetch_object()) {


?>
    <option value="<?php echo $order->SID; ?>"><?php echo $order->SID; ?></option>
                     
<?php } ?>
                            
         </select>
         <button type="submit" class="btn btn-sm btn-primary ml-2" name="bussinessId">Go</button>
                        </div>
                     
                      </form>
                      
                    </div>
                    <!-- <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-shopping-cart"></i>
                      </div>
                    </div> -->
                  </div>
                    <!-- </a> -->
                </div>
              </div>
            </div>
          
            
          </div>
        </div>
      </div>
    </div>
 
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row mt-5">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Filtered By Businesses ID</h3>
                </div>
                <div class="col text-right">
                  <a href="export.php" class="btn btn-sm btn-primary">Export As Excel</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th class="text-success" scope="col"><b>STAFF ID</b></th>
                    <th class="text-success" scope="col"><b>Bussiness ID</b></th>
                    <th class="text-success" scope="col"><b>Product  Name</b></th>
                 
                    <th class="text-success" scope="col"><b>QUANTITY</b></th>
                    <th scope="col"><b>Total</b></th>
                    <th scop="col"><b>Status</b></th>
                    <th class="text-success" scope="col"><b>Date</b></th>
                  </tr>
                </thead>
                <tbody>
                  
                  <?php
                  if (isset($_POST['bussinessId'])) {
                    // Retrieve form data
                    $businessId = $_POST["business_id"];
                    }
                  $ret = "SELECT * FROM  invoice_products  WHERE business_id = '$businessId'";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  while ($order = $res->fetch_object()) {
                 

                  ?>
                    <tr>
                      <th class="text-success" scope="row"><?php echo $order->SID; ?></th>
                     
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order->business_id; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order->PNAME; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order->QTY; ?></td>
       
                      <td>₵ <?php echo $order->TOTAL ?></td>
                      <td><?php if ($order->order_status == '') {
                            echo "<span class='badge badge-danger'>Not Paid</span>";
                          } else {
                            echo "<span class='badge badge-success'>$order->order_status</span>";
                          } ?></td>
                      <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
	
      
      <!-- Footer -->
      <?php require_once('partials/_footer.php'); ?>
    </div>
  </div>
  <!-- Argon Scripts -->
  <?php
  require_once('partials/_scripts.php');
  ?>
</body>

</html>