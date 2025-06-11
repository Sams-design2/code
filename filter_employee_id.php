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
                      <h5 class="card-title text-uppercase text-muted mb-0">Filter BY Employee ID</h5>
                      <!-- <span class="h2 font-weight-bold mb-0"><?php //echo $orders; ?></span> -->
                      <form method="POST"action="filter_employee_id.php">
                        <div style="display:flex">
                      <select name="employee_id" id="cars" style="border-radius:10px;width:150px">
                      <option value="">All Employees</option>
                            <?php

    
$ret = "SELECT * FROM invoice_products
INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
WHERE invoice_products.business_id = '$business_id' GROUP BY rpos_payments.sales_person";
       $result = $mysqli->query($ret);
       $stmt->execute();
       $res = $stmt->get_result();
       $allResults = [];
       while ($order = $result->fetch_assoc()) {


?>
    
    <option value="<?php echo $order['sales_person']; ?>"><?php echo $order['sales_person']; ?></option>
                     
<?php } ?>
                            
         </select>
         <button type="submit" class="btn btn-sm btn-primary ml-2" name="employeeId">Go</button>
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
                  <h3 class="mb-0">Filtered By Employee ID</h3>
                </div>
                <div class="col text-right">
                  <form action="export_employee.php" method="post">
                  
                    <?php
                  if (isset($_POST['employeeId'])) {
                    // Retrieve form data
                    $employee_id = $_POST["employee_id"];
                   // echo $employee_id;
                    }
                    if(empty($employee_id)){
                      $ret = "SELECT * FROM invoice_products
          INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
          WHERE invoice_products.business_id = '$business_id'";
                     
                    }
                    else{
                      $ret = "SELECT * FROM invoice_products
          INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
          WHERE invoice_products.business_id = '$business_id' AND rpos_payments.sales_person ='$employee_id'";
                     
                    }
                         
                $result = $mysqli->query($ret);
          $stmt->execute();
          $res = $stmt->get_result();
          $allResults = [];
          while ($order = $result->fetch_assoc()) {
   

          ?>
                  
                 
                      <input type="hidden" name="employee_id" value="<?php echo $order['sales_person'];?>">
                      <input type="hidden" name="business_id" value="<?php echo $business_id ?>">
                  <?php } ?>
                  <button class="btn btn-sm btn-primary" type="submit" name="employeeID">Export As Excel</button>
                  </form>
                  </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                <tr>
                    <th class="text-success" scope="col"><b>STAFF NAME</b></th>
                    <!-- <th class="text-success" scope="col"><b>Recorded By</b></th> -->
                    <th class="text-success" scope="col"><b>Product  Name</b></th>
                    <th class="text-success" scope="col"><b>Product Price</b></th>
                    <th class="text-success" scope="col"><b>Pay ID</b></th>
                    <th class="text-success" scope="col"><b>Pay Code</b></th>
                    <th class="text-success" scope="col"><b>Payment Method</b></th>
                    <th class="text-success" scope="col"><b>Order Status</b></th>
                    <th class="text-success" scope="col"><b>Quantity</b></th>
                    <th scope="col" class="text-success"><b>Total</b></th>
                    <th class="text-success" scope="col"><b>Date</b></th>
                  </tr>
                </thead>
                <tbody>
                  
                  <?php
                  if (isset($_POST['employeeId'])) {
                    // Retrieve form data
                    $employee_id = $_POST["employee_id"];
                    }
                  if(empty($employee_id)){
                    
                    $ret = "SELECT * FROM invoice_products
                    INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
                    WHERE invoice_products.business_id = '$business_id'";
                    
                  }
                  else{
                    $ret = "SELECT * FROM invoice_products
                    INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
                    WHERE invoice_products.business_id = '$business_id' AND rpos_payments.sales_person ='$employee_id'";
                    }
                                      // $ret = "SELECT * FROM  invoice_products  WHERE staff_number = '$employee_id'";
                          // echo $ret;
                          $result = $mysqli->query($ret);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $allResults = [];
                    while ($order = $result->fetch_assoc()) {
             
          
                    ?>
                     <tr>
                    <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['sales_person']; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['PNAME']; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['PRICE']; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['pay_id']; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['pay_code']; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['pay_method']; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['order_status']; ?></td>
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['QTY']; ?></td>
                      
                      <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order['TOTAL']; ?></td>
                      <td class="text-success"><?php echo date('d/M/Y g:i', strtotime($order['created_at'])); ?></td>
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