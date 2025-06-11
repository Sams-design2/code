<?php
ob_start();
session_start();
$title = 'One Solution POS- Admin Dashboard';
$business_id = $_SESSION['business_id'];
include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');
include('models/DataAnalyzer.php');
include('models/Admin.php');
include('models/Cashier.php');



check_login();

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
    </script>
    <div style="background-image: url(assets/img/theme/restro00.jpg); background-size: cover;"
      class="header  pb-8 pt-5 pt-md-8">
      <span class="mask bg-gradient-dark opacity-8"></span>
      <div class="container-fluid">
        <div class="header-body">
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--8">
      <div class="card shadow">
        <div class="card-header border-0">
          <?php
          // Try/Catch the instantiation of the Data Analyzer object
          try {
            $data_analyzer = new DataAnalyzer($con, $business_id);
          } catch (Exception $e) {
            $data_analyzer = false;
          }

          // Have a check here to see if our object has been 
          // instantiated perfectly
          if ($data_analyzer !== false) {
          ?>
          <!-- Filter for the Data Analytics -->
          <div class="data-filter">
            <!-- <div>
              <span>All Time</span>
              <label class="switch">
                <input id="_allTimeToggle" type="checkbox">
                <span class="slider round"></span>
              </label>
            </div> -->
            <div class="date-range-filter">
              <span>Date Range</span>
              <div id="_reportRange" style="cursor: pointer; background: #fff; padding: 5px 10px; border: 1px solid #ccc; width: 100%;">
                  <i class="fa fa-calendar"></i>&nbsp;
                  <span></span> <i class="fa fa-caret-down"></i>
              </div>
            </div>
            <div class="employee-filter">
              <span>Sales Person</span>
              <select name="" id="_employeesF" style="cursor: pointer; background: #fff; padding: 5px 10px; border: 1px solid #ccc; width: 100%;">
              <option value="all">All</option>
              <?php
              $admin_names = [];
              $cashier_names = [];

              // Get all admins that recorded orders
              $sql = "SELECT staff_number from invoice_products WHERE business_id = '$business_id' AND recorded_by = 'admin' AND order_status = 'Paid' GROUP BY staff_number";
              $result = $con->query($sql);
              echo "<optgroup label='Admins'>";
              while ($admin_row = mysqli_fetch_assoc($result)) {
                // Get the admin name with the admin id
                $admin_id = $admin_row["staff_number"];
                $admin = new Admin($con, $business_id, $admin_id); // Instantiate an Admin object
                $admin_name = $admin->getAdminName(); // Use the getAdminName() method to get the admin's name

                if ($admin_name !== false) {
                  echo "<option value='admin:".$admin_id."'>".$admin_name."</option>";
                }

              }
              echo "</optgroup>";

              // Get all cashiers that recorded orders
              $sql = "SELECT staff_number from invoice_products WHERE business_id = '$business_id' AND recorded_by = 'cashier' AND order_status = 'Paid' GROUP BY staff_number";
              $result = $con->query($sql);
              echo "<optgroup label='Cashiers'>";
              while ($cashier_row = mysqli_fetch_assoc($result)) {
                // Get the cashier's name with the cashier's staff number
                $cashier_id = $cashier_row["staff_number"];
                $cashier = new Cashier($con, $business_id, $cashier_id); // Instantiate a Cashier object
                $cashier_name = $cashier->getCashierName(); // Use the getCashierName() method to get the cashier's name

                if ($cashier_name !== false) {
                  echo "<option value='cashier:".$cashier_id."'>".$cashier_name."</option>";
                }

              }
              echo "</optgroup>";
              ?>
              </select>
            </div>
            <div></div>
          </div>
          <!-- Navigator for the Data Analytics -->
          <div class="navigator">
            <div class="navigator-item active">
              <span>Gross Sales</span>
              <span class="amount">GHC <?php echo $data_analyzer->getTotalGrossSales(); ?></span>
              <span>More info</span>
            </div>
            <div class="navigator-item">
              <span>Cost of Goods</span>
              <span class="amount">GHC <?php echo $data_analyzer->getTotalCost(); ?></span>
              <span>More info</span>
            </div>
            <div class="navigator-item">
              <span>Tax</span>
              <span class="amount">GHC <?php echo $data_analyzer->getTotalTax(); ?></span>
              <span>More info</span>
            </div>
            <div class="navigator-item">
              <span>Net Sales</span>
              <span class="amount">GHC <?php echo $data_analyzer->getTotalNetSales(); ?></span>
              <span>More info</span>
            </div>
            <div class="navigator-item">
              <span>Gross Profit</span>
              <span class="amount">GHC <?php echo $data_analyzer->getTotalGrossProfit(); ?></span>
              <span>More info</span>
            </div>
          </div>
        </div>
        <!-- Chart -->
        <div id="_chartData" class="chart-cont row">
          <div class="card-body col-md">
            <div class="chart-wrapper">
              <div id="gs_Chart" class="chart active-chart">
                <canvas id="_grossSChart"></canvas>
              </div>
              <div id="cs_Chart" class="chart">
                <canvas id="_costGChart"></canvas>
              </div>
              <div id="tx_Chart" class="chart">
                <canvas id="_taxChart"></canvas>
              </div>
              <div id="nt_Chart" class="chart">
                <canvas id="_netSChart"></canvas>
              </div>
              <div id="gp_Chart" class="chart">
                <canvas id="_grossPChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        <?php
        // If the Data Analyzer object wasn't instantiated
        // let's make them have this
        } else {
          echo "<h3>There was an error loading this page. Please try again later.</h3>";
        }
        ?>
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
  <script>
    $(document).ready(function() {
      // Navigator Item Click Handler
      $(".navigator").on("click", ".navigator-item", function() {
        if ( !$(this).hasClass("active") ) {
          let navigatorItems = $(".navigator-item");
          let charts = $(".chart");
          let targetElement = $(this).attr("target-element");
          let counter = 0;

          // Remove active class from any other navigator button
          for (let i = 0; i < navigatorItems.length; i++) {
              const navigatorItem = navigatorItems[i];
              if ($(navigatorItem).hasClass("active")) {
                  $(navigatorItem).removeClass("active");
              }
              counter++;
          }

          // Remove active class from any other chart
          for (let i = 0; i < charts.length; i++) {
            const currentChart = charts[i];
            if ($(currentChart).attr("id") !== targetElement) {
              $(currentChart).removeClass("active-chart");
            } else {
              $(currentChart).addClass("active-chart");
            }
          }

          // Add class to this selected navigator button
          if (counter === navigatorItems.length) $(this).addClass("active");
        }
      });

      var allTimeToggle = false;
      $("#_allTimeToggle").click(function() {
        if ( $(this).attr("checked") ) {
          allTimeToggle = false; 
          $(this).removeAttr("checked");
          $(".date-range-filter, .employee-filter").removeClass("inactive");
        } else {
          allTimeToggle = true; 
          $(this).attr("checked", "checked");
          $(".date-range-filter, .employee-filter").addClass("inactive");
        }
      });

      $("#_employeesF").on("change", function() {
        if (!allTimeToggle) {
          loadAnalyticsData();
        }
      });

      var startDate;
      var endDate;

      $( function() {
        // Starting range is form the last 30 days
        // var start = moment().subtract(29, 'days');
        // var end = moment();

        // Starting range is from the last month
        var start = moment().subtract(1, 'month').startOf('month');
        var end = moment().subtract(1, 'month').endOf('month');

        function cb(start, end) {
          $('#_reportRange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
          startDate = start.format('YYYY-MM-DD');
          endDate = end.format('YYYY-MM-DD');

          loadAnalyticsData();
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


      function loadAnalyticsData() {
        if (!allTimeToggle) {
          let employeeID = $("#_employeesF").val();
          if (startDate !== undefined && endDate !== undefined && (employeeID !== "" || employeeID == "all")) {
            loadDataByFilter(startDate, endDate, employeeID);
          }
        } else {
          loadAllTimeData();
        }
      }

      function loadDataByFilter(sDate, eDate, employee) {
        $(".navigator").html("<h3>Please wait...</h3>");
        $.post("load_data_analytics.php", {
          sd: sDate.toString(),
          ed: eDate.toString(),
          ei: employee
        }).
        done(function(data) {
          if (data == "error" || data === undefined) {
            alert("There was an error loading the data. Please refresh the page and try again.");
          } else {
            var outputArray = JSON.parse(data);
            $(".navigator").html(outputArray[0]);
            $("#_chartData").append(outputArray[1]);
            // console.log(data);
          }
        });
      }

      function resetChart() {
        let charts = $(".charts");
        for (let i = 0; i < charts.length; i++) {
          let currentChart = charts[i];
          $(currentChart).removeClass("active-chart");
        }
        // $("#gs_Chart").addClass("active-chart");
      }
    });
  </script>
</body>

</html>