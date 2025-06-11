<?php
session_start();
$business_id = $_SESSION['business_id'];
include('config/config.php');
include('models/DataAnalyzer.php');

if (isset($_POST["sd"]) && isset($_POST["ed"]) && isset($_POST["ei"])) {
    $start_date = mysqli_real_escape_string($con, $_POST["sd"]);
    $end_date = mysqli_real_escape_string($con, $_POST["ed"]);
    $employee = mysqli_real_escape_string($con, $_POST["ei"]);

    if ($start_date !== "" && $end_date !== "" && $employee !== "") {

        try {
            $data_analyzer = new DataAnalyzer($con, $business_id);
        } catch (Exception $e) {
            echo "error";
            exit(1);
        }

        

        // The overall output array to return to data_analytics.php
        // it would be JSON encoded and returned.
        // The array is divided to two parts:
        // First: the HTML for the satistics of gross sales, cost of goods etc
        // Second: the <script> for the Chart that would be generated
        $output_array = array();

        // Using the DataAnalyzer Class and it's methods, calculate the necessary data
        // based on the filter criterias provided from the user
        $gross_sales = $data_analyzer->getGrossSales($start_date, $end_date, $employee);
        $cost_goods = $data_analyzer->getCostOfGoods($start_date, $end_date, $employee);
        $tax = $data_analyzer->getTax($start_date, $end_date, $employee);
        $net_sales = $data_analyzer->getNetSales($start_date, $end_date, $employee);
        $gross_profit = $data_analyzer->getGrossProfit($start_date, $end_date, $employee);


        // // Work on the necessary data for the Chart...
        // // First, get the dates
        $gross_sales_dates = $data_analyzer->getGrossSalesAmounts($start_date, $end_date, $employee)[0];
        $cost_goods_dates = $data_analyzer->getCostOfGoodsAmounts($start_date, $end_date, $employee)[0];
        $tax_dates = $data_analyzer->getTaxAmounts($start_date, $end_date, $employee)[0];
        $net_sales_dates = $data_analyzer->getNetSalesAmounts($start_date, $end_date, $employee)[0];
        $gross_profit_dates = $data_analyzer->getGrossProfitAmounts($start_date, $end_date, $employee)[0];
        
        // // Second, get the corresponding amounts for the date
        $gross_sales_amounts = $data_analyzer->getGrossSalesAmounts($start_date, $end_date, $employee)[1];
        $cost_goods_amounts = $data_analyzer->getCostOfGoodsAmounts($start_date, $end_date, $employee)[1];
        $tax_amounts = $data_analyzer->getTaxAmounts($start_date, $end_date, $employee)[1];
        $net_sales_amounts = $data_analyzer->getNetSalesAmounts($start_date, $end_date, $employee)[1];
        $gross_profit_amounts = $data_analyzer->getGrossProfitAmounts($start_date, $end_date, $employee)[1];

        
      //   The HTML output for the calculated information above...
        $html_output = '<div class="navigator-item active" target-element="gs_Chart">
        <span>Gross Sales</span>
        <span class="amount" >GHC '.$gross_sales.'</span>
        <span>No More info</span>
      </div>
      <div class="navigator-item" target-element="cs_Chart">
        <span>Cost of Goods</span>
        <span class="amount">GHC '.$cost_goods.'</span>
        <span>No More info</span>
      </div>
      <div class="navigator-item" target-element="tx_Chart">
        <span>Tax</span>
        <span class="amount">GHC '.$tax.'</span>
        <span>No More info</span>
      </div>
      <div class="navigator-item" target-element="nt_Chart">
        <span>Net Sales</span>
        <span class="amount">GHC '.$net_sales.'</span>
        <span>No More info</span>
      </div>
      <div class="navigator-item" target-element="gp_Chart">
        <span>Gross Profit</span>
        <span class="amount">GHC '.$gross_profit.'</span>
        <span>No More info</span>
      </div>';

      array_push($output_array, $html_output);

      $chart_script = "
      <script>
      function grossSalesChart() {
        const labels = ".json_encode($gross_sales_dates).";
        const amounts = ".json_encode($gross_sales_amounts).";
        const ctx = document.getElementById('_grossSChart');
        new Chart(ctx, {
          type : 'line',
          data: {
            labels: labels,
            datasets: [{
                label: 'Gross Sales',
                data: amounts,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
      }

      function costGoodsChart() {
        const labels = ".json_encode($cost_goods_dates).";
        const amounts = ".json_encode($cost_goods_amounts).";
        const ctx = document.getElementById('_costGChart');
        new Chart(ctx, {
          type : 'line',
          data: {
            labels: labels,
            datasets: [{
                label: 'Cost of Goods',
                data: amounts,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
      }

      function taxChart() {
        const labels = ".json_encode($tax_dates).";
        const amounts = ".json_encode($tax_amounts).";
        const ctx = document.getElementById('_taxChart');
        new Chart(ctx, {
          type : 'line',
          data: {
            labels: labels,
            datasets: [{
                label: 'Tax',
                data: amounts,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
      }
      
      function netChart() {
        const labels = ".json_encode($net_sales_dates).";
        const amounts = ".json_encode($net_sales_amounts).";
        const ctx = document.getElementById('_netSChart');
        new Chart(ctx, {
          type : 'line',
          data: {
            labels: labels,
            datasets: [{
                label: 'Net Sales',
                data: amounts,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
      }

      function grossPChart() {
        const labels = ".json_encode($gross_profit_dates).";
        const amounts = ".json_encode($gross_profit_amounts).";
        const ctx = document.getElementById('_grossPChart');
        new Chart(ctx, {
          type : 'line',
          data: {
            labels: labels,
            datasets: [{
                label: 'Gross Profit',
                data: amounts,
                borderWidth: 1
            }]
            },
            options: {
            scales: {
                y: {
                beginAtZero: true
                }
            }
            }
        });
      }

      grossSalesChart();
      costGoodsChart();
      taxChart();
      netChart();
      grossPChart();
      </script>";
      
      array_push($output_array, $chart_script);

      $output_array = json_encode($output_array);

      echo $output_array;

    } else {
        echo "error";
    }
}