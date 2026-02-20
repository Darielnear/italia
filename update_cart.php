<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    if (isset($_POST['remove'])) {
        unset($_SESSION['cart'][$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    $delta = (int)$_POST['delta'];
    if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 0;
    
    $_SESSION['cart'][$id] += $delta;
    
    if ($_SESSION['cart'][$id] <= 0) {
        unset($_SESSION['cart'][$id]);
        $newQty = 0;
    } else {
        $newQty = $_SESSION['cart'][$id];
    }

    echo json_encode([
        'success' => true, 
        'newQty' => $newQty,
        'totalItems' => array_sum($_SESSION['cart'])
    ]);
}