<?php
session_start();
include('config/config.php');
include('config/checklogin.php');
check_login();
//Update Profile
if (isset($_POST['ChangeProfile'])) {
    $staff_id = $_SESSION['staff_id'];
    $staff_name = $_POST['staff_name'];
    $staff_email = $_POST['staff_email'];
    $Qry = "UPDATE rpos_staff SET staff_name =?, staff_email =? WHERE staff_id =?";
    $postStmt = $mysqli->prepare($Qry);
    //bind paramaters
    $rc = $postStmt->bind_param('ssi', $staff_name, $staff_email, $staff_id);
    $postStmt->execute();
    //declare a varible which will be passed to alert function
    if ($postStmt) {
        $success = "Account Updated" && header("refresh:1; url=dashboard.php");
    } else {
        $err = "Please Try Again Or Try Later";
    }
}
if (isset($_POST['changePincode'])) {

    //Change Password
    $error = 0;
    if (isset($_POST['old_pincode']) && !empty($_POST['old_pincode'])) {
        $old_pincode = mysqli_real_escape_string($mysqli, ($_POST['old_pincode']));
    } else {
        $error = 1;
        $err = "Old Pincode Cannot Be Empty";
    }
    if (isset($_POST['new_pincode']) && !empty($_POST['new_pincode'])) {
        $new_pincode = mysqli_real_escape_string($mysqli, ($_POST['new_pincode']));
    } else {
        $error = 1;
        $err = "New Pincode Cannot Be Empty";
    }
    if (isset($_POST['confirm_pincode']) && !empty($_POST['confirm_pincode'])) {
        $confirm_pincode = mysqli_real_escape_string($mysqli, ($_POST['confirm_pincode']));
    } else {
        $error = 1;
        $err = "Confirmation Pincode Cannot Be Empty";
    }
 
    if (!$error) {
        $staff_id = $_SESSION['staff_id'];
        $sql = "SELECT * FROM rpos_staff   WHERE staff_id = '$staff_id'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            
            if ($old_pincode != $row['staff_pincode']) {
                $err =  "Please Enter Correct Old Pincode";
            } elseif ($new_pincode != $confirm_pincode) {
                $err = "Confirmation Pincode Does Not Match";
            }
           
             else {
                $newq = "SELECT * FROM rpos_staff WHERE staff_pincode = '$new_pincode' AND business_id = '$business_id'";
                $run = mysqli_query($mysqli, $newq);
                $check = mysqli_num_rows($run);
                if($check > 0){
$err = "Already Exists";
                }else{
                $new_pincode=($_POST['new_pincode']);
                //Insert Captured information to a database table
                $query = "UPDATE rpos_staff SET  staff_pincode =? WHERE staff_id =?";
                $stmt = $mysqli->prepare($query);
                //bind paramaters
                $rc = $stmt->bind_param('si', $new_pincode, $staff_id);
                $stmt->execute();

                //declare a varible which will be passed to alert function
                if ($stmt) {
                    $success = "Password Changed" && header("refresh:1; url=dashboard.php");
                } else {
                    $err = "Please Try Again Or Try Later";
                }
            }
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
        $staff_id = $_SESSION['staff_id'];
        //$login_id = $_SESSION['login_id'];
        $ret = "SELECT * FROM  rpos_staff  WHERE staff_id = '$staff_id'";
        $stmt = $mysqli->prepare($ret);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($staff = $res->fetch_object()) {
        ?>
            <!-- Header -->
            <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(../admin/assets/img/theme/restro00.jpg); background-size: cover; background-position: center top;">
                <!-- Mask -->
                <span class="mask bg-gradient-default opacity-8"></span>
                <!-- Header container -->
                <div class="container-fluid d-flex align-items-center">
                    <div class="row">
                        <div class="col-lg-7 col-md-10">
                            <h1 class="display-2 text-white">Hello <?php echo $staff->staff_name; ?></h1>
                            <p class="text-white mt-0 mb-5">This is your profile page. You can customize your profile as you want And also change password too</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page content -->
            <div class="container-fluid mt--8">
                <div class="row">
                    <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                        <div class="card card-profile shadow">
                            <div class="row justify-content-center">
                                <div class="col-lg-3 order-lg-2">
                                    <div class="card-profile-image">
                                        <a href="#">
                                            <img src="../admin/assets/img/theme/user-a-min.png" class="rounded-circle">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                                <div class="d-flex justify-content-between">
                                </div>
                            </div>
                            <div class="card-body pt-0 pt-md-4">
                                <div class="row">
                                    <div class="col">
                                        <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                            <div>
                                            </div>
                                            <div>
                                            </div>
                                            <div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h3>
                                        <?php echo $staff->staff_name; ?></span>
                                    </h3>
                                    <div class="h5 font-weight-300">
                                        <i class="ni location_pin mr-2"></i><?php echo $staff->staff_email; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8 order-xl-1">
                        <div class="card bg-secondary shadow">
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h3 class="mb-0">My account</h3>
                                    </div>
                                    <div class="col-4 text-right">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="post">
                                    <h6 class="heading-small text-muted mb-4">User information</h6>
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-username">User Name</label>
                                                    <input type="text" name="staff_name" value="<?php echo $staff->staff_name; ?>" id="input-username" class="form-control form-control-alternative" ">
                                                    </div>
                                                    </div>
                                                    <div class=" col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-email">Email address</label>
                                                        <input type="email" id="input-email" value="<?php echo $staff->staff_email; ?>" name="staff_email" class="form-control form-control-alternative">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input type="submit" id="input-email" name="ChangeProfile" class="btn btn-success form-control-alternative" value="Submit"">
                                                        </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </form>
                                                <hr>
                                                <form method ="post">
                                        <h6 class="heading-small text-muted mb-4">Change Pincode</h6>
                                        <div class="pl-lg-4">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-username">Old Pincode</label>
                                                        <input type="password" name="old_pincode"maxlength="4" id="input-username" class="form-control form-control-alternative">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-email">New Pincode</label>
                                                        <input type="password" name="new_pincode" maxlength="4" class="form-control form-control-alternative">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="input-email">Confirm New Pincode</label>
                                                        <input type="password" name="confirm_pincode" maxlength="4" class="form-control form-control-alternative">
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <input type="submit" id="input-email" name="changePincode" class="btn btn-success form-control-alternative" value="Change Password">
                                                    </div>
                                                </div>
                                            </div>
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
        }
            ?>
            </div>
    </div>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_sidebar.php');
    ?>
</body>

</html>