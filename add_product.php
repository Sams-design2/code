<?php
ob_start();
session_start();
$title ='One Solution POS- Admin Dashboard';

include('config/config.php');
include('config/checklogin.php');
include('config/code-generator.php');

check_login();
if (isset($_POST['addProduct'])) {
  //Prevent Posting Blank Values
  if (empty($_POST["prod_code"]) || empty($_POST["prod_name"]) ||  empty($_POST['prod_price'])) {
    $err = "Blank Values Not Accepted";
  } else {
    $prod_id = $_POST['prod_id'];
    $prod_code = $_POST['prod_code'];
    $prod_name = mysqli_real_escape_string($mysqli, $_POST['prod_name']);
    $prod_img = $_FILES['prod_img']['name'];
    move_uploaded_file($_FILES["prod_img"]["tmp_name"], "assets/img/products/" . $_FILES["prod_img"]["name"]);
    $prod_desc = mysqli_real_escape_string($mysqli,$_POST['prod_desc']);
    $prod_price = $_POST['prod_price'];
    $prod_cost = $_POST['prod_cost'];
    $prod_catg =$_POST['prod_catg'];
    $prod_stock =$_POST['prod_stock'];
    $prod_barcode = $_POST['prod_barcode'];
    $business_id = $_SESSION['business_id'] ;

    //Insert Captured information to a database table
    $postQuery = "INSERT INTO rpos_products (prod_id, prod_code, prod_name, prod_img, prod_desc, prod_price,prod_cost,prod_catg,prod_stock,prod_barcode,business_id) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
    $postStmt = $mysqli->prepare($postQuery);
    //bind paramaters
    $rc = $postStmt->bind_param('sssssssssss', $prod_id, $prod_code, $prod_name, $prod_img, $prod_desc, $prod_price, $prod_cost, $prod_catg, $prod_stock,$prod_barcode,$business_id);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
      $success = "Product Added" && header("refresh:1; url=products.php");
    } else {
      $err = "Please Try Again Or Try Later";
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
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
              <h3>Please Fill All Fields</h3>
            </div>
            <div class="card-body">
              <form method="POST" enctype="multipart/form-data">
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Name <span class="text-danger">*</span> </label>
                    <input type="text" required name="prod_name" value="<?php if(isset($_POST['prod_name'])){echo $_POST['prod_name'];} ; ?>" class="form-control">
                    <input type="hidden" name="prod_id" value="<?php echo $prod_id; ?>" class="form-control">
                  </div>
                  <div class="col-md-6">
                    <label>SKU <span class="text-danger">*</span></label>
                    <input type="text" required name="prod_code" value="<?php echo $alpha; ?>-<?php echo $beta; ?>"
                      class="form-control">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Price <span class="text-danger">*</span></label>
                    <input type="number" required name="prod_price" step='any' value="<?php if(isset($_POST['prod_price'])){echo $_POST['prod_price'];}  ?>" class="form-control" >
                  </div>
                  <div class="col-md-6">
                    <label>Product Cost <span class="text-danger">*</span></label>
                    <input type="number" required name="prod_cost" step='any' class="form-control" value="<?php if(isset($_POST['prod_cost'])){echo $_POST['prod_cost'];}  ?>">
                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Product Image <span class="text-danger"></span></label>
                    <input 
                     type="file"  name="prod_img" class="btn btn-outline-success form-control" value="<?php if(isset($_POST['prod_img'])){echo $_POST['prod_img'];}  ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Product Category <span class="text-danger"></span></label>
                    <select class="form-control"  name="prod_catg" id="">
                    <option value="">No Category</option>
                    <?php
                      //Load All Customers
                      $ret = "SELECT * FROM  rpos_categories WHERE business_id = '$business_id'";
                      $stmt = $mysqli->prepare($ret);
                      $stmt->execute();
                      $res = $stmt->get_result();
                    while ($catg = $res->fetch_object()) {
                      ?>
                     
            <option><?php echo $catg->catg_name; ?></option>
           
            <?php }?>
                    </select>

                  </div>
                </div>
                <hr>
                <div class="form-row">
                  <div class="col-md-6">
                    <label>Barcode <span class="text-danger"></span></label>
                    <input type="text" name="prod_barcode" class="form-control" value="<?php if(isset($_POST['prod_barcode'])){echo $_POST['prod_barcode'];} ?>">
                  </div>
                  <div class="col-md-6">
                    <label>Stock <span class="text-danger">*</span></label>
                    <input type="number" step='any' name="prod_stock" required class="form-control"value="<?php if(isset($_POST['prod_stock'])){echo $_POST['prod_stock'];}  ?>">
                  </div>
                </div>
                <hr>
                
                <div class="form-row">
                  <div class="col-md-12">
                    <label>Product Description</label>
                    <textarea rows="5" name="prod_desc" class="form-control" value="<?php if(isset($_POST['prod_desc'])){echo $_POST['prod_desc'];}  ?>"></textarea>
                  </div>
                </div>
                <br>
                <div class="form-row">
                  <div class="col-md-6">
                    <input type="submit" name="addProduct" value="Add Product" class="btn btn-success">
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