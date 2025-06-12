<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="author" content="MartDevelopers Inc">
    <title>Cashier Dashboard </title>
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/dg.png">


    <link rel="manifest" href="assets/img/icons/site.webmanifest">
    <link rel="mask-icon" href="assets/img/icons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Fonts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <!-- Icons -->
    <link href="assets/vendor/nucleo/css/nucleo.css" rel="stylesheet">
    <link href="assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
    <!-- Argon CSS -->
    <link type="text/css" href="assets/css/argon.css?v=1.0.0" rel="stylesheet">
    <script src="assets/js/swal.js"></script>
    <!--Load Swal-->
    <?php if (isset($success)) { ?>
        <!--This code for injecting success alert-->
        <script>
            setTimeout(function() {
                    swal("Success", "<?php echo $success; ?>", "success");
                },
                100);
        </script>


    <?php } ?>
    <?php if (isset($err)) { ?>
        <!--This code for injecting error alert-->
        <script>
            setTimeout(function() {
                    swal("Failed", "<?php echo $err; ?>", "error");
                },
                100);
        </script>

    <?php } ?>
    <?php if (isset($info)) { ?>
        <!--This code for injecting info alert-->
        <script>
            setTimeout(function() {
                    swal("Success", "<?php echo $info; ?>", "info");
                },
                100);
        </script>

    <?php } ?>
    <script>
        function getCustomer(val) {
            $.ajax({

                type: "POST",
                url: "customer_ajax.php",
                data: 'custName=' + val,
                success: function(data) {
                    //alert(data);
                    $('#customerID').val(data);
                }
            });

        }
        // Get the product name and the row number
        // return the price of the product
        function getPrice(val, rowNumber) {
            if (val !== "") {
                $.ajax({
                type: "POST",
                url: "customer_ajax.php",
                data: 'prodName=' + val,
                success: function(data) {
                    if (data !== "no_stock") {
                        // Use the row number to get the exact price input
                        // then set the value of the product's prize to it
                        $('#priceI'+rowNumber).val(data);

                        // Get the total of the product with the qty and prize
                        if ( $('#qtyI'+rowNumber).val() !== "" ) {
                            $('#totalI'+rowNumber).val($('#qtyI'+rowNumber).val() * $('#priceI'+rowNumber).val())
                            
                            // The grand_total function:
                            var tot=0;
                            $(".total").each(function(){
                                tot+=Number($(this).val());
                            });
                            $("#grand_total").val(tot);

                            // $("#btn-add-row").click();
                            // let newRowNumber = rowNumber + 1;
                            // $("#prodName"+newRowNumber).focus();
                        }
                    }
                }
                });
            }
        }

        // Get the product name and the row number
        // return the ID of the product
        function getID(val, rowNumber) {
            if (val !== "") {
                $.ajax({
                type: "POST",
                url: "customer_ajax.php",
                data: 'prodNameForPrice=' + val,
                success: function(data) {
                    if (data == "no_stock") {
                        // If the product is not in stock, send an alert
                        alert('The product you chose is not available for sale');
                        $('#prodName'+rowNumber).val('');
                        $('#prodName'+rowNumber).next("button").children("div").children("div").children("div").text("--Select Products--")
                        document.getElementById('prodName'+rowNumber).selectedIndex = 0;
                    } else {
                        let productArray = JSON.parse(data);
                        // Use the row number to get the exact product ID hidden input
                        // and set the ID to it
                        $('#pID'+rowNumber).val(productArray[0]);

                        // Use the same row number to get the product label
                        $('.prodNameLabel'+rowNumber).text(productArray[1]);
                    }
                }
                });
            }
        }
        
        function getPrices(val) {
            $.ajax({

                type: "POST",
                url: "customer_ajax.php",
                data: 'prodNames=' + val,
                success: function(data) {
                    //alert(data);
                    $('#priceII').val(data);
                }
            });

        }
    </script>
</head>