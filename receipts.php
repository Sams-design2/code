<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
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
                            Paid Orders
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr class="text-center">
                                        <th class="text-success" scope="col">Code</th>
                                        <th scope="col">Products</th>
                                        <th class="text-success" scope="col">Total Price</th>
                                      
                                        <th scope="col">Total Quantity</th>
                                        <th class="text-success" scope="col">Date</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
   $business_id = $_SESSION['business_id'];                                                                   
    $qq = "SELECT *,GROUP_CONCAT(PNAME SEPARATOR ', ') AS PNAMES, SUM(TOTAL) AS TOT, SUM(QTY) AS QTYS FROM invoice_products WHERE order_status = 'Paid' AND business_id = '$business_id' GROUP BY SID ORDER BY created_at DESC";

                                    $stmt = $mysqli->prepare($qq);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($order = $res->fetch_object()) {
                                   

                                    ?>
                                        <tr  class="text-center">
                                            <th class="text-success" scope="row"><?php echo $order->SID; ?></th>
                                    
                                            <td style="width: 300px; white-space: pre-line;" class="text-success"><?php echo $order->PNAMES; ?></td>
                                            <td>₵ <?php echo $order->TOT; ?></td>
                                             <td class="text-success"><?php echo $order->QTYS; ?>
                                         
                                            <td><?php echo date('d/M/Y g:i', strtotime($order->created_at)); ?></td>
                                            <td>
                                                <a target="_blank" href="print.php?SID=<?php echo $order->SID; ?>">
                                                    <button class="btn btn-sm btn-primary">
                                                        <i class="fas fa-print"></i>
                                                        Print Receipt
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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