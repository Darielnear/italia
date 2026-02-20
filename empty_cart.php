<?php
session_start();
unset($_SESSION['cart']);
header('Location: checkout.php');
exit;