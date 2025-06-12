<!-- Core -->
<script src="assets/vendor/jquery/dist/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/argon.js?v=1.0.0"></script>
<script src="assets/vendor/chart.js/dist/Chart.min.js"></script>
<script src="assets/vendor/chart.js/dist/Chart.extension.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<!-- Print Receipt Link Functionality -->
<!-- 
  Basically, reroute the user to the order creation page. If the user, wants to print the receipt
  open the PDF on a new tab
-->
<script>
  function genAndPrint(order_code, redirectToOrder = false) {
    const dataUrl = "print.php?SID="+order_code;
    const newTab = window.open(dataUrl, "_blank");
    newTab.focus();
    newTab.onload = function() {
      newTab.print();
      if (redirectToOrder === true) {
        window.location.href = "http://<?php echo $_SERVER["HTTP_HOST"].rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); ?>/invo.php";
      }
    }
  }

  $(".print-receipt-link").click(function(e) {
    e.preventDefault();
    let orderCode = $(this).attr("data-sid");
    if ($(this).attr("href") == "invo.php") {
      genAndPrint(orderCode, true);
    } else {
      genAndPrint(orderCode);
    }
  });
</script>