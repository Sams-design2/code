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
        <div class="data-filter">
            <div class="card card-stats mb-4 mb-xl-0" style="padding: 10px;">
            <div class="date-range-filter">
                <span>Filter By Date Range</span>
                <div style="display:flex">

               
                
                <div id="_reportRange" style="cursor: pointer; background: #fff; padding: 5px 10px; border: 1px solid #ccc; width: 100%;">
                <form method="POST"action="filter_by_date.php">
                <input type="hidden" name="dateRange" id="dateRange">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>  
                </div>
                <input type="submit" value="Go" name="filterRange" class="btn btn-sm btn-primary ml-2">
                </form>
                </div>
            </div>
</div>
          
           
           
 
    <!-- Page content -->
    <div class="container-fluid mt--7">
      <div class="row" style="margin-top:120px;margin-left:-50px">
        <div class="col-xl-12 mb-5 mb-xl-0">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Filtered By  Date</h3>
                </div>
                <div class="col text-right">
                  <form action="export_by_date.php" method="post">
                  

                   <?php
                     if (isset($_POST['filterRange'])) {
                      // Retrieve form data
                      $daterange = $_POST['dateRange'];
                     // echo $daterange;
                   ?>
                 
                      <input type="hidden" name="range" value="<?php echo $daterange ?>" id ="dateselect">
                    <?php } ?>
                      <input type="hidden" name="business_id" value="<?php echo $business_id?>">
                      
                      
              
                  <input class="btn btn-sm btn-primary" type="submit" name="exportRange" value="Export As Excel" >
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
                 if (isset($_POST['filterRange'])) {
                  // Retrieve form data
                  $daterange = $_POST['dateRange'];
                  echo $daterange;
                 // Extract start and end dates
                  list($startDateStr, $endDateStr) = explode(" - ", $daterange);

                  // Convert to MySQL date format
                  $start_date = date("Y-m-d H:i:s", strtotime($startDateStr));
                  $end_date = date("Y-m-d H:i:s", strtotime($endDateStr . " 23:59:59")); // Assuming the end date should include the entire day


                  }
                 // $ret = "SELECT * FROM invoice_products WHERE created_at BETWEEN '$start_date' AND '$end_date'";
                 $sql = "SELECT * FROM invoice_products
                 INNER JOIN rpos_payments ON invoice_products.business_id = rpos_payments.business_id
                 WHERE invoice_products.business_id = '$business_id' AND invoice_products.created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
                   $result = $mysqli->query($sql);
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
<script>
    $(document).ready(function() {
      // Navigator Item Click Handler
      

      var startDate;
      var endDate;

      $( function() {
        
        var start = moment().subtract(1, 'month').startOf('month');
        var end = moment().subtract(1, 'month').endOf('month');

        function cb(start, end) {
          $('#_reportRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          startDate = start.format('YYYY-MM-DD');
          endDate = end.format('YYYY-MM-DD');
          var daterange = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')   
          var a = document.getElementById("dateRange").value = daterange;
          console.log(a)
          // loadAnalyticsData();
        }

        $('#_reportRange').daterangepicker({
          startDate: start,
          endDate: end,
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          }
        }, cb);

        cb(start, end);
      });


      

     


    });
  </script>


</html>