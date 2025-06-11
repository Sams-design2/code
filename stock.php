<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
$title ='One Solution POS- Admin dashboard';
check_login();

$business_id = $_SESSION['business_id'] ;

if (isset($_POST['prod_id'])) $prod_id = $_POST['prod_id'];
if (isset($_POST['add_stock'])) {
  $sql = "UPDATE rpos_products SET";

  $update_prod_stock = false;
  $update_neg_sell = false;

  $all_prod_id = [];
  $when_clauses = [];
  $neg_when_clauses = [];

  for ($i = 0; $i < count($_POST['prod_id']); $i++) {
    $prod_stock_index = "prod_stock-".$i;
    if ( isset($_POST[$prod_stock_index]) ) {
      if ( $_POST[$prod_stock_index] >= 0 ) {
        if ($update_prod_stock === false) $update_prod_stock = true;
        $when_clauses[] = "WHEN prod_id='".$_POST['prod_id'][$i]."' THEN ".$_POST[$prod_stock_index];
        $all_prod_id[] = "'".$_POST['prod_id'][$i]."'";
      }
    }
  }
  
  if ($update_prod_stock === true) {
    $sql .= " prod_stock = CASE ";
    $sql .= implode(" ", $when_clauses);
    $sql .= " ELSE prod_stock END";
  }

  for ($i = 0; $i < count($_POST['prod_id']); $i++) {
    $sell_neg_index = "sell_neg-".$i;
    if ( isset($_POST[$sell_neg_index]) ) {
      if ($update_neg_sell === false) $update_neg_sell = true;
      $neg_when_clauses[] = "WHEN prod_id='".$_POST['prod_id'][$i]."' THEN '1'";
      if (!in_array($_POST['prod_id'][$i], $all_prod_id)) $all_prod_id[] = "'".$_POST['prod_id'][$i]."'";
    }
  }

  if ($update_prod_stock === true && $update_neg_sell === true) {
    $sql .= ",";
  }

  if ($update_neg_sell === true) {
    $sql .= " sell_negative = CASE ";
    $sql .= implode(" ", $neg_when_clauses);
    $sql .= " ELSE sell_negative END";
  }

  $sql.= " WHERE prod_id IN(".implode(',',$all_prod_id).")";

  $query = $con->query($sql);
  if ($query) {
    $success = "Stock updated" && header("refresh:1; url=products.php");
  } else {
    $err = "Please Try Again Or Try Later";
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
            <div class="h2 card-header border-0">
            
                Update Product Stock
              
            </div>
            <div class="table-responsive p-3 ">
                      <form method='POST'>
              <table id ='datatable' class="px-2 table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Sell to Negative</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $ret = "SELECT * FROM  rpos_products WHERE business_id = '$business_id'";
                  $stmt = $mysqli->prepare($ret);
                  $stmt->execute();
                  $res = $stmt->get_result();
                  $count = 0;
                  while ($prod = $res->fetch_object()) {
                  ?>
                    <tr>
                      <td>
                        <img src="assets/img/products/<?php if(($prod->prod_img)){echo $prod->prod_img;} else{echo 'place.jpeg'; } ?>" height='60' width='60'>
                      </td>
                      <td><?php echo $prod->prod_name; ?></td>
                      <td><?php echo $prod->prod_price; ?></td>
                      <td><input name ='prod_stock-<?php echo $count; ?>' value=<?= $prod->prod_stock ?> class='form-control w-50' inputmode = 'numeric' type="numbers"></td>
                      <td><input name='sell_neg-<?php echo $count; ?>' type='checkbox' class='form-control' <?php if ($prod->sell_negative == "1") echo "checked"; ?>></td>
                      <input name ='prod_id[]' value = '<?= $prod->prod_id ?>' class='form-control w-50' type="hidden">
                    </tr>
                  <?php 
                  $count++;
                  } ?>
                </tbody>
              </table>
              <br>
              <hr>
              <input type ="submit" name='add_stock' value='Apply' class='w-25 btn btn-info'>
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
       <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
$(document).ready(function () {
    $('#datatable').DataTable();
});
</script>
</body>

</html>