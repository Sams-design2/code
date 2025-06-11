<?php
session_start();
unset($_SESSION['staff_id']);
header("Location:index.php");
exit;
